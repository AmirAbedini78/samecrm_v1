<?php

namespace App\Http\Controllers;

use App\Models\InventoryAlertSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InventoryAlertController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Get alert settings
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $query = InventoryAlertSetting::with('inventory:inventory_id,inventory_name,inventory_code');

            if ($request->has('inventory_id')) {
                $query->where('inventory_id', $request->inventory_id);
            } else {
                $query->whereNull('inventory_id'); // Global settings
            }

            $settings = $query->get()->map(function ($setting) {
                return $this->formatAlertSetting($setting);
            });

            return response()->json([
                'success' => true,
                'data' => $settings
            ]);
        } catch (\Exception $e) {
            Log::error('Get Alert Settings Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store alert setting
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'inventory_id' => 'nullable|exists:inventory,inventory_id',
                'alert_type' => 'required|in:expiry,quantity,minimum,maximum',
                'threshold_value' => 'nullable|numeric|min:0',
                'threshold_days' => 'nullable|integer|min:0',
                'alert_email' => 'nullable|boolean',
                'alert_sms' => 'nullable|boolean',
                'alert_email_addresses' => 'nullable|string',
                'alert_phone_numbers' => 'nullable|string',
                'is_active' => 'nullable|boolean',
            ]);

            $data['alert_email'] = $request->boolean('alert_email', true);
            $data['alert_sms'] = $request->boolean('alert_sms', false);
            $data['is_active'] = $request->boolean('is_active', true);

            $setting = InventoryAlertSetting::create($data);

            return response()->json([
                'success' => true,
                'data' => $this->formatAlertSetting($setting),
                'message' => 'تنظیمات هشدار با موفقیت ایجاد شد'
            ]);
        } catch (\Exception $e) {
            Log::error('Create Alert Setting Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update alert setting
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $setting = InventoryAlertSetting::findOrFail($id);

            $data = $request->validate([
                'alert_type' => 'sometimes|required|in:expiry,quantity,minimum,maximum',
                'threshold_value' => 'nullable|numeric|min:0',
                'threshold_days' => 'nullable|integer|min:0',
                'alert_email' => 'nullable|boolean',
                'alert_sms' => 'nullable|boolean',
                'alert_email_addresses' => 'nullable|string',
                'alert_phone_numbers' => 'nullable|string',
                'is_active' => 'nullable|boolean',
            ]);

            if ($request->has('alert_email')) {
                $data['alert_email'] = $request->boolean('alert_email');
            }
            if ($request->has('alert_sms')) {
                $data['alert_sms'] = $request->boolean('alert_sms');
            }
            if ($request->has('is_active')) {
                $data['is_active'] = $request->boolean('is_active');
            }

            $setting->update($data);

            return response()->json([
                'success' => true,
                'data' => $this->formatAlertSetting($setting),
                'message' => 'تنظیمات هشدار با موفقیت به‌روزرسانی شد'
            ]);
        } catch (\Exception $e) {
            Log::error('Update Alert Setting Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete alert setting
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $setting = InventoryAlertSetting::findOrFail($id);
            $setting->delete();

            return response()->json([
                'success' => true,
                'message' => 'تنظیمات هشدار با موفقیت حذف شد'
            ]);
        } catch (\Exception $e) {
            Log::error('Delete Alert Setting Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle alert setting active status
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggle($id)
    {
        try {
            $setting = InventoryAlertSetting::with('inventory:inventory_id,inventory_name,inventory_code')->findOrFail($id);
            $setting->is_active = !$setting->is_active;
            $setting->save();

            return response()->json([
                'success' => true,
                'data' => $this->formatAlertSetting($setting),
                'message' => 'وضعیت هشدار تغییر کرد'
            ]);
        } catch (\Exception $e) {
            Log::error('Toggle Alert Setting Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    private function formatAlertSetting(InventoryAlertSetting $setting)
    {
        return [
            'alert_id' => $setting->alert_id,
            'inventory_id' => $setting->inventory_id,
            'inventory_name' => optional($setting->inventory)->inventory_name,
            'inventory_code' => optional($setting->inventory)->inventory_code,
            'alert_type' => $setting->alert_type,
            'threshold_value' => $setting->threshold_value,
            'threshold_days' => $setting->threshold_days,
            'alert_email' => $setting->alert_email,
            'alert_sms' => $setting->alert_sms,
            'alert_email_addresses' => $setting->alert_email_addresses,
            'alert_phone_numbers' => $setting->alert_phone_numbers,
            'is_active' => $setting->is_active,
        ];
    }
}

