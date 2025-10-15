<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->index();

            // Identité
            $table->string('identite_nom')->nullable();
            $table->string('identite_prenom')->nullable();
            $table->date('identite_date_naissance')->nullable();
            $table->string('identite_email')->nullable();
            $table->string('identite_telephone')->nullable();
            $table->string('identite_adresse')->nullable();
            $table->string('identite_profession')->nullable();
            $table->boolean('identite_newsletter')->default(false);

            // Groupes (JSON)
            $table->json('antecedents')->nullable();
            $table->json('autoimmunes')->nullable();
            $table->string('autoimmunes_autre')->nullable();
            $table->json('allergies')->nullable();
            $table->string('allergies_autre')->nullable();
            $table->text('traitements')->nullable();
            $table->json('esthetiques')->nullable();
            $table->string('esthetiques_autre')->nullable();
            $table->json('motifs')->nullable();
            $table->string('motifs_autre')->nullable();

            // Parrainage & Déclaration
            $table->boolean('parrainage')->default(false);
            $table->boolean('declaration_exactitude')->default(false);

            $table->timestamps();

            // Optional foreign key to users (or customers) if applicable
            // Comment out if your DB uses a different customers table name
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_infos');
    }
};


