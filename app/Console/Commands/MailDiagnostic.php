<?php
// app/Console/Commands/MailDiagnostic.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class MailDiagnostic extends Command
{
    protected $signature = 'mail:diagnostic';
    protected $description = 'Diagnostic de la configuration email';

    public function handle(): int
    {
        $this->info("🔍 DIAGNOSTIC EMAIL HOSTINGER");
        $this->line("");
        
        // Configuration actuelle
        $this->info("📋 CONFIGURATION ACTUELLE :");
        $this->table(['Paramètre', 'Valeur'], [
            ['MAIL_MAILER', config('mail.default')],
            ['MAIL_HOST', config('mail.mailers.smtp.host')],
            ['MAIL_PORT', config('mail.mailers.smtp.port')],
            ['MAIL_ENCRYPTION', config('mail.mailers.smtp.encryption')],
            ['MAIL_USERNAME', config('mail.mailers.smtp.username')],
            ['MAIL_FROM_ADDRESS', config('mail.from.address')],
            ['MAIL_FROM_NAME', config('mail.from.name')],
            ['ADMIN_EMAIL', config('mail.admin_email')],
        ]);
        
        $this->line("");
        
        // Vérifications
        $this->info("✅ VÉRIFICATIONS :");
        
        $checks = [
            'Username configuré' => !empty(config('mail.mailers.smtp.username')),
            'Password configuré' => !empty(config('mail.mailers.smtp.password')),
            'Host correct' => config('mail.mailers.smtp.host') === 'smtp.hostinger.com',
            'Port valide' => in_array(config('mail.mailers.smtp.port'), [587, 465]),
            'Encryption configurée' => in_array(config('mail.mailers.smtp.encryption'), ['tls', 'ssl']),
            'From address configurée' => !empty(config('mail.from.address')),
        ];
        
        foreach ($checks as $check => $result) {
            $status = $result ? '✅' : '❌';
            $this->line("{$status} {$check}");
        }
        
        $this->line("");
        
        // Recommandations
        if (!$checks['Host correct']) {
            $this->warn("⚠️  Host incorrect. Utilisez : smtp.hostinger.com");
        }
        
        if (!in_array(config('mail.mailers.smtp.port'), [587, 465])) {
            $this->warn("⚠️  Port recommandé : 587 (TLS) ou 465 (SSL)");
        }
        
        $this->line("");
        $this->info("💡 Pour tester l'envoi : php artisan mail:test");
        
        return self::SUCCESS;
    }
}