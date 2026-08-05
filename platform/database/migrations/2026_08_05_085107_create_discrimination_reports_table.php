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
        Schema::create('discrimination_reports', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_code')->unique();
            $table->string('reporter_name');
            $table->string('reporter_email')->nullable();
            $table->string('reporter_phone')->nullable();
            $table->string('type');
            $table->text('description');
            $table->string('location')->nullable();
            $table->date('incident_date')->nullable();
            $table->string('evidence_file')->nullable();
            $table->enum('status', ['new', 'in_review', 'resolved', 'dismissed'])->default('new');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discrimination_reports');
    }
};
