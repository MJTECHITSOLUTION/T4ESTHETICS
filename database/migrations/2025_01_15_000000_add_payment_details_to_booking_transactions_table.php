<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking_transactions', function (Blueprint $table) {
            // Add notes field
            $table->text('notes')->nullable()->after('external_transaction_id');
            
            // Add cheque fields
            $table->string('cheque_number', 50)->nullable()->after('notes');
            $table->date('cheque_date')->nullable()->after('cheque_number');
            $table->string('bank_name', 100)->nullable()->after('cheque_date');
            
            // Add transfer fields
            $table->string('transfer_reference', 50)->nullable()->after('bank_name');
            $table->date('transfer_date')->nullable()->after('transfer_reference');
            
            // Add card fields
            $table->string('card_last_four', 4)->nullable()->after('transfer_date');
            $table->enum('card_type', ['visa', 'mastercard', 'amex', 'other'])->nullable()->after('card_last_four');
            $table->string('transaction_reference', 50)->nullable()->after('card_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_transactions', function (Blueprint $table) {
            $table->dropColumn([
                'notes',
                'cheque_number',
                'cheque_date',
                'bank_name',
                'transfer_reference',
                'transfer_date',
                'card_last_four',
                'card_type',
                'transaction_reference'
            ]);
        });
    }
};
