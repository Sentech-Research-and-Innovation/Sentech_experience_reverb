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


            $fileName = time() . "sentiments.pdf";

            //             Browsershot::html($someHtml)
            //    ->showBrowserHeaderAndFooter()
            //    ->headerHtml($someHtml)
            //    ->footerHtml($someHtml)
            //    ->save('example.pdf');
            // /$someHtml = "hallo";

            // $style = '<style>
            // .footer{
            // width:1000px;
            // color:red;
            // background-color:#000000;
            // display:block;
            // font-size:30px;
            // -webkit-print-color-adjust: exact
            //  }
            // </style>';


            // $html = '<footer><div class="footer">My foorter</div></footer>';



            $footerHtml =  '<style>
    html {
        -webkit-print-color-adjust: exact;
    }

    .footer {

        color: #ffffff;
        width: 100%;
        background-color: #144f9f;
        display: block;
        font-size: 11px;
        -webkit-print-color-adjust: exact;
        position: fixed;
        left: 0;
        bottom: 0;
        padding: 10px 10px;
        text-align: center
    }
</style>



<div class="footer">

    www.sentech.co.za | Email: support@sentech.co.za | Call Centre: 0860 736 832

</div>';
            $headerHtml =  view('reports/header')->render();

            $pdfStoredPath = PDF::loadView('reports/sentiments', compact('data'))->margins(10, 25, 17, 25)
                ->showBrowserHeaderAndFooter()->footerHtml($footerHtml)->headerHtml($headerHtml)->showBackground();
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
