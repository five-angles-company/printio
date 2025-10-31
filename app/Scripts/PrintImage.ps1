param(
    [string]$File,
    [string]$Printer,
    [int]$Copies = 1
)

if (-not (Test-Path $File)) {
    Write-Error "File not found: $File"
    exit 1
}

Add-Type -AssemblyName System.Drawing
Add-Type -AssemblyName System.Windows.Forms

$pd = New-Object System.Drawing.Printing.PrintDocument
$pd.PrinterSettings.PrinterName = $Printer
$pd.PrinterSettings.Copies = 4

$pd.add_PrintPage({
    param($sender, $e)
    $img = [System.Drawing.Image]::FromFile($File)
    $e.Graphics.DrawImage($img, $e.PageBounds)
    $e.HasMorePages = $false
})

$pd.Print()
