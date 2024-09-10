<?php

use App\Models\Room;
use App\Models\User;
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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->text('reason');
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(\App\Models\TransferStatus::class);
            $table->foreignIdFor(Room::class, "from_room_id");
            $table->foreignIdFor(Room::class, "to_room_id");
//            $table->schemalessAttributes('extra_attributes');
            $table->timestamps();
//            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
