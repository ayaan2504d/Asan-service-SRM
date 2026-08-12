<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            if (!Schema::hasColumn('leads', 'address')) {
                $table->text('address')->nullable()->after('contact');
            }
            if (!Schema::hasColumn('leads', 'service_type')) {
                $table->string('service_type', 100)->nullable()->after('address');
            }
            if (!Schema::hasColumn('leads', 'lead_source')) {
                $table->string('lead_source', 100)->nullable()->after('service_type');
            }
            if (!Schema::hasColumn('leads', 'status')) {
                $table->string('status', 50)->default('Pending')->after('lead_source')->index();
            }
            if (!Schema::hasColumn('leads', 'num_plates')) {
                $table->integer('num_plates')->nullable()->after('status');
            }
            if (!Schema::hasColumn('leads', 'quoted_amount')) {
                $table->decimal('quoted_amount', 12, 2)->nullable()->after('num_plates');
            }
            if (!Schema::hasColumn('leads', 'num_sofas')) {
                $table->integer('num_sofas')->nullable()->after('quoted_amount');
            }
            if (!Schema::hasColumn('leads', 'sofa_type')) {
                $table->string('sofa_type', 100)->nullable()->after('num_sofas');
            }
            if (!Schema::hasColumn('leads', 'house_size')) {
                $table->string('house_size', 100)->nullable()->after('sofa_type');
            }
            if (!Schema::hasColumn('leads', 'floors')) {
                $table->string('floors', 50)->nullable()->after('house_size');
            }
            if (!Schema::hasColumn('leads', 'notes')) {
                $table->text('notes')->nullable()->after('floors');
            }
            if (!Schema::hasColumn('leads', 'converted_type')) {
                $table->string('converted_type', 100)->nullable()->after('notes');
            }
            if (!Schema::hasColumn('leads', 'converted_id')) {
                $table->unsignedBigInteger('converted_id')->nullable()->after('converted_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn([
                'address',
                'service_type',
                'lead_source',
                'status',
                'num_plates',
                'quoted_amount',
                'num_sofas',
                'sofa_type',
                'house_size',
                'floors',
                'notes',
                'converted_type',
                'converted_id',
            ]);
        });
    }
};
