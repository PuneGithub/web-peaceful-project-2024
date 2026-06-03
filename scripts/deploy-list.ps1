# File list for FileZilla upload since last deploy (git tag: last-deploy)
# See DEPLOY.md (Thai guide)

param(
    [switch]$MarkDeployed,
    [switch]$Help
)

$ErrorActionPreference = 'Stop'
$RepoRoot = Split-Path -Parent $PSScriptRoot
Set-Location $RepoRoot

function Show-Help {
    Write-Host @'

deploy-list.ps1 - files to upload via FileZilla

  .\scripts\deploy-list.ps1                 List changes since last-deploy
  .\scripts\deploy-list.ps1 -MarkDeployed   Save deploy point (tag last-deploy)
  .\scripts\deploy-list.ps1 -Help

See DEPLOY.md for full guide (Thai).

'@
}

if ($Help) {
    Show-Help
    exit 0
}

if (-not (Get-Command git -ErrorAction SilentlyContinue)) {
    Write-Error 'git not found. Install Git first.'
}

if ($MarkDeployed) {
    git tag -f last-deploy HEAD
    Write-Host ''
    Write-Host 'Saved deploy point: last-deploy ->' -NoNewline
    Write-Host (' ' + (git rev-parse --short HEAD)) -ForegroundColor Green
    Write-Host 'Next run will list only files changed after this commit.'
    exit 0
}

$hasTag = $false
$prevEap = $ErrorActionPreference
$ErrorActionPreference = 'SilentlyContinue'
git rev-parse --verify refs/tags/last-deploy *>$null
if ($LASTEXITCODE -eq 0) {
    $hasTag = $true
}
$ErrorActionPreference = $prevEap

if (-not $hasTag) {
    Write-Host 'No last-deploy tag yet. Showing uncommitted files only.' -ForegroundColor Yellow
    Write-Host 'After production matches this code, run: .\scripts\deploy-list.ps1 -MarkDeployed' -ForegroundColor Yellow
    Write-Host ''

    $status = git status --porcelain
    if (-not $status) {
        Write-Host 'No uncommitted changes.' -ForegroundColor Green
        Write-Host 'If production is up to date, run -MarkDeployed to start tracking.'
        exit 0
    }

    $files = $status | ForEach-Object {
        $line = $_.Trim()
        if ($line -match '^\?\? (.+)$') { $Matches[1] }
        elseif ($line -match '^[ MADRCU?!]{2} (.+)$') { $Matches[1] }
    } | Where-Object { $_ } | Sort-Object -Unique

    $rangeLabel = 'uncommitted changes'
}
else {
    $from = git rev-parse --short last-deploy
    $to = git rev-parse --short HEAD
    $rangeLabel = "last-deploy ($from) .. HEAD ($to)"

    $files = git diff --name-only last-deploy..HEAD
    if (-not $files) {
        Write-Host "No file changes in $rangeLabel" -ForegroundColor Green
        exit 0
    }
    $files = $files | Sort-Object
}

Write-Host "=== Upload these files ($rangeLabel) ===" -ForegroundColor Cyan
Write-Host ''

$skipPatterns = @(
    '^node_modules/',
    '^\.git/',
    '\.sql$'
)

$upload = @()
foreach ($item in $files) {
    $skip = $false
    foreach ($p in $skipPatterns) {
        if ($item -match $p) { $skip = $true; break }
    }
    if (-not $skip) { $upload += $item }
}

$byFolder = $upload | Group-Object {
    if ($_ -match '^([^/\\]+)[/\\]') { $Matches[1] + '/' } else { '(root)/' }
} | Sort-Object Name

foreach ($g in $byFolder) {
    Write-Host ('--- ' + $g.Name + ' ---') -ForegroundColor DarkGray
    foreach ($item in ($g.Group | Sort-Object)) {
        Write-Host ('  ' + $item)
    }
    Write-Host ''
}

Write-Host ('Total: ' + $upload.Count + ' file(s)') -ForegroundColor Cyan
Write-Host ''
Write-Host 'Note: img/ files not committed will not appear here.' -ForegroundColor Yellow
Write-Host 'After FileZilla upload: .\scripts\deploy-list.ps1 -MarkDeployed' -ForegroundColor Yellow
