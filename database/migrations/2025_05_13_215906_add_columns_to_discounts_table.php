<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToDiscountsTable extends Migration
{
    public function up()
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('field_id');
            $table->integer('max_usage')->nullable()->after('percentage'); // maksimum penggunaan
            $table->integer('used_count')->default(0)->after('max_usage'); // sudah digunakan
            $table->integer('max_discount')->nullable()->after('percentage'); // max nominal
            $table->integer('min_order_total')->nullable()->after('max_discount'); // minimum order total
            $table->boolean('status')->default(true)->after('automatic'); // aktif/nonaktif
            $table->text('description')->nullable()->after('status'); // deskripsi
        });

        // Optional: tambahkan foreign key untuk user_id
        Schema::table('discounts', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id',
                'max_usage',
                'used_count',
                'max_discount',
                'min_order_total',
                'status',
                'description',
            ]);
        });
    }
};
