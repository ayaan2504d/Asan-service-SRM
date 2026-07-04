<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solar_clients', function (Blueprint $table) {
            $table->id();
            $table->string('client_id', 20)->unique()->index();
            $table->string('name')->nullable();
            $table->string('contact', 30)->nullable()->index();
            $table->text('address')->nullable();
            $table->string('area')->nullable();
            $table->integer('num_plates')->nullable();
            $table->string('package')->nullable();
            $table->decimal('amount', 12, 2)->nullable();
            $table->string('payment_mode')->nullable();
            $table->string('received_by')->nullable();
            $table->date('first_visit_date')->nullable();
            $table->date('second_visit_date')->nullable();
            $table->decimal('second_amount', 12, 2)->nullable();
            $table->string('second_payment_mode')->nullable();
            $table->string('second_received_by')->nullable();
            $table->text('notes')->nullable();
            $table->date('reminder_date')->nullable()->index();
            $table->boolean('reminder_sent')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solar_clients');
    }
};
