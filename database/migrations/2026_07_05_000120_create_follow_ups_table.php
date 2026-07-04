<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('follow_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solar_client_id')->constrained()->cascadeOnDelete();
            $table->date('due_date')->index();
            $table->string('status', 30)->default('pending')->index();
            $table->string('source', 50)->default('solar_latest_visit');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['solar_client_id', 'due_date', 'source']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('follow_ups');
    }
};
