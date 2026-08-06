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
        Schema::create('ngo_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ngo_id')->constrained()->cascadeOnDelete();
            // What happened, so visibility changes (which leave the status alone) are
            // still readable in the trail: submitted, approved, rejected, reopened,
            // unpublished, republished.
            $table->string('event');
            $table->string('old_status')->nullable();
            $table->string('new_status');
            $table->text('note')->nullable();
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['ngo_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ngo_status_histories');
    }
};
