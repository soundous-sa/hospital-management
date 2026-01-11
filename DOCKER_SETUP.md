# 🐳 Guide Complet - Configuration et Test avec Docker

## 📋 Prérequis

- Docker installé
- Docker Compose installé
- PowerShell (Windows) ou Terminal (Linux/Mac)
- Un client HTTP (curl, Postman, ou PowerShell)

---

## 🚀 Étapes de Configuration

### **Étape 1 : Construire les Conteneurs Docker**

```bash
cd C:\Users\NB\Desktop\EmG\devweb\hospital-API\hospital-management
docker-compose build
```

**Attendus :**

- ✅ Build réussi sans erreurs
- ✅ Image `hospital-api` créée

---

### **Étape 2 : Démarrer les Conteneurs**

```bash
docker-compose up -d
```

**Attendus :**

- ✅ Conteneurs lancés en arrière-plan
- ✅ Services accessibles

Vérifiez avec :

```bash
docker-compose ps
```

---

### **Étape 3 : Installer les Dépendances PHP**

```bash
# Entre dans le conteneur backend
docker-compose exec hospital-api bash

# Depuis le conteneur
composer install
php artisan key:generate
```

---

### **Étape 4 : Configurer la Base de Données**

```bash
# Toujours dans le conteneur
php artisan migrate:refresh
# Ou pour la première fois
php artisan migrate
```

**Attendus :**

- ✅ Tables créées sans erreurs
- ✅ Message : "Migrated" ou "Already migrated"

---

### **Étape 5 : Générer le Secret JWT**

**IMPORTANT** : C'est obligatoire pour l'authentification JWT !

```bash
# Toujours dans le conteneur
php artisan jwt:secret
```

**Attendus :**

- ✅ Clé générée
- ✅ `.env` modifié avec `JWT_SECRET=xxx`

Vérifiez avec :

```bash
cat .env | grep JWT_SECRET
```

Vous devez voir :

```
JWT_SECRET=votre_cle_secrete_longue
```

---

### **Étape 6 : Vérifier les Routes**

```bash
# Toujours dans le conteneur
php artisan route:list | grep api
```

**Attendus :**

```
POST   /api/register
POST   /api/login
GET    /api/profile
```

---

## 🧪 Tests de l'API

### **Option 1 : Depuis PowerShell (Windows)**

Quittez le conteneur d'abord :

```bash
exit
```

Puis testez :

```powershell
# Test 1 : Inscription
$BASE_URL = "http://localhost:8000"
$email = "patient-$(Get-Random)@hospital.com"

$registerBody = @{
    name = "Dupont Jean"
    email = $email
    password = "Secure123!"
    cin = "12345$(Get-Random)"
    date_naissance = "1990-05-15"
    sexe = "M"
    telephone = "+33612345678"
    adresse = "123 Rue de l'Hôpital, 75000 Paris"
    contact_urgence = "+33698765432"
    blood_type = "AB+"
    chronic_diseases = "Hypertension"
    allergies = "Pollen"
} | ConvertTo-Json

$response = Invoke-RestMethod -Uri "$BASE_URL/api/register" -Method Post `
  -Headers @{"Content-Type" = "application/json"} `
  -Body $registerBody

$TOKEN = $response.token
Write-Host "✅ Inscription réussie!"
Write-Host "Token reçu: $($TOKEN.Substring(0, 20))..."

# Test 2 : Login
$loginBody = @{
    email = $email
    password = "Secure123!"
} | ConvertTo-Json

$loginResp = Invoke-RestMethod -Uri "$BASE_URL/api/login" -Method Post `
  -Headers @{"Content-Type" = "application/json"} `
  -Body $loginBody

Write-Host "✅ Login réussi!"

# Test 3 : Profil protégé
$profile = Invoke-RestMethod -Uri "$BASE_URL/api/profile" -Method Get `
  -Headers @{"Authorization" = "Bearer $TOKEN"}

Write-Host "✅ Profil récupéré:"
Write-Host ($profile | ConvertTo-Json)
```

---

### **Option 2 : Depuis bash/curl (dans le conteneur)**

```bash
docker-compose exec hospital-api bash

