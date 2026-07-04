<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_settings', function (Blueprint $table) {
            $table->id();
            $table->string('business_number', 30)->nullable();
            $table->string('default_country_code', 8)->default('92');
            $table->boolean('enabled')->default(true);
            $table->text('solar_follow_up_template')->nullable();
            $table->text('sofa_cleaning_reminder_template')->nullable();
            $table->text('deep_cleaning_reminder_template')->nullable();
            $table->text('payment_reminder_template')->nullable();
            $table->text('thank_you_template')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_settings');
    }
};
