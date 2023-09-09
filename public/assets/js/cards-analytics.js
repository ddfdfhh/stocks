"use strict";
!(function () {
    let o, e, r, a, t;
    t = isDarkStyle
        ? ((o = config.colors_dark.cardColor),
          (e = config.colors_dark.headingColor),
          (r = config.colors_dark.axisColor),
          (a = config.colors_dark.borderColor),
          "dark")
        : ((o = config.colors.white),
          (e = config.colors.headingColor),
          (r = config.colors.axisColor),
          (a = config.colors.borderColor),
          "light");
    const s = document.querySelectorAll(".chart-report");
    s &&
        s.forEach(function (o) {
            var e = config.colors[o.dataset.color],
                r = o.dataset.series,
                e =
                    ((e = e),
                    (r = r),
                    {
                        chart: { height: 50, width: 50, type: "radialBar" },
                        plotOptions: {
                            radialBar: {
                                hollow: { size: "25%" },
                                dataLabels: { show: !1 },
                                track: { background: a },
                            },
                        },
                        stroke: { lineCap: "round" },
                        colors: [e],
                        grid: {
                            padding: {
                                top: -8,
                                bottom: -10,
                                left: -5,
                                right: 0,
                            },
                        },
                        series: [r],
                        labels: ["Progress"],
                    });
            const t = new ApexCharts(o, e);
            t.render();
        });
    var i = document.querySelector("#analyticsBarChart"),
        n = {
            chart: { height: 260, type: "bar", toolbar: { show: !1 } },
            plotOptions: {
                bar: {
                    horizontal: !1,
                    columnWidth: "20%",
                    borderRadius: 3,
                    startingShape: "rounded",
                },
            },
            dataLabels: { enabled: !1 },
            colors: [config.colors.primary, config.colors_label.primary],
            series: [
                { name: "2020", data: [8, 9, 15, 20, 14, 22, 29, 27, 13] },
                { name: "2019", data: [5, 7, 12, 17, 9, 17, 26, 21, 10] },
            ],
            grid: { borderColor: a },
            xaxis: {
                categories: [
                    "Jan",
                    "Feb",
                    "Mar",
                    "Apr",
                    "May",
                    "Jun",
                    "Jul",
                    "Aug",
                    "Sep",
                ],
                axisBorder: { show: !1 },
                axisTicks: { show: !1 },
                labels: { style: { colors: r } },
            },
            yaxis: {
                min: 0,
                max: 30,
                tickAmount: 3,
                labels: { style: { colors: r } },
            },
            legend: { show: !1 },
            tooltip: {
                y: {
                    formatter: function (o) {
                        return "$ " + o + " thousands";
                    },
                },
            },
        };
    if (null !== i) {
        const l = new ApexCharts(i, n);
        l.render();
    }
    (i = document.querySelector("#referralLineChart")),
        (n = {
            series: [{ data: [0, 150, 25, 100, 15, 149] }],
            chart: {
                height: 100,
                parentHeightOffset: 0,
                parentWidthOffset: 0,
                type: "line",
                toolbar: { show: !1 },
            },
            markers: {
                size: 6,
                colors: "transparent",
                strokeColors: "transparent",
                strokeWidth: 4,
                discrete: [
                    {
                        fillColor: o,
                        seriesIndex: 0,
                        dataPointIndex: 5,
                        strokeColor: config.colors.success,
                        strokeWidth: 4,
                        size: 6,
                        radius: 2,
                    },
                ],
                hover: { size: 7 },
            },
            dataLabels: { enabled: !1 },
            stroke: { width: 4, curve: "smooth" },
            grid: { show: !1, padding: { top: -25, bottom: -20 } },
            colors: [config.colors.success],
            xaxis: {
                show: !1,
                axisBorder: { show: !1 },
                axisTicks: { show: !1 },
                labels: { show: !1 },
            },
            yaxis: { labels: { show: !1 } },
        });
    if (null !== i) {
        const c = new ApexCharts(i, n);
        c.render();
    }
    (i = document.querySelector("#conversionBarchart")),
        (n = {
            chart: {
                height: 100,
                stacked: !0,
                type: "bar",
                toolbar: { show: !1 },
                sparkline: { enabled: !0 },
            },
            plotOptions: {
                bar: {
                    columnWidth: "25%",
                    borderRadius: 2,
                    startingShape: "rounded",
                },
                distributed: !0,
            },
            colors: [config.colors.primary, config.colors.warning],
            series: [
                {
                    name: "New Clients",
                    data: [
                        75, 150, 225, 200, 35, 50, 150, 180, 50, 150, 240, 140,
                        75, 35, 60, 120,
                    ],
                },
                {
                    name: "Retained Clients",
                    data: [
                        -100, -55, -40, -120, -70, -40, -60, -50, -70, -30, -60,
                        -40, -50, -70, -40, -50,
                    ],
                },
            ],
            grid: { show: !1, padding: { top: 0, bottom: -10 } },
            legend: { show: !1 },
            dataLabels: { enabled: !1 },
            tooltip: { x: { show: !1 } },
        });
    if (null !== i) {
        const d = new ApexCharts(i, n);
        d.render();
    }
    (i = document.querySelector("#impressionDonutChart")),
        (n = {
            chart: { height: 225, fontFamily: "IBM Plex Sans", type: "donut" },
            dataLabels: { enabled: !1 },
            series: [80, 30, 60],
            labels: ["Social", "Email", "Search"],
            stroke: { width: 0, lineCap: "round" },
            colors: [
                config.colors.primary,
                config.colors.info,
                config.colors.warning,
            ],
            plotOptions: {
                pie: {
                    donut: {
                        size: "90%",
                        labels: {
                            show: !0,
                            name: { fontSize: "0.938rem", offsetY: 20 },
                            value: {
                                show: !0,
                                fontSize: "1.625rem",
                                fontFamily: "Rubik",
                                fontWeight: "500",
                                color: e,
                                offsetY: -20,
                                formatter: function (o) {
                                    return o;
                                },
                            },
                            total: {
                                show: !0,
                                label: "Impression",
                                color: config.colors.secondary,
                                formatter: function (o) {
                                    return o.globals.seriesTotals.reduce(
                                        function (o, e) {
                                            return o + e;
                                        },
                                        0
                                    );
                                },
                            },
                        },
                    },
                },
            },
            legend: {
                show: !0,
                position: "bottom",
                horizontalAlign: "center",
                labels: { colors: r, useSeriesColors: !1 },
                markers: { width: 10, height: 10, offsetX: -3 },
            },
        });
    if (null !== i) {
        const h = new ApexCharts(i, n);
        h.render();
    }
    (i = document.querySelector("#growthRadialChart")),
        (n = {
            chart: {
                height: 220,
                fontFamily: "IBM Plex Sans",
                type: "radialBar",
                sparkline: { show: !0 },
            },
            grid: { show: !1, padding: { top: -25 } },
            plotOptions: {
                radialBar: {
                    size: 100,
                    startAngle: -135,
                    endAngle: 135,
                    offsetY: 10,
                    hollow: { size: "55%" },
                    track: { strokeWidth: "50%", background: o },
                    dataLabels: {
                        value: {
                            offsetY: -15,
                            color: e,
                            fontFamily: "Rubik",
                            fontWeight: 500,
                            fontSize: "26px",
                        },
                        name: {
                            fontSize: "15px",
                            color: config.colors.secondary,
                            offsetY: 24,
                        },
                    },
                },
            },
            colors: [config.colors.danger],
            fill: {
                type: "gradient",
                gradient: {
                    shade: "dark",
                    type: "horizontal",
                    shadeIntensity: 0.5,
                    gradientToColors: [config.colors.primary],
                    inverseColors: !0,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 100],
                },
            },
            stroke: { dashArray: 3 },
            series: [78],
            labels: ["Growth"],
        });
    if (null !== i) {
        const f = new ApexCharts(i, n);
        f.render();
    }
    (i = document.querySelector("#visitsRadialChart")),
        (n = {
            chart: { height: 270, type: "radialBar" },
            colors: [
                config.colors.primary,
                config.colors.danger,
                config.colors.warning,
            ],
            series: [75, 80, 85],
            plotOptions: {
                radialBar: {
                    offsetY: -10,
                    hollow: { size: "45%" },
                    track: { margin: 10, background: o },
                    dataLabels: {
                        name: {
                            fontSize: "15px",
                            colors: [config.colors.secondary],
                            fontFamily: "IBM Plex Sans",
                            offsetY: 25,
                        },
                        value: {
                            fontSize: "2rem",
                            fontFamily: "Rubik",
                            fontWeight: 500,
                            color: e,
                            offsetY: -15,
                        },
                        total: {
                            show: !0,
                            label: "Total Visits",
                            fontSize: "15px",
                            fontWeight: 400,
                            fontFamily: "IBM Plex Sans",
                            color: config.colors.secondary,
                        },
                    },
                },
            },
            grid: { padding: { top: -10, bottom: -10 } },
            stroke: { lineCap: "round" },
            labels: ["Target", "Mart", "Ebay"],
            legend: {
                show: !0,
                position: "bottom",
                horizontalAlign: "center",
                labels: { colors: r, useSeriesColors: !1 },
                itemMargin: { horizontal: 15 },
                markers: { width: 10, height: 10, offsetX: -3 },
            },
        });
    if (null !== i) {
        const g = new ApexCharts(i, n);
        g.render();
    }
    (i = document.querySelector("#statisticsRadialChart")),
        (n = {
            series: [78],
            labels: ["Total Visits"],
            chart: { height: 225, type: "radialBar" },
            colors: [config.colors.warning],
            plotOptions: {
                radialBar: {
                    offsetY: 0,
                    startAngle: -140,
                    endAngle: 140,
                    hollow: {
                        size: "78%",
                        image: assetsPath + "img/icons/unicons/rocket.png",
                        imageWidth: 24,
                        imageHeight: 24,
                        imageOffsetY: -35,
                        imageClipped: !1,
                    },
                    track: { strokeWidth: "100%", background: a },
                    dataLabels: {
                        value: {
                            offsetY: 0,
                            color: e,
                            fontSize: "2rem",
                            fontWeight: "500",
                            fontFamily: "Rubik",
                        },
                        name: {
                            offsetY: 40,
                            color: config.colors.secondary,
                            fontSize: "0.938rem",
                            fontWeight: "400",
                            fontFamily: "IBM Plex Sans",
                        },
                    },
                },
            },
            stroke: { lineCap: "round" },
            grid: { padding: { top: -7, bottom: 8 } },
            states: {
                hover: { filter: { type: "none" } },
                active: { filter: { type: "none" } },
            },
        });
    if (null !== i) {
        const p = new ApexCharts(i, n);
        p.render();
    }
    (i = document.querySelector("#timeSpentGaugeChart")),
        (n = {
            series: [67],
            labels: ["Time Spent"],
            chart: { height: 230, type: "radialBar" },
            colors: [config.colors.success],
            plotOptions: {
                radialBar: {
                    offsetY: 0,
                    startAngle: -140,
                    endAngle: 140,
                    hollow: { size: "55%" },
                    track: { strokeWidth: "100%", background: a },
                    dataLabels: {
                        name: {
                            offsetY: 0,
                            color: e,
                            fontSize: "1.125rem",
                            fontFamily: "Rubik",
                        },
                        value: {
                            offsetY: 10,
                            color: config.colors.secondary,
                            fontSize: "0.938rem",
                            fontWeight: 500,
                            fontFamily: "IBM Plex Sans",
                            formatter: function (o) {
                                return o + "min";
                            },
                        },
                    },
                },
            },
            stroke: { lineCap: "round" },
            grid: { padding: { top: -35, left: -15, right: -15, bottom: 7 } },
            states: {
                hover: { filter: { type: "none" } },
                active: { filter: { type: "none" } },
            },
        });
    if (null !== i) {
        const u = new ApexCharts(i, n);
        u.render();
    }
    (i = document.querySelector("#revenueGrowthChart")),
        (n = {
            chart: {
                height: 100,
                type: "bar",
                stacked: !0,
                toolbar: { show: !1 },
            },
            grid: {
                show: !1,
                padding: { left: 0, right: 0, top: -15, bottom: -20 },
            },
            plotOptions: {
                bar: {
                    horizontal: !1,
                    columnWidth: "20%",
                    borderRadius: 2,
                    startingShape: "rounded",
                    endingShape: "flat",
                },
            },
            legend: { show: !1 },
            dataLabels: { enabled: !1 },
            colors: [config.colors.info, config.colors_label.secondary],
            series: [
                {
                    name: "2020",
                    data: [
                        80, 60, 125, 40, 50, 30, 70, 80, 100, 40, 80, 60, 120,
                        75, 25, 135, 65,
                    ],
                },
                {
                    name: "2021",
                    data: [
                        50, 65, 40, 100, 30, 30, 80, 20, 50, 45, 30, 90, 70, 40,
                        50, 40, 60,
                    ],
                },
            ],
            xaxis: {
                categories: [
                    "10",
                    "",
                    "",
                    "",
                    "",
                    "",
                    "",
                    "",
                    "15",
                    "",
                    "",
                    "",
                    "",
                    "",
                    "",
                    "",
                    "20",
                ],
                axisBorder: { show: !1 },
                axisTicks: { show: !1 },
                labels: { style: { colors: r }, offsetY: -5 },
            },
            yaxis: { show: !1, floating: !0 },
            tooltip: { x: { show: !1 } },
        });
    if (null !== i) {
        const m = new ApexCharts(i, n);
        m.render();
    }
    (i = document.querySelector("#totalEarningChart")),
        (n = {
            chart: {
                height: 130,
                width: 130,
                fontFamily: "IBM Plex Sans",
                type: "donut",
            },
            dataLabels: { enabled: !1 },
            grid: { padding: { top: -11, bottom: -4, left: -8, right: 0 } },
            series: [60, 45, 60],
            labels: ["Social", "Email", "Search"],
            stroke: { width: 8, lineCap: "round", colors: o },
            colors: [
                config.colors.primary,
                config.colors.warning,
                config.colors.success,
            ],
            plotOptions: {
                pie: {
                    donut: {
                        size: "75%",
                        labels: {
                            show: !0,
                            name: { fontSize: "0.938rem", offsetY: -12 },
                            value: {
                                show: !0,
                                fontSize: "1.625rem",
                                fontFamily: "Rubik",
                                fontWeight: "500",
                                color: e,
                                offsetY: 0,
                                formatter: function (o) {
                                    return o + "%";
                                },
                            },
                            total: {
                                show: !0,
                                label: "Total",
                                color: config.colors.secondary,
                                formatter: function (o) {
                                    return o.globals.seriesTotals.reduce(
                                        function (o, e) {
                                            return o + e;
                                        },
                                        0
                                    );
                                },
                            },
                        },
                    },
                },
            },
            legend: { show: !1 },
            states: { active: { filter: { type: "none" } } },
        });
    if (null !== i) {
        const b = new ApexCharts(i, n);
        b.render();
    }
    (i = document.querySelector("#orderSummaryChart")),
        (n = {
            chart: {
                height: 250,
                type: "area",
                toolbar: !1,
                dropShadow: {
                    enabled: !0,
                    top: 18,
                    left: 2,
                    blur: 3,
                    color: config.colors.primary,
                    opacity: 0.15,
                },
            },
          
            series: [{ data: [15, 18, 13, 19, 16, 31, 18, 26, 23, 39] }],
            dataLabels: { enabled: !1 },
            stroke: { curve: "smooth", lineCap: "round" },
            colors: [config.colors.primary],
            fill: {
                type: "gradient",
                gradient: {
                    shade: t,
                    shadeIntensity: 0.8,
                    opacityFrom: 0.7,
                    opacityTo: 0.25,
                    stops: [0, 95, 100],
                },
            },
            grid: {
                show: !0,
                borderColor: a,
                padding: { top: -15, bottom: -10, left: 15, right: 10 },
            },
            xaxis: {
                categories: [
                    "Jan",
                    "Feb",
                    "Mar",
                    "Apr",
                    "May",
                    "Jun",
                    "Jul",
                    "Aug",
                    "Sep",
                    "Oct",
                ],
                labels: { offsetX: 0, style: { colors: r, fontSize: "13px" } },
                axisBorder: { show: !1 },
                axisTicks: { show: !1 },
                lines: { show: !1 },
            },
            yaxis: {
                labels: {
                    offsetX: 7,
                    formatter: function (o) {
                        return "$" + o;
                    },
                    style: { fontSize: "13px", colors: r },
                },
                min: 0,
                max: 40,
                tickAmount: 4,
            },
        });
    if (null !== i) {
        const y = new ApexCharts(i, n);
        y.render();
    }
})();
