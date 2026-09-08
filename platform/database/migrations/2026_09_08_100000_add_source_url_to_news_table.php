<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            // Where an imported article came from. Unique so pasting the same
            // link twice is a no-op instead of a duplicate.
            $table->string('source_url')->nullable()->unique()->after('image');
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropUnique(['source_url']);
            $table->dropColumn('source_url');
        });
    }
};
