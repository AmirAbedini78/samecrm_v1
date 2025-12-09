<?php

/** ---------------------------------------------------------------------------------------------------
 * Guarantee Letter Alert Cron
 * Process guarantee letter notifications and send alerts
 * This cronjob is invoked by the task scheduler which is in 'application/app/Console/Kernel.php'
 *      - the scheduler is set to run this every five minutes
 * @package    Grow CRM
 * @author     NextLoop
 *-----------------------------------------------------------------------------------------------------*/

namespace App\Cronjobs;

use App\Services\GuaranteeLetterAlertService;
use Illuminate\Support\Facades\Log;

class GuaranteeLetterAlertCron
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

        try {
            $alertService = app(GuaranteeLetterAlertService::class);

            // Check all guarantee letter notifications
            $alertsSent = $alertService->checkAllNotifications();

            if (count($alertsSent) > 0) {
                Log::info('Guarantee letter alerts processed', [
                    'process' => '[cronjob][guarantee-letter-alerts]',
                    'alerts_sent' => count($alertsSent),
                    'details' => $alertsSent
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Guarantee Letter Alert Cron Error', [
                'process' => '[cronjob][guarantee-letter-alerts]',
                'error' => $e->getMessage(),
                'file' => basename(__FILE__),
                'line' => __LINE__,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}

