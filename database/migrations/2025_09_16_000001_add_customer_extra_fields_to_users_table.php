<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('profession')->nullable()->after('gender');
            $table->text('adresse')->nullable()->after('profession');
            $table->json('langue_parlee')->nullable()->after('adresse');
            $table->string('adressage')->nullable()->after('langue_parlee');
            $table->json('motif_consultation')->nullable()->after('adressage');
            $table->string('origine_patient')->nullable()->after('motif_consultation');
            $table->text('remarque_interne')->nullable()->after('origine_patient');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'profession',
                'adresse',
                'langue_parlee',
                'adressage',
                'motif_consultation',
                'origine_patient',
                'remarque_interne',
            ]);
        });
    }
};


