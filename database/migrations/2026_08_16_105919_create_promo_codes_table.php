<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->decimal('amount', 10, 2);
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->boolean('is_used')->default(false);
            $table->timestamps();
        });

        Schema::create('promo_code_event_ticket_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promo_code_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_ticket_type_id')->constrained('event_ticket_types')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // Disable FK checks during rollback
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('promo_code_event_ticket_type');
        Schema::dropIfExists('promo_code_ticket_type');
        Schema::dropIfExists('promo_code_ticket');
        Schema::dropIfExists('promo_codes');

        // Re-enable FK checks
        Schema::enableForeignKeyConstraints();
    }
};