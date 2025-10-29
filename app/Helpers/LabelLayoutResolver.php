<?php

namespace App\Helpers;

use App\Data\LabelData;
use App\Encoders\Label\BaseEncoder;

class LabelLayoutResolver
{
    /**
     * Base design (3x2 inches) positions
     */
    protected static array $baseLayout = [
        'pharmacy' => ['x' => 50, 'y' => 30, 'size' => 1],
        'drug'     => ['x' => 20, 'y' => 70, 'size' => 1],
        'barcode'  => ['x' => 20, 'y' => 120, 'size' => 1],
        'barcode_text' => ['x' => 180, 'y' => 230, 'size' => 1],
        'price'    => ['x' => 20, 'y' => 280, 'size' => 1],
        'date'     => ['x' => 400, 'y' => 280, 'size' => 1],
    ];

    protected static array $baseSize = [3.0, 2.0]; // 3x2 inches design

    /**
     * Resolve positions and render to encoder
     */
    public static function resolve(string $size, LabelData $data, BaseEncoder $encoder): string
    {
        [$targetW, $targetH] = self::parseSize($size);
        [$baseW, $baseH] = self::$baseSize;

        // scaling factors
        $scaleX = $targetW / $baseW;
        $scaleY = $targetH / $baseH;
        $scaleFont = ($scaleX + $scaleY) / 2; // keep font proportional

        $encoder->initialize($targetW, $targetH);

        foreach (self::$baseLayout as $key => $pos) {
            $x = round($pos['x'] * $scaleX);
            $y = round($pos['y'] * $scaleY);
            $size = isset($pos['size']) ? round($pos['size'] * $scaleFont) : null;

            switch ($key) {
                case 'pharmacy':
                    $encoder->text('Almoharib Pharmacy', $x, $y, $size);
                    break;

                case 'drug':
                    $encoder->text($data->productName ?? 'Drug Name', $x, $y, $size);
                    break;

                case 'barcode':
                    $encoder->barcode($data->barcode ?? '0000000000000', $x, $y);
                    break;

                // case 'barcode_text':
                //     $encoder->text($data->barcode ?? '', $x, $y, $size);
                //     break;

                case 'price':
                    $encoder->text('SR: ' . ($data->price ?? '0.00'), $x, $y, $size);
                    break;

                case 'date':
                    $encoder->text($data->expiry ?? date('d.m.y'), $x, $y, $size);
                    break;
            }
        }

        return $encoder->copies($data->copies)->getBuffer();
    }

    /**
     * Convert "3x2" → [widthInches, heightInches]
     */
    protected static function parseSize(string $size): array
    {
        [$w, $h] = explode('x', strtolower($size));
        return [(float)$w, (float)$h];
    }
}
