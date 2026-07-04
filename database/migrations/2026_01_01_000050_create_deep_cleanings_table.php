<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deep_cleanings', function (Blueprint $table) {
            $table->id();
            $table->string('record_id', 20)->unique()->index();
            $table->string('customer_name')->nullable()->index();
            $table->string('contact', 30)->nullable()->index();
            $table->text('address')->nullable();
            $table->string('property_type')->nullable();
            $table->integer('num_rooms')->nullable();
            $table->string('area_size')->nullable();
            $table->decimal('service_charges', 12, 2)->nullable();
            $table->date('service_date')->nullable()->index();
            $table->string('team')->nullable();
            $table->string('status')->nullable()->index();
            $table->decimal('amount_received', 12, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deep_cleanings');
    }
};
