<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fix Bug #3: Payment model and PaymentService use columns
 * that were missing from the original payments migration.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('enrollment_id')
                  ->nullable()->after('student_id')
                  ->constrained()->nullOnDelete();

            $table->date('payment_date')->nullable()->after('amount');
            $table->string('method')->nullable()->after('payment_date');
            $table->string('reference')->nullable()->after('method');
            $table->text('notes')->nullable()->after('reference');

            $table->foreignId('created_by')
                  ->nullable()->after('notes')
                  ->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['enrollment_id']);
            $table->dropForeign(['created_by']);
            $table->dropColumn(['enrollment_id', 'payment_date', 'method', 'reference', 'notes', 'created_by']);
        });
    }
};