# Depuis le conteneur
BASE_URL="http://localhost:8000"
EMAIL="test-$(date +%s)@hospital.com"

# Test 1 : Register
curl -X POST "$BASE_URL/api/register" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Dupont Jean",
    "email": "'$EMAIL'",
    "password": "Secure123!",
    "cin": "12345ABC",
    "date_naissance": "1990-05-15",
    "sexe": "M",
    "telephone": "+33612345678",
    "adresse": "123 Rue de l'\''Hôpital, 75000 Paris",
    "contact_urgence": "+33698765432",
    "blood_type": "AB+",
    "chronic_diseases": "Hypertension",
    "allergies": "Pollen"
  }' | jq .
```

---

### **Option 3 : Utiliser Postman**

1. **Ouvrir Postman**
2. **Créer une requête POST** vers `http://localhost:8000/api/register`
3. **Onglet "Body"** → Sélectionner "raw" + "JSON"
4. **Coller** :

```json
{
  "name": "Dupont Jean",
  "email": "test@hospital.com",
  "password": "Secure123!",
  "cin": "12345ABC",
  "date_naissance": "1990-05-15",
  "sexe": "M",
  "telephone": "+33612345678",
  "adresse": "123 Rue de l'Hôpital, 75000 Paris",
  "contact_urgence": "+33698765432",
  "blood_type": "AB+",
  "chronic_diseases": "Hypertension",
  "allergies": "Pollen"
}
```

5. **Cliquer** "Send"

---

## 🔍 Dépannage

### ❌ Problème : Port 8000 Déjà Utilisé

```bash
# Vérifier quel processus utilise le port 8000
netstat -ano | findstr :8000

# Si Docker utilise le port
docker-compose down
docker-compose up -d --force-recreate
```

---

### ❌ Problème : "SQLSTATE[42S22]: Column Not Found"

**Cause :** Migration non appliquée.

```bash
# Dans le conteneur
docker-compose exec hospital-api php artisan migrate:refresh
```

---

### ❌ Problème : "JWT_SECRET not set"

**Cause :** Secret JWT non généré.

```bash
docker-compose exec hospital-api php artisan jwt:secret
docker-compose exec hospital-api php artisan cache:clear
```

---

### ❌ Problème : "The given data was invalid"

**Cause :** Données invalides.

**Vérifiez :**

- `email` unique (pas de doublon)
- `cin` unique (pas de doublon)
- `date_naissance` au format `YYYY-MM-DD`
- `sexe` être `M` ou `F`

---

### ❌ Problème : Base de Données Inaccessible

```bash
# Vérifier le conteneur DB
docker-compose ps

# Si arrêté, redémarrer
docker-compose restart
```

---

## 📊 Vérifier les Données dans la BD

```bash
# Depuis le conteneur Laravel
docker-compose exec hospital-api php artisan tinker

# Puis dans le shell Tinker
>>> App\Models\User::all();
>>> App\Models\Patient::all();
>>> exit()
```

---

## 🧹 Nettoyer et Réinitialiser

```bash
# Arrêter tous les conteneurs
docker-compose down

# Supprimer les volumes (données)
docker-compose down -v

# Relancer proprement
docker-compose up -d
docker-compose exec hospital-api php artisan migrate:refresh
docker-compose exec hospital-api php artisan jwt:secret
```

---

## ✅ Checklist Finale

- [ ] Conteneurs lancés : `docker-compose ps` ✅
- [ ] Migrations appliquées : `php artisan migrate` ✅
- [ ] JWT Secret généré : `JWT_SECRET` dans `.env` ✅
- [ ] Routes visibles : `php artisan route:list` ✅
- [ ] Register fonctionne (201 reçu) ✅
- [ ] Login fonctionne (200 + token reçu) ✅
- [ ] Profile protégée fonctionne (200 reçu) ✅

---

## 📞 Support

Si une erreur persiste, vérifiez les logs :

```bash
docker-compose logs hospital-api
```

Ou dans le conteneur :

```bash
docker-compose exec hospital-api tail -f storage/logs/laravel.log
```
