<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('athletes', function (Blueprint $table) {
            // Rename job_image_path if it exists, or add passport_image_path
            if (Schema::hasColumn('athletes', 'job_image_path')) {
                $table->renameColumn('job_image_path', 'passport_image_path');
            } else {
                $table->string('passport_image_path')->nullable();
            }

            // Add text field for Job/Work
            $table->string('job_work')->nullable()->after('passport_image_path');
        });
    }

    public function down(): void
    {
        Schema::table('athletes', function (Blueprint $table) {
            $table->renameColumn('passport_image_path', 'job_image_path');
            $table->dropColumn('job_work');
        });
    }
};