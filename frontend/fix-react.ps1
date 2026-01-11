param(
    [switch]$Clean
)

$frontend_path = "C:\Users\NB\Desktop\EmG\devweb\hospital-API\hospital-management\frontend"

Write-Host "=====================================" -ForegroundColor Cyan
Write-Host "  React Display Fix - Automation" -ForegroundColor Cyan
Write-Host "=====================================" -ForegroundColor Cyan

if ($Clean) {
    Write-Host "`n[1] Suppression de node_modules..." -ForegroundColor Yellow
    if (Test-Path "$frontend_path\node_modules") {
        Remove-Item -Recurse -Force "$frontend_path\node_modules"
        Write-Host "✅ node_modules supprimé" -ForegroundColor Green
    }
}

Write-Host "`n[2] Installation des dépendances..." -ForegroundColor Yellow
cd $frontend_path
npm install

if ($?) {
    Write-Host "✅ npm install réussi" -ForegroundColor Green
}

Write-Host "`n[3] Lancement du serveur React..." -ForegroundColor Yellow
Write-Host "Attendez que la compilation soit terminée..." -ForegroundColor Cyan
Write-Host "Puis ouvrez : http://localhost:3000/" -ForegroundColor Magenta

npm start
