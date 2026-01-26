param(
    [Parameter(Mandatory = $true)]
    [string]$filePath,

    [Parameter(Mandatory = $true)]
    [string]$printerName,

    [int]$copies = 1,

    [int]$maxHeightMm = 800  # Safe default for most thermal printers (~31 inches)
)

try {
    if (-not (Test-Path $filePath)) {
        Write-Error "File not found: $filePath"
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
        Write-Warning "Printer DPI not reported correctly. Using default 203 DPI."
        $dpiX = 203
        $dpiY = 203
    }

    # Calculate target print dimensions (in pixels at printer DPI)
    $widthInches = $printableArea.Width / 100.0
    $printWidthPx = [int]($widthInches * $dpiX)
    $scaleRatio = $printWidthPx / $img.Width
    $printHeightPx = [int]($img.Height * $scaleRatio)

    # Max height in pixels (convert mm to pixels at printer DPI)
    $maxHeightPx = [int]($maxHeightMm * $dpiY / 25.4)

    # Check if image fits in single page
    $needsPagination = $printHeightPx -gt $maxHeightPx

    # Store image path for closure at script scope
    $script:localFilePath = $filePath
    $script:currentPage = 0
    $script:totalPages = if ($needsPagination) { [Math]::Ceiling($printHeightPx / $maxHeightPx) } else { 1 }

    if (-not $needsPagination) {
        # SIMPLE CASE: Single page - use original working logic
        $heightHundredthsInch = [int]($printHeightPx * 100 / $dpiY)
        $widthHundredthsInch = [int]($printWidthPx * 100 / $dpiX)
        $paperSize = New-Object System.Drawing.Printing.PaperSize("CustomPage", $widthHundredthsInch, $heightHundredthsInch)

        $pd.DefaultPageSettings.PaperSize = $paperSize
        $pd.DefaultPageSettings.Margins = New-Object System.Drawing.Printing.Margins(0, 0, 0, 0)
        $pd.OriginAtMargins = $false

        $pd.add_PrintPage({
            param($sender, $e)
            $imgLocal = [System.Drawing.Image]::FromFile($script:localFilePath)
            $bounds = $e.PageBounds
            $aspect = $imgLocal.Height / $imgLocal.Width
            $height = [int]($bounds.Width * $aspect)
            $e.Graphics.DrawImage($imgLocal, 0, 0, $bounds.Width, $height)
            $e.HasMorePages = $false
            $imgLocal.Dispose()
        })
    }
    else {
        # PAGINATION CASE: Split image into chunks
        # Calculate chunk height in source image pixels
        $srcChunkHeight = [int]($maxHeightPx / $scaleRatio)

        # Store pagination state
        $script:paginationState = @{
            FilePath = $script:localFilePath
            SrcChunkHeight = $srcChunkHeight
            ScaleRatio = $scaleRatio
            MaxHeightPx = $maxHeightPx
            PrintWidthPx = $printWidthPx
            DpiX = $dpiX
            DpiY = $dpiY
        }

        # Set page size for max height pages
        $heightHundredthsInch = [int]($maxHeightPx * 100 / $dpiY)
        $widthHundredthsInch = [int]($printWidthPx * 100 / $dpiX)
        $paperSize = New-Object System.Drawing.Printing.PaperSize("CustomPage", $widthHundredthsInch, $heightHundredthsInch)

        $pd.DefaultPageSettings.PaperSize = $paperSize
        $pd.DefaultPageSettings.Margins = New-Object System.Drawing.Printing.Margins(0, 0, 0, 0)
        $pd.OriginAtMargins = $false

        $pd.add_PrintPage({
            param($sender, $e)
            $state = $script:paginationState
            $page = $script:currentPage

            $imgLocal = [System.Drawing.Image]::FromFile($state.FilePath)

            # Calculate source Y position and height for this page
            $srcY = $page * $state.SrcChunkHeight
            $srcHeight = [Math]::Min($state.SrcChunkHeight, $imgLocal.Height - $srcY)

            # Calculate destination height for this chunk
            $destHeight = [int]($srcHeight * $state.ScaleRatio)

            # Draw the chunk
            $srcRect = New-Object System.Drawing.Rectangle 0, $srcY, $imgLocal.Width, $srcHeight
            $destRect = New-Object System.Drawing.Rectangle 0, 0, $e.PageBounds.Width, $destHeight

            $e.Graphics.DrawImage($imgLocal, $destRect, $srcRect, [System.Drawing.GraphicsUnit]::Pixel)

            $imgLocal.Dispose()

            $script:currentPage++
            $e.HasMorePages = ($script:currentPage -lt $script:totalPages)
        })
    }

    $pd.Print()
    $img.Dispose()

    if ($script:totalPages -gt 1) {
        Write-Host "Printed '$filePath' on '$printerName' ($($script:totalPages) pages, $copies copies)."
    } else {
        Write-Host "Printed '$filePath' on '$printerName' ($copies copies)."
    }
    exit 0

} catch {
    Write-Error "Unexpected print error: $_"
    exit 3
}
