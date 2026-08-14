<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Models\Sentiment as Tweet;
use App\Support\Browsershot\Pdf as PDF;
use App\Services\Sentiment\OverviewService;
use Symfony\Component\HttpFoundation\StreamedResponse;

use App\Services\Sentiment\TrendsService;

class SentimentsReport extends Controller
{
    protected $sentimentService;
    protected $tweets;
    protected $searchFilter;
    protected $trendsService;

    public function __construct(OverviewService $sentimentService, TrendsService $trendsService)
    {
        $this->sentimentService = $sentimentService;
        $this->tweets = Tweet::all();
        $this->searchFilter = request()->searchFilter;
        $this->trendsService = $trendsService;
    }

    public function index()
    {
        $reportType = request()->reportType;

        $overallSentiments = $this->sentimentService->overallSentiments($this->tweets, $this->searchFilter);

        $sentimentsTimeline = $this->sentimentService->sentimentsTimeline($this->tweets, $this->searchFilter);

        $tweetsByLocation = $this->sentimentService->tweetsByLocation($this->tweets, $this->searchFilter);
        $tweets = Tweet::limit(100)->get();
        $tweetContent = $this->trendsService->tweetsContent($tweets, $this->searchFilter);

        $data = [
            "overallSentiments" => $overallSentiments,
            "sentimentsTimeline" => $sentimentsTimeline,
            "tweetsByLocation" => $tweetsByLocation,
            "tweetContent" => $tweetContent
        ];
        if ($reportType == "pdf") {


            $pdfStoredPath = PDF::loadView('reports/sentiments', compact('data'))->margins(10, 25, 17, 25);

            return $pdfStoredPath->download('sentiments-analysis-report-pdf' . '.pdf');
        } else {
            $csvFileName = 'sentiments-analysis-report-csv.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $csvFileName . '"',
            ];

            $callback = function () use ($tweetContent) {
                $csvContent = fopen('php://output', 'w');
                fputcsv($csvContent, ['Sentiment', 'Tweet', 'User', 'Date']);

                foreach ($tweetContent['tweetsContent'] as $row) {
                    fputcsv($csvContent, $row);
                }

                fclose($csvContent);
            };

            return new StreamedResponse($callback, 200, $headers);
        }
    }
}
