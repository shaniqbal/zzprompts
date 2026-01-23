# ZZ Prompts Theme - ThemeForest Package Builder
# Run from theme root: .\build-package.ps1
# Creates final ZIP for ThemeForest submission

$ErrorActionPreference = "Stop"

# Configuration
$themeName = "zzprompts"
$themeVersion = "1.0.0"
$sourceDir = "c:\laragon\www\zzprompts\wp-content\themes\zzprompts"
$childDir = "c:\laragon\www\zzprompts\wp-content\themes\zzprompts-child"
$buildDir = "c:\laragon\www\zzprompts\wp-content\themes\build"
$packageDir = "$buildDir\$themeName"
$childPackageDir = "$buildDir\$themeName-child"
$outputZip = "c:\laragon\www\zzprompts\wp-content\themes\$themeName-themeforest-v$themeVersion.zip"

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  ZZ Prompts - ThemeForest Packager" -ForegroundColor Cyan
Write-Host "  Version: $themeVersion" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Step 1: Clean build directory
Write-Host "[1/8] Cleaning build directory..." -ForegroundColor Yellow
if (Test-Path $buildDir) { 
    Remove-Item $buildDir -Recurse -Force 
}
New-Item -ItemType Directory -Path $packageDir -Force | Out-Null
New-Item -ItemType Directory -Path $childPackageDir -Force | Out-Null

# Step 2: Copy Theme Files (excluding dev files)
Write-Host "[2/8] Copying theme files..." -ForegroundColor Yellow

# Root files to include
$rootFiles = @(
    "*.php",
    "style.css",
    "screenshot.png",
    "readme.txt"
)

foreach ($pattern in $rootFiles) {
    Get-ChildItem -Path $sourceDir -Filter $pattern -File | Copy-Item -Destination $packageDir
}

# Directories to include
$includeFolders = @(
    "assets",
    "demo-content",
    "documentation",
    "inc",
    "languages",
    "licensing",
    "page-templates",
    "plugins",
    "template-parts"
)

foreach ($folder in $includeFolders) {
    $folderPath = "$sourceDir\$folder"
    if (Test-Path $folderPath) {
        Copy-Item -Path $folderPath -Destination $packageDir -Recurse
        Write-Host "   + $folder" -ForegroundColor Gray
    }
    else {
        Write-Warning "   ! Missing: $folder"
    }
}

# Step 3: Copy Child Theme
Write-Host "[3/8] Copying child theme..." -ForegroundColor Yellow
if (Test-Path $childDir) {
    Copy-Item -Path "$childDir\*" -Destination $childPackageDir -Recurse
    Write-Host "   + zzprompts-child" -ForegroundColor Gray
}
else {
    Write-Warning "   ! Child theme not found at $childDir"
}

# Step 4: Clean up development files
Write-Host "[4/8] Removing development files..." -ForegroundColor Yellow

$devPatterns = @(
    ".DS_Store",
    "Thumbs.db",
    "*.log",
    ".gitignore",
    ".git",
    ".github",
    ".vscode",
    ".zencoder",
    ".zenflow",
    ".gemini",
    ".agent",
    "node_modules",
    "package.json",
    "package-lock.json",
    "bs-config.js"
)

# Remove dev files/folders from package recursively
foreach ($pattern in $devPatterns) {
    Get-ChildItem -Path $packageDir -Filter $pattern -Recurse -Force -ErrorAction SilentlyContinue | 
    Remove-Item -Force -Recurse -ErrorAction SilentlyContinue
}

# Also remove specific dev markdown files from root
$devMdFiles = @(
    "1 Themeforest Official Rules For Any WP Theme.md",
    "2 A Method To Make Audit For WP.md",
    "3 Themeforest Submission Rules Official.md",
    "Demo Content Official Blueprint For Any WP Theme.md",
    "Gemini Pro 3 High Audit v1.md",
    "Opus 4.5 Audit v1.md",
    "Themeforest_Detailed_Guide.md",
    "Themeforest_Quick_Details.md",
    "CLAUDE.md",
    "claude.md"
)

foreach ($file in $devMdFiles) {
    $filePath = "$packageDir\$file"
    if (Test-Path $filePath) {
        Remove-Item $filePath -Force
        Write-Host "   - Removed: $file" -ForegroundColor DarkGray
    }
}

# Remove Python script
if (Test-Path "$packageDir\fix_xml.py") {
    Remove-Item "$packageDir\fix_xml.py" -Force
    Write-Host "   - Removed: fix_xml.py" -ForegroundColor DarkGray
}

# Step 5: Verify required files
Write-Host "[5/8] Verifying required files..." -ForegroundColor Yellow

$requiredFiles = @(
    "style.css",
    "functions.php",
    "index.php",
    "header.php",
    "footer.php",
    "screenshot.png",
    "readme.txt"
)

$allPresent = $true
foreach ($file in $requiredFiles) {
    $filePath = "$packageDir\$file"
    if (Test-Path $filePath) {
        Write-Host "   [OK] $file" -ForegroundColor Green
    }
    else {
        Write-Host "   [MISSING] $file" -ForegroundColor Red
        $allPresent = $false
    }
}

if (-not $allPresent) {
    Write-Error "Required files are missing! Build aborted."
    exit 1
}

# Step 6: Verify demo content
Write-Host "[6/8] Verifying demo content..." -ForegroundColor Yellow
$demoXml = "$packageDir\demo-content\demo-content.xml"
if (Test-Path $demoXml) {
    $size = (Get-Item $demoXml).Length / 1KB
    Write-Host "   [OK] demo-content.xml (${size:N0} KB)" -ForegroundColor Green
}
else {
    Write-Host "   [WARNING] Demo content not found" -ForegroundColor Yellow
}

# Step 7: Create child theme ZIP inside package
Write-Host "[7/8] Creating child theme ZIP..." -ForegroundColor Yellow
$childZip = "$packageDir\zzprompts-child.zip"
if (Test-Path $childPackageDir) {
    Compress-Archive -Path $childPackageDir -DestinationPath $childZip -Force
    Write-Host "   Created: zzprompts-child.zip" -ForegroundColor Green
    # Remove child folder from build (we have the zip now)
    Remove-Item $childPackageDir -Recurse -Force
}

# Step 8: Create final ZIP
Write-Host "[8/8] Creating ThemeForest package..." -ForegroundColor Yellow
if (Test-Path $outputZip) { 
    Remove-Item $outputZip -Force 
}
Compress-Archive -Path $packageDir -DestinationPath $outputZip -Force

# Final report
$finalSize = (Get-Item $outputZip).Length / 1MB
Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "  BUILD COMPLETE!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "Package: $outputZip" -ForegroundColor White
Write-Host "Size: ${finalSize:N2} MB" -ForegroundColor White
Write-Host ""
Write-Host "Contents:" -ForegroundColor Cyan
Write-Host "  - zzprompts/           (Main theme)" -ForegroundColor Gray
Write-Host "  - zzprompts-child.zip  (Child theme)" -ForegroundColor Gray
Write-Host "  - documentation/       (Help files)" -ForegroundColor Gray
Write-Host "  - licensing/           (License info)" -ForegroundColor Gray
Write-Host "  - demo-content/        (XML import)" -ForegroundColor Gray
Write-Host ""
Write-Host "Ready for ThemeForest submission!" -ForegroundColor Yellow

# Cleanup build folder
Remove-Item $buildDir -Recurse -Force
