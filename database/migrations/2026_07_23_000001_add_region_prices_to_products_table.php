<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Per-region prices. `price` stays the GBP base; these override it for US/CA.
            $table->decimal('price_usd', 8, 2)->nullable()->after('price');
            $table->decimal('price_cad', 8, 2)->nullable()->after('price_usd');
        });

        // Seed sensible starting values from the GBP base (admin can edit any of these).
        // One-time only — not a live conversion. Rounded to nice .99 pricing.
        foreach (DB::table('products')->get() as $p) {
            DB::table('products')->where('id', $p->id)->update([
                'price_usd' => max(0.99, ceil((float) $p->price * 1.27) - 0.01),
                'price_cad' => max(0.99, ceil((float) $p->price * 1.72) - 0.01),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['price_usd', 'price_cad']);
        });
    }
};
