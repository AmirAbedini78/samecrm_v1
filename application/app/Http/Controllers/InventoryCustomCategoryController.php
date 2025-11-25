<?php

namespace App\Http\Controllers;

use App\Models\InventoryCustomCategory;
use App\Models\InventoryCustomCategoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

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
                    'items.inventory:inventory_id,inventory_name,inventory_code'
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
            $data = $request->validate([
                'category_name' => 'required|string|max:255',
                'category_type' => 'required|string|in:item,customer',
                'category_color' => 'nullable|string|max:20',
                'category_icon' => 'nullable|string|max:255',
                'category_image' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:500',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
            ]);

            $data['user_id'] = Auth::id();

            $category = InventoryCustomCategory::create($data);

            return response()->json([
                'success' => true,
                'data' => $this->formatCategoryPayload($category->fresh('items.inventory')),
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

            $data = $request->validate([
                'category_name' => 'sometimes|required|string|max:255',
                'category_type' => 'sometimes|required|string|in:item,customer',
                'category_color' => 'nullable|string|max:20',
                'category_icon' => 'nullable|string|max:255',
                'category_image' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:500',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
            ]);

            $category->update($data);

            return response()->json([
                'success' => true,
                'data' => $this->formatCategoryPayload($category->fresh('items.inventory')),
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

            // Delete related items
            InventoryCustomCategoryItem::where('custom_category_id', $id)->delete();

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
            $data = $request->validate([
                'inventory_id' => 'required|exists:inventory,inventory_id',
                'custom_category_id' => 'required|exists:inventory_custom_categories,category_id',
                'alias_name' => 'nullable|string|max:255',
                'alias_color' => 'nullable|string|max:20',
                'alias_image' => 'nullable|string|max:255',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'notes' => 'nullable|string|max:500',
            ]);

            $item = InventoryCustomCategoryItem::updateOrCreate(
                Arr::only($data, ['inventory_id', 'custom_category_id']),
                Arr::except($data, ['inventory_id', 'custom_category_id'])
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
            $data = $request->validate([
                'inventory_id' => 'required|exists:inventory,inventory_id',
                'custom_category_id' => 'required|exists:inventory_custom_categories,category_id',
            ]);

            InventoryCustomCategoryItem::where('inventory_id', $data['inventory_id'])
                ->where('custom_category_id', $data['custom_category_id'])
                ->delete();

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
        $category->loadMissing(['items.inventory:inventory_id,inventory_name,inventory_code']);

        return [
            'category_id' => $category->category_id,
            'category_name' => $category->category_name,
            'category_type' => $category->category_type,
            'category_color' => $category->category_color,
            'category_icon' => $category->category_icon,
            'category_image' => $category->category_image,
            'description' => $category->description,
            'start_date' => optional($category->start_date)->format('Y-m-d'),
            'end_date' => optional($category->end_date)->format('Y-m-d'),
            'is_active' => $category->isActive(),
            'items_count' => $category->items->count(),
            'items' => $category->items->map(function ($item) {
                return $this->formatCategoryItemPayload($item);
            })->values(),
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
            'inventory_name' => optional($item->inventory)->inventory_name,
            'inventory_code' => optional($item->inventory)->inventory_code,
            'alias_name' => $item->alias_name,
            'alias_color' => $item->alias_color,
            'alias_image' => $item->alias_image,
            'start_date' => optional($item->start_date)->format('Y-m-d'),
            'end_date' => optional($item->end_date)->format('Y-m-d'),
            'notes' => $item->notes,
        ];
    }
}
