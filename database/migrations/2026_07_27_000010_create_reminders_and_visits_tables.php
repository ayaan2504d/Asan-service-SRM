<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('expenses', 'category')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->string('category', 100)->default('Other Expenses')->after('title')->index();
            });
        }

        if (!Schema::hasTable('service_reminders')) {
            Schema::create('service_reminders', function (Blueprint $table) {
                $table->id();
                $table->string('customer_id', 50)->nullable()->index();
                $table->string('customer_name')->nullable()->index();
                $table->string('contact', 30)->nullable()->index();
                $table->string('service_type', 100)->nullable()->index();
                $table->date('last_service_date')->nullable();
                $table->date('next_reminder_date')->nullable()->index();
                $table->string('status', 50)->default('Pending')->index();
                $table->string('reminder_type', 50)->default('14_days')->index();
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('customer_visits')) {
            Schema::create('customer_visits', function (Blueprint $table) {
                $table->id();
                $table->string('customer_id', 50)->nullable()->index();
                $table->string('customer_name')->nullable()->index();
                $table->string('contact', 30)->nullable()->index();
                $table->string('service_type', 100)->nullable()->index();
                $table->integer('visit_number')->default(1);
                $table->date('visit_date')->nullable()->index();
                $table->string('status', 50)->default('Scheduled')->index();
                $table->decimal('amount', 12, 2)->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_visits');
        Schema::dropIfExists('service_reminders');

        if (Schema::hasColumn('expenses', 'category')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->dropColumn('category');
            });
        }
    }
};
