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
                    <h6 class="pt-2 kpi-name">Predictive maintenance report</h6>
                </div>
                <div class="col-6 pb-2 d-flex justify-content-end">
                    <img src="{{ public_path('sentech-logo2.png') }}" class="
                            img-width " />
                </div>
            </div>



        </div>
        <div class="line-breaker col-12"></div>

        <div class="col-12 py-4 overview rounded my-4">
            <div class="col-12 px-4">
                <div class="col-12">
                    <h4 class="section-heading">Overview KPIs and metrics</h4>
                </div>
                <div class="line-breaker2 col-12"></div>


                <div class="col-12 pt-4 px-0 mx-0">
                    <div class="d-flex justify-content-between">
                        <div class="col-4">
                            <div class="col-12 kpi-bg rounded py-3 px-4">
                                <span class="kpi-name-label">SITE NAMES</span>
                                <div class="d-flex justify-content-between">
                                    <div class="col-5 pt-1">
                                        <i class="fas fa-sitemap"
                                            style="
                                                    color: #010440;
                                                    font-size: 50px;
                                                    background-color: #fff;
                                                "></i>
                                    </div>

                                    <div class="col-7 kpi-value-sites">
                                        @foreach ($data['sensorInAlarmBySite'] as $site)
                                            {{ htmlspecialchars(implode(', ', $site)) }}
                                            <br />
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 px-2">
                            <div class="col-12 kpi-bg rounded py-3 px-4">
                                <span class="kpi-name-label">MONITORED SENSORS</sapn>
                                    <div class="d-flex justify-content-between">
                                        <div class="col-6 pt-1">

                                            <i class="fas fa-cubes"
                                                style="
                                                    color: #010440;
                                                    font-size: 45px;
                                                    background-color: #fff;
                                                "></i>
                                        </div>
                                        <div class="col-6 kpi-value">{{ $data['monitoredSensorCount'] }}</div>
                                    </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="col-12 kpi-bg rounded py-3 px-4">
                                <span class="kpi-name-label">DATE BETWEEN</span>
                                <div class="d-flex justify-content-between">
                                    <div class="col-6 pt-1">
                                        <i class="fas fa-calendar-alt"
                                            style="
                                                    color: #010440;
                                                    font-size: 45px;
                                                    background-color: #fff;
                                                "></i>
                                    </div>
                                    <div class="col-6 kpi-value-date">
                                        {{ $data['start_date'] }}<br />
                                        {{ $data['end_date'] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- new line -->
                <div class="col-12 pt-2 px-0 mx-0">
                    <div class="d-flex justify-content-between">
                        <div class="col-4">
                            <div class="col-12 kpi-bg rounded py-3 px-4">
                                <span class="kpi-name-label">ALARM FLAGS</span>
                                <div class="d-flex justify-content-between">
                                    <div class="col-6 pt-1">
                                        <i class="fas fa-bell"
                                            style="
                                                    color: #010440;
                                                    font-size: 45px;
                                                    background-color: #fff;
                                                "></i>
                                    </div>
                                    <div class="col-6 kpi-value-sites">
                                        @foreach ($data['alarmStatusCount'] as $statusCount)
                                            {{ htmlspecialchars(implode(', ', $statusCount)) }}
                                            <br />
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 px-2">
                            <div class="col-12 kpi-bg rounded py-3 px-4">
                                <span class="kpi-name-label">CLASSIFICATION</span>
                                <div class="d-flex justify-content-between">
                                    <div class="col-6 pt-1">
                                        <i class="fas fa-trophy"
                                            style="
                                                    color: #010440;
                                                    font-size: 45px;
                                                    background-color: #fff;
                                                "></i>
                                    </div>
                                    <div class="col-6 kpi-value-sites">
                                        @foreach ($data['classSatusCount'] as $classSatusCount)
                                            {{ htmlspecialchars(implode(', ', $classSatusCount)) }}
                                            <br />
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="col-12 kpi-bg rounded py-3 px-4">
                                <span class="kpi-name-label">DEVICES</span>
                                <div class="d-flex justify-content-between">
                                    <div class="col-6 pt-1">
                                        <i class="fas fa-desktop"
                                            style="
                                                    color: #010440;
                                                    font-size: 30px;
                                                    background-color: #fff;
                                                "></i>
                                    </div>
                                    <div class="col-6 kpi-value">{{ $data['deviceNamesCount'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 px-4">
            <h4 class="section-heading">Count of Sensors in Alarm State by Date</h4>
            <div class="line-breaker2" style="margin-bottom:20px"></div>
            <div class="d-flex">
                <div style="width: 65%">
                    <canvas id="myChart"></canvas>
                </div>
                <div style="width: 33%; margin-left:2%; margin-top:2px" class=" d-flex justify-content-center">
                    <div style="width:80%">
                        <canvas id="myChart2"></canvas>
                    </div>
                </div>
            </div>
        </div>


        {{-- <div style="width:1200px">
            <h4 class="section-heading">Count of Sensors in Alarm State by Date</h4>
            <div class="line-breaker2" style="margin-bottom:20px"></div>
            <div class="d-flex">
                <div style="width: 60%">
                    <canvas id="myChart"></canvas>
                </div>
                <div style="width: 38%; margin-left:2%; margin-top:20px" class=" d-flex justify-content-center">
                    <div style="width:68%">
                        <canvas id="myChart2"></canvas>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>


</body>

</html>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dataFromPHP = @json($data);

        const labels = dataFromPHP.alarmSateByDate.labels;
        const chartData = dataFromPHP.alarmSateByDate.series;
        const alarmSatatus = dataFromPHP.alarmStatusCount;

        let normalCount = 0;
        let alarmCount = 0;
        let preAlarmCount = 0;

        alarmSatatus.forEach(item => {
            if (item.alarmStatus === "Normal") {
                normalCount = item.count;
            } else if (item.alarmStatus === "Alarm") {
                alarmCount = item.count;
            }
        });

        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Showing top 10 by date',
                    data: chartData,
                    backgroundColor: [
                        'rgba(20, 79, 159, 0.85)'

                    ],
                }]
            },
            options: {
                animation: {
                    duration: 0,
                },


                scales: {
                    y: {
                        ticks: {
                            color: '#010440',

                        },
                        beginAtZero: true
                    },
                    x: {
                        ticks: {
                            color: '#010440',

                        },
                        beginAtZero: true
                    }

                }
            }
        });


        const ctx2 = document.getElementById('myChart2').getContext('2d');
        const myChart2 = new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: ['Normal', 'Alarm', 'Pre-Alarm'],
                datasets: [{
                    backgroundColor: ["#41e809", "#e80909", "#ffc107"],
                    data: [normalCount, alarmCount, preAlarmCount],
                }, ],
            },
            options: {
                animation: {
                    duration: 0,
                },
                plugins: {
                    datalabels: {
                        display: true,


                    },

                },
            },

        });





        // myChart.canvas.style.width = '1400px';
        // myChart.canvas.style.width = '100%';

    });
</script>
