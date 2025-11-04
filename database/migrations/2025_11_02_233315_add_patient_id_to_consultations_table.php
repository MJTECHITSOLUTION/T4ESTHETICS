<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->unsignedBigInteger('patient_id')->nullable()->after('consultation_date');
            $table->foreign('patient_id')->references('id')->on('users')->onDelete('set null');
            $table->index('patient_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropForeign(['patient_id']);
            $table->dropIndex(['patient_id']);
            $table->dropColumn('patient_id');
        });
    }
};
