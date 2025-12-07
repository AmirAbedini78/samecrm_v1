<?php

namespace App\Http\Controllers;

use App\Helpers\PersianCalendarHelper;
use App\Models\InventoryCustomCategory;
use App\Models\InventoryCustomCategoryClient;
use App\Models\InventoryCustomCategoryItem;
use App\Models\InventoryEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class InventoryCustomCategoryController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Get all custom categories
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $categories = InventoryCustomCategory::with([
                    'items.inventory:inventory_id,inventory_name,inventory_code',
                    'items.entry:entry_id,inventory_id,entry_code,lot_number,serial_number,entry_date,expiry_date',
                    'clients.client:client_id,client_company_name'
                ])
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($category) {
                    return $this->formatCategoryPayload($category);
                })
                ->values();

            return response()->json([
                'success' => true,
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            Log::error('Get Custom Categories Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new custom category
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'category_name' => 'required|string|max:255',
                'category_type' => 'required|string|in:item,customer',
                'category_color' => 'nullable|string|max:20',
                'category_icon' => 'nullable|string|max:255',
                'category_image' => 'nullable|string|max:255',
                'category_image_upload' => 'nullable|string',
                'category_image_remove' => 'nullable|boolean',
                'description' => 'nullable|string|max:500',
                'start_date' => 'nullable|string',
                'end_date' => 'nullable|string',
                'entity_ids' => 'nullable|array',
                'entity_ids.*' => 'numeric',
                'entity_entries' => 'nullable|array',
                'entity_entries.*.inventory_id' => 'required_with:entity_entries|numeric',
                'entity_entries.*.inventory_entry_id' => 'nullable|numeric',
            ]);

            $data = Arr::except($validated, ['entity_ids', 'entity_entries', 'category_image_upload', 'category_image_remove']);
            $entityIds = array_filter($validated['entity_ids'] ?? []);
            $entityEntries = $this->normalizeEntityEntryPayloads($request->input('entity_entries', []));

            $data['start_date'] = $this->normalizeDateInput($request->input('start_date'), 'start_date');
            $data['end_date'] = $this->normalizeDateInput($request->input('end_date'), 'end_date');
            $this->validateDateOrder($data['start_date'], $data['end_date']);

            $imagePayload = $this->prepareCategoryImagePayload($request);
            if ($imagePayload['updated']) {
                $data['category_image'] = $imagePayload['value'];
            }

            $data['user_id'] = Auth::id();

            $category = InventoryCustomCategory::create($data);
            $this->attachInitialEntities($category, $entityIds, $entityEntries);

            return response()->json([
                'success' => true,
                'data' => $this->formatCategoryPayload($category->fresh(['items.inventory', 'clients.client'])),
                'message' => 'دسته‌بندی با موفقیت ایجاد شد'
            ]);
        } catch (\Exception $e) {
            Log::error('Create Custom Category Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update custom category
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $category = InventoryCustomCategory::where('category_id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $validated = $request->validate([
                'category_name' => 'sometimes|required|string|max:255',
                'category_type' => 'sometimes|required|string|in:item,customer',
                'category_color' => 'nullable|string|max:20',
                'category_icon' => 'nullable|string|max:255',
                'category_image' => 'nullable|string|max:255',
                'category_image_upload' => 'nullable|string',
                'category_image_remove' => 'nullable|boolean',
                'description' => 'nullable|string|max:500',
                'start_date' => 'nullable|string',
                'end_date' => 'nullable|string',
                'entity_ids' => 'nullable|array',
                'entity_ids.*' => 'numeric',
                'entity_entries' => 'nullable|array',
                'entity_entries.*.inventory_id' => 'required_with:entity_entries|numeric',
                'entity_entries.*.inventory_entry_id' => 'nullable|numeric',
            ]);

            $data = Arr::except($validated, ['entity_ids', 'entity_entries', 'category_image_upload', 'category_image_remove']);
            $entityIds = array_filter($validated['entity_ids'] ?? []);
            $entityEntries = $this->normalizeEntityEntryPayloads($request->input('entity_entries', []));

            if (array_key_exists('start_date', $data)) {
                $data['start_date'] = $this->normalizeDateInput($request->input('start_date'), 'start_date');
            }

            if (array_key_exists('end_date', $data)) {
                $data['end_date'] = $this->normalizeDateInput($request->input('end_date'), 'end_date');
            }

            $this->validateDateOrder($data['start_date'] ?? $category->start_date?->format('Y-m-d'), $data['end_date'] ?? $category->end_date?->format('Y-m-d'));

            $imagePayload = $this->prepareCategoryImagePayload($request, $category);
            if ($imagePayload['updated']) {
                $data['category_image'] = $imagePayload['value'];
            }

            if (!empty($data)) {
                $category->update($data);
            }

            if (!empty($entityIds) || !empty($entityEntries)) {
                $this->attachInitialEntities($category, $entityIds, $entityEntries);
            }

            return response()->json([
                'success' => true,
                'data' => $this->formatCategoryPayload($category->fresh(['items.inventory', 'clients.client'])),
                'message' => 'دسته‌بندی با موفقیت به‌روزرسانی شد'
            ]);
        } catch (\Exception $e) {
            Log::error('Update Custom Category Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete custom category
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $category = InventoryCustomCategory::where('category_id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // Delete related items & clients
            InventoryCustomCategoryItem::where('custom_category_id', $id)->delete();
            InventoryCustomCategoryClient::where('custom_category_id', $id)->delete();

            if ($category->category_image) {
                $this->deleteCategoryImage($category->category_image);
            }

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'دسته‌بندی با موفقیت حذف شد'
            ]);
        } catch (\Exception $e) {
            Log::error('Delete Custom Category Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add inventory to category
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addInventory(Request $request)
    {
        try {
            $request->validate([
                'custom_category_id' => 'required|exists:inventory_custom_categories,category_id',
                'entity_type' => 'nullable|in:item,customer',
            ]);

            $category = InventoryCustomCategory::where('category_id', $request->input('custom_category_id'))
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $entityType = $request->input('entity_type', $category->category_type);

            if ($entityType === 'customer') {
                if ($category->category_type !== 'customer') {
                    throw ValidationException::withMessages([
                        'entity_type' => 'این دسته برای مشتریان تعریف نشده است.',
                    ]);
                }

                $data = $request->validate([
                    'client_id' => 'required|exists:clients,client_id',
                    'alias_name' => 'nullable|string|max:255',
                    'alias_color' => 'nullable|string|max:20',
                    'alias_image' => 'nullable|string|max:255',
                    'start_date' => 'nullable|string',
                    'end_date' => 'nullable|string',
                    'notes' => 'nullable|string|max:500',
                ]);

                $data['start_date'] = $this->normalizeDateInput($request->input('start_date'), 'start_date');
                $data['end_date'] = $this->normalizeDateInput($request->input('end_date'), 'end_date');
                $this->validateDateOrder($data['start_date'], $data['end_date']);

                $clientEntry = InventoryCustomCategoryClient::updateOrCreate(
                    [
                        'client_id' => $data['client_id'],
                        'custom_category_id' => $category->category_id,
                    ],
                    Arr::except($data, ['client_id'])
                );

                return response()->json([
                    'success' => true,
                    'data' => $this->formatCategoryClientPayload($clientEntry->fresh('client')),
                    'message' => 'مشتری با موفقیت در دسته ثبت شد'
                ]);
            }

            if ($category->category_type !== 'item') {
                throw ValidationException::withMessages([
                    'entity_type' => 'این دسته برای کالاها تعریف نشده است.',
                ]);
            }

            $data = $request->validate([
                'inventory_id' => 'required|exists:inventory,inventory_id',
                'inventory_entry_id' => 'nullable|exists:inventory_entries,entry_id',
                'alias_name' => 'nullable|string|max:255',
                'alias_color' => 'nullable|string|max:20',
                'alias_image' => 'nullable|string|max:255',
                'start_date' => 'nullable|string',
                'end_date' => 'nullable|string',
                'notes' => 'nullable|string|max:500',
            ]);

            $data['start_date'] = $this->normalizeDateInput($request->input('start_date'), 'start_date');
            $data['end_date'] = $this->normalizeDateInput($request->input('end_date'), 'end_date');
            $this->validateDateOrder($data['start_date'], $data['end_date']);
            $this->assertEntryMatchesInventory($data['inventory_entry_id'] ?? null, $data['inventory_id']);

            $item = InventoryCustomCategoryItem::updateOrCreate(
                [
                    'inventory_id' => $data['inventory_id'],
                    'inventory_entry_id' => $data['inventory_entry_id'] ?? null,
                    'custom_category_id' => $category->category_id,
                ],
                Arr::except($data, ['inventory_id', 'inventory_entry_id'])
            );

            return response()->json([
                'success' => true,
                'data' => $this->formatCategoryItemPayload($item->fresh('inventory')),
                'message' => 'کالا با موفقیت در دسته ثبت شد'
            ]);
        } catch (\Exception $e) {
            Log::error('Add Inventory to Category Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove inventory from category
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeInventory(Request $request)
    {
        try {
            $request->validate([
                'custom_category_id' => 'required|exists:inventory_custom_categories,category_id',
                'entity_type' => 'nullable|in:item,customer',
            ]);

            $category = InventoryCustomCategory::where('category_id', $request->input('custom_category_id'))
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $entityType = $request->input('entity_type', $category->category_type);

            if ($entityType === 'customer') {
                $data = $request->validate([
                    'client_id' => 'required|exists:clients,client_id',
                ]);

                InventoryCustomCategoryClient::where('client_id', $data['client_id'])
                    ->where('custom_category_id', $category->category_id)
                    ->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'مشتری با موفقیت از دسته حذف شد'
                ]);
            }

            $data = $request->validate([
                'inventory_id' => 'required|exists:inventory,inventory_id',
                'inventory_entry_id' => 'nullable|exists:inventory_entries,entry_id',
            ]);

            $itemsQuery = InventoryCustomCategoryItem::where('inventory_id', $data['inventory_id'])
                ->where('custom_category_id', $category->category_id)
                ->when($data['inventory_entry_id'] ?? null, function ($query, $entryId) {
                    $query->where('inventory_entry_id', $entryId);
                });

            $itemsQuery->delete();

            return response()->json([
                'success' => true,
                'message' => 'کالا با موفقیت از دسته حذف شد'
            ]);
        } catch (\Exception $e) {
            Log::error('Remove Inventory from Category Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Format category payload for responses
     */
    private function formatCategoryPayload(InventoryCustomCategory $category)
    {
        $category->loadMissing([
            'items.inventory:inventory_id,inventory_name,inventory_code',
            'items.entry:entry_id,inventory_id,entry_code,lot_number,serial_number,entry_date,expiry_date',
            'clients.client:client_id,client_company_name'
        ]);

        $items = $category->items->map(function ($item) {
            return $this->formatCategoryItemPayload($item);
        })->values();

        $clients = $category->clients->map(function ($client) {
            return $this->formatCategoryClientPayload($client);
        })->values();

        return [
            'category_id' => $category->category_id,
            'category_name' => $category->category_name,
            'category_type' => $category->category_type,
            'category_color' => $category->category_color,
            'category_icon' => $category->category_icon,
            'category_image' => $category->category_image,
            'category_image_url' => $category->category_image ? asset($category->category_image) : null,
            'description' => $category->description,
            'start_date' => optional($category->start_date)->format('Y-m-d'),
            'end_date' => optional($category->end_date)->format('Y-m-d'),
            'start_date_persian' => $category->start_date ? PersianCalendarHelper::gregorianToPersian($category->start_date->format('Y-m-d')) : null,
            'end_date_persian' => $category->end_date ? PersianCalendarHelper::gregorianToPersian($category->end_date->format('Y-m-d')) : null,
            'is_active' => $category->isActive(),
            'items_count' => $items->count(),
            'clients_count' => $clients->count(),
            'entities_count' => $category->category_type === 'customer' ? $clients->count() : $items->count(),
            'items' => $items,
            'clients' => $clients,
        ];
    }

    /**
     * Format category item payload
     */
    private function formatCategoryItemPayload(InventoryCustomCategoryItem $item)
    {
        return [
            'id' => $item->id,
            'inventory_id' => $item->inventory_id,
            'inventory_entry_id' => $item->inventory_entry_id,
            'inventory_name' => optional($item->inventory)->inventory_name,
            'inventory_code' => optional($item->inventory)->inventory_code,
            'entry_code' => optional($item->entry)->entry_code,
            'lot_number' => optional($item->entry)->lot_number,
            'serial_number' => optional($item->entry)->serial_number,
            'entry_date' => optional($item->entry?->entry_date)->format('Y-m-d'),
            'entry_date_persian' => $item->entry && $item->entry->entry_date
                ? PersianCalendarHelper::gregorianToPersian($item->entry->entry_date->format('Y-m-d'))
                : null,
            'expiry_date' => optional($item->entry?->expiry_date)->format('Y-m-d'),
            'expiry_date_persian' => $item->entry && $item->entry->expiry_date
                ? PersianCalendarHelper::gregorianToPersian($item->entry->expiry_date->format('Y-m-d'))
                : null,
            'alias_name' => $item->alias_name,
            'alias_color' => $item->alias_color,
            'alias_image' => $item->alias_image,
            'start_date' => optional($item->start_date)->format('Y-m-d'),
            'end_date' => optional($item->end_date)->format('Y-m-d'),
            'start_date_persian' => $item->start_date ? PersianCalendarHelper::gregorianToPersian($item->start_date->format('Y-m-d')) : null,
            'end_date_persian' => $item->end_date ? PersianCalendarHelper::gregorianToPersian($item->end_date->format('Y-m-d')) : null,
            'notes' => $item->notes,
        ];
    }

    private function formatCategoryClientPayload(InventoryCustomCategoryClient $client)
    {
        return [
            'id' => $client->id,
            'client_id' => $client->client_id,
            'client_name' => optional($client->client)->client_company_name,
            'alias_name' => $client->alias_name,
            'alias_color' => $client->alias_color,
            'alias_image' => $client->alias_image,
            'start_date' => optional($client->start_date)->format('Y-m-d'),
            'end_date' => optional($client->end_date)->format('Y-m-d'),
            'start_date_persian' => $client->start_date ? PersianCalendarHelper::gregorianToPersian($client->start_date->format('Y-m-d')) : null,
            'end_date_persian' => $client->end_date ? PersianCalendarHelper::gregorianToPersian($client->end_date->format('Y-m-d')) : null,
            'notes' => $client->notes,
        ];
    }

    private function normalizeDateInput(?string $value, string $field = 'date')
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        $value = trim($value);

        if (strpos($value, '/') !== false) {
            if (!PersianCalendarHelper::isValidPersianDate($value)) {
                throw ValidationException::withMessages([
                    $field => 'تاریخ وارد شده معتبر نیست.',
                ]);
            }
            return PersianCalendarHelper::persianToGregorian($value);
        }

        $date = \DateTime::createFromFormat('Y-m-d', $value);
        if ($date) {
            return $date->format('Y-m-d');
        }

        throw ValidationException::withMessages([
            $field => 'تاریخ وارد شده معتبر نیست.',
        ]);
    }

    private function validateDateOrder(?string $startDate, ?string $endDate): void
    {
        if ($startDate && $endDate && $startDate > $endDate) {
            throw ValidationException::withMessages([
                'end_date' => 'تاریخ پایان باید بعد از تاریخ شروع باشد.',
            ]);
        }
    }

    private function prepareCategoryImagePayload(Request $request, ?InventoryCustomCategory $category = null): array
    {
        $remove = $request->boolean('category_image_remove');
        $uploadData = $request->input('category_image_upload');
        $currentPath = $category ? $category->category_image : null;
        $updated = false;

        if ($remove && $currentPath) {
            $this->deleteCategoryImage($currentPath);
            $currentPath = null;
            $updated = true;
        }

        if ($uploadData) {
            if ($currentPath) {
                $this->deleteCategoryImage($currentPath);
            }
            $currentPath = $this->storeBase64Image($uploadData);
            $updated = true;
        }

        return [
            'value' => $currentPath,
            'updated' => $updated,
        ];
    }

    private function storeBase64Image(string $data): string
    {
        if (!preg_match('/^data:image\/(\w+);base64,/', $data, $matches)) {
            throw ValidationException::withMessages([
                'category_image' => 'فرمت تصویر پشتیبانی نمی‌شود.',
            ]);
        }

        $extension = strtolower($matches[1]);
        $allowed = ['png', 'jpg', 'jpeg', 'gif', 'webp', 'svg'];
        if (!in_array($extension, $allowed)) {
            throw ValidationException::withMessages([
                'category_image' => 'فرمت تصویر پشتیبانی نمی‌شود.',
            ]);
        }

        $base64 = substr($data, strpos($data, ',') + 1);
        $binary = base64_decode($base64);

        if ($binary === false) {
            throw ValidationException::withMessages([
                'category_image' => 'ذخیره‌سازی تصویر با خطا مواجه شد.',
            ]);
        }

        $path = 'custom-categories/' . Str::random(32) . '.' . $extension;
        Storage::disk('public')->put($path, $binary);

        return 'storage/' . $path;
    }

    private function deleteCategoryImage(?string $path): void
    {
        if (!$path) {
            return;
        }

        $cleanPath = str_replace('storage/', '', $path);
        Storage::disk('public')->delete($cleanPath);
    }

    private function attachInitialEntities(InventoryCustomCategory $category, array $entityIds = [], array $entityEntries = []): void
    {
        if (empty($entityIds) && empty($entityEntries)) {
            return;
        }

        if ($category->category_type === 'customer') {
            foreach ($entityIds as $clientId) {
                $clientId = (int) $clientId;
                if (!$clientId) {
                    continue;
                }
                InventoryCustomCategoryClient::updateOrCreate(
                    [
                        'custom_category_id' => $category->category_id,
                        'client_id' => $clientId,
                    ],
                    []
                );
            }
            return;
        }

        $payloads = !empty($entityEntries) ? $entityEntries : array_map(function ($id) {
            return ['inventory_id' => (int) $id, 'inventory_entry_id' => null];
        }, $entityIds);

        foreach ($payloads as $payload) {
            $inventoryId = (int) Arr::get($payload, 'inventory_id');
            $entryId = Arr::get($payload, 'inventory_entry_id');

            $inventoryId = (int) $inventoryId;
            if (!$inventoryId) {
                continue;
            }
            $this->assertEntryMatchesInventory($entryId ? (int) $entryId : null, $inventoryId);
            InventoryCustomCategoryItem::updateOrCreate(
                [
                    'custom_category_id' => $category->category_id,
                    'inventory_id' => $inventoryId,
                    'inventory_entry_id' => $entryId,
                ],
                []
            );
        }
    }

    private function normalizeEntityEntryPayloads(array $rows): array
    {
        if (empty($rows)) {
            return [];
        }

        return collect($rows)->map(function ($row) {
            return [
                'inventory_id' => isset($row['inventory_id']) ? (int) $row['inventory_id'] : null,
                'inventory_entry_id' => isset($row['inventory_entry_id']) ? (int) $row['inventory_entry_id'] : null,
            ];
        })->filter(function ($row) {
            return !empty($row['inventory_id']);
        })->values()->toArray();
    }

    private function assertEntryMatchesInventory(?int $entryId, ?int $inventoryId): void
    {
        if (!$entryId || !$inventoryId) {
            return;
        }

        $entry = InventoryEntry::find($entryId);
        if (!$entry || $entry->inventory_id !== $inventoryId) {
            throw ValidationException::withMessages([
                'inventory_entry_id' => 'ورود انتخاب‌شده با کالا مطابقت ندارد.',
            ]);
        }
    }
}
