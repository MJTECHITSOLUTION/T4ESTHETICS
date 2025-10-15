<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customer_act_galleries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_act_id');
            $table->enum('phase', ['before', 'after'])->default('after');
            $table->dateTime('session_date')->nullable();
            $table->text('note')->nullable();
            // Audit columns expected by BaseModel
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['customer_act_id', 'phase']);
            $table->foreign('customer_act_id')
                ->references('id')->on('customer_acts')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_act_galleries');
    }
};


