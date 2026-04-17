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
       Schema::create('queue_entries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('queue_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // For now, no auth → just name
            $table->string('user_name');

            // Position in queue
            $table->integer('position');

            // Status lifecycle
            $table->enum('status', [
                'waiting',
                'serving',
                'done',
                'left'
            ])->default('waiting');

            $table->timestamp('joined_at')->useCurrent();
            $table->timestamp('served_at')->nullable();

            $table->timestamps();

            // 🚀 Important for performance
            $table->index(['queue_id', 'status']);
            $table->index(['queue_id', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queue_entries');
    }
};
