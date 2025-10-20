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
        Schema::create('customer_consents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Customer ID
            $table->unsignedBigInteger('consent_id'); // Consent type ID
            $table->boolean('has_consented')->default(false); // Whether customer has given consent
            $table->timestamp('consented_at')->nullable(); // When consent was given
            $table->timestamp('revoked_at')->nullable(); // When consent was revoked
            $table->text('notes')->nullable(); // Additional notes
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('consent_id')->references('id')->on('consents')->onDelete('cascade');
            
            // Unique constraint to prevent duplicate consent records
            $table->unique(['user_id', 'consent_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_consents');
    }
};
