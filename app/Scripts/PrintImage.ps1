param(
    [Parameter(Mandatory = $true)]
    [string]$filePath,

    [Parameter(Mandatory = $true)]
    [string]$printerName,

    [int]$copies = 1
)

try {
    if (-not (Test-Path $filePath)) {
        Write-Error "❌ File not found: $filePath"
        exit 1
    }

    Add-Type -AssemblyName System.Drawing

    $img = [System.Drawing.Image]::FromFile($filePath)

    $pd = New-Object System.Drawing.Printing.PrintDocument
    $pd.PrinterSettings.PrinterName = $printerName
    $pd.PrinterSettings.Copies = $copies

    $printableArea = $pd.DefaultPageSettings.PrintableArea
    $dpiX = $pd.DefaultPageSettings.PrinterResolution.X
    $dpiY = $pd.DefaultPageSettings.PrinterResolution.Y

    if ($dpiX -le 0 -or $dpiY -le 0) {
        Write-Warning "⚠️ Printer DPI not reported correctly. Using default 203 DPI."
        $dpiX = 203
        $dpiY = 203
    }

    $widthInches = $printableArea.Width / 100.0
    $widthPx = [int]($widthInches * $dpiX)
    $aspect = $img.Height / $img.Width
    $heightPx = [int]($widthPx * $aspect)

    $heightHundredthsInch = [int]($heightPx * 100 / $dpiY)
    $widthHundredthsInch  = [int]($widthPx * 100 / $dpiX)
    $paperSize = New-Object System.Drawing.Printing.PaperSize("CustomPage", $widthHundredthsInch, $heightHundredthsInch)

    $pd.DefaultPageSettings.PaperSize = $paperSize
    $pd.DefaultPageSettings.Margins = New-Object System.Drawing.Printing.Margins(0, 0, 0, 0)
    $pd.OriginAtMargins = $false

    # Capture filePath locally for closure
    $localFile = $filePath

    $pd.add_PrintPage({
        param($sender, $e)
        try {
            $imgLocal = [System.Drawing.Image]::FromFile($localFile)
            $bounds = $e.PageBounds
            $aspect = $imgLocal.Height / $imgLocal.Width
            $height = [int]($bounds.Width * $aspect)
            $e.Graphics.DrawImage($imgLocal, 0, 0, $bounds.Width, $height)
            $e.HasMorePages = $false
            $imgLocal.Dispose()
        } catch {
            Write-Error "❌ Error during PrintPage: $_"
            exit 2
        }
    })

    $pd.Print()
    $img.Dispose()

    Write-Host "✅ Printed '$filePath' on printer '$printerName' ($copies copies)."
    exit 0

} catch {
    Write-Error "❌ Unexpected print error: $_"
    exit 3
}
