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
        href="{{ asset('https://fonts.googleapis.com/css2?family=Roboto+Serif:ital,opsz,wght@0,8..144,100;0,8..144,600;0,8..144,800;1,8..144,500&display=swap') }}"
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

        body {
            font-family: "Roboto", sans-serif;
            font-optical-sizing: auto;
        }

        .header,
        .header-space,
        .footer,
        .footer-space {
            height: 100px;
        }

        .header {
            position: fixed;
            top: 0;
        }

        .footer {
            position: fixed;
            bottom: 0;
        }

        .reports {
            color: #144f9f !important;
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
            background-color: #F4F5F5;
        }

        .kpi-bg {
            background-color: #ffffff;
            min-height: 90px
        }


        .kpi-bg-total {
            background-color: #FFA430;
            min-height: 90px
        }



        .kpi-bg-positive {
            background-color: #8DB03E;
            min-height: 90px
        }




        .kpi-bg-neutral {
            background-color: #2FA4C3;
            min-height: 90px
        }



        .kpi-bg-negative {
            background-color: #D82A1A;
            min-height: 90px
        }


        .rounded {
            border-radius: 6px !important
        }

        .kpi-name {
            color: #666666;
            font-weight: 400 !important;
            font-size: 15px;
        }

        .tweet-text {
            color: #666666;
            font-weight: 300 !important;
            font-size: 14px;
        }

        .section-heading {
            font-family: 'Roboto Serif', serif !important;
            font-weight: 600 !important;
            font-size: 18px;

        }

        .kpi-value {
            font-weight: 700;
            font-size: 30px;
            line-height: 30px;
            color: #ffff !important
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
            width: 250px;
            margin-top: 10px;
            height: 43px
        }

        h2 {
            font-weight: 700;
            font-size: 35px;
            line-height: 15px
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

            border-bottom: 1px solid #000000;
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


    <div class="col-12  mb-4 mx-0 px-0">
        <div class="reports col-12 px-0">
            <div class="d-flex justify-content-between">
                <div class="col-8 pb-2">
                    <h6 class="pb-0 kpi-name">Sentiment analysis report</h6>
                    <h2>Engage 360 Platform</h2>
                    {{-- <div class="col-12 py-1">
                        <i class="fas fa-envelope"></i> frfrfrfrf
                    </div> --}}

                </div>
                <div class="col-4 pt-0 d-flex justify-content-end">
                    <img src="{{ public_path('sentech-logo2.png') }}" class="
                            img-width " />
                </div>
            </div>



        </div>

        <div class="col-12 mt-5 pt-5 mb-3">
            <h4 class=" kpi-name">Overview KPIs and metrics</h4>
        </div>
        <div class="col-12 py-3 overview px-0 rounded px-2">
            <div class="col-12 px-0">




                <div class="d-flex justify-content-between">

                    <div class="col-4">


                        <div class="col-12 mb-2 px-2">
                            <div class="col-12 kpi-bg-total rounded py-4 px-3">
                                <div class="col-12 text-stat pt-0 " style="color: #ffff;font-size: 14px;">
                                    Total Tweets
                                </div>
                                <div class="col-12 text-start kpi-value pt-0">
                                    {{ $data['overallSentiments']['totalTweets'] }}</div>

                            </div>
                        </div>
                        <div class="col-12  mb-2 px-2">
                            <div class="col-12 kpi-bg-positive rounded py-4 px-3">
                                <div class="col-12 text-start pt-0 " style="color: #ffff;font-size: 14px;">
                                    Positive Tweets
                                </div>
                                <div class="col-12 text-start kpi-value pt-0">
                                    {{ $data['overallSentiments']['positiveTweets'] }}</div>

                            </div>
                        </div>

                        <div class="col-12 mb-2 px-2">
                            <div class="col-12 kpi-bg-neutral rounded py-4 px-3">
                                <div class="col-12 text-start pt-0 " style="color: #ffff;font-size: 14px;">
                                    Neutral Tweets
                                </div>
                                <div class="col-12 text-start kpi-value pt-0">

                                    {{ $data['overallSentiments']['neutralTweets'] }}</div>

                            </div>
                        </div>

                        <div class="col-12 mb-0 px-2">
                            <div class="col-12 kpi-bg-negative rounded py-4 px-3">
                                <div class="col-12 text-start pt-0 " style="color: #ffff;font-size: 14px;">
                                    Negative Tweets
                                </div>
                                <div class="col-12 text-start kpi-value pt-0">
                                    {{ $data['overallSentiments']['negativeTweets'] }}</div>

                            </div>
                        </div>
                    </div>



                    <div class="col-7 pt-3">
                        <div class="col-12 px-4">
                            <div style="width:90%">
                                <canvas id="overviewPie"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="col-12 pt-5">
        <h4 class=" kpi-name">Overview</h4>
    </div>

    <div class="col-12 py-3 overview px-0 rounded px-2">

        <div style="width:100%">
            <canvas id="timelineChart"></canvas>
        </div>

    </div>

    <div class="col-12 pt-4 pb-2">
        <h4 class=" kpi-name">Location metrics</h4>
    </div>
    <div class="col-12 py-0 overview px-0 rounded px-2">
        <div style="width: 90%; margin-left:auto; margin-right:auto">
            <canvas id="locationDoughnut"></canvas>
        </div>

    </div>

    <div class="row pt-5 pb-2 tweets-container">

        <div class="col-12 pb-2">
            <h4 class=" kpi-name">Sentiment overview</h4>
        </div>
        <div class="col-9 tweet-text">
            <strong>Tweet Content</strong>
        </div>
        <div class="col-3 tweet-text">
            <strong>Sentiment status</strong>
        </div>
    </div>
    @foreach ($data['tweetContent']['tweetsContent'] as $tweet1)
        <div class="col-12 tweets-container py-3">

            <div class="row">
                <div class="col-9 tweet-text">
                    <strong> {{ $tweet1['text'] }} </strong>
                </div>
                <div class="col-3 tweet-text">
                    <strong> {{ $tweet1['sentiment'] }} </strong>
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
                    backgroundColor: ["#8DB03E", "#2FA4C3",
                        "#D82A1A"
                    ],
                    data: [positiveTweets, neutralTweets, negativeTweets],
                    borderWidth: 1
                }],
            },
            options: {
                animation: {
                    duration: 0,
                },
                plugins: {
                    datalabels: {
                        color: '#ffff',


                        formatter: (value, context) => {
                            let total = context.chart.data.datasets[0].data.reduce((a, b) => a + b,
                                0);
                            let percentage = (value / total * 100).toFixed(2) + '%';
                            return percentage;
                        },
                        font: {
                            weight: 'bold',
                            size: 13
                        },
                        backgroundColor: '',
                        borderRadius: 3,
                        align: 'center',
                        // anchor: 'center',
                        //align: 'start',
                    }
                },
            },
            plugins: [ChartDataLabels]
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
                        backgroundColor: ["#8DB03E"],

                        data: positiveData,
                        order: 2
                    },
                    {
                        label: 'Neutral',
                        data: neutralData,
                        type: 'bar',
                        borderColor: ["#2FA4C3"],
                        backgroundColor: ["#2FA4C3"],
                        order: 1
                    },
                    {
                        label: 'Negative',
                        data: negativeData,
                        type: 'bar',

                        fill: true,
                        backgroundColor: "#D82A1A",


                    }
                ],


                labels: labels,
            },
            options: {
                animation: {
                    duration: 0,
                },

                plugins: {
                    legend: {
                        display: false
                    },
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
                    legend: {
                        position: 'right',
                        labels: {
                            pointStyle: "circle",
                            usePointStyle: true,

                            padding: 13,
                            font: {
                                size: 14
                            }
                        }
                    },
                    datalabels: {
                        display: true,

                        color: '#000',
                    },

                },
            },
            plugins: [ChartDataLabels]
        });

    });
</script>
