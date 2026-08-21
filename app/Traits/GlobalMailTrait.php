<?php

namespace App\Traits;

use App\Jobs\GlobalMailJob;
use App\Mail\GlobalMail;
use App\Services\RuntimeMailConfig;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Modules\GlobalSetting\app\Models\EmailTemplate;

trait GlobalMailTrait
{
    private static function isQueable(): bool
    {
        return getSettingStatus('is_queueable');
    }

    /**
     * Queue only when the admin toggle is on AND cron is confirmed running.
     * Otherwise mails sit in the jobs table and never get delivered.
     */
    private static function shouldQueueMail(): bool
    {
        return self::isQueable() && Cache::get('corn_working') === 'working';
    }

    /**
     * Sends an email using the specified subject and message.
     *
     * @param  string  $mail_address  The email address to send the mail to.
     * @param  string  $mail_subject  The subject of the email.
     * @param  string  $mail_message  The body message of the email.
     * @param  array  $link  An associative array containing one key-value pair. Example: ['Link Name' => 'https://example.com/link']
     * @param  bool  $immediate  Force SMTP send now (OTP / time-sensitive mail).
     * @return void
     */
    public function sendMail($mail_address, $mail_subject, $mail_message, $link = [], bool $immediate = false)
    {
        RuntimeMailConfig::apply();

        try {
            if (! $immediate && self::shouldQueueMail()) {
                dispatch(new GlobalMailJob($mail_address, $mail_subject, $mail_message, $link));
                Log::info('Mail queued', [
                    'to' => $mail_address,
                    'subject' => $mail_subject,
                ]);

                return;
            }

            Mail::mailer('smtp')->to($mail_address)->send(new GlobalMail($mail_subject, $mail_message, $link));
            Log::info('Mail sent via SMTP', [
                'to' => $mail_address,
                'subject' => $mail_subject,
                'host' => config('mail.mailers.smtp.host'),
            ]);
        } catch (\Exception $e) {
            Log::error('Mail send failed: ' . $e->getMessage(), [
                'to' => $mail_address,
                'subject' => $mail_subject,
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
            ]);
            throw $e;
        }
    }

    /**
     * Handle exceptions related to mail configuration and sending.
     *
     * Logs the exception message and determines the appropriate error message for different types of exceptions.
     * Redirects back with an error notification message.
     *
     * @param  \Exception  $e  The exception to handle.
     * @return \Illuminate\Http\RedirectResponse Redirects back with an error notification.
     */
    public function handleMailException(\Exception $e)
    {
        info($e->getMessage());
        if ($e instanceof \Symfony\Component\Mailer\Exception\TransportExceptionInterface) {
            $message = __('Please check your mail server configuration.');
        } elseif ($e instanceof \ErrorException) {
            if (strpos($e->getMessage(), 'Trying to access array offset on value of type null') !== false) {
                $message = __('Check your mail server configuration.');
            } else {
                $message = __('An unexpected error occurred.');
            }
        } else {
            $message = __('Mail sending operation failed. Please try again.');
        }

        $notification = ['message' => $message, 'alert-type' => 'error'];

        return redirect()->back()->with($notification);
    }

    /**
     * Fetches and processes an email template by replacing placeholders with actual values.
     *
     * - string The subject of the email template.
     * - string The processed message with placeholders replaced by actual values.
     *
     * @param  string  $templateName  The name of the email template to fetch.
     * @param  array  $str_replace  An optional associative array of placeholders and their corresponding values. Placeholders should be provided without the surrounding curly braces. Example: ['user_name' => 'John Doe', 'app_name' => 'MyApp']
     * @return array Returns an array with two elements:
     */
    public function fetchEmailTemplate($templateName, $str_replace = [])
    {
        // Fetch the template by name
        $template = EmailTemplate::where('name', $templateName)->first();

        if (!$template) {
            throw new \RuntimeException("Email template '{$templateName}' not found.");
        }

        $subject = loyalSanitizeBrandText($template->subject);
        $message = $template->message;

        // Check if the $str_replace array exists and is not empty
        if (! empty($str_replace)) {
            // Replace placeholders with actual values
            foreach ($str_replace as $key => $value) {
                if ($key === 'app_name') {
                    $value = loyalBrandName();
                }
                $message = str_replace(['{{'.$key.'}}', '{{ '.$key.' }}'], (string) $value, $message);
            }
        }

        $message = loyalSanitizeBrandText($message);

        return [$subject, $message];
    }
}
