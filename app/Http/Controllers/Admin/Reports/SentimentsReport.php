<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Models\Sentiment as Tweet;
use VerumConsilium\Browsershot\Facades\PDF;
use App\Services\Sentiment\OverviewService;

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
        $tweets = Tweet::get();
        $tweetContent = $this->trendsService->tweetsContent($tweets, $this->searchFilter);

        $data = [
            "overallSentiments" => $overallSentiments,
            "sentimentsTimeline" => $sentimentsTimeline,
            "tweetsByLocation" => $tweetsByLocation,
            "tweetContent" => $tweetContent
        ];
        if ($reportType == "pdf") {


            $fileName = time() . "sentiments.pdf";

            $pdfStoredPath = PDF::loadView('reports/sentiments', compact('data'))->margins(10, 0, 0, 0);
            // ->setNodeBinary('/root/.nvm/versions/node/v16.0.0/bin/node')

            // ->setNpmBinary('/root/.nvm/versions/node/v16.0.0/bin/npm')->noSandbox();
            //   ->storeAs('pdfs/', $fileName);
            return $pdfStoredPath->download('report' . '.pdf');
            // return view('reports/sentiments', compact('data'));
        } else {

            $filename = 'employee-data.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
            ];

            return response()->stream(function () {
                $handle = fopen('php://output', 'w');

                // Add CSV headers
                fputcsv($handle, [
                    'First Name',
                    'Last Name',
                    'Email',
                    'Phone Number',
                    'Date of Birth',
                    'Gender',
                    'Address',
                    'Salary',
                    'Skills'
                ]);

                // Fetch and process data in chunks

                foreach ($tweetContent as $employee) {
                    // Extract data from each employee.
                    $data = [
                        isset($employee->first_name) ? $employee->first_name : '',
                        isset($employee->last_name) ? $employee->last_name : '',
                        isset($employee->email) ? $employee->email : '',
                        isset($employee->phone) ? $employee->phone : '',
                        isset($employee->date_of_birth) ? $employee->date_of_birth : '',
                        isset($employee->gender) ? $employee->gender : '',
                        isset($employee->address) ? $employee->address : '',
                        isset($employee->basic_salary) ? $employee->basic_salary : '',
                        isset($employee->skills) ? implode(", ", json_decode($employee->skills)) : '',
                    ];

                    // Write data to a CSV file.
                    fputcsv($handle, $data);
                }


                // Close CSV file handle
                fclose($handle);
            }, 200, $headers);
        }
    }
}
