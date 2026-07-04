<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solar_clients', function (Blueprint $table) {
            $table->string('whatsapp_number', 30)->nullable()->after('contact');
            $table->date('third_visit_date')->nullable()->after('second_visit_date');
            $table->text('third_visit_notes')->nullable()->after('third_visit_date');
            $table->date('fourth_visit_date')->nullable()->after('third_visit_notes');
            $table->text('fourth_visit_notes')->nullable()->after('fourth_visit_date');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->string('whatsapp_number', 30)->nullable()->after('contact');
        });

        Schema::table('sofa_cleanings', function (Blueprint $table) {
            $table->string('whatsapp_number', 30)->nullable()->after('contact');
        });

        Schema::table('deep_cleanings', function (Blueprint $table) {
            $table->string('whatsapp_number', 30)->nullable()->after('contact');
        });
    }

    public function down(): void
    {
        Schema::table('deep_cleanings', function (Blueprint $table) {
            $table->dropColumn('whatsapp_number');
        });

        Schema::table('sofa_cleanings', function (Blueprint $table) {
            $table->dropColumn('whatsapp_number');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('whatsapp_number');
        });

        Schema::table('solar_clients', function (Blueprint $table) {
            $table->dropColumn([
                'whatsapp_number',
                'third_visit_date',
                'third_visit_notes',
                'fourth_visit_date',
                'fourth_visit_notes',
            ]);
        });
    }
};
