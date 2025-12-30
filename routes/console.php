<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

Artisan::command('debug:mail-config', function () {
    $this->info('Current Mail Config:');
    $this->info('Driver: ' . Config::get('mail.default'));
    $this->info('Host: ' . Config::get('mail.mailers.smtp.host'));
    $this->info('Port: ' . Config::get('mail.mailers.smtp.port'));
    $this->info('Encryption: ' . Config::get('mail.mailers.smtp.encryption'));
    $this->info('Username: ' . Config::get('mail.mailers.smtp.username'));
    $this->info('From: ' . json_encode(Config::get('mail.from')));
    
    // Mailgun specific
    $this->info('Mailgun Domain: ' . Config::get('services.mailgun.domain'));
    $this->info('Mailgun Endpoint: ' . Config::get('services.mailgun.endpoint'));
});
