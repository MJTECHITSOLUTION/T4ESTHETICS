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
        Schema::table('customer_consents', function (Blueprint $table) {
            $table->text('signature_data')->nullable(); // Base64 signature data
            $table->string('signature_file_path')->nullable(); // Path to signature image file
            $table->timestamp('signed_at')->nullable(); // When the signature was made
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_consents', function (Blueprint $table) {
            $table->dropColumn(['signature_data', 'signature_file_path', 'signed_at']);
        });
    }
};
