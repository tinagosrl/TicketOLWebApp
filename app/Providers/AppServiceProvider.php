<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\GlobalSetting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Avoid running this during migration or if table doesn't exist
        if (!Schema::hasTable('global_settings')) {
            return;
        }

        try {
            $settings = GlobalSetting::where('group', 'system')->pluck('value', 'key');
            
            if ($settings->isNotEmpty()) {
                // Determine Mailer
                $mailer = $settings['mail_mailer'] ?? config('mail.default');
                config(['mail.default' => $mailer]);

                // Configure SMTP
                if ($mailer === 'smtp') {
                    config([
                        'mail.mailers.smtp.host' => $settings['mail_host'] ?? config('mail.mailers.smtp.host'),
                        'mail.mailers.smtp.port' => $settings['mail_port'] ?? config('mail.mailers.smtp.port'),
                        'mail.mailers.smtp.encryption' => $settings['mail_encryption'] ?? config('mail.mailers.smtp.encryption'),
                        'mail.mailers.smtp.username' => $settings['mail_username'] ?? config('mail.mailers.smtp.username'),
                        'mail.mailers.smtp.password' => $settings['mail_password'] ?? config('mail.mailers.smtp.password'),
                    ]);
                }

                // Configure Mailgun
                if ($mailer === 'mailgun') {
                    config([
                        'services.mailgun.domain' => $settings['mailgun_domain'] ?? config('services.mailgun.domain'),
                        'services.mailgun.secret' => $settings['mailgun_secret'] ?? config('services.mailgun.secret'),
                        'services.mailgun.endpoint' => $settings['mailgun_endpoint'] ?? config('services.mailgun.endpoint'),
                    ]);
                }

                // Configure From Address
                config([
                    'mail.from.address' => $settings['mail_from_address'] ?? config('mail.from.address'),
                    'mail.from.name' => $settings['mail_from_name'] ?? config('mail.from.name'),
                ]);

                // Configure Aruba SMS (Example if needed globally later)
                // config(['services.aruba.sms...']);
            }

        } catch (\Exception $e) {
            // Config loading failed, fallback to .env/config defaults
            // Log::error('Failed to load global settings: ' . $e->getMessage());
        }
    }
}
