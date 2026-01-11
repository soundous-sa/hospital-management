# ⚡ QUICK START - 5 Minutes Top Chrono

## 🎯 COMMANDE UNIQUE

Copier-coller ceci dans **PowerShell** :

```powershell
Stop-Process -Name node -Force -ErrorAction SilentlyContinue; Start-Sleep 2; cd "C:\Users\NB\Desktop\EmG\devweb\hospital-API\hospital-management\frontend"; Remove-Item -Recurse -Force "hospital-frontend" -ErrorAction SilentlyContinue; npm cache clean --force; Remove-Item -Recurse -Force "node_modules"; npm install; Write-Host "`n✅ ETAPE 1/3 COMPLETE!`nEtape 2: Vider cache navigateur (F12)`nEtape 3: npm start" -ForegroundColor Green
```

Attendez que ça se termine (~3-4 minutes).

---

## 📋 APRÈS LA COMMANDE

### **Étape 2 : Vider Cache Navigateur (30 secondes)**

1. Ouvrez le navigateur
2. Appuyez sur **F12**
3. Onglet **Application**
4. **Service Workers** → **Unregister** (si existe)
5. **Cache Storage** → Supprimez les entrées
6. Appuyez sur **Ctrl+Shift+R**

---

### **Étape 3 : Relancer npm (10 secondes)**

```bash
npm start
```

Attendez que le navigateur s'ouvre.

---

## ✅ VÉRIFICATION (30 secondes)

Vous devez voir sur `http://localhost:3000/` :

```
🏥 Hospital Frontend
Bienvenue sur l'application de gestion hospitalière

- Connexion
- Inscription
```

---

## ✨ C'est Tout !

**Temps total : ~5-6 minutes**

Si c'est fait ✅, vous êtes prêt pour :

- Tester `/register` et `/login`
- Intégrer avec le backend Laravel

---

## 🆘 Ça N'a Pas Marché ?

### **Le logo React s'affiche toujours**

```bash
# Vérifier que hospital-frontend est supprimé
dir | findstr hospital-frontend

# Si c'est là, supprimer manuellement dans l'Explorateur Windows
```

### **Erreur "Cannot find module"**

```bash
npm install --legacy-peer-deps
```

### **Port 3000 déjà utilisé**

```bash
# Trouver le processus
netstat -ano | findstr :3000

# Tuer le processus (remplacer PID par le numéro)
taskkill /PID <PID> /F

# Relancer
npm start
```

---

## 📚 Besoin de Plus de Détails ?

Allez à : [INDEX.md](INDEX.md)

---

**GO!** 🚀
