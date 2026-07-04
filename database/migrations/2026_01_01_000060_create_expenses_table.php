<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expense_id', 20)->unique()->index();
            $table->date('date')->index();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('amount', 12, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
