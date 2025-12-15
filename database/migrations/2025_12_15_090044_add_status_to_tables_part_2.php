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
        Schema::table('ruc_queries', function (Blueprint $table) {
            if (!Schema::hasColumn('ruc_queries', 'status')) {
                $table->string('status')->nullable()->after('environment');
            }
        });

        Schema::table('api_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('api_logs', 'status')) {
                $table->string('status')->nullable()->after('status_code');
            }
        });

        Schema::table('de_sifen', function (Blueprint $table) {
            if (!Schema::hasColumn('de_sifen', 'status')) {
                $table->string('status')->nullable()->after('environment');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ruc_queries', function (Blueprint $table) {
             $table->dropColumn('status');
        });
        Schema::table('api_logs', function (Blueprint $table) {
             $table->dropColumn('status');
        });
        Schema::table('de_sifen', function (Blueprint $table) {
             $table->dropColumn('status');
        });
    }
};
