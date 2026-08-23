<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Ubah carts table (product_id -> product_variant_id)
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');
            $table->foreignId('product_variant_id')->after('user_id')->constrained('product_variants')->cascadeOnDelete();
        });

        // 2. Ubah order_items table (product_id -> product_variant_id)
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');
            $table->foreignId('product_variant_id')->after('order_id')->constrained('product_variants')->cascadeOnDelete();
        });

        // 3. Hapus price, stock, unit dari products table
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['price', 'stock', 'unit']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price', 15, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->string('unit')->default('pcs');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['product_variant_id']);
            $table->dropColumn('product_variant_id');
            $table->foreignId('product_id')->after('order_id')->constrained('products')->cascadeOnDelete();
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['product_variant_id']);
            $table->dropColumn('product_variant_id');
            $table->foreignId('product_id')->after('user_id')->constrained('products')->cascadeOnDelete();
        });
    }
};
