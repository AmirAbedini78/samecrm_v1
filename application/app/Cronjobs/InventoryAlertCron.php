<?php

/** ---------------------------------------------------------------------------------------------------
 * Inventory Alert Cron
 * Check and send inventory alerts (expiry, quantity, etc.)
 * This cronjob is invoked by the task scheduler which is in 'application/app/Console/Kernel.php'
 *      - the scheduler is set to run this every 5 minutes
 * @package    Grow CRM
 * @author     NextLoop
 *-----------------------------------------------------------------------------------------------------*/

namespace App\Cronjobs;

use App\Services\InventoryAlertService;
use Illuminate\Support\Facades\Log;

class InventoryAlertCron
{
    public function __invoke()
    {
        //[MT] - tenants only
        if (env('MT_TPYE')) {
            if (\Spatie\Multitenancy\Models\Tenant::current() == null) {
                return;
            }
        }

        //boot system settings
        middlewareBootSettings();

        //[MT] boot mail settings
        env('MT_TPYE') ? middlewareSaaSBootMail() : middlewareBootMail();

        try {
            $alertService = app(InventoryAlertService::class);
            $results = $alertService->processAlerts();

            // Log results
            if ($results['total'] > 0) {
                Log::info('Inventory alerts processed', [
                    'process' => '[cronjob][inventory-alerts]',
                    'expiry_alerts' => count($results['expiry']),
                    'quantity_alerts' => count($results['quantity']),
                    'total' => $results['total']
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Inventory Alert Cron Error', [
                'process' => '[cronjob][inventory-alerts]',
                'error' => $e->getMessage(),
                'file' => basename(__FILE__),
                'line' => __LINE__
            ]);
        }
    }
}



