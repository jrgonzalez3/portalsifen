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
        Schema::create('de_sifen', function (Blueprint $table) {
            $table->id();
            $table->string('cdc')->unique();
            $table->text('xml')->nullable();
            $table->json('jsonbody')->nullable();
            $table->string('environment'); // dev, pruebas, prod
            $table->timestamp('moment')->useCurrent();
            $table->timestamps();
            
            $table->index('cdc');
            $table->index('moment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('de_sifen');
    }
};
