<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Sentiment as Tweet;
use Carbon\Carbon;

class ImportTweets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tweets:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import tweets from JSON files in S3';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Set the secondary bucket name from environment variables
        $secondaryBucket = env('AWS_SECONDARY_BUCKET');
        config(['filesystems.disks.s3.bucket' => $secondaryBucket]);

        // Initialize the S3 disk
        $disk = Storage::disk('s3_secondary');

        // Retrieve the latest tweet date from the database
        $latestTweet = Tweet::orderBy('date', 'desc')->first();
        $latestDate = $latestTweet ? Carbon::parse($latestTweet->date)->startOfDay() : Carbon::parse("2022-02-22 11:52:13");

        $this->info('Latest tweet date in database: ' . ($latestDate ? $latestDate->toDateString() : 'None'));

        // List files in the Sentiment_data folder of the S3 bucket
        $files = $disk->files('Sentiment_data');

        $this->info('Files in Sentiment_data folder: ' . implode(', ', $files));

        // Filter files to only include those with a date later than the latest date
        $filesToImport = array_filter($files, function ($file) use ($latestDate) {
            preg_match('/sentiment_(\d{4}-\d{2}-\d{2})\.json$/', $file, $matches);
            if ($matches) {
                $fileDate = Carbon::parse($matches[1])->startOfDay();
                return !$latestDate || $fileDate->greaterThan($latestDate);
            }
            return false;
        });

        if (empty($filesToImport)) {
            $this->info('No new tweets to import.');
            return 0;
        }

        $this->info('Files to import: ' . implode(', ', $filesToImport));

        // Import tweets from selected files
        foreach ($filesToImport as $file) {
            $fileContents = $disk->get($file);
            $tweetsData = json_decode($fileContents, true);

            if (!$tweetsData || !isset($tweetsData['tweets'])) {
                $this->error('Invalid JSON file format: ' . $file);
                continue;
            }

            foreach ($tweetsData['tweets'] as $tweetData) {
                Tweet::create([
                    'date' => Carbon::parse($tweetData['date']),
                    'text' => $tweetData['text'],
                    'sentiment' => $tweetData['sentiment'],
                    'id_str' => $tweetData['id_str'],
                    'hashtags' => json_encode($tweetData['hashtags']),
                    'user_mentions' => json_encode($tweetData['user_mentions']),
                    'place' => $tweetData['place'],
                    'user' => $tweetData['user'],
                    'language' => $tweetData['language'],
                    'possibly_sensitive' => $tweetData['possibly_sensitive'],
                    'location_point' => $tweetData['location_point'],
                    'location_box' => $tweetData['location_box'],
                ]);
            }

            $this->info('Tweets from ' . $file . ' imported successfully');
        }

        return 0;
    }
}
