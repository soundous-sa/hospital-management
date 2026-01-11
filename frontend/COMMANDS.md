# 📋 Commandes Copy-Paste

## 🚀 Solution Rapide (1 minute)

### **Copier-coller dans PowerShell :**

```powershell
# Étape 1 : Arrêter npm
Stop-Process -Name node -Force -ErrorAction SilentlyContinue
Start-Sleep -Seconds 2

# Étape 2 : Aller au bon dossier
cd "C:\Users\NB\Desktop\EmG\devweb\hospital-API\hospital-management\frontend"

# Étape 3 : Supprimer le mauvais dossier
Remove-Item -Recurse -Force "hospital-frontend" -ErrorAction SilentlyContinue

# Étape 4 : Nettoyer npm
npm cache clean --force

# Étape 5 : Supprimer et réinstaller
Remove-Item -Recurse -Force "node_modules"
npm install

# ✅ Fait !
Write-Host "✅ Nettoyage Complète! Maintenant:" -ForegroundColor Green
Write-Host "1. Vider le cache navigateur (F12)" -ForegroundColor Cyan
Write-Host "2. Lancer: npm start" -ForegroundColor Cyan
```

---

## 🔍 Solution Détaillée (Avec Étapes Individuelles)

### **Étape 1 : Arrêter npm**

**Dans le terminal PowerShell/CMD où npm s'exécute :**

```
Ctrl+C
```

**Ou en PowerShell :**

```powershell
Stop-Process -Name node -Force -ErrorAction SilentlyContinue
Start-Sleep -Seconds 2
```

---

### **Étape 2 : Aller au bon dossier**

```powershell
cd "C:\Users\NB\Desktop\EmG\devweb\hospital-API\hospital-management\frontend"
```

---

### **Étape 3 : Vérifier l'arborescence**

```powershell
# Vérifier que hospital-frontend existe
dir | grep hospital-frontend

# Ou en PowerShell pur
Get-ChildItem -Name | Select-String "hospital-frontend"
```

Si vous voyez `hospital-frontend`, continuez à l'étape suivante.

---

### **Étape 4 : Supprimer le dossier mauvais**

**Option A : PowerShell (Recommandé)**

```powershell
Remove-Item -Recurse -Force "hospital-frontend"
```

**Option B : CMD**

```bash
rmdir /s /q hospital-frontend
```

**Option C : Manuellement dans l'Explorateur**

- Ouvrir `C:\Users\NB\Desktop\EmG\devweb\hospital-API\hospital-management\frontend\`
- Clic droit sur `hospital-frontend`
- Cliquer **Supprimer**

---

### **Étape 5 : Vérifier que c'est supprimé**

```powershell
dir
```

Vous ne devez **PAS** voir `hospital-frontend` dans la liste.

---

### **Étape 6 : Nettoyer npm**

```powershell
npm cache clean --force
```

---

### **Étape 7 : Supprimer node_modules**

```powershell
# PowerShell
Remove-Item -Recurse -Force "node_modules"

# OU CMD
rmdir /s /q node_modules
```

---

### **Étape 8 : Réinstaller les dépendances**

```powershell
npm install
```

Attendez que ça se termine (peut prendre 1-2 minutes).

---

### **Étape 9 : Vider le cache navigateur**

1. Ouvrez le navigateur
2. Appuyez sur **F12** (DevTools)
3. Allez à l'onglet **Application**
4. Dans le menu de gauche, cliquez sur **Service Workers**
5. Si une entrée existe, cliquez **Unregister**
6. Allez à **Cache Storage** dans le menu de gauche
7. Si des entrées existent, supprimez-les
8. Appuyez sur **Ctrl+Shift+R** (Hard Refresh) ou **Cmd+Shift+R** (Mac)

---

### **Étape 10 : Lancer le serveur**

```powershell
npm start
```

Attendez que le navigateur s'ouvre automatiquement sur `http://localhost:3000`.

---

## ✅ Vérification

### **Dans le Navigateur**

Vous devez voir :

```
🏥 Hospital Frontend
Bienvenue sur l'application de gestion hospitalière

- Connexion
- Inscription
```

**Et PAS :**

```
[Logo React Animé]
Edit src/App.js and save to reload.
```

---

### **Dans la Console (F12)**

Appuyez sur **F12** → Onglet **Console**.

Vous devez voir :

- ❌ Pas de messages d'erreur rouges
- ❌ Pas de messages "Cannot find module"
- ✅ Éventuellement des avertissements jaunes (normal)

---

## 🧪 Test des Routes

Visitez ces URLs :

```
http://localhost:3000/              → Affiche Home ✅
http://localhost:3000/register       → Affiche Register ✅
http://localhost:3000/login          → Affiche Login ✅
```

---

## ❌ Si ça N'a Pas Marché

### **Le logo React s'affiche toujours ?**

```powershell
# Vérifier que hospital-frontend n'existe plus
dir | grep hospital-frontend

# Si ça affiche quelque chose, supprimer à nouveau
Remove-Item -Recurse -Force "hospital-frontend" -Force
```

---

### **Erreur "Cannot find module" ?**

```powershell
# Réinstaller les dépendances
npm install --legacy-peer-deps

# Ou nettoyer complètement
npm cache clean --force
Remove-Item -Recurse -Force "node_modules"
npm install
```

---

### **Port 3000 déjà utilisé ?**

```powershell
# Trouver le processus qui utilise le port 3000
netstat -ano | findstr :3000

# Arrêter le processus (remplacer PID par le numéro trouvé)
taskkill /PID <PID> /F

# Relancer npm
npm start
```

---

## 📊 Récapitulatif des Commandes

| Action                          | Commande                                                                        |
| ------------------------------- | ------------------------------------------------------------------------------- |
| **Aller au bon dossier**        | `cd "C:\Users\NB\Desktop\EmG\devweb\hospital-API\hospital-management\frontend"` |
| **Arrêter npm**                 | `Stop-Process -Name node -Force`                                                |
| **Vérifier l'arborescence**     | `dir`                                                                           |
| **Supprimer hospital-frontend** | `Remove-Item -Recurse -Force "hospital-frontend"`                               |
| **Nettoyer npm**                | `npm cache clean --force`                                                       |
| **Supprimer node_modules**      | `Remove-Item -Recurse -Force "node_modules"`                                    |
| **Réinstaller**                 | `npm install`                                                                   |
| **Lancer**                      | `npm start`                                                                     |

---

## 🎉 Succès !

Une fois tout fait, vous verrez :

✅ Application React fonctionnelle  
✅ Page d'accueil personnalisée  
✅ Routes /register et /login accessibles  
✅ Pas de logo React par défaut  
✅ Prête pour l'intégration avec Laravel

**Bravo !** 🚀
