<?php

use App\Domain\Order\ValueObjects\OrderStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->foreignUlid('user_id');
            $table->string('user_name');
            $table->string('destination');
            $table->timestampTz('departure_date');
            $table->timestampTz('arrival_date');
            $table->string('status')->default(OrderStatus::REQUESTED->value);
            $table->timestamps();

            $table->index('user_id');
            $table->index('status');
            $table->index('departure_date');
            $table->index('arrival_date');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
