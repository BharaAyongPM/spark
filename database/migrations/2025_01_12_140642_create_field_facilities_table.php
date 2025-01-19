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
        Schema::create('field_facilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('field_id'); // Foreign key ke tabel fields
            $table->unsignedBigInteger('facility_id'); // Foreign key ke tabel facilities
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('field_id')->references('id')->on('fields')->onDelete('cascade');
            $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('field_facilities');
    }
};
