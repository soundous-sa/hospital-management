param(
    [switch]$Force
)

$frontend_path = "C:\Users\NB\Desktop\EmG\devweb\hospital-API\hospital-management\frontend"

Write-Host "╔════════════════════════════════════════════════════════════╗" -ForegroundColor Cyan
Write-Host "║   FIX COMPLÈTE - Logo React Problem (Duplicate Folder)    ║" -ForegroundColor Cyan
Write-Host "╚════════════════════════════════════════════════════════════╝" -ForegroundColor Cyan

Write-Host "`n[DIAG] Vérification de l'arborescence..." -ForegroundColor Yellow
$bad_folder = "$frontend_path\hospital-frontend"
$has_bad_folder = Test-Path $bad_folder

if ($has_bad_folder) {
    Write-Host "⚠️  Dossier mauvais trouvé: $bad_folder" -ForegroundColor Red
} else {
    Write-Host "✅ Aucun dossier mauvais trouvé" -ForegroundColor Green
}

# Arrêter npm si en cours
Write-Host "`n[1/5] Arrêt des processus Node en cours..." -ForegroundColor Magenta
$node_procs = Get-Process node -ErrorAction SilentlyContinue
if ($node_procs) {
    Write-Host "  Processus Node trouvé, arrêt en cours..." -ForegroundColor Yellow
    Stop-Process -Name node -Force -ErrorAction SilentlyContinue
    Start-Sleep -Seconds 2
    Write-Host "✅ Processus Node arrêtés" -ForegroundColor Green
} else {
    Write-Host "✅ Aucun processus Node en cours" -ForegroundColor Green
}

# Supprimer le dossier mauvais
if ($has_bad_folder) {
    Write-Host "`n[2/5] Suppression du dossier 'hospital-frontend'..." -ForegroundColor Magenta
    try {
        Remove-Item -Recurse -Force $bad_folder -ErrorAction Stop
        Write-Host "✅ Dossier supprimé" -ForegroundColor Green
    } catch {
        Write-Host "❌ Erreur lors de la suppression: $_" -ForegroundColor Red
        if ($Force) {
            Write-Host "Tentative en mode Force..." -ForegroundColor Yellow
            cmd /c rmdir /s /q "$bad_folder" 2>$null
            Write-Host "✅ Dossier supprimé (mode force)" -ForegroundColor Green
        } else {
            Write-Host "Utilisez -Force pour forcer la suppression" -ForegroundColor Yellow
        }
    }
}

# Nettoyer npm cache
Write-Host "`n[3/5] Nettoyage du cache npm..." -ForegroundColor Magenta
npm cache clean --force | Out-Null
Write-Host "✅ Cache npm nettoyé" -ForegroundColor Green

# Supprimer node_modules
Write-Host "`n[4/5] Suppression de node_modules..." -ForegroundColor Magenta
$node_modules = "$frontend_path\node_modules"
if (Test-Path $node_modules) {
    Remove-Item -Recurse -Force $node_modules
    Write-Host "✅ node_modules supprimé" -ForegroundColor Green
} else {
    Write-Host "✅ node_modules n'existe pas (déjà nettoyé)" -ForegroundColor Green
}

# Réinstaller les dépendances
Write-Host "`n[5/5] Réinstallation des dépendances..." -ForegroundColor Magenta
cd $frontend_path
npm install 2>$null | Out-Null
if ($?) {
    Write-Host "✅ Dépendances réinstallées" -ForegroundColor Green
} else {
    Write-Host "❌ Erreur lors de npm install" -ForegroundColor Red
}

Write-Host "`n╔════════════════════════════════════════════════════════════╗" -ForegroundColor Cyan
Write-Host "║              Nettoyage Complet Terminé! ✅                 ║" -ForegroundColor Cyan
Write-Host "╚════════════════════════════════════════════════════════════╝" -ForegroundColor Cyan

Write-Host "`nProchaines étapes:`n" -ForegroundColor Yellow
Write-Host "1. Ouvrir le navigateur et vider le cache:" -ForegroundColor Cyan
Write-Host "   - Appuyez sur F12 (DevTools)" -ForegroundColor White
Write-Host "   - Allez à 'Application' → 'Service Workers' → Cliquez 'Unregister'" -ForegroundColor White
Write-Host "   - Allez à 'Application' → 'Cache Storage' → Supprimez les entrées" -ForegroundColor White
Write-Host "   - Appuyez sur Ctrl+Shift+R (Hard Refresh)" -ForegroundColor White

Write-Host "`n2. Lancer le serveur React:" -ForegroundColor Cyan
Write-Host "   npm start" -ForegroundColor White

Write-Host "`n3. Vérifier que la page affiche 'Hospital Frontend':" -ForegroundColor Cyan
Write-Host "   http://localhost:3000/" -ForegroundColor White

Write-Host "`n4. Tester les routes:" -ForegroundColor Cyan
Write-Host "   http://localhost:3000/register" -ForegroundColor White
Write-Host "   http://localhost:3000/login" -ForegroundColor White

Write-Host "`n" -ForegroundColor Green
