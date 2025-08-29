<?php

use App\Enums\PrinterStatus;
use App\Enums\PrinterType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('printers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->enum('status', array_column(PrinterStatus::cases(), 'value'))->default(PrinterStatus::READY->value);
            $table->enum('type', array_column(PrinterType::cases(), 'value'));
            $table->timestamps();
        });

        Schema::create('printer_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('printer_id')->constrained('printers')->cascadeOnDelete();
            $table->json('settings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('printers');
        Schema::dropIfExists('printer_settings');
    }
};
