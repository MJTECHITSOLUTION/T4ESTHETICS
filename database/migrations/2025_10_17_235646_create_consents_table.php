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
        Schema::create('consents', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Name of the consent
            $table->text('description')->nullable(); // Description of what the consent is for
            $table->text('content')->nullable(); // Full content/text of the consent
            $table->boolean('is_active')->default(true); // Whether this consent is currently active
            $table->boolean('is_required')->default(false); // Whether this consent is required for customers
            $table->integer('sort_order')->default(0); // For ordering consents
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consents');
    }
};
