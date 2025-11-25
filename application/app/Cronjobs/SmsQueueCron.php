<?php

/** ---------------------------------------------------------------------------------------------------
 * SMS Queue Cron
 * Process SMS queue and send SMS messages
 * This cronjob is invoked by the task scheduler which is in 'application/app/Console/Kernel.php'
 *      - the scheduler is set to run this every minute
 * @package    Grow CRM
 * @author     NextLoop
 *-----------------------------------------------------------------------------------------------------*/

namespace App\Cronjobs;

use App\Services\SmsService;
use Illuminate\Support\Facades\Log;

class SmsQueueCron
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
            $smsService = app(SmsService::class);

            // Check if SMS service is configured
            if (!$smsService->isConfigured()) {
                return; // Skip if not configured
            }

            // Process SMS queue (limit 20 per run)
            $processed = $smsService->processQueue(20);

            if ($processed > 0) {
                Log::info('SMS queue processed', [
                    'process' => '[cronjob][sms-queue]',
                    'processed' => $processed
                ]);
            }
        } catch (\Exception $e) {
            Log::error('SMS Queue Cron Error', [
                'process' => '[cronjob][sms-queue]',
                'error' => $e->getMessage(),
                'file' => basename(__FILE__),
                'line' => __LINE__
            ]);
        }
    }
}

