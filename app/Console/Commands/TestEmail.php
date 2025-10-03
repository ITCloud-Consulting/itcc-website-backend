<?php
// app/Console/Commands/TestEmail.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    protected $signature = 'mail:test {email? : Email de destination}';
    protected $description = 'Teste la configuration email avec Gmail';

    public function handle(): int
    {
        $email = $this->argument('email') ?: config('mail.admin_email');

        if (empty($email)) {
            $this->error("❌ Aucun email de destination fourni.");
            $this->line("💡 Utilisation: php artisan mail:test user@example.com");
            $this->line("   ou définissez ADMIN_EMAIL dans votre fichier .env");
            return self::FAILURE;
        }
        
        $this->info("🧪 Test d'envoi d'email vers : {$email}");
        $this->info("📡 Serveur SMTP : " . config('mail.mailers.smtp.host'));
        $this->info("🔌 Port : " . config('mail.mailers.smtp.port'));
        $this->info("🔒 Encryption : " . config('mail.mailers.smtp.encryption'));
        
        try {
            Mail::raw('Ceci est un email de test depuis Laravel vers Gmail!', function ($message) use ($email) {
                $message->to($email)
                       ->subject('🧪 Test Email - Laravel + Gmail')
                       ->from(config('mail.from.address'), config('mail.from.name'));
            });
            
            $this->info("✅ Email de test envoyé avec succès !");
            return self::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("❌ Erreur lors de l'envoi : " . $e->getMessage());
            $this->error("🔍 Vérifiez vos paramètres SMTP dans le .env");
            return self::FAILURE;
        }
    }
}