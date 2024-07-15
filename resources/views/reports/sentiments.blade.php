<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Roboto+Serif:ital,opsz,wght@0,8..144,100;0,8..144,600;0,8..144,800;1,8..144,500&display=swap"
        rel="stylesheet">


    <!--This only works pdf -->
    <link rel="stylesheet" href="{{ public_path('reportscss/theme.css') }}">
    <link rel="stylesheet" href="{{ public_path('reportscss/font-awesome.css') }}">

    <!--This only works on viewing blade-->
    {{-- <link rel="stylesheet" href="{{ asset('reportscss/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('reportscss/font-awesome.css') }}"> --}}

    <style>
        html {
            -webkit-print-color-adjust: exact;
        }

        .reports {
            color: #010440 !important;
        }

        .line-breaker {
            background-color: #f2c744;
            height: 4px;
        }

        .line-breaker2 {
            background-color: #f2c744;
            height: 2px;
            margin-top: 10px;
        }

        .overview {
            background-color: #e3f6f5;
        }

        .kpi-bg {
            background-color: #ffffff;
            min-height: 120px
        }

        .kpi-name {
            color: #2b6360;
            font-family: 'Roboto Serif', serif !important;
            font-weight: 500 !important
        }

        .section-heading {
            font-family: 'Roboto Serif', serif !important;
            font-weight: 600 !important;
            font-size: 18px;

        }

        .kpi-value {
            font-weight: 700;
            font-size: 35px;
            line-height: 35px;
            color: #020440 !important
        }

        .kpi-value-date {
            font-weight: 700;
            font-size: 13px;
            color: #020440 !important
        }

        .kpi-value-sites {
            font-weight: 700;
            font-size: 10px !important;
            color: #020440 !important
        }



        .kpi-name-label {
            color: #2b6360;
            font-weight: bold;
            font-size: 12px
        }

        .img-width {
            width: 130px;
            margin-top: 10px;
            height: 30px
        }

        h2 {
            font-family: 'Roboto Serif', serif !important;
            font-weight: 800 !important
        }

        .keyword-input {
            height: 36px !important;
            border: 1px solid #dddddd !important;
        }

        .btn-primary {
            background-color: #144f9f;
            border: none;
            height: 36px;
        }

        .tweets-container {
            color: #000 !important;
            border-top: 1px solid #c0bcbc;
        }

        .tweets-container-negative {
            color: rgba(255, 69, 96, 0.85) !important;
        }

        .tweets-container-neutral {
            color: rgba(119, 93, 208, 0.85) !important;
        }

        .tweets-container-positive {
            color: rgba(0, 227, 150, 0.85) !important;
        }

        .sentiment {
            border-right: 1px solid #dddddd;
        }

        .likes {
            border-right: 1px solid #dddddd;
        }

        .tweets-wrapper {}

        .sentiments-counts {
            border-left: 3px solid #dddddd;
            color: #737272;
        }

        .sentiments-labels {
            color: #144f9f !important;
        }

        .highlightedYellow {
            background-color: yellow !important;
            font-weight: bold;
        }
    </style>

</head>

