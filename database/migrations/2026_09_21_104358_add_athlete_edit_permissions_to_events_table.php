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
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('allow_athlete_edit')
                ->default(false)
                ->after('is_active');

            $table->boolean('allow_edit_required')
                ->default(false)
                ->after('allow_athlete_edit');

            $table->boolean('allow_edit_personal')
                ->default(false)
                ->after('allow_edit_required');

            $table->boolean('allow_edit_contact')
                ->default(false)
                ->after('allow_edit_personal');

            $table->boolean('allow_edit_participant')
                ->default(false)
                ->after('allow_edit_contact');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'allow_athlete_edit',
                'allow_edit_required',
                'allow_edit_personal',
                'allow_edit_contact',
                'allow_edit_participant',
            ]);
        });
    }
};
