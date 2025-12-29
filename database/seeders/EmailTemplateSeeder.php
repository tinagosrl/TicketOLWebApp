<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        EmailTemplate::create([
            'name' => 'User Invitation',
            'subject_en' => 'You have been invited to join {tenant_name}',
            'body_en' => '<p>Hello,</p><p>You have been invited to join the team at <strong>{tenant_name}</strong>.</p><p><a href="{link}">Click here to accept</a></p>',
            'subject_it' => 'Sei stato invitato a unirti a {tenant_name}',
            'body_it' => '<p>Ciao,</p><p>Sei stato invitato a unirti al team di <strong>{tenant_name}</strong>.</p><p><a href="{link}">Clicca qui per accettare</a></p>',
        ]);

        EmailTemplate::create([
            'name' => 'Welcome Email',
            'subject_en' => 'Welcome to {app_name}',
            'body_en' => '<p>Welcome to {app_name}!</p><p>We are glad to have you.</p>',
            'subject_it' => 'Benvenuto su {app_name}',
            'body_it' => '<p>Benvenuto su {app_name}!</p><p>Siamo felici di averti con noi.</p>',
        ]);
    }
}
