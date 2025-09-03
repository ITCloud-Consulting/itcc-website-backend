# IT Cloud Consulting - Backend

Ce projet est l'API backend pour le site IT Cloud Consulting, développé avec Laravel 10. Il gère les soumissions de formulaire de contact et l'envoi d'emails.

## Prérequis

- Docker et Docker Compose
- Git
- Un client HTTP comme Postman ou cURL pour tester les endpoints

## Installation

1. **Cloner le dépôt**
   ```bash
   git clone [URL_DU_REPO]
   cd companyBackend
   ```

2. **Copier le fichier d'environnement**
   ```bash
   cp .env.example .env
   ```

3. **Générer une clé d'application**
   ```bash
   docker-compose run --rm app php artisan key:generate
   ```

4. **Configurer l'environnement**
   Éditez le fichier `.env` avec vos paramètres :
   ```env
   APP_NAME="IT Cloud Consulting"
   APP_ENV=local
   APP_DEBUG=true
   APP_URL=http://localhost:8000
   
   DB_CONNECTION=mysql
   DB_HOST=db
   DB_PORT=3306
   DB_DATABASE=company
   DB_USERNAME=company
   DB_PASSWORD=secret
   
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.hostinger.com
   MAIL_PORT=465
   MAIL_USERNAME=support@itcloudconsultings.com
   MAIL_PASSWORD=votre_mot_de_passe
   MAIL_ENCRYPTION=ssl
   MAIL_FROM_ADDRESS=support@itcloudconsultings.com
   MAIL_FROM_NAME="IT Cloud Consulting"
   ```

5. **Démarrer les conteneurs Docker**
   ```bash
   docker-compose up -d
   ```

6. **Installer les dépendances**
   ```bash
   docker-compose exec app composer install
   ```

7. **Exécuter les migrations**
   ```bash
   docker-compose exec app php artisan migrate
   ```

## Démarrage

1. **Démarrer les services**
   ```bash
   # Si ce n'est pas déjà fait
   docker-compose up -d
   
   # Démarrer le worker pour les files d'attente
   docker-compose exec app php artisan queue:work --daemon
   ```

2. **Accéder à l'application**
   - API : http://localhost:8000/api
   - Documentation : http://localhost:8000/api/documentation (si configurée)

## Utilisation

### Soumettre un formulaire de contact

**Endpoint** : `POST /api/v1/contact`

**Corps de la requête** :
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "subject": "Demande d'information",
    "message": "Bonjour, j'aimerais plus d'informations sur vos services."
}
```

**Réponse en cas de succès (201)** :
```json
{
    "success": true,
    "message": "Votre message a été envoyé avec succès. Nous vous répondrons bientôt!",
    "data": {
        "id": 1,
        "submitted_at": "2025-08-28T13:45:00.000000Z"
    }
}
```

## Configuration des emails

Le projet est configuré pour envoyer deux types d'emails :
1. Un accusé de réception automatique au visiteur
2. Une notification à l'équipe de support

### Configuration SMTP
Assurez-vous que les paramètres SMTP dans le fichier `.env` sont correctement configurés :
- `MAIL_MAILER=smtp`
- `MAIL_HOST=votre_hote_smtp`
- `MAIL_PORT=465`
- `MAIL_USERNAME=votre_email`
- `MAIL_PASSWORD=votre_mot_de_passe`
- `MAIL_ENCRYPTION=ssl`

## Tests

### Exécution des tests

Pour exécuter tous les tests :

```bash
docker-compose exec app php artisan test
```

### Tests d'emails

Le projet inclut des tests pour vérifier l'envoi des emails. Les tests couvrent :

1. **Email de réponse automatique**
   - Vérifie que l'email de confirmation est envoyé au visiteur
   - Vérifie le contenu et le destinataire de l'email

2. **Email de notification**
   - Vérifie que la notification est envoyée à l'équipe de support
   - Vérifie le contenu et le destinataire de l'email

3. **Commande de diagnostic**
   - Vérifie la configuration SMTP
   - Détecte les erreurs de configuration
   - Affiche un rapport de diagnostic

#### Exécution des tests d'emails

Pour exécuter uniquement les tests d'emails :

```bash
docker-compose exec app php artisan test tests/Feature/MailSendingTest.php
```

Pour exécuter le test de diagnostic de configuration email :

```bash
docker-compose exec app php artisan test tests/Feature/Commands/MailDiagnosticCommandTest.php
```

#### Vérification de la configuration email

Le projet inclut une commande pour diagnostiquer la configuration email :

```bash
docker-compose exec app php artisan mail:diagnostic
```

Cette commande affiche :
- La configuration actuelle du service email
- Les vérifications de configuration
- Les problèmes potentiels
- Des suggestions de résolution

## Maintenance

### Commandes de base

#### Arrêter les conteneurs
```bash
docker-compose down
```

#### Voir les logs
```bash
docker-compose logs -f
```

### Commandes de base de données

#### Exécuter les migrations
```bash
docker-compose exec app php artisan migrate
```

#### Annuler la dernière migration
```bash
docker-compose exec app php artisan migrate:rollback
```

#### Recréer toute la base de données (attention: supprime les données existantes)
```bash
docker-compose exec app php artisan migrate:fresh
```

#### Générer une migration
```bash
docker-compose exec app php artisan make:migration nom_de_la_migration
```

### Commandes de test

#### Lancer tous les tests
```bash
docker-compose exec app php artisan test
```

#### Lancer les tests avec couverture de code
```bash
docker-compose exec app php artisan test --coverage
```

#### Lancer un fichier de test spécifique
```bash
docker-compose exec app php artisan test tests/Feature/ContactControllerTest.php
```

#### Lancer une méthode de test spécifique
```bash
docker-compose exec app php artisan test --filter=test_can_create_contact
```

#### Générer un test
```bash
docker-compose exec app php artisan make:test NomDuTest
```

## Sécurité

- Ne jamais commiter le fichier `.env` avec des informations sensibles
- Utiliser toujours des connexions sécurisées (HTTPS) en production
- Mettre à jour régulièrement les dépendances

## Licence

[À spécifier]
