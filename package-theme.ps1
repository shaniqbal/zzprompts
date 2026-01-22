$source = "c:\laragon\www\zzprompts\wp-content\themes\zzprompts"
$buildDir = "c:\laragon\www\zzprompts\wp-content\themes\build"
$dest = "$buildDir\zzprompts"
$zip = "c:\laragon\www\zzprompts\wp-content\themes\zzprompts.zip"

Write-Host "Starting build process..."

# Clean build dir
if (Test-Path $buildDir) { Remove-Item $buildDir -Recurse -Force }
New-Item -ItemType Directory -Path $dest -Force | Out-Null

# Files to include (Root level)
$includes = @(
    "*.php",
    "style.css",
    "screenshot.png",
    "readme.txt"
)

# Folders to copy
$folders = @(
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

# Copy Root Files
Write-Host "Copying root files..."
foreach ($pattern in $includes) {
    Get-ChildItem -Path $source -Filter $pattern -File | Copy-Item -Destination $dest
}

# Copy Folders
Write-Host "Copying directories..."
foreach ($folder in $folders) {
    $folderPath = "$source\$folder"
    if (Test-Path $folderPath) {
        Copy-Item -Path $folderPath -Destination $dest -Recurse
    } else {
        Write-Warning "Folder not found: $folder"
    }
}

# Additional Cleanup in Destination
Write-Host "Cleaning up..."
Get-ChildItem -Path $dest -Include ".DS_Store", "Thumbs.db", "*.log" -Recurse | Remove-Item -Force -ErrorAction SilentlyContinue

# Create Zip
Write-Host "Creating ZIP archive..."
if (Test-Path $zip) { Remove-Item $zip -Force }
Compress-Archive -Path $dest -DestinationPath $zip

Write-Host "Theme packaged successfully: $zip"
