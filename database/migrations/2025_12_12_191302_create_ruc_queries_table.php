<?php

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
        Schema::create('ruc_queries', function (Blueprint $table) {
            $table->id();
            $table->string('ruc_number');
            $table->string('taxpayer_name')->nullable();
            $table->json('taxpayer_data')->nullable();
            $table->string('environment'); // dev, pruebas, prod
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
            
            $table->index('ruc_number');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruc_queries');
    }
};
