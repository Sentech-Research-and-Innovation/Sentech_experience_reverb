<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\Sentiment as Tweet;
use Carbon\Carbon;


class ManualImport extends Command
{
    protected $signature = 'manual:imports';
    protected $description = 'Import tweets from JSON files in the public/json_files directory';

    public function handle()
    {
        $path = public_path('json_files');
        $files = File::allFiles($path);

        foreach ($files as $file) {
            $this->info("Processing file: {$file->getFilename()}");

            // Read the JSON content
            $content = File::get($file);
            $data = json_decode($content, true);

            if (isset($data['tweets'])) {
                foreach ($data['tweets'] as $tweet) {
                    Tweet::create([
                        'date' => Carbon::parse($tweet['date']),
                        'text' => $tweet['text'],
                        'sentiment' => $tweet['sentiment'],
                        'id_str' => $tweet['id_str'],
                        'hashtags' => json_encode($tweet['hashtags']),
                        'user_mentions' => json_encode($tweet['user_mentions']),
                        'place' => $tweet['place'],
                        'user' => $tweet['user'],
                        'language' => $tweet['language'],
                        'possibly_sensitive' => $tweet['possibly_sensitive'],
                        'location_point' => $tweet['location_point'],
                        'location_box' => $tweet['location_box'],
                    ]);
                }
                $this->info("Imported tweets from {$file->getFilename()} successfully.");
            } else {
                $this->warn("No tweets found in {$file->getFilename()}.");
            }
        }

        $this->info('All files processed.');
    }
}
