<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sentiment as Tweet;

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
    protected $description = 'Import tweets from a JSON file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $filePath = public_path('tweets.json');

        if (!file_exists($filePath)) {
            $this->error('File does not exist: ' . $filePath);
            return 1;
        }

        $tweetsData = json_decode(file_get_contents($filePath), true);

        if (!$tweetsData || !isset($tweetsData['tweets'])) {
            $this->error('Invalid JSON file format');
            return 1;
        }

        foreach ($tweetsData['tweets'] as $tweetData) {
            Tweet::create([
                'date' => $tweetData['date'],
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

        $this->info('Tweets imported successfully');
        return 0;
    }
}
