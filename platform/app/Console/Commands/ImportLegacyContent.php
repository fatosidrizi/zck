<?php

namespace App\Console\Commands;

use App\Models\News;
use App\Models\Ngo;
use App\Models\PublicCall;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * One-off migration of the legacy WordPress site
 * (zck-kpz-platform.rks-gov.net) into this platform.
 *
 * The harvest/transform runs outside Laravel and leaves a JSON payload;
 * this command is the only thing that touches the database, so the risky
 * half of the migration is a single reviewable file.
 */
class ImportLegacyContent extends Command
{
    protected $signature = 'zck:import-legacy
                            {--file= : Path to the transformed import.json}
                            {--fresh : Delete existing ngos/news/public_calls first}';

    protected $description = 'Import NGOs, news, bulletins and public calls migrated from the legacy WordPress site';

    public function handle(): int
    {
        $path = $this->option('file');

        if (! $path || ! is_file($path)) {
            $this->error("Pass --file=/path/to/import.json (got: ".var_export($path, true).")");

            return self::FAILURE;
        }

        $data = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);

        $author = User::where('role', 'super_admin')->first() ?? User::first();

        if (! $author) {
            $this->error('No user to attribute imported content to.');

            return self::FAILURE;
        }

        if ($this->option('fresh')) {
            DB::transaction(function () {
                // ngo_status_histories has an FK onto ngos, so it goes first.
                $counts = [
                    'ngo_status_histories' => DB::table('ngo_status_histories')->delete(),
                    'news' => DB::table('news')->delete(),
                    'public_calls' => DB::table('public_calls')->delete(),
                    'ngos' => DB::table('ngos')->delete(),
                ];
                foreach ($counts as $table => $n) {
                    $this->line("  deleted {$n} from {$table}");
                }
            });
        }

        DB::transaction(function () use ($data, $author) {
            foreach ($data['ngos'] as $row) {
                Ngo::create([
                    'name' => $row['name'],
                    'slug' => $row['slug'],
                    'abbreviation' => $row['abbreviation'],
                    'registration_number' => $row['registration_number'],
                    'fiscal_number' => $row['fiscal_number'],
                    'responsible_person' => $row['responsible_person'],
                    'responsible_person_contact' => $row['responsible_person_contact'],
                    'contact_email' => $row['contact_email'],
                    'contact_phone' => $row['contact_phone'],
                    'primary_community' => $row['primary_community'],
                    'additional_communities' => $row['additional_communities'],
                    'category' => $row['category'],
                    'declared_at' => $row['declared_at'],
                    'is_active' => true,
                    'status' => 'approved',
                ]);
            }
            $this->line('  imported '.count($data['ngos']).' ngos');

            foreach ($data['news'] as $row) {
                News::create([
                    'title' => $row['title'],
                    'slug' => $row['slug'],
                    'body' => $row['body'],
                    'image' => $row['image'],
                    'category' => $row['category'],
                    'status' => 'published',
                    'published_at' => $row['published_at'],
                    'author_id' => $author->id,
                ]);
            }
            $this->line('  imported '.count($data['news']).' news/bulletin/report rows');

            foreach ($data['public_calls'] as $row) {
                PublicCall::create([
                    'title' => $row['title'],
                    'slug' => $row['slug'],
                    'body' => $row['body'],
                    'type' => $row['type'],
                    'status' => 'published',
                    'author_id' => $author->id,
                ]);
            }
            $this->line('  imported '.count($data['public_calls']).' public calls');
        });

        $this->info('Legacy import complete.');

        return self::SUCCESS;
    }
}
