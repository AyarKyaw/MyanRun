<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Make sure this is here

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("UPDATE tickets SET 
            created_at = DATE_ADD(created_at, INTERVAL 390 MINUTE), 
            updated_at = DATE_ADD(updated_at, INTERVAL 390 MINUTE)");
    }

    public function down(): void
    {
        DB::statement("UPDATE tickets SET 
            created_at = DATE_SUB(created_at, INTERVAL 390 MINUTE), 
            updated_at = DATE_SUB(updated_at, INTERVAL 390 MINUTE)");
    }
};
