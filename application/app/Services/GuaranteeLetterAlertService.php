<?php

namespace App\Services;

use App\Models\GuaranteeLetter;
use App\Models\GuaranteeLetterNotification;
use App\Models\GuaranteeLetterNotificationLog;
use App\Models\EmailQueue;
use App\Models\User;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GuaranteeLetterAlertService
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Check all guarantee letter notifications
     *
     * @return array Alerts sent
     */
    public function checkAllNotifications()
    {
        $alertsSent = [];
        
        // Get all active notifications
        $notifications = GuaranteeLetterNotification::with('guaranteeLetter')
            ->where('is_active', true)
            ->get();

        foreach ($notifications as $notification) {
            $guarantee = $notification->guaranteeLetter;
            if (!$guarantee) {
                continue;
            }

            // Get the target date based on date_column
            $targetDate = $this->getTargetDate($guarantee, $notification->date_column);
            if (!$targetDate) {
                continue;
            }

            // Check if notification should be sent
            if ($notification->shouldSend($targetDate)) {
                $result = $this->sendNotification($notification, $guarantee, $targetDate);
                if ($result) {
                    $alertsSent[] = [
                        'guarantee_id' => $guarantee->guarantee_id,
                        'notification_id' => $notification->notification_id,
                        'date_column' => $notification->date_column,
                        'target_date' => $targetDate->format('Y-m-d'),
                    ];
                }
            }
        }

        return $alertsSent;
    }

    /**
     * Get target date from guarantee letter based on date column
     *
     * @param GuaranteeLetter $guarantee
     * @param string $dateColumn
     * @return Carbon|null
     */
    protected function getTargetDate($guarantee, $dateColumn)
    {
        switch ($dateColumn) {
            case 'issue_date':
                return $guarantee->issue_date ? Carbon::parse($guarantee->issue_date) : null;
            case 'expiry_date':
                return $guarantee->expiry_date ? Carbon::parse($guarantee->expiry_date) : null;
            case 'renewal_date':
                return $guarantee->renewal_date ? Carbon::parse($guarantee->renewal_date) : null;
            case 'settlement_date':
                return $guarantee->settlement_date ? Carbon::parse($guarantee->settlement_date) : null;
            default:
                return null;
        }
    }

    /**
     * Send notification for a guarantee letter
     *
     * @param GuaranteeLetterNotification $notification
     * @param GuaranteeLetter $guarantee
     * @param Carbon $targetDate
     * @return bool
     */
    protected function sendNotification($notification, $guarantee, $targetDate)
    {
        $message = $this->buildMessage($notification, $guarantee, $targetDate);
        $recipients = $this->getRecipients($notification, $guarantee);

        if (empty($recipients)) {
            Log::warning('No recipients found for guarantee letter notification', [
                'guarantee_id' => $guarantee->guarantee_id,
                'notification_id' => $notification->notification_id,
            ]);
            return false;
        }

        $sent = false;

        foreach ($recipients as $recipient) {
            // Send email
            if (in_array($notification->notification_type, ['email', 'both'])) {
                if ($this->sendEmail($recipient, $message, $notification, $guarantee)) {
                    $sent = true;
                }
            }

            // Send SMS
            if (in_array($notification->notification_type, ['sms', 'both'])) {
                if ($this->sendSms($recipient, $message, $notification, $guarantee)) {
                    $sent = true;
                }
            }
        }

        if ($sent) {
            // Update notification tracking
            $notification->last_sent_at = now();
            $notification->sent_count = $notification->sent_count + 1;
            $notification->save();
        }

        return $sent;
    }

    /**
     * Get recipients for notification
     *
     * @param GuaranteeLetterNotification $notification
     * @param GuaranteeLetter $guarantee
     * @return array
     */
    protected function getRecipients($notification, $guarantee)
    {
        $recipients = [];

        // Get recipients from notification settings
        $recipientUserIds = $notification->recipient_user_ids_array;
        
        // If no specific recipients, use assigned user
        if (empty($recipientUserIds)) {
            if ($guarantee->assigned_user_id) {
                $recipientUserIds = [$guarantee->assigned_user_id];
            } else {
                // Fallback to creator
                $recipientUserIds = [$guarantee->guarantee_creatorid];
            }
        }

        // Get user details
        foreach ($recipientUserIds as $userId) {
            $user = User::find($userId);
            if ($user) {
                $recipients[] = [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'phone' => $user->phone ?? null,
                    'name' => $user->first_name . ' ' . $user->last_name,
                ];
            }
        }

        return $recipients;
    }

    /**
     * Build notification message
     *
     * @param GuaranteeLetterNotification $notification
     * @param GuaranteeLetter $guarantee
     * @param Carbon $targetDate
     * @return array
     */
    protected function buildMessage($notification, $guarantee, $targetDate)
    {
        $now = Carbon::now();
        $daysDiff = $now->diffInDays($targetDate, false);
        
        $dateLabel = $this->getDateLabel($notification->date_column);
        $formattedDate = runtimeDate($targetDate->format('Y-m-d'));
        
        // Use custom message if provided
        if (!empty($notification->custom_message)) {
            $message = $notification->custom_message;
            $message = str_replace('{guarantee_number}', $guarantee->guarantee_number, $message);
            $message = str_replace('{date}', $formattedDate, $message);
            $message = str_replace('{days}', abs($daysDiff), $message);
            $message = str_replace('{date_label}', $dateLabel, $message);
        } else {
            // Default message
            if ($daysDiff > 0) {
                $message = "ضمانت نامه شماره {$guarantee->guarantee_number} تا {$daysDiff} روز دیگر به {$dateLabel} ({$formattedDate}) می‌رسد.";
            } else {
                $message = "ضمانت نامه شماره {$guarantee->guarantee_number} به {$dateLabel} ({$formattedDate}) رسیده است.";
            }
        }

        $subject = "هشدار ضمانت نامه: {$guarantee->guarantee_number}";
        $smsText = $message;

        return [
            'subject' => $subject,
            'body' => $message,
            'sms_text' => $smsText,
        ];
    }

    /**
     * Get date label in Persian
     *
     * @param string $dateColumn
     * @return string
     */
    protected function getDateLabel($dateColumn)
    {
        $labels = [
            'issue_date' => 'تاریخ صدور',
            'expiry_date' => 'تاریخ انقضا',
            'renewal_date' => 'تاریخ تمدید',
            'settlement_date' => 'تاریخ تسویه',
        ];

        return $labels[$dateColumn] ?? $dateColumn;
    }

    /**
     * Send email notification
     *
     * @param array $recipient
     * @param array $message
     * @param GuaranteeLetterNotification $notification
     * @param GuaranteeLetter $guarantee
     * @return bool
     */
    protected function sendEmail($recipient, $message, $notification, $guarantee)
    {
        if (empty($recipient['email'])) {
            return false;
        }

        try {
            EmailQueue::create([
                'emailqueue_to' => $recipient['email'],
                'emailqueue_subject' => $message['subject'],
                'emailqueue_message' => $message['body'],
                'emailqueue_type' => 'guarantee_letter_alert',
                'emailqueue_status' => 'new',
            ]);

            // Log the notification
            $this->logNotification($guarantee, $notification, $recipient['email'], 'email', $message['body'], 'sent');

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to queue email for guarantee letter notification', [
                'guarantee_id' => $guarantee->guarantee_id,
                'email' => $recipient['email'],
                'error' => $e->getMessage()
            ]);

            $this->logNotification($guarantee, $notification, $recipient['email'], 'email', $message['body'], 'failed', $e->getMessage());

            return false;
        }
    }

    /**
     * Send SMS notification
     *
     * @param array $recipient
     * @param array $message
     * @param GuaranteeLetterNotification $notification
     * @param GuaranteeLetter $guarantee
     * @return bool
     */
    protected function sendSms($recipient, $message, $notification, $guarantee)
    {
        if (empty($recipient['phone'])) {
            return false;
        }

        try {
            $this->smsService->queueSms($recipient['phone'], $message['sms_text'], 'guarantee_letter_alert');

            // Log the notification
            $this->logNotification($guarantee, $notification, $recipient['phone'], 'sms', $message['sms_text'], 'sent');

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to queue SMS for guarantee letter notification', [
                'guarantee_id' => $guarantee->guarantee_id,
                'phone' => $recipient['phone'],
                'error' => $e->getMessage()
            ]);

            $this->logNotification($guarantee, $notification, $recipient['phone'], 'sms', $message['sms_text'], 'failed', $e->getMessage());

            return false;
        }
    }

    /**
     * Log notification
     *
     * @param GuaranteeLetter $guarantee
     * @param GuaranteeLetterNotification $notification
     * @param string $sentTo
     * @param string $sentType
     * @param string $message
     * @param string $status
     * @param string|null $errorMessage
     */
    protected function logNotification($guarantee, $notification, $sentTo, $sentType, $message, $status, $errorMessage = null)
    {
        try {
            GuaranteeLetterNotificationLog::create([
                'guarantee_id' => $guarantee->guarantee_id,
                'notification_id' => $notification->notification_id,
                'sent_at' => now(),
                'sent_to' => $sentTo,
                'sent_type' => $sentType,
                'message' => $message,
                'status' => $status,
                'error_message' => $errorMessage,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log guarantee letter notification', [
                'guarantee_id' => $guarantee->guarantee_id,
                'error' => $e->getMessage()
            ]);
        }
    }
}

