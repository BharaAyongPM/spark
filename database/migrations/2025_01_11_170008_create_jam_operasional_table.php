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
        Schema::create('jam_operasional', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('field_id'); // Relasi ke tabel fields
            $table->string('senin', 50)->nullable(); // Contoh format: "07:00-23:00"
            $table->string('selasa', 50)->nullable();
            $table->string('rabu', 50)->nullable();
            $table->string('kamis', 50)->nullable();
            $table->string('jumat', 50)->nullable();
            $table->string('sabtu', 50)->nullable();
            $table->string('minggu', 50)->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('field_id')->references('id')->on('fields')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jam_operasional');
    }
};
