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
                show: true,
                tools: {
                    download: false,
                    zoom: false,
                    zoomin: false,
                    zoomout: false,
                    reset: false,
                },
            },
        },
        colors: ["#ec1c24", "#00c83c", "#118dff"],
        dataLabels: {
            enabled: false,
            name: {
                show: false,
            },
            value: {
                show: false,
            },
        },
        grid: {
            border: true,
            strokeDashArray: 3,
            show: false,
            padding: {
                left: -16,
                right: 0,
            },
            xaxis: {
                lines: {
                    show: true,
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
        stroke: {
            width: [2, 2, 2],
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
