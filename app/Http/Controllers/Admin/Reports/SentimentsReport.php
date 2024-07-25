<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Models\Sentiment as Tweet;
use VerumConsilium\Browsershot\Facades\PDF;
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

        return $tweetsByLocation = $this->sentimentService->tweetsByLocation($this->tweets, $this->searchFilter);
        $tweets = Tweet::get();
        $tweetContent = $this->trendsService->tweetsContent($tweets, $this->searchFilter);

        return $data = [
            "overallSentiments" => $overallSentiments,
            "sentimentsTimeline" => $sentimentsTimeline,
            "tweetsByLocation" => $tweetsByLocation,
            "tweetContent" => $tweetContent
        ];
        if ($reportType == "pdf") {


            $fileName = time() . "sentiments.pdf";

            $pdfStoredPath = PDF::loadView('reports/sentiments', compact('data'))->margins(10, 0, 0, 0);
            // ->setNodeBinary('/home/ubuntu/.nvm/versions/node/v16.0.0/bin/node')

            // ->setNpmBinary('/home/ubuntu/.nvm/versions/node/v16.0.0/bin/npm')->noSandbox();
            //   ->storeAs('pdfs/', $fileName);
            return $pdfStoredPath->download('report' . '.pdf');
            // return view('reports/sentiments', compact('data'));
        } else {
            $csvFileName = 'sentiments-report.csv';

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
