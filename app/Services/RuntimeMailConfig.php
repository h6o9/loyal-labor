<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;

class RuntimeMailConfig
{
    /**
     * Apply SMTP credentials from admin Email Configuration (settings table)
     * so every outgoing mail uses the live panel config, not a stale mailer.
     */
    public static function apply(?object $setting = null): void
    {
        $setting = $setting ?? getSettings();

        $host = self::value($setting?->mail_host ?? null, env('MAIL_HOST'));
        $port = (int) self::value($setting?->mail_port ?? null, env('MAIL_PORT', 587));
        $username = self::value($setting?->mail_username ?? null, env('MAIL_USERNAME'));
        $password = self::value($setting?->mail_password ?? null, env('MAIL_PASSWORD'));
        $encryption = strtolower((string) self::value($setting?->mail_encryption ?? null, env('MAIL_ENCRYPTION', 'tls')));
        $senderEmail = self::value($setting?->mail_sender_email ?? null, env('MAIL_FROM_ADDRESS'));
        $senderName = loyalSanitizeBrandText((string) ($setting?->mail_sender_name ?? env('MAIL_FROM_NAME', loyalBrandName())));

        if ($host === '' || $username === '' || $password === '') {
            return;
        }

        $scheme = ($port === 465 || $encryption === 'ssl') ? 'smtps' : 'smtp';

        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp' => [
                'transport' => 'smtp',
                'scheme' => $scheme,
                'host' => $host,
                'port' => $port,
                'encryption' => $encryption !== '' ? $encryption : ($scheme === 'smtps' ? 'ssl' : 'tls'),
                'username' => $username,
                'password' => $password,
                'timeout' => 30,
                'auth_mode' => null,
            ],
            'mail.from.address' => $username !== '' ? $username : $senderEmail,
            'mail.from.name' => $senderName !== '' ? $senderName : loyalBrandName(),
        ]);

        Mail::purge();
        Mail::purge('smtp');
    }

    private static function value(mixed $value, mixed $fallback): string
    {
        $value = trim((string) $value);
        $placeholders = ['', 'mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_sender_email'];

        if (in_array($value, $placeholders, true)) {
            return trim((string) $fallback);
        }

        return $value;
    }
}
