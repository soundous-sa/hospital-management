# Configuration Rapide pour Docker

## Avant de lancer les tests

### 1️⃣ Assurez-vous que .env contient:

```
JWT_SECRET=votre_secret_genere_par_php_artisan_jwt_secret
JWT_ALGO=HS256
JWT_TTL=60
JWT_REFRESH_TTL=20160
JWT_BLACKLIST_ENABLED=true
```

### 2️⃣ Exécutez dans le conteneur:

```bash
docker-compose down -v    # Réinitialiser
docker-compose up -d      # Redémarrer
docker-compose exec hospital-api bash

# Depuis le conteneur
composer install
php artisan key:generate
php artisan jwt:secret
php artisan migrate:fresh --seed
php artisan cache:clear
exit
```

### 3️⃣ Testez l'API

```bash
# PowerShell - Test rapide d'inscription
$url = "http://localhost:8000/api/register"
$body = @{
    name = "Test"
    email = "test@test.com"
    password = "password123"
    cin = "111111"
    date_naissance = "1990-01-01"
    sexe = "M"
    telephone = "0600000000"
    adresse = "123 Street"
    contact_urgence = "0612345678"
} | ConvertTo-Json

Invoke-RestMethod -Uri $url -Method Post -Headers @{"Content-Type"="application/json"} -Body $body
```

Si vous recevez un `token`, c'est que tout fonctionne! ✅
