# Remove unnecessary language directories
$languagesToKeep = @("english", "persian", "arabic", "chinese", "turkish")
$langPath = "C:\laragon\www\samecrm_v1\application\resources\lang"

# Get all directories in lang folder
$allDirs = Get-ChildItem -Path $langPath -Directory

foreach ($dir in $allDirs) {
    if ($dir.Name -notin $languagesToKeep) {
        Write-Host "Removing: $($dir.Name)"
        Remove-Item -Path $dir.FullName -Recurse -Force
    } else {
        Write-Host "Keeping: $($dir.Name)"
    }
}

Write-Host "Language cleanup completed!"