<body>


    <div class="col-12  mb-3 mx-0 px-0">
        <div class="reports col-12 px-4">
            <div class="d-flex justify-content-between">
                <div class="col-6 pb-2">
                    <h2>Engage 360 Platform</h2>
                    {{-- <div class="col-12 py-1">
                        <i class="fas fa-envelope"></i> frfrfrfrf
                    </div> --}}
                    <h6 class="pt-2 kpi-name">Sentiment analysis report</h6>
                </div>
                <div class="col-6 pb-2 d-flex justify-content-end">
                    <img src="{{ public_path('sentech-logo2.png') }}" class="
                            img-width " />
                </div>
            </div>



        </div>
        <div class="line-breaker col-12"></div>

        <div class="col-12 py-5 overview rounded my-4">
            <div class="col-12 px-4">
                <div class="col-12">
                    <h4 class="section-heading">Overview KPIs and metrics</h4>
                </div>
                <div class="line-breaker2 col-12"></div>

                <!-- new line -->
                <div class="col-12 pt-3 px-0 mx-0">

                    <div class="col-12 px-0">
                        <div class="d-flex justify-content-between">

                            <div class="col-6 mb-3 px-2">
                                <div class="col-12 kpi-bg rounded py-3">
                                    <div class="col-12 text-center kpi-value pt-2">
                                        {{ $data['overallSentiments']['totalTweets'] }}</div>
                                    <div class="col-12 text-center pt-0 " style="color: #feb019;font-size: 16px;">
                                        Total Tweets
                                    </div>
                                </div>
                            </div>
                            <div class="col-6  mb-3 px-2">
                                <div class="col-12 kpi-bg rounded py-3">
                                    <div class="col-12 text-center kpi-value pt-2">
                                        {{ $data['overallSentiments']['positiveTweets'] }}</div>
                                    <div class="col-12 text-center pt-0 " style="color: #00e396;font-size: 16px;">
                                        Positive Tweets
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">


                            <div class="col-6 mb-3 px-2">
                                <div class="col-12 kpi-bg rounded py-3">
                                    <div class="col-12 text-center kpi-value pt-2">
                                        {{ $data['overallSentiments']['neutralTweets'] }}</div>
                                    <div class="col-12 text-center pt-0 "
                                        style="color: rgba(0, 0, 206, 0.2);font-size: 16px;">
                                        Neutral Tweets
                                    </div>
                                </div>
                            </div>


                            <div class="col-6 mb-3 px-2">
                                <div class="col-12 kpi-bg rounded py-3">
                                    <div class="col-12 text-center kpi-value pt-2">
                                        {{ $data['overallSentiments']['negativeTweets'] }}</div>
                                    <div class="col-12 text-center pt-0 " style="color: #ff4560;font-size: 16px;">
                                        Negative Tweets
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <div class="col-8">
                                <div class="col-12 px-4">
                                    <div style="width:100%">
                                        <canvas id="overviewPie"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 px-4 pb-5">
            <h4 class="section-heading">Overview</h4>
            <div class="line-breaker2" style="margin-bottom:20px"></div>
            <div>

                <div style="width: 100%; margin-left:0%; margin-top:2px" class=" d-flex justify-content-center">
                    <div style="width:100%">
                        <canvas id="timelineChart"></canvas>
                    </div>
                </div>

                <div style="width: 100%">
                    <canvas id="locationDoughnut"></canvas>
                </div>
            </div>
        </div>

        <div class="row pt-5 px-5 pb-2">
            <div class="col-3">
                <strong>Sentiments</strong>
            </div>
            <div class="col-9">
                <strong>Tweet Content</strong>
            </div>
        </div>
        @foreach ($data['tweetContent']['tweetsContent'] as $tweet1)
            <div class="col-12 tweets-container py-3 px-5">

                <div class="row">
                    <div class="col-3 tweetsColor">
                        {{ $tweet1['sentiment'] }}
                    </div>
                    <div class="col-9 tweetsColor">
                        {{ $tweet1['text'] }}
                    </div>
                    </di>
                </div>
            </div>
        @endforeach


        {{-- <div style="width:1200px">
            <h4 class="section-heading">Count of Sensors in Alarm State by Date</h4>
            <div class="line-breaker2" style="margin-bottom:20px"></div>
            <div class="d-flex">
                <div style="width: 60%">
                    <canvas id="myChart"></canvas>
                </div>
                <div style="width: 38%; margin-left:2%; margin-top:20px" class=" d-flex justify-content-center">
                    <div style="width:68%">
                        <canvas id="overviewPie"></canvas>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>

</body>

</html>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js">
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const dataFromPHP = @json($data);

        let positiveTweets = dataFromPHP.overallSentiments.positiveTweets
        let negativeTweets = dataFromPHP.overallSentiments.negativeTweets
        let neutralTweets = dataFromPHP.overallSentiments.neutralTweets


        const ctx2 = document.getElementById('overviewPie').getContext('2d');
        const overviewPie = new Chart(ctx2, {
            type: 'pie',
            data: {

                datasets: [{
                    backgroundColor: ["rgba(0, 227, 150, 0.2)", "rgba(0, 0, 206, 0.3)",
                        "rgba(206, 0, 0, 0.3)"
                    ],
                    data: [positiveTweets, neutralTweets, negativeTweets],
                    borderWidth: 0
                }],
            },
            options: {
                animation: {
                    duration: 0,
                },
                plugins: {
                    datalabels: {
                        color: '#000',
                        formatter: (value, context) => {
                            let total = context.chart.data.datasets[0].data.reduce((a, b) => a + b,
                                0);
                            let percentage = (value / total * 100).toFixed(2) + '%';
                            return percentage;
                        },
                        font: {
                            weight: 'bold',
                            size: 20
                        },
                        backgroundColor: '#ccc',
                        borderRadius: 3,
                        align: 'center',
                        // anchor: 'center',
                        //align: 'start',
                    }
                },
            },
            plugins: [ChartDataLabels] // Add this line to include the datalabels plugin
        });





        const labels = Object.keys(dataFromPHP.sentimentsTimeline).map(key => {
            const month = dataFromPHP.sentimentsTimeline[key];
            return `${month.year}-${month.month}`;
        });

        const positiveData = Object.keys(dataFromPHP.sentimentsTimeline).map(key => dataFromPHP
            .sentimentsTimeline[key]
            .sentiments.POSITIVE);
        const neutralData = Object.keys(dataFromPHP.sentimentsTimeline).map(key => dataFromPHP
            .sentimentsTimeline[key].sentiments.NEUTRAL);
        const negativeData = Object.keys(dataFromPHP.sentimentsTimeline).map(key => dataFromPHP
            .sentimentsTimeline[key].sentiments
            .NEGATIVE);


        const ctx3 = document.getElementById('timelineChart').getContext('2d');

        const timelineChart = new Chart(ctx3, {
            type: 'bar',
            data: {
                datasets: [{
                        label: 'Positive',
                        fill: true,
                        backgroundColor: ["rgba(0, 227, 150, 0.2)"],

                        data: positiveData,
                        order: 2
                    },
                    {
                        label: 'Neutral',
                        data: neutralData,
                        type: 'bar',
                        borderColor: ["rgba(0, 0, 206, 0.2)"],
                        backgroundColor: ["rgba(0, 0, 206, 0.2)"],
                        order: 1
                    },
                    {
                        label: 'Negative',
                        data: negativeData,
                        type: 'bar',

                        fill: true,
                        backgroundColor: "rgba(206, 0, 0, 0.2)",


                    }
                ],


                labels: labels,
            },
            options: {
                animation: {
                    duration: 0,
                },

                plugins: {
                    datalabels: {

                        color: '#000',
                        formatter: (value, context) => {
                            let total = context.chart.data.datasets[0].data.reduce((a, b) => a + b,
                                0);
                            let percentage = (value / total * 100).toFixed(2) + '%';
                            return percentage;
                        },

                        backgroundColor: '#ccc',
                        borderRadius: 3,
                        align: 'center',
                    },

                },
            },
        });

        const labelsLocation = Object.keys(dataFromPHP.tweetsByLocation);
        const dataLocation = Object.values(dataFromPHP.tweetsByLocation);

        const ctx4 = document.getElementById('locationDoughnut').getContext('2d');
        const locationDoughnut = new Chart(ctx4, {
            type: 'doughnut',
            data: {
                labels: labelsLocation,
                datasets: [{

                    data: dataLocation,
                }],
            },
            options: {
                animation: {
                    duration: 0,
                },

                plugins: {
                    datalabels: {
                        display: true,

                        color: '#000',
                    },

                },
            },
            plugins: [ChartDataLabels] // Add this line to include the datalabels plugin
        });


    });
</script>
