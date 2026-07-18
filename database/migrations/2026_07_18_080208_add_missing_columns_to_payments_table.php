<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('enrollment_id')->nullable()->constrained()->nullOnDelete()->after('student_id');
            $table->date('payment_date')->after('amount');
            $table->string('method')->after('payment_date');
            $table->string('reference')->nullable()->after('method');
            $table->text('notes')->nullable()->after('reference');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['enrollment_id']);
            $table->dropForeign(['created_by']);
            $table->dropColumn([
                'enrollment_id',
                'payment_date',
                'method',
                'reference',
                'notes',
                'created_by',
            ]);
        });
    }
};

