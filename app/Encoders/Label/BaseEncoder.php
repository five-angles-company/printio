<?php

namespace App\Encoders\Label;

use InvalidArgumentException;

abstract class BaseEncoder
{
    protected float $widthMm  = 0;
    protected float $heightMm = 0;
    protected int   $copies   = 1;
    protected array $commands = [];

    // Font size mapping
    protected const FONT_SIZES = [
        'xs' => 1,  // extra small
        's'  => 2,  // small
        'm'  => 3,  // normal/medium
        'l'  => 4,  // large
        'xl' => 5,  // extra large
    ];

    abstract protected function dpi(): int;
    abstract protected function buildHeader(): void;
    abstract protected function buildFooter(): void;
    abstract protected function textCommand(int $x, int $y, string $text, int $fontSize): string;
    abstract protected function barcodeCommand(int $x, int $y, string $data, int $height): string;

    // Font size mapping for each encoder
    abstract protected function mapFontSize(string $size): int;

    public function size(float $widthMm, float $heightMm): self
    {
        if ($widthMm <= 0 || $heightMm <= 0) {
            throw new InvalidArgumentException('Label dimensions must be positive');
        }

        $this->widthMm  = $widthMm;
        $this->heightMm = $heightMm;
        $this->commands = [];

        $this->buildHeader();
        return $this;
    }

    public function text(
        string $text,
        string $xAlign = 'left',   // 'left', 'center', 'right'
        string $yAlign = 'top',    // 'top', 'center', 'bottom'
        string $fontSize = 'm',    // 'xs', 's', 'm', 'l', 'xl'
        float $xOffset = 0,        // mm offset from the aligned position
        float $yOffset = 0         // mm offset from the aligned position
    ): self {
        if ($text === '') {
            throw new InvalidArgumentException('Text cannot be empty');
        }

        if (!isset(self::FONT_SIZES[$fontSize])) {
            throw new InvalidArgumentException("Invalid font size: {$fontSize}. Use: xs, s, m, l, xl");
        }

        $x = $this->calculateXPosition($xAlign, $this->calculateTextWidth($text, $fontSize), $xOffset);
        $y = $this->calculateYPosition($yAlign, $yOffset);
        $mappedFontSize = $this->mapFontSize($fontSize);

        return $this->textDirect($text, $x, $y, $mappedFontSize);
    }

    public function barcode(
        string $data,
        string $xAlign = 'left',   // 'left', 'center', 'right'
        string $yAlign = 'top',    // 'top', 'center', 'bottom'
        string $heightSize = 'm',  // 'xs', 's', 'm', 'l', 'xl'
        float $xOffset = 0,        // mm offset from the aligned position
        float $yOffset = 0         // mm offset from the aligned position
    ): self {
        if ($data === '') {
            throw new InvalidArgumentException('Barcode data cannot be empty');
        }

        if (!isset(self::FONT_SIZES[$heightSize])) {
            throw new InvalidArgumentException("Invalid height size: {$heightSize}. Use: xs, s, m, l, xl");
        }

        $barcodeWidth = $this->calculateBarcodeWidth($data);
        $x = $this->calculateXPosition($xAlign, $barcodeWidth, $xOffset);
        $y = $this->calculateYPosition($yAlign, $yOffset);
        $heightMm = $this->mapBarcodeHeight($heightSize);

        return $this->barcodeDirect($data, $x, $y, $heightMm);
    }

    // Direct positioning methods with font size
    public function textDirect(string $text, float $xMm, float $yMm, int $fontSize = 3): self
    {
        $x = $this->mmToDot($xMm);
        $y = $this->mmToDot($yMm);

        $this->commands[] = $this->textCommand($x, $y, $text, $fontSize);
        return $this;
    }

    public function barcodeDirect(string $data, float $xMm, float $yMm, float $heightMm = 8.0): self
    {
        $x = $this->mmToDot($xMm);
        $y = $this->mmToDot($yMm);
        $height = $this->mmToDot($heightMm);

        $this->commands[] = $this->barcodeCommand($x, $y, $data, $height);
        return $this;
    }

    // Legacy methods for backward compatibility
    public function textXY(string $text, float $xMm, float $yMm, int $fontSize = 3): self
    {
        return $this->textDirect($text, $xMm, $yMm, $fontSize);
    }

    public function barcodeXY(string $data, float $xMm, float $yMm, float $heightMm = 8.0): self
    {
        return $this->barcodeDirect($data, $xMm, $yMm, $heightMm);
    }

    public function copies(int $count): self
    {
        $this->copies = max(1, $count);
        return $this;
    }

    public function getBuffer(): string
    {
        $this->buildFooter();
        return implode("\n", $this->commands) . "\n";
    }

    protected function mmToDot(float $mm): int
    {
        $inches = $mm / 25.4;
        return (int) round($inches * $this->dpi());
    }

    protected function calculateTextWidth(string $text, string $fontSize): float
    {
        // Different width multipliers for different font sizes
        $multipliers = [
            'xs' => 1.2,  // extra small - narrower
            's'  => 1.5,  // small
            'm'  => 1.8,  // normal
            'l'  => 2.2,  // large
            'xl' => 2.8,  // extra large - wider
        ];

        $multiplier = $multipliers[$fontSize] ?? 1.8;
        return strlen($text) * $multiplier;
    }

    protected function calculateBarcodeWidth(string $data, float $moduleWidth = 1): float
    {
        $dataLength = strlen($data);

        // Rough estimation for Code128 barcode:
        // 11 start + (11 per character) + 11 stop + 13 termination pattern
        $totalModules = 11 + ($dataLength * 11) + 11 + 13;

        // Convert module width (in dots) to millimeters.
        // For 203 dpi: 1 dot = 25.4 / 203 ≈ 0.125 mm
        $mmPerDot = 25.4 / $this->dpi();

        // Each module = moduleWidth * mmPerDot
        return $totalModules * $moduleWidth * $mmPerDot;
    }

    protected function calculateXPosition(string $align, float $elementWidth, float $offset = 0): float
    {
        $x = match ($align) {
            'left'   => $offset,
            'center' => ($this->widthMm - $elementWidth) / 2 + $offset,
            'right'  => $this->widthMm - $elementWidth - $offset,
            default  => $offset,
        };

        // Prevent negative coordinates (ZPL ^FO doesn’t allow them)
        return max(0, $x);
    }

    protected function calculateYPosition(string $align, float $offset = 0): float
    {
        return match ($align) {
            'top' => $offset,
            'center' => ($this->heightMm / 2) + $offset,
            'bottom' => $this->heightMm - $offset,
            default => $offset
        };
    }

    protected function mapBarcodeHeight(string $size): float
    {
        return match ($size) {
            'xs' => 5.0,  // extra small
            's'  => 6.0,  // small
            'm'  => 8.0,  // normal
            'l'  => 10.0, // large
            'xl' => 12.0, // extra large
            default => 8.0
        };
    }
}
