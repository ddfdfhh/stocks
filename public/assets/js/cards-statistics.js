"use strict";
!(function () {
    let o, r, e;
    e = isDarkStyle
        ? ((o = config.colors_dark.axisColor),
          (r = config.colors_dark.borderColor),
          "dark")
        : ((o = config.colors.axisColor),
          (r = config.colors.borderColor),
          "light");
    var s = document.querySelector("#conversationChart"),
        t = {
            series: [{ data: [50, 100, 0, 60, 20, 30] }],
            chart: {
                height: 40,
                type: "line",
                zoom: { enabled: !1 },
                sparkline: { enabled: !0 },
                toolbar: { show: !1 },
            },
            dataLabels: { enabled: !1 },
            tooltip: { enabled: !1 },
            stroke: { curve: "smooth", width: 3 },
            grid: {
                show: !1,
                padding: { top: 5, left: 10, right: 10, bottom: 5 },
            },
            colors: [config.colors.primary],
            fill: {
                type: "gradient",
                gradient: {
                    shade: e,
                    type: "horizontal",
                    gradientToColors: void 0,
                    opacityFrom: 0,
                    opacityTo: 0.9,
                    stops: [0, 30, 70, 100],
                },
            },
            xaxis: {
                labels: { show: !1 },
                axisBorder: { show: !1 },
                axisTicks: { show: !1 },
            },
            yaxis: { labels: { show: !1 } },
        };
    if (null !== s) {
        const i = new ApexCharts(s, t);
        i.render();
    }
    (s = document.querySelector("#incomeChart")),
        (t = {
            series: [{ data: [40, 70, 38, 90, 40, 65] }],
            chart: {
                height: 40,
                type: "line",
                zoom: { enabled: !1 },
                sparkline: { enabled: !0 },
                toolbar: { show: !1 },
            },
            dataLabels: { enabled: !1 },
            tooltip: { enabled: !1 },
            stroke: { curve: "smooth", width: 3 },
            grid: {
                show: !1,
                padding: { top: 10, left: 10, right: 10, bottom: 0 },
            },
            colors: [config.colors.warning],
            fill: {
                type: "gradient",
                gradient: {
                    shade: e,
                    type: "horizontal",
                    gradientToColors: void 0,
                    opacityFrom: 0,
                    opacityTo: 0.9,
                    stops: [0, 30, 70, 100],
                },
            },
            xaxis: {
                labels: { show: !1 },
                axisBorder: { show: !1 },
                axisTicks: { show: !1 },
            },
            yaxis: { labels: { show: !1 } },
        });
    if (null !== s) {
        const l = new ApexCharts(s, t);
        l.render();
    }
    (s = document.querySelector("#profitChart")),
        (t = {
            series: [{ data: [50, 80, 10, 82, 52, 95] }],
            chart: {
                height: 40,
                type: "line",
                zoom: { enabled: !1 },
                sparkline: { enabled: !0 },
                toolbar: { show: !1 },
            },
            dataLabels: { enabled: !1 },
            tooltip: { enabled: !1 },
            stroke: { curve: "smooth", width: 3 },
            grid: {
                show: !1,
                padding: { top: 10, left: 10, right: 10, bottom: 0 },
            },
            colors: [config.colors.success],
            fill: {
                type: "gradient",
                gradient: {
                    shade: e,
                    type: "horizontal",
                    gradientToColors: void 0,
                    opacityFrom: 0,
                    opacityTo: 0.9,
                    stops: [0, 30, 70, 100],
                },
            },
            xaxis: {
                labels: { show: !1 },
                axisBorder: { show: !1 },
                axisTicks: { show: !1 },
            },
            yaxis: { labels: { show: !1 } },
        });
    if (null !== s) {
        const n = new ApexCharts(s, t);
        n.render();
    }
    (s = document.querySelector("#expensesLineChart")),
        (t = {
            series: [{ data: [80, 40, 85, 5, 80, 35] }],
            chart: {
                height: 40,
                type: "line",
                zoom: { enabled: !1 },
                sparkline: { enabled: !0 },
                toolbar: { show: !1 },
            },
            dataLabels: { enabled: !1 },
            tooltip: { enabled: !1 },
            stroke: { curve: "smooth", width: 3 },
            grid: {
                show: !1,
                padding: { top: 5, left: 10, right: 10, bottom: 5 },
            },
            colors: [config.colors.danger],
            fill: {
                type: "gradient",
                gradient: {
                    shade: e,
                    type: "horizontal",
                    gradientToColors: void 0,
                    opacityFrom: 0,
                    opacityTo: 0.9,
                    stops: [0, 30, 70, 100],
                },
            },
            xaxis: {
                labels: { show: !1 },
                axisBorder: { show: !1 },
                axisTicks: { show: !1 },
            },
            yaxis: { labels: { show: !1 } },
        });
    if (null !== s) {
        const c = new ApexCharts(s, t);
        c.render();
    }
    const a = document.querySelectorAll(".chart-report");
    a &&
        a.forEach(function (o) {
            var e = config.colors[o.dataset.color],
                s = o.dataset.series,
                e =
                    ((e = e),
                    (s = s),
                    {
                        chart: { height: 55, width: 40, type: "radialBar" },
                        plotOptions: {
                            radialBar: {
                                hollow: { size: "32%" },
                                dataLabels: { show: !1 },
                                track: { background: r },
                            },
                        },
                        colors: [e],
                        grid: {
                            padding: {
                                top: -10,
                                bottom: -10,
                                left: -5,
                                right: 0,
                            },
                        },
                        series: [s],
                        labels: ["Progress"],
                    });
            const t = new ApexCharts(o, e);
            t.render();
        });
    (s = document.querySelector("#registrationsBarChart")),
        (t = {
            chart: { height: 70, type: "bar", toolbar: { show: !1 } },
            plotOptions: {
                bar: {
                    barHeight: "80%",
                    columnWidth: "50%",
                    startingShape: "rounded",
                    endingShape: "rounded",
                    borderRadius: 2,
                    distributed: !0,
                },
            },
            tooltip: { enabled: !1 },
            grid: {
                show: !1,
                padding: { top: -20, bottom: -12, left: 0, right: 0 },
            },
            colors: [
                config.colors_label.warning,
                config.colors_label.warning,
                config.colors_label.warning,
                config.colors_label.warning,
                config.colors.warning,
                config.colors_label.warning,
                config.colors_label.warning,
            ],
            dataLabels: { enabled: !1 },
            series: [{ data: [30, 55, 45, 95, 70, 50, 65] }],
            legend: { show: !1 },
            xaxis: {
                categories: ["M", "T", "W", "T", "F", "S", "S"],
                axisBorder: { show: !1 },
                axisTicks: { show: !1 },
                labels: { show: !1 },
            },
            yaxis: { labels: { show: !1 } },
        });
    if (null !== s) {
        const d = new ApexCharts(s, t);
        d.render();
    }
    (s = document.querySelector("#visitsBarChart")),
        (t = {
            chart: { height: 70, type: "bar", toolbar: { show: !1 } },
            plotOptions: {
                bar: {
                    barHeight: "80%",
                    columnWidth: "50%",
                    startingShape: "rounded",
                    endingShape: "rounded",
                    borderRadius: 2,
                    distributed: !0,
                },
            },
            tooltip: { enabled: !1 },
            grid: {
                show: !1,
                padding: { top: -20, bottom: -12, left: 0, right: 0 },
            },
            colors: [
                config.colors_label.success,
                config.colors_label.success,
                config.colors_label.success,
                config.colors_label.success,
                config.colors.success,
                config.colors_label.success,
                config.colors_label.success,
            ],
            dataLabels: { enabled: !1 },
            series: [{ data: [15, 42, 33, 54, 98, 48, 37] }],
            legend: { show: !1 },
            xaxis: {
                categories: ["M", "T", "W", "T", "F", "S", "S"],
                axisBorder: { show: !1 },
                axisTicks: { show: !1 },
                labels: { show: !1 },
            },
            yaxis: { labels: { show: !1 } },
        });
    if (null !== s) {
        const h = new ApexCharts(s, t);
        h.render();
    }
    (s = document.querySelector("#registrationsChart")),
        (t = {
            series: [{ data: [57, 25, 94, 32, 98, 81, 125] }],
            chart: {
                height: 120,
                parentHeightOffset: 0,
                parentWidthOffset: 0,
                type: "line",
                toolbar: { show: !1 },
            },
            dataLabels: { enabled: !1 },
            stroke: { width: 3, curve: "straight" },
            grid: {
                show: !1,
                padding: { top: -30, left: 2, right: 0, bottom: -10 },
            },
            colors: [config.colors.success],
            xaxis: {
                show: !1,
                categories: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
                axisBorder: { show: !0, color: r },
                axisTicks: { show: !0, color: r },
                labels: {
                    show: !0,
                    style: {
                        fontSize: "0.813rem",
                        fontFamily: "IBM Plex Sans",
                        colors: o,
                    },
                },
            },
            yaxis: { labels: { show: !1 } },
        });
    if (null !== s) {
        const g = new ApexCharts(s, t);
        g.render();
    }
    (s = document.querySelector("#expensesChart")),
        (t = {
            series: [{ data: [115, 70, 105, 34, 122, 21, 62] }],
            chart: {
                height: 120,
                parentHeightOffset: 0,
                parentWidthOffset: 0,
                type: "line",
                toolbar: { show: !1 },
            },
            dataLabels: { enabled: !1 },
            stroke: { width: 3, curve: "straight" },
            grid: {
                show: !1,
                padding: { top: -30, left: 2, right: 0, bottom: -10 },
            },
            colors: [config.colors.danger],
            xaxis: {
                show: !1,
                categories: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
                axisBorder: { show: !0, color: r },
                axisTicks: { show: !0, color: r },
                labels: {
                    show: !0,
                    style: {
                        fontSize: "0.813rem",
                        fontFamily: "IBM Plex Sans",
                        colors: o,
                    },
                },
            },
            yaxis: { labels: { show: !1 } },
        });
    if (null !== s) {
        const b = new ApexCharts(s, t);
        b.render();
    }
    (s = document.querySelector("#usersChart")),
        (t = {
            series: [{ data: [58, 27, 141, 60, 98, 31, 165] }],
            chart: {
                height: 120,
                parentHeightOffset: 0,
                parentWidthOffset: 0,
                type: "line",
                toolbar: { show: !1 },
            },
            dataLabels: { enabled: !1 },
            stroke: { width: 3, curve: "straight" },
            grid: {
                show: !1,
                padding: { top: -30, left: 2, right: 0, bottom: -10 },
            },
            colors: [config.colors.primary],
            xaxis: {
                show: !1,
                categories: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
                axisBorder: { show: !0, color: r },
                axisTicks: { show: !0, color: r },
                labels: {
                    show: !0,
                    style: {
                        fontSize: "0.813rem",
                        fontFamily: "IBM Plex Sans",
                        colors: o,
                    },
                },
            },
            yaxis: { labels: { show: !1 } },
        });
    if (null !== s) {
        const p = new ApexCharts(s, t);
        p.render();
    }
})();
