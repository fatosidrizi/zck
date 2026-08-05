<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // News: title, body
        $this->convertToJson('news', ['title', 'body']);

        // Public Calls: title, body
        $this->convertToJson('public_calls', ['title', 'body']);

        // NGOs: name, description
        $this->convertToJson('ngos', ['name', 'description']);

        // Communities: name, description
        $this->convertToJson('communities', ['name', 'description']);

        // Events: title, description
        $this->convertToJson('events', ['title', 'description']);
    }

    public function down(): void
    {
        $this->revertFromJson('news', ['title', 'body']);
        $this->revertFromJson('public_calls', ['title', 'body']);
        $this->revertFromJson('ngos', ['name', 'description']);
        $this->revertFromJson('communities', ['name', 'description']);
        $this->revertFromJson('events', ['title', 'description']);
    }

    private function convertToJson(string $table, array $columns): void
    {
        // First wrap existing string values in JSON {"en": "value"}
        foreach ($columns as $column) {
            DB::table($table)->whereNotNull($column)->eachById(function ($row) use ($table, $column) {
                $value = $row->$column;
                // Skip if already JSON
                if (str_starts_with($value, '{')) return;
                DB::table($table)->where('id', $row->id)->update([
                    $column => json_encode(['en' => $value]),
                ]);
            });
        }

        // Then change column type to JSON
        Schema::table($table, function (Blueprint $t) use ($columns) {
            foreach ($columns as $column) {
                $t->json($column)->nullable()->change();
            }
        });
    }

    private function revertFromJson(string $table, array $columns): void
    {
        Schema::table($table, function (Blueprint $t) use ($columns) {
            foreach ($columns as $column) {
                $t->text($column)->nullable()->change();
            }
        });

        foreach ($columns as $column) {
            DB::table($table)->whereNotNull($column)->eachById(function ($row) use ($table, $column) {
                $value = $row->$column;
                $decoded = json_decode($value, true);
                if (is_array($decoded)) {
                    DB::table($table)->where('id', $row->id)->update([
                        $column => $decoded['en'] ?? '',
                    ]);
                }
            });
        }
    }
};
