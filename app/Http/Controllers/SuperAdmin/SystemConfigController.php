<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\GlobalSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SystemConfigController extends Controller
{
    public function edit()
    {
        $settings = GlobalSetting::where('group', 'system')->pluck('value', 'key');
        return view('admin.system_config.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        // Validation (can be expanded)
        $request->validate([
            'mail_mailer' => 'nullable|string', // smtp, mailgun, etc.
            'mail_host' => 'nullable|string',
            'mail_port' => 'nullable|numeric',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|string',
            'mail_from_address' => 'nullable|email',
            'mail_from_name' => 'nullable|string',
            
            // Mailgun specific
            'mailgun_domain' => 'nullable|string',
            'mailgun_secret' => 'nullable|string',
            'mailgun_endpoint' => 'nullable|string',
            
            'aruba_sms_username' => 'nullable|string',
            'aruba_sms_password' => 'nullable|string',
            'aruba_sms_sender' => 'nullable|string',
            'seo_meta_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string',
            'llm_context' => 'nullable|string',
        ]);

        $keys = [
            'mail_mailer', 'mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_encryption', 'mail_from_address', 'mail_from_name',
            'mailgun_domain', 'mailgun_secret', 'mailgun_endpoint',
            'aruba_sms_username', 'aruba_sms_password', 'aruba_sms_sender'
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                 GlobalSetting::set($key, $request->input($key), 'system');
            }
        }

        return redirect()->back()->with('success', 'Configurazione di sistema aggiornata con successo.');
    }

    public function sendTestEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            $testEmail = $request->input('test_email');
            
            Mail::raw('This is a test email from the System Configuration module. If you received this, your mail configuration is working correctly.', function ($message) use ($testEmail) {
                $message->to($testEmail)
                        ->subject('System Config Test Email');
            });

            return redirect()->back()->with('success', 'Email di prova inviata con successo a ' . $testEmail);
        } catch (\Exception $e) {
            Log::error('Test Email Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Invio fallito: ' . $e->getMessage());
        }
    }
}
