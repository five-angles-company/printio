<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        $tables = ['printers', 'printer_settings', 'print_jobs'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                try {
                    DB::statement("DELETE FROM {$table}");
                } catch (\Throwable $e) {
                    echo "⚠️ Could not empty table: {$table}\n";
                }
            }
        }

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        // Nothing to roll back — this only clears data
    }
};
