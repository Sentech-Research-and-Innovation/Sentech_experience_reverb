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

        body {
            font-family: "Roboto", sans-serif;
            font-optical-sizing: auto;
        }

        .reports {
            color: #144f9f !important;
        }


        .overview {
            background-color: #F4F5F5;
        }

        .kpi-bg {
            background-color: #ffffff;
            min-height: 120px
        }

        .kpi-name {
            color: #666666;
            font-weight: 400 !important;
            font-size: 18px;
        }


        .kpi-value {
            font-weight: 700;
            font-size: 20px;
            line-height: 20px;
            color: #ffffff !important
        }

        .kpi-value-number {
            font-weight: 700;
            font-size: 35px;
            line-height: 35px;
            color: #FFFFFF !important
        }

        .kpi-value-date {
            font-weight: 700;
            font-size: 13px;
            color: #020440 !important
        }

        .kpi-name-label {
            color: #ffffff;
            font-weight: 100;
            font-size: 13px
        }

        .img-width {
            width: 250px;
            margin-top: 10px;
            height: 43px
        }

        h2 {
            font-weight: 700;
            font-size: 30px;
            line-height: 30px
        }

        .site-name-bg {
            background-color: #FFA430;
            min-height: 125px
        }

        .sensors-bg {
            background-color: #8DB03E;
            min-height: 125px
        }

        .date-bg {
            background-color: #2FA4C3;
            min-height: 125px
        }

        .alarm-bg {
            background-color: #D82A1A;
            min-height: 125px
        }

        .class-bg {
            background-color: #5D6B6B;
            min-height: 125px
        }

        .device-bg {
            background-color: #0C49A3;
            min-height: 125px
        }
    </style>

</head>

<body>

    <div class="col-12  mb-3 mx-0 pt-5">
        <div class="reports col-12 px-0">
            <div class="d-flex justify-content-between">
                <div class="col-6 pb-2">
                    <h6 class="py-1 kpi-name">Predictive maintenance report</h6>
                    <h2>Engage 360 Platform</h2>
                </div>
                <div class="col-6 pb-2 d-flex justify-content-end"> <img src="{{ public_path('sentech-logo2.png') }}"
                        class="img-width " />
                </div>
            </div>
        </div>

        <div class="col-12 pt-5 mt-5">
            <h4 class="kpi-name">Overview KPIs and metrics</h4>
        </div>
        <div class="col-12 py-3 overview rounded mb-4">
            <div class="col-12 px-3">

                <div class="col-12 px-0 mx-0">
                    <div class="d-flex justify-content-between">
                        <div class="col-4">
                            <div class="col-12 rounded py-3 px-3 site-name-bg">
                                <span class="kpi-name-label">Site names</span>



                                <div class="col-12 kpi-value">
                                    @foreach ($data['sensorInAlarmBySite'] as $site)
                                        {{ ucwords(strtolower(htmlspecialchars(implode(' ', $site)))) }}
                                        <br />
                                    @endforeach

                                </div>

                            </div>
                        </div>
                        <div class="col-4 px-2">
                            <div class="col-12  rounded sensors-bg py-3 px-3">
                                <span class="kpi-name-label">Monitored sensors</span>


                                <div class="col-12 kpi-value-number">{{ $data['monitoredSensorCount'] }}</div>


                            </div>
                        </div>
                        <div class="col-4">
                            <div class="col-12 date-bg rounded py-3 px-3">
                                <span class="kpi-name-label">Date between</span>


                                <div class="col-12 kpi-value">
                                    {{ $data['start_date'] }}<br />
                                    {{ $data['end_date'] }}
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                <!-- new line -->
                <div class="col-12 pt-2 px-0 mx-0">
                    <div class="d-flex justify-content-between">
                        <div class="col-4">
                            <div class="col-12 kpi-bg rounded alarm-bg py-3 px-3">
                                <span class="kpi-name-label">Alarm flags</span>

                                <div class="col-12 kpi-value">
                                    @foreach ($data['alarmStatusCount'] as $statusCount)
                                        {{ htmlspecialchars(implode(' ', $statusCount)) }}
                                        <br />
                                    @endforeach
                                </div>
                            </div>

                        </div>
                        <div class="col-4 px-2">
                            <div class="col-12 kpi-bg rounded class-bg py-3 px-3">
                                <span class="kpi-name-label">Classification</span>

                                <div class="col-12 kpi-value">
                                    @foreach ($data['classSatusCount'] as $classSatusCount)
                                        {{ htmlspecialchars(implode(' ', $classSatusCount)) }}
                                        <br />
                                    @endforeach
                                </div>
                            </div>

                        </div>
                        <div class="col-4">
                            <div class="col-12 kpi-bg rounded device-bg py-3 px-3">
                                <span class="kpi-name-label">Devices</span>

                                <div class="col-6 kpi-value-number">{{ $data['deviceNamesCount'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-12 pt-5">
            <h4 class="kpi-name">Count of sensors in alarm state by date</h4>
        </div>

        <div class="col-12 py-3 overview rounded mb-4">

            <div class="col-12 px-4">
                <div class="d-flex">
                    <div style="width: 65%">
                        <canvas id="myChart"></canvas>
                    </div>
                    <div style="width: 33%; margin-left:2%; margin-top:30px" class=" d-flex justify-content-end">
                        <div style="width:80%">
                            <canvas id="myChart2"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


</body>

</html>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js">
</script>


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
                    backgroundColor: ["#5D6B6B", "#D82A1A", "#FFA430"],
                    data: [normalCount, alarmCount, preAlarmCount],
                }, ],
            },
            options: {
                animation: {
                    duration: 0,
                },
                plugins: {

                    legend: {
                        position: 'bottom',
                        align: "center",

                        labels: {
                            pointStyle: "circle",

                            usePointStyle: true,

                            padding: 13,
                            font: {
                                size: 11
                            }
                        }
                    },

                    datalabels: {
                        color: '#ffff',
                        display: false,

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
                        // anchor: 'right',
                        //align: 'start',
                    }
                },

            },
            plugins: [ChartDataLabels]

        });





        // myChart.canvas.style.width = '1400px';
        // myChart.canvas.style.width = '100%';

    });
</script>
