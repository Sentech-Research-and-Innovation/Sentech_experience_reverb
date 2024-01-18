<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <!--This only works pdf -->
    <link rel="stylesheet" href="{{ public_path('reportscss/theme.css') }}">
    <link rel="stylesheet" href="{{ public_path('reportscss/font-awesome.css') }}">

    <!--This only works on viewing blade-->
    <link rel="stylesheet" href="{{ asset('reportscss/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('reportscss/font-awesome.css') }}">


    <style>
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
        }

        .section-heading {
            font-size: 18px
        }

        .kpi-value {
            font-weight: 700;
            font-size: 35px;
            color: #020440 !important
        }

        .kpi-value-date {
            font-weight: 700;
            font-size: 17px;
            color: #020440 !important
        }

        .kpi-value-sites {
            font-weight: 700;
            font-size: 11px;
            color: #020440 !important
        }



        .kpi-name-label {
            color: #2b6360;
            font-weight: bold;
            font-size: 12px
        }

        .img-width {
            width: 120px;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="col-12 px-0 mx-0">
        <div class="col-12  mb-3 mr-4">
            <div class="reports">
                <div class="row px-4">
                    <div class="col-6 pb-2 px-0">
                        <h2>Engage 360 Platform {{ $data['date'][0] }}</h2>
                        {{-- <div class="col-12 py-1">
                        <i class="fas fa-envelope"></i> frfrfrfrf
                    </div> --}}
                        <h6 class="pt-2 kpi-name">Predictive maintenance report</h6>
                    </div>
                    <div class="col-6 pb-2 px-0 text-right">

                        <img src="{{ public_path('sentech-logo2.png') }}"
                            class="
                            img-width img-fluid" />
                    </div>
                </div>

                <div class="line-breaker"></div>

            </div>
            <div class="col-12 py-4 overview rounded my-4">
                <h4 class="section-heading">Overview KPIs and metrics</h4>
                <div class="line-breaker2"></div>
                <div class="col-12 pt-4 px-0 mx-0">
                    <div class="row">
                        <div class="col-4">
                            <div class="col-12 kpi-bg rounded py-3">
                                <span class="kpi-name-label">SITE NAMES</span>
                                <div class="row pt-1">
                                    <div class="col-6">
                                        <i class="fas fa-sitemap"
                                            style="
                                                    color: #010440;
                                                    font-size: 50px;
                                                    background-color: #fff;
                                                "></i>
                                    </div>
                                    <div class="col-6 kpi-value-sites">
                                        JOHANNESBURG ( 20 ) <br />
                                        CAPE TOWN ( 20 ) <br />
                                        EAST LONDON ( 20 )
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="col-12 kpi-bg rounded py-3">
                                <span class="kpi-name-label">MONITORED SENSORS</sapn>
                                    <div class="row pt-1">
                                        <div class="col-6">

                                            <i class="fas fa-cubes"
                                                style="
                                                    color: #010440;
                                                    font-size: 50px;
                                                    background-color: #fff;
                                                "></i>
                                        </div>
                                        <div class="col-6 kpi-value">38</div>
                                    </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="col-12 kpi-bg rounded py-3">
                                <span class="kpi-name-label">DATE BETWEEN</span>
                                <div class="row pt-1">
                                    <div class="col-6">
                                        <i class="fas fa-calendar-alt"
                                            style="
                                                    color: #010440;
                                                    font-size: 45px;
                                                    background-color: #fff;
                                                "></i>
                                    </div>
                                    <div class="col-6 kpi-value-date">
                                        2023-02-01 <br />
                                        2023-02-10
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- new line -->
                <div class="col-12 pt-4 px-0 mx-0">
                    <div class="row">
                        <div class="col-4">
                            <div class="col-12 kpi-bg rounded py-3">
                                <span class="kpi-name-label">ALARM FLAGS</span>
                                <div class="row pt-1">
                                    <div class="col-6">
                                        <i class="fas fa-bell"
                                            style="
                                                    color: #010440;
                                                    font-size: 45px;
                                                    background-color: #fff;
                                                "></i>
                                    </div>
                                    <div class="col-6 kpi-value-sites">
                                        ALARM (30 )<br />
                                        NORMAL ( 40 ) <br />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="col-12 kpi-bg rounded py-3">
                                <span class="kpi-name-label">CLASSIFICATION</span>
                                <div class="row pt-1">
                                    <div class="col-6">
                                        <i class="fas fa-trophy"
                                            style="
                                                    color: #010440;
                                                    font-size: 45px;
                                                    background-color: #fff;
                                                "></i>
                                    </div>
                                    <div class="col-6 kpi-value-sites">
                                        PLATINUM ( 5 ) <br />
                                        GOLD ( 20 ) <br />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="col-12 kpi-bg rounded py-3">
                                <span class="kpi-name-label">DEVICE NAMES</span>
                                <div class="row pt-1">
                                    <div class="col-6">
                                        <i class="fas fa-desktop"
                                            style="
                                                    color: #010440;
                                                    font-size: 45px;
                                                    background-color: #fff;
                                                "></i>
                                    </div>
                                    <div class="col-6 kpi-value">96</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 py-4 rounded my-4 px-0">
                <h4 class="section-heading">Count of Sensors in Alarm State by Date</h4>
                <div class="line-breaker2"></div>
            </div>
        </div>
    </div>

    </div>
    {{-- <script src="{{ public_path('reportscss/vue.js') }}"></script> --}}

    {{-- <script>
        // Example using the Fetch API to make a GET request to an API
        fetch('/admin/predictive-maintenance/predictions', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    // Add any additional headers if needed
                },
                body: JSON.stringify({
                    searchData: {
                        searchFilter: {
                            siteNames: ["PORT ELIZABETH", "CONSTANTIABERG", "JOHANNESBURG"],
                            date: ["2023-03-01T08:54:00.000Z", "2023-08-26T08:54:00.000Z"]
                        }
                    }
                    // Add any other data you need to send in the request body
                }),
            })
            .then(response => {
                console.log('Raw Response:', response);
            })
            .then(data => {
                // Handle the data received from the API
                console.log(data);

                // You can manipulate the DOM or update your UI based on the API response here
            })
            .catch(error => {
                // Handle errors
                console.error('Error fetching data:', error);
            });
    </script> --}}
</body>

</html>
