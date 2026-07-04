<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solar_sites', function (Blueprint $table) {
            $table->id();
            $table->string('site_id', 20)->unique()->index();
            $table->string('client_name')->nullable();
            $table->string('contact', 30)->nullable()->index();
            $table->text('address')->nullable();
            $table->string('area')->nullable();
            $table->integer('num_plates')->nullable();
            $table->decimal('charges', 12, 2)->nullable();
            $table->date('visit_date')->nullable()->index();
            $table->time('visit_time')->nullable();
            $table->string('team')->nullable();
            $table->string('status')->nullable()->index();
            $table->string('payment_type')->nullable();
            $table->string('received_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solar_sites');
    }
};
