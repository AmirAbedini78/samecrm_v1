<?php

namespace App\Services;

use App\Models\SmsQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Exception;

class SmsService
{
    protected $apiKey;
    protected $sender;
    protected $baseUrl = 'https://api.ghasedak.me/v2';

    public function __construct()
    {
        $this->apiKey = env('GHASEDAK_API_KEY');
        $this->sender = env('GHASEDAK_SENDER');
    }

    /**
     * Send SMS directly
     *
     * @param string $to Phone number
     * @param string $message Message text
     * @param string $type Message type
     * @return bool
     */
    public function sendSms($to, $message, $type = 'general')
    {
        if (!$this->isConfigured()) {
            Log::warning('SMS service is not configured. Please set GHASEDAK_API_KEY and GHASEDAK_SENDER in .env');
            return false;
        }

        try {
            // Remove any non-numeric characters from phone number
            $to = preg_replace('/[^0-9]/', '', $to);
            
            // Ensure phone number starts with country code (for Iran: 98)
            if (strlen($to) == 10) {
                $to = '98' . $to;
            } elseif (strpos($to, '0') === 0) {
                $to = '98' . substr($to, 1);
            }

            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
            ])->asForm()->post($this->baseUrl . '/sms/send/simple', [
                'receptor' => $to,
                'message' => $message,
                'sender' => $this->sender,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                
                if (isset($result['result']['code']) && $result['result']['code'] == 200) {
                    Log::info('SMS sent successfully', [
                        'to' => $to,
                        'type' => $type,
                        'result' => $result
                    ]);
                    return true;
                } else {
                    Log::error('SMS sending failed', [
                        'to' => $to,
                        'type' => $type,
                        'error' => $result
                    ]);
                    return false;
                }
            } else {
                Log::error('SMS API request failed', [
                    'to' => $to,
                    'type' => $type,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return false;
            }
        } catch (Exception $e) {
            Log::error('SMS sending exception', [
                'to' => $to,
                'type' => $type,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Queue SMS for later sending
     *
     * @param string $to Phone number
     * @param string $message Message text
     * @param string $type Message type
     * @return SmsQueue
     */
    public function queueSms($to, $message, $type = 'general')
    {
        return SmsQueue::create([
            'sms_to' => $to,
            'sms_message' => $message,
            'sms_type' => $type,
            'sms_status' => 'new',
        ]);
    }

    /**
     * Send bulk SMS
     *
     * @param array $recipients Array of phone numbers
     * @param string $message Message text
     * @param string $type Message type
     * @return array Results
     */
    public function sendBulkSms($recipients, $message, $type = 'general')
    {
        $results = [];
        
        foreach ($recipients as $to) {
            $results[$to] = $this->sendSms($to, $message, $type);
        }
        
        return $results;
    }

    /**
     * Process SMS queue
     *
     * @param int $limit Number of SMS to process
     * @return int Number of SMS processed
     */
    public function processQueue($limit = 20)
    {
        $smsList = SmsQueue::new()->take($limit)->get();
        $processed = 0;

        foreach ($smsList as $sms) {
            $success = $this->sendSms($sms->sms_to, $sms->sms_message, $sms->sms_type);
            
            if ($success) {
                $sms->markAsSent();
                $processed++;
            } else {
                $sms->markAsFailed('Failed to send SMS');
            }
        }

        return $processed;
    }

    /**
     * Check if SMS service is configured
     *
     * @return bool
     */
    public function isConfigured()
    {
        return !empty($this->apiKey) && !empty($this->sender);
    }

    /**
     * Get SMS balance (if API supports it)
     *
     * @return float|null
     */
    public function getBalance()
    {
        if (!$this->isConfigured()) {
            return null;
        }

        try {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
            ])->get($this->baseUrl . '/sms/account/info');

            if ($response->successful()) {
                $result = $response->json();
                return $result['result']['balance'] ?? null;
            }
        } catch (Exception $e) {
            Log::error('Failed to get SMS balance', ['error' => $e->getMessage()]);
        }

        return null;
    }
}





