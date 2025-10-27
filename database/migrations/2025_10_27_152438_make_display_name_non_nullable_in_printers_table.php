<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Backfill missing or duplicate display_names
        $printers = DB::table('printers')->orderBy('id')->get();

        $usedNames = [];

        foreach ($printers as $printer) {
            $baseName = $printer->name;
            $displayName = $baseName;
            $counter = 1;

            // Make sure display_name is unique in this migration context
            while (in_array(strtolower($displayName), $usedNames, true)) {
                $counter++;
                $displayName = "{$baseName} {$counter}";
            }

            $usedNames[] = strtolower($displayName);

            DB::table('printers')
                ->where('id', $printer->id)
                ->update(['display_name' => $displayName]);
        }

        // Step 2: Make display_name non-nullable
        Schema::table('printers', function (Blueprint $table) {
            $table->string('display_name')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('printers', function (Blueprint $table) {
            $table->string('display_name')->nullable()->change();
        });
    }
};
