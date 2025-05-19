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
        Schema::create('withdraws', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Vendor yang mengajukan
            $table->unsignedBigInteger('rekening_id'); // Rekening tujuan
            $table->decimal('amount', 15, 2); // Jumlah withdraw
            $table->string('status')->default('pending'); // pending, approved, canceled, completed
            $table->unsignedBigInteger('admin_id')->nullable(); // Admin yang memproses
            $table->text('note')->nullable(); // Catatan jika ada
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('rekening_id')->references('id')->on('rekening')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('withdraws');
    }
};
