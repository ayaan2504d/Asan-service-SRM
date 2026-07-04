<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('lead_id', 20)->unique()->index();
            $table->date('date')->nullable()->index();
            $table->string('name')->nullable()->index();
            $table->string('contact', 30)->nullable()->index();
            $table->string('platform')->nullable();
            $table->string('campaign_name')->nullable();
            $table->string('response')->nullable();
            $table->string('location')->nullable();
            $table->string('type_of_site')->nullable();
            $table->string('type_of_service')->nullable();
            $table->string('size_of_area')->nullable();
            $table->integer('num_items')->nullable();
            $table->date('booking_date')->nullable();
            $table->date('service_date')->nullable();
            $table->time('appointment_time')->nullable();
            $table->decimal('estimated_amount', 12, 2)->nullable();
            $table->decimal('amount_received', 12, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
