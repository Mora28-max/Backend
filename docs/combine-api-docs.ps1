# Script para combinar todos los archivos YAML de documentación de API
# Creado el 13/08/2025
# El script es el siguiente: .\docs\combine-api-docs.ps1

# Ruta de los archivos
$baseFile = "docs/api-docs-base.yaml"
$outputFile = "docs/api-docs-combined.yaml"
$finalFile = "docs/api-docs.yaml"
$partialsDir = "docs/partials"

# Asegurarse de que el directorio de partials existe
if (-not (Test-Path $partialsDir)) {
    Write-Host "El directorio $partialsDir no existe. Creándolo..." -ForegroundColor Yellow
    New-Item -Path $partialsDir -ItemType Directory -Force | Out-Null
}

# Obtener todos los archivos YAML en el directorio de partials
$partialFiles = Get-ChildItem -Path $partialsDir -Filter "*.yaml" | Select-Object -ExpandProperty FullName

if ($partialFiles.Count -eq 0) {
    Write-Host "No se encontraron archivos YAML en $partialsDir." -ForegroundColor Yellow
    Copy-Item -Path $baseFile -Destination $finalFile -Force
    Write-Host "Se ha copiado el archivo base a $finalFile." -ForegroundColor Green
    exit
}

# Construir el comando de redocly join
$redoclyCommand = "redocly join $baseFile $($partialFiles -join ' ') -o $outputFile"
Write-Host "Ejecutando: $redoclyCommand" -ForegroundColor Cyan

# Ejecutar el comando de redocly
try {
    Invoke-Expression $redoclyCommand
    if ($LASTEXITCODE -eq 0) {
        Write-Host "Archivos YAML combinados exitosamente en $outputFile" -ForegroundColor Green

        # Limpia los 'servers: []' del archivo combinado
        Write-Host "Limpiando servers vacíos..." -ForegroundColor Yellow
        $content = Get-Content $outputFile -Raw
$cleanedContent = $content -replace '(?m)^\s*servers:\s*\[\]\s*\r?\n', ''

        # Agregar los servers del archivo base después de la línea 'version:'
$serversSection = @"
servers:
  - url: https://api-v1.soapamz.xyz/public
    description: Producción
  - url: http://localhost:8000
    description: Desarrollo
"@

$cleanedContent = $cleanedContent -replace '(version:\s*[\d\.]+)\s*\n', "`$1`n$serversSection`n"
        Set-Content $outputFile $cleanedContent -NoNewline
        Write-Host "Servers globales agregados y servers vacíos eliminados." -ForegroundColor Green

        # Mover el archivo combinado al archivo final
        Move-Item -Path $outputFile -Destination $finalFile -Force
        Write-Host "Archivo combinado movido a $finalFile" -ForegroundColor Green
    } else {
        Write-Host "Error al combinar los archivos YAML. Código de salida: $LASTEXITCODE" -ForegroundColor Red
        exit 1
    }
} catch {
    Write-Host "Error al ejecutar redocly: $_" -ForegroundColor Red
    exit 1
}

Write-Host "Proceso completado exitosamente." -ForegroundColor Green
Write-Host "Archivos combinados: $($partialFiles.Count)" -ForegroundColor Cyan
