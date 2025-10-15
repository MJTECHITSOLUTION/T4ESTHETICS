<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customer_acts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->dateTime('act_date')->nullable();
            $table->string('status')->default('planned');
            $table->text('note')->nullable();
            $table->double('service_price')->default(0);
            $table->integer('duration_min')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['user_id', 'service_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_acts');
    }
};


