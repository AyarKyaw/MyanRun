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
        Schema::table('athletes', function (Blueprint $table) {
            // Adding nullable string columns for storing uploaded image relative paths
            $table->string('job_image_path')->nullable()->after('face_image_path');
            $table->string('visa_image_path')->nullable()->after('job_image_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('athletes', function (Blueprint $table) {
            $table->dropColumn(['job_image_path', 'visa_image_path']);
        });
    }
};