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
        Schema::table('fields', function (Blueprint $table) {
            $table->string('no_whatsapp', 15)->nullable();
            $table->string('custom_domain', 255)->nullable();
            $table->string('instagram', 255)->nullable();
            $table->string('facebook', 255)->nullable();
            $table->string('video', 255)->nullable();
            $table->enum('batas_pembayaran', ['30 menit', '1 jam', '2 jam', '10 jam', '24 jam'])->nullable();
            $table->text('syarat_ketentuan')->nullable();
            $table->boolean('status')->default(1); // 1 untuk aktif, 0 untuk nonaktif
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fields', function (Blueprint $table) {
            $table->dropColumn([
                'no_whatsapp',
                'custom_domain',
                'instagram',
                'facebook',
                'video',
                'batas_pembayaran',
                'syarat_ketentuan',
                'status'
            ]);
        });
    }
};
