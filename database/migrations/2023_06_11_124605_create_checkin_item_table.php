<?php

use App\Models\Checkin;
use App\Models\Item;
use App\Models\Room;
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
        Schema::create('checkin_item', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Checkin::class);
            $table->foreignIdFor(Item::class);
            $table->foreignIdFor(\App\Models\Rack::class)->nullable();
            $table->unsignedFloat('quantity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkin_item');
    }
};
