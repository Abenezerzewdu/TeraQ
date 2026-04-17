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
        Schema::create('queues', function (Blueprint $table) {
            $table->id();

            $table->foreignId('business_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('name'); // e.g. "Haircut Queue"

            $table->enum('status', ['open', 'closed'])
                  ->default('open');

            $table->integer('avg_service_time') // in minutes
                  ->default(10);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};
