export default {
    chartOptions: {
        legend: {
            show: true,
        },
        chart: {
            animations: {
                speed: 500,
            },
            offsetX: 0,
            toolbar: {
                show: false,
                tools: {
                    download: false,
                    zoom: false,
                    zoomin: false,
                    zoomout: false,
                    reset: false,
                },
            },
        },
        colors: ["#ff4560", "#00e396", "#775dd0"],
        dataLabels: {
            enabled: false,
            name: {
                show: false,
            },
            value: {
                show: false,
            },
        },
        stroke: {
            curve: "smooth",
        },

        // fill: {
        //     type: "gradient",
        //     gradient: {
        //         opacityFrom: 0.6,
        //         opacityTo: 0.8,
        //     },
        // },
        grid: {
            border: false,
            strokeDashArray: 3,
            show: true,

            xaxis: {
                lines: {
                    show: false,
                },
            },
            yaxis: {
                lines: {
                    show: true,
                },
            },
            row: {
                colors: undefined,
                opacity: 0.1,
            },
            column: {
                colors: undefined,
                opacity: 0.1,
            },
        },

        xaxis: {
            axisTicks: {
                show: true,
            },
            labels: {
                show: true,
            },
        },

        yaxis: {
            labels: {
                show: true,
            },
        },
        tooltip: {
            y: [
                {
                    formatter: function (e) {
                        return void 0 !== e ? e.toFixed(0) : e;
                    },
                },
                {
                    formatter: function (e) {
                        return void 0 !== e ? e.toFixed(0) : e;
                    },
                },
                {
                    formatter: function (e) {
                        return void 0 !== e ? e.toFixed(0) : e;
                    },
                },
            ],
        },

        markers: {
            hover: {
                sizeOffset: 5,
            },
        },
    },
};
