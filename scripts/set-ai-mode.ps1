param(
  [Parameter(Mandatory=$true)]
  [ValidateSet("cursor","local")]
  [string]$Mode
)

$File = "AI-MODE.yml"
if (-not (Test-Path $File)) {
  Write-Error "AI-MODE.yml not found. Run from repo root."
  exit 1
}

$Now = (Get-Date).ToUniversalTime().ToString("yyyy-MM-ddTHH:mm:ssZ")
$Who = if ($Mode -eq "local") { "ollama" } else { "cursor" }

$content = Get-Content $File -Raw
$content = [regex]::Replace($content, '^active_profile:.*$', "active_profile: $Mode", 'Multiline')
$content = [regex]::Replace($content, '^last_set_by:.*$', "last_set_by: `"$Who`"", 'Multiline')
$content = [regex]::Replace($content, '^last_set_at:.*$', "last_set_at: `"$Now`"", 'Multiline')

Set-Content -Path $File -Value $content -Encoding UTF8
Write-Host "AI mode set to: $Mode (last_set_by=$Who, last_set_at=$Now)"
