<?php

use App\Models\Item;
use App\Models\Room;
use App\Models\Transfer;
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
        Schema::create('item_transfer', function (Blueprint $table) {
            $table->id();

            $table->boolean('fullTransfer');
            $table->string('newCode')->nullable();
            $table->foreignIdFor(\App\Models\Rack::class, 'from_rack_id')->nullable();
            $table->foreignIdFor(\App\Models\Rack::class, 'to_rack_id')->nullable();
            $table->integer('quantity');

            $table->foreignIdFor(Transfer::class);
            $table->foreignIdFor(Item::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_transfer');
    }
};
