<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        // 1. User Invitation (For Team Members)
        EmailTemplate::updateOrCreate(
            ['code' => 'user_invitation'],
            [
                'name' => 'User Invitation',
                'subject_en' => 'You have been invited to join {tenant_name}',
                'body_en' => '<p>Hello,</p><p>You have been invited to join the team at <strong>{tenant_name}</strong>.</p><p><a href="{link}" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Click here to accept</a></p>',
                'subject_it' => 'Sei stato invitato a unirti a {tenant_name}',
                'body_it' => '<p>Ciao,</p><p>Sei stato invitato a unirti al team di <strong>{tenant_name}</strong>.</p><p><a href="{link}" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Clicca qui per accettare</a></p>',
                'variables' => ['{tenant_name}', '{link}']
            ]
        );

        // 2. Welcome Email (After Registration)
        EmailTemplate::updateOrCreate(
            ['code' => 'welcome_email'],
            [
                'name' => 'Welcome Email',
                'subject_en' => 'Welcome to {app_name}',
                'body_en' => '<p>Welcome to {app_name}!</p><p>We are glad to have you on board.</p>',
                'subject_it' => 'Benvenuto su {app_name}',
                'body_it' => '<p>Benvenuto su {app_name}!</p><p>Siamo felici di averti con noi.</p>',
                'variables' => ['{app_name}', '{user_name}']
            ]
        );

        // 3. Ticket Issued (Sent to customer after purchase)
        EmailTemplate::updateOrCreate(
            ['code' => 'ticket_issued'],
            [
                'name' => 'Ticket Issued',
                'subject_en' => 'Your Tickets for {event_name}',
                'body_en' => '<p>Hi {customer_name},</p><p>Here are your tickets for <strong>{event_name}</strong>.</p><p>Please find the PDF attached or download it here: <a href="{download_link}">Download Tickets</a></p>',
                'subject_it' => 'I tuoi biglietti per {event_name}',
                'body_it' => '<p>Ciao {customer_name},</p><p>Ecco i tuoi biglietti per <strong>{event_name}</strong>.</p><p>Trovi il PDF in allegato o puoi scaricarlo qui: <a href="{download_link}">Scarica Biglietti</a></p>',
                'variables' => ['{customer_name}', '{event_name}', '{download_link}']
            ]
        );
    }
}
