# Exemples de Test de l'API Hospital

## 🚀 Variables d'Environnement

Avant de tester, définissez l'URL de base selon votre environnement :

```bash
# En local
$BASE_URL = "http://localhost:8000"

# En Docker (si exposé sur le port 8000)
$BASE_URL = "http://localhost:8000"

# Depuis un autre conteneur Docker
$BASE_URL = "http://hospital-api:8000"
```

---

## 1️⃣ Inscription d'un Nouvel Utilisateur (Register)

### 📌 Requête POST `/api/register`

```bash
curl -X POST "$BASE_URL/api/register" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Jean Dupont",
    "email": "jean.dupont@example.com",
    "password": "password123",
    "cin": "12345678A",
    "date_naissance": "1990-05-15",
    "sexe": "M",
    "telephone": "06-12-34-56-78",
    "adresse": "123 Rue de la Paix, 75000 Paris",
    "contact_urgence": "06-87-65-43-21",
    "blood_type": "O+",
    "chronic_diseases": "Diabète",
    "allergies": "Pénicilline"
  }'
```

### ✅ Réponse Attendue (201)

```json
{
    "message": "Utilisateur et patient créés avec succès",
    "user": {
        "id": 1,
        "name": "Jean Dupont",
        "email": "jean.dupont@example.com",
        "role": "user",
        "created_at": "2026-01-11T10:30:00.000000Z",
        "updated_at": "2026-01-11T10:30:00.000000Z"
    },
    "patient": {
        "id": 1,
        "user_id": 1,
        "cin": "12345678A",
        "date_naissance": "1990-05-15",
        "sexe": "M",
        "telephone": "06-12-34-56-78",
        "adresse": "123 Rue de la Paix, 75000 Paris",
        "contact_urgence": "06-87-65-43-21",
        "groupe_sanguin": "O+",
        "maladies_chroniques": "Diabète",
        "allergies": "Pénicilline",
        "created_at": "2026-01-11T10:30:00.000000Z",
        "updated_at": "2026-01-11T10:30:00.000000Z"
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
}
```

---

## 2️⃣ Connexion (Login)

### 📌 Requête POST `/api/login`

```bash
curl -X POST "$BASE_URL/api/login" \
  -H "Content-Type: application/json" \
  -d '{
    "email": "jean.dupont@example.com",
    "password": "password123"
  }'
```

### ✅ Réponse Attendue (200)

```json
{
    "message": "Connexion réussie",
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "user": {
        "id": 1,
        "name": "Jean Dupont",
        "email": "jean.dupont@example.com",
        "role": "user",
        "created_at": "2026-01-11T10:30:00.000000Z",
        "updated_at": "2026-01-11T10:30:00.000000Z"
    }
}
```

---

## 3️⃣ Accéder au Profil (Route Protégée)

### 📌 Requête GET `/api/profile` (Authentifiée)

```bash
# Remplacez TOKEN par le token retourné à la connexion
TOKEN="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."

curl -X GET "$BASE_URL/api/profile" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json"
```

### ✅ Réponse Attendue (200)

```json
{
    "id": 1,
    "name": "Jean Dupont",
    "email": "jean.dupont@example.com",
    "role": "user",
    "created_at": "2026-01-11T10:30:00.000000Z",
    "updated_at": "2026-01-11T10:30:00.000000Z"
}
```

---

## ❌ Erreurs Courantes et Solutions

### ❌ 422 - Validation Error

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email has already been taken."]
    }
}
```

**Solution :** Utilisez une adresse email unique pour chaque test.

---

### ❌ 400 - Erreur SQL

```json
{
    "error": "SQLSTATE[HY000]: General error: 1364 Field 'groupe_sanguin' doesn't have a default value"
}
```

**Solution :**

-   Relancez les migrations : `php artisan migrate:refresh`
-   Vérifiez que la migration a `$table->id()` en premier

---

### ❌ 401 - Unauthorized (Connexion échouée)

```json
{
    "error": "Email ou mot de passe incorrect"
}
```

**Solution :** Vérifiez email/password corrects.

---

### ❌ 500 - Token Error

```json
{
    "error": "Erreur lors de la génération du token"
}
```

**Solution :**

-   Vérifiez que `JWT_SECRET` est défini dans `.env`
-   Lancez : `php artisan jwt:secret` si ce n'est pas fait

---

## 🧪 Script de Test Automatisé (PowerShell)

Créez un fichier `test-api.ps1` :

```powershell
# Configuration
$BASE_URL = "http://localhost:8000"
$EMAIL = "test-$(Get-Random)@example.com"
$PASSWORD = "password123"

Write-Host "=== Test API Hospital ===" -ForegroundColor Cyan

# 1. Register
Write-Host "`n[1] Inscription d'un nouvel utilisateur..." -ForegroundColor Yellow
$registerBody = @{
    name = "Test User"
    email = $EMAIL
    password = $PASSWORD
    cin = "TEST$(Get-Random)"
    date_naissance = "1990-05-15"
    sexe = "M"
    telephone = "06-00-00-00-00"
    adresse = "Test Address"
    contact_urgence = "06-00-00-00-01"
    blood_type = "O+"
} | ConvertTo-Json

$registerResponse = Invoke-RestMethod -Uri "$BASE_URL/api/register" -Method Post -Headers @{"Content-Type" = "application/json"} -Body $registerBody

Write-Host "✅ Inscription réussie!" -ForegroundColor Green
Write-Host "Token: $($registerResponse.token.Substring(0, 30))..." -ForegroundColor Cyan
$TOKEN = $registerResponse.token

# 2. Login
Write-Host "`n[2] Test de connexion..." -ForegroundColor Yellow
$loginBody = @{
    email = $EMAIL
    password = $PASSWORD
} | ConvertTo-Json

$loginResponse = Invoke-RestMethod -Uri "$BASE_URL/api/login" -Method Post -Headers @{"Content-Type" = "application/json"} -Body $loginBody

Write-Host "✅ Connexion réussie!" -ForegroundColor Green

# 3. Get Profile
Write-Host "`n[3] Récupération du profil..." -ForegroundColor Yellow
$profileResponse = Invoke-RestMethod -Uri "$BASE_URL/api/profile" -Method Get -Headers @{
    "Authorization" = "Bearer $TOKEN"
    "Content-Type" = "application/json"
}

Write-Host "✅ Profil récupéré!" -ForegroundColor Green
Write-Host $profileResponse | ConvertTo-Json

Write-Host "`n=== Tous les tests réussis! ===" -ForegroundColor Green
```

Exécutez :

```bash
powershell.exe -ExecutionPolicy Bypass -File test-api.ps1
```

---

## 📋 Checklist de Vérification

-   [ ] `.env` contient `JWT_SECRET`
-   [ ] `php artisan migrate:refresh` exécuté
-   [ ] `php artisan jwt:secret` exécuté
-   [ ] Le serveur Laravel tourne : `php artisan serve`
-   [ ] Les routes `/api/register`, `/api/login` sont définies
-   [ ] Le modèle `Patient` contient tous les `$fillable`
-   [ ] La migration `patients` contient `$table->id()`
