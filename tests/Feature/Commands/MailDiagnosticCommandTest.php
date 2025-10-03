<?php

namespace Tests\Feature\Commands;

use Tests\TestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;

class MailDiagnosticCommandTest extends TestCase
{
    public function test_mail_diagnostic_command()
    {
        // Configuration de test
        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp', [
            'transport' => 'smtp',
            'host' => 'smtp.gmail.com',
            'port' => 465,
            'encryption' => 'ssl',
            'username' => 'test@example.com',
            'password' => 'secret',
            'timeout' => null,
            'local_domain' => null,
        ]);
        Config::set('mail.from', [
            'address' => 'test@example.com',
            'name' => 'Test Sender',
        ]);
        Config::set('mail.admin_email', 'admin@example.com');

        // Exécuter la commande
        $this->artisan('mail:diagnostic')
            ->assertExitCode(0)
            ->expectsOutputToContain('DIAGNOSTIC EMAIL GMAIL')
            ->expectsOutputToContain('CONFIGURATION ACTUELLE')
            ->expectsOutputToContain('VÉRIFICATIONS');
    }

    public function test_mail_diagnostic_shows_errors()
    {
        // Configuration invalide
        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp', [
            'transport' => 'smtp',
            'host' => '',
            'port' => 123,
            'encryption' => '',
            'username' => '',
            'password' => '',
        ]);
        Config::set('mail.from', [
            'address' => '',
            'name' => '',
        ]);
        Config::set('mail.admin_email', '');

        $this->artisan('mail:diagnostic')
            ->assertExitCode(0)
            ->expectsOutputToContain('❌');
    }
}
