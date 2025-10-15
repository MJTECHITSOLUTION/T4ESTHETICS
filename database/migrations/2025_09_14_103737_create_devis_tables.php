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
        // Main devis tables already exist in models; create converted factures table
        Schema::create('devis_factures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('devis_user_id');
            $table->string('facture_number')->unique();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->string('status')->default('unpaid'); // unpaid, paid, cancelled
            $table->timestamp('issued_at')->nullable();
            $table->timestamps();

            $table->foreign('devis_user_id')->references('id')->on('devis_user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devis_factures');
    }
};
