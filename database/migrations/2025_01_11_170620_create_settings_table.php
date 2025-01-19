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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('fee_service', 15, 2)->default(0); // Fee dalam format uang
            $table->decimal('fee_xendit', 15, 2)->default(0); // Fee Xendit dalam format uang
            $table->decimal('fee_penarikan', 15, 2)->default(0); // Fee Penarikan dalam format uang
            $table->string('baner1')->nullable(); // Path untuk baner 1
            $table->string('baner2')->nullable(); // Path untuk baner 2
            $table->string('baner3')->nullable(); // Path untuk baner 3
            $table->text('deskripsi')->nullable(); // Deskripsi umum
            $table->string('nama_web')->nullable(); // Nama website
            $table->string('email_suport')->nullable(); // Email support
            $table->string('wa_suport', 15)->nullable(); // Nomor WhatsApp support
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
