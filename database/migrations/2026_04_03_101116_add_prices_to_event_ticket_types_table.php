<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_ticket_types', function (Blueprint $table) {
            // Add the missing price columns
            if (!Schema::hasColumn('event_ticket_types', 'national_price')) {
                $table->integer('national_price')->default(0)->after('type');
            }
            if (!Schema::hasColumn('event_ticket_types', 'foreign_price')) {
                $table->integer('foreign_price')->default(0)->after('national_price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('event_ticket_types', function (Blueprint $table) {
            $table->dropColumn(['national_price', 'foreign_price']);
        });
    }
};
