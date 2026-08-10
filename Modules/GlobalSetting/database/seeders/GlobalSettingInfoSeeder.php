<?php

namespace Modules\GlobalSetting\database\seeders;

use Exception;
use Illuminate\Database\Seeder;
use Modules\GlobalSetting\app\Models\AdminNotification;
use Modules\GlobalSetting\app\Models\Setting;

class GlobalSettingInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::truncate();

        $setting_data = [
            'app_name'                      => 'Loylabor',
            'version'                       => '3.0.0',
            'logo'                          => 'website/images/logo.webp',
            'logo_dark'                     => 'website/images/logo_2.webp',
            'timezone'                      => 'Asia/Dhaka',
            'date_format'                   => 'Y-m-d',
            'time_format'                   => 'h:i A',
            'favicon'                       => 'uploads/website-images/favicon.png',
            'cookie_status'                 => 'active',
            'border'                        => 'normal',
            'corners'                       => 'thin',
            'background_color'              => '#184dec',
            'text_color'                    => '#fafafa',
            'border_color'                  => '#0a58d6',
            'btn_bg_color'                  => '#fffceb',
            'btn_text_color'                => '#222758',
            'link_text'                     => 'Privacy Policy',
            'btn_text'                      => 'Yes',
            'message'                       => 'This website uses essential cookies to ensure its proper operation and tracking cookies to understand how you interact with it. The latter will be set only upon approval.',
            'copyright_text'                => '©2025 WebSolutionUS. All rights reserved.',
            'recaptcha_site_key'            => 'recaptcha_site_key',
            'recaptcha_secret_key'          => 'recaptcha_secret_key',
            'recaptcha_status'              => 'inactive',
            'tawk_status'                   => 'inactive',
            'tawk_chat_link'                => 'tawk_chat_link',
            'googel_tag_status'             => 'inactive',
            'googel_tag_id'                 => 'google_tag_id',
            'google_analytic_status'        => 'active',
            'google_analytic_id'            => 'google_analytic_id',
            'pixel_status'                  => 'inactive',
            'pixel_app_id'                  => 'pixel_app_id',
            'google_login_status'           => 'active',
            'google_client_id'              => 'google_client_id',
            'google_secret_id'              => 'google_secret_id',
            'default_avatar'                => 'uploads/website-images/default-avatar.png',
            'default_user_image'            => 'uploads/website-images/default-user-image.png',
            'breadcrumb_image'              => 'website/images/breadcrumbs_bg.webp',
            'admin_auth_bg'                 => 'backend/img/admin-auth-bg.webp',
            'admin_login_prefix'            => 'admin',
            'mail_host'                     => 'sandbox.smtp.mailtrap.io',
            'mail_sender_email'             => 'sender@gmail.com',
            'mail_username'                 => 'mail_username',
            'mail_password'                 => 'mail_password',
            'mail_port'                     => 2525,
            'mail_encryption'               => 'ssl',
            'mail_sender_name'              => 'Loylabor',
            'contact_message_receiver_mail' => 'receiver@gmail.com',
            'pusher_status'                 => 'inactive',
            'pusher_app_id'                 => 'pusher_app_id',
            'pusher_app_key'                => 'pusher_app_key',
            'pusher_app_secret'             => 'pusher_app_secret',
            'pusher_app_cluster'            => 'pusher_app_cluster',
            'maintenance_mode'              => 0,
            'maintenance_image'             => 'uploads/website-images/maintenance.jpg',
            'maintenance_title'             => 'Website Under maintenance',
            'maintenance_description'       => '<p>We are currently performing maintenance on our website to<br>improve your experience. Please check back later.</p>
            <p><a title="Websolutions" href="https://websolutionus.com/">Websolutions</a></p>',
            'last_update_date'              => date('Y-m-d H:i:s'),
            'is_queueable'                  => 'inactive',
            'comments_auto_approved'        => 'active',
            'search_engine_indexing'        => 'active',
            'theme'                         => 1,
            'has_vendor'                    => 1,
            'has_app'                       => 0,
            'can_guest_checkout'            => 1,
            'sku_prefix'                    => 'SKU-',
            'invoice_prefix'                => 'INV-',
            'sku_length'                    => 8,
            'invoice_length'                => 8,
            'wallet_amount_auto_approve'    => 1,
            'product_commission_rate'       => 5,
            'order_cancel_minutes_before'   => 35,
            'show_all_homepage'             => 1,
            'marketing_status'              => 1,
        ];

        if (env('DB_USERNAME') == 'root' && app()->isLocal()) {
            // TODO: Remove this condition in production
            $setting_data['mail_host']     = '127.0.0.1';
            $setting_data['mail_username'] = '';
            $setting_data['mail_password'] = '';
            $setting_data['mail_port']     = '25';
        }

        foreach ($setting_data as $index => $setting_item) {
            $new_item        = new Setting;
            $new_item->key   = $index;
            $new_item->value = $setting_item;
            $new_item->save();
        }

        try {
            $notification          = new AdminNotification;
            $notification->type    = 'success';
            $notification->title   = 'Installed Successfully';
            $notification->message = 'Welcome to ' . $setting_data['app_name'] . '(' . $setting_data['version'] . ')';
            $notification->save();
        } catch (Exception $e) {
            logError("Admin Notification Error", $e);
        }
    }
}
