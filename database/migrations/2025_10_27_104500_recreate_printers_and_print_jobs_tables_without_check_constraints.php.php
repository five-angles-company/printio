<?php

use App\Enums\PrinterType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Get current enum values from PrinterType
        $values = array_map(fn($case) => $case->value, PrinterType::cases());

        // --- STEP 1: rebuild printers table ---
        Schema::create('printers_new', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->string('status')->default('Ready'); // emulate enum
            $table->string('type'); // emulate enum
            $table->timestamps();
        });

        // Copy data from old table
        if (Schema::hasTable('printers')) {
            DB::statement('
                INSERT INTO printers_new (id, name, display_name, description, status, type, created_at, updated_at)
                SELECT id, name, display_name, description, status, type, created_at, updated_at FROM printers
            ');

            Schema::drop('printers');
        }

        Schema::rename('printers_new', 'printers');

        // --- STEP 2: rebuild print_jobs table ---
        Schema::create('print_jobs_new', function (Blueprint $table) {
            $table->id();
            $table->integer('remote_id')->nullable();
            $table->foreignId('printer_id')->constrained('printers')->cascadeOnDelete();
            $table->string('name');
            $table->string('type'); // emulate enum
            $table->string('status')->default('Pending');
            $table->json('data');
            $table->timestamps();
        });

        if (Schema::hasTable('print_jobs')) {
            DB::statement('
                INSERT INTO print_jobs_new (id, remote_id, printer_id, name, type, status, data, created_at, updated_at)
                SELECT id, remote_id, printer_id, name, type, status, data, created_at, updated_at FROM print_jobs
            ');

            Schema::drop('print_jobs');
        }

        Schema::rename('print_jobs_new', 'print_jobs');
    }

    public function down(): void
    {
        // Rollback both tables to old state
        Schema::create('printers_old', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->string('status')->default('Ready');
            $table->string('type');
            $table->timestamps();
        });

        if (Schema::hasTable('printers')) {
            DB::statement('
                INSERT INTO printers_old (id, name, display_name, description, status, type, created_at, updated_at)
                SELECT id, name, display_name, description, status, type, created_at, updated_at FROM printers
            ');

            Schema::drop('printers');
        }

        Schema::rename('printers_old', 'printers');

        Schema::create('print_jobs_old', function (Blueprint $table) {
            $table->id();
            $table->integer('remote_id')->nullable();
            $table->foreignId('printer_id')->constrained('printers')->cascadeOnDelete();
            $table->string('name');
            $table->string('type');
            $table->string('status')->default('Pending');
            $table->json('data');
            $table->timestamps();
        });

        if (Schema::hasTable('print_jobs')) {
            DB::statement('
                INSERT INTO print_jobs_old (id, remote_id, printer_id, name, type, status, data, created_at, updated_at)
                SELECT id, remote_id, printer_id, name, type, status, data, created_at, updated_at FROM print_jobs
            ');

            Schema::drop('print_jobs');
        }

        Schema::rename('print_jobs_old', 'print_jobs');
    }
};
