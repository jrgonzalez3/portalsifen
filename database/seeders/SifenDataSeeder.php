<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class SifenDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // RUC Queries
        if (DB::table('ruc_queries')->count() == 0) {
            DB::table('ruc_queries')->insert([
                [
                    'ruc_number' => '80000001-1',
                    'taxpayer_name' => 'Empresa de Prueba SA',
                    'taxpayer_data' => json_encode(['nombre' => 'Empresa de Prueba SA', 'tipo' => 'Juridica']),
                    'environment' => 'pruebas',
                    'status' => 'activo',
                    'user_id' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'ruc_number' => '80000002-2',
                    'taxpayer_name' => 'Juan Perez',
                    'taxpayer_data' => json_encode(['nombre' => 'Juan Perez', 'tipo' => 'Fisica']),
                    'environment' => 'prod',
                    'status' => 'bloqueado',
                    'user_id' => 1,
                    'created_at' => Carbon::now()->subDays(1),
                    'updated_at' => Carbon::now()->subDays(1),
                ]
            ]);
        }

        // CDC Documents
        if (DB::table('cdc_documents')->count() == 0) {
            DB::table('cdc_documents')->insert([
                [
                    'cdc_number' => '01800000010100100100100100100100100100100100',
                    'environment' => 'pruebas',
                    'response_data' => json_encode(['estado' => 'Aprobado']),
                    'status' => 'Aprobado',
                    'user_id' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            ]);
        }

        // Lote Batches
        if (DB::table('lote_batches')->count() == 0) {
            DB::table('lote_batches')->insert([
                [
                    'batch_number' => '123456789',
                    'environment' => 'prod',
                    'response_data' => json_encode(['procesados' => 50, 'rechazados' => 0]),
                    'status' => 'Procesado',
                    'user_id' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            ]);
        }
        
         // DE Sifen
        if (DB::table('de_sifen')->count() == 0) {
            DB::table('de_sifen')->insert([
                [
                    'cdc' => '01800000010100100100100122200100100100100122',
                    'xml' => '<xml>Dummy XML</xml>',
                    'jsonbody' => json_encode(['id' => 1, 'data' => 'test']),
                    'environment' => 'dev',
                    'status' => 'Generado',
                    'moment' => Carbon::now(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            ]);
        }

        // API Logs
        if (DB::table('api_logs')->count() == 0) {
            DB::table('api_logs')->insert([
                [
                    'api_name' => 'ruc',
                    'environment' => 'pruebas',
                    'endpoint' => 'https://sifen.set.gov.py/de/ws/consultas/ruc',
                    'status_code' => 200,
                    'status' => 'OK',
                    'response_time' => 150,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'api_name' => 'kude',
                    'environment' => 'dev',
                    'endpoint' => 'https://sifen.set.gov.py/de/ws/consultas/kude',
                    'status_code' => 404,
                    'status' => 'Not Found',
                    'response_time' => 120,
                    'created_at' => Carbon::now()->subHour(),
                    'updated_at' => Carbon::now()->subHour(),
                ]
            ]);
        }
    }
}
