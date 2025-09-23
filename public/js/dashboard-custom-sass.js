$(function () {
    new ApexCharts(document.querySelector("#pageViews"), {
        chart: {
            height: 50,
            type: "area",
            dropShadow: { enabled: !0, opacity: 0.1, blur: 2, left: -1, top: 5 },
            responsive: !0,
            zoom: { enabled: !1 },
            toolbar: { show: !1 },
            animations: { enabled: !0, easing: "easeinout", speed: 800 },
            sparkline: { enabled: !0 },
        },
        colors: ["#9BB237"],
        dataLabels: { enabled: !1 },
        fill: { type: "solid", opacity: 0 },
        tooltip: { theme: "light", style: { fontSize: "12px" }, marker: { show: !1 }, x: { show: !0 } },
        stroke: { show: !0, curve: "smooth", width: 3 },
        series: [{ name: "Pageviews", data: [70, 90, 75, 89, 63, 25, 44, 12, 36, 19, 54] }],
        labels: ["1 Apr 20", "2 Apr 20", "3 Apr 20", "4 Apr 20", "5 Apr 20", "6 Apr 20", "7 Apr 20", "8 Apr 20", "9 Apr 20", "10 Apr 20", "11 Apr 20"],
    }).render(),
        new ApexCharts(document.querySelector("#revenueBar"), {
            chart: { type: "bar", height: 40, sparkline: { enabled: !0 }, dropShadow: { enabled: !0, opacity: 0.1, blur: 2, left: -1, top: 5 } },
            colors: ["#4e37b2", "#22b783"],
            plotOptions: { bar: { horizontal: !1, columnWidth: "50%" } },
            dataLabels: { enabled: !1 },
            fill: { opacity: 1 },
            tooltip: { theme: "light", style: { fontSize: "12px" }, marker: { show: !1 }, x: { show: !1 } },
            series: [
                { name: "Profit", data: [129, 100, 57, 56, 61, 58, 63, 60, 66] },
                { name: "Revenue", data: [76, 85, 101, 98, 87, 105, 91, 114, 94] },
            ],
        }).render();
}),
    $(window).on("load", function () {
        var e = "#828D99";
        new ApexCharts(document.querySelector("#apexChartD"), {
            chart: { height: 360, type: "bar", toolbar: { show: !1 } },
            plotOptions: { bar: { horizontal: !1, columnWidth: "20%", endingShape: "rounded" } },
            legend: { horizontalAlign: "right", offsetY: -10, markers: { radius: 50, height: 8, width: 8 } },
            dataLabels: { enabled: !1 },
            colors: ["#4e37b2", "#E2ECFF"],
            fill: { type: "gradient", gradient: { shade: "light", type: "vertical", inverseColors: !0, opacityFrom: 1, opacityTo: 1, stops: [0, 70, 100] } },
            series: [
                { name: "2024", data: [80, 95, 150, 210, 140, 230, 300, 280, 130] },
                { name: "2025", data: [50, 70, 130, 180, 90, 180, 270, 220, 110] },
            ],
            xaxis: { categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep"], axisBorder: { show: !1 }, axisTicks: { show: !1 }, labels: { style: { colors: e } } },
            yaxis: { min: 0, max: 300, tickAmount: 3, labels: { style: { color: e } } },
            legend: { show: !1 },
            tooltip: {
                y: {
                    formatter: function (e) {
                        return +e + " thousands";
                    },
                },
            },
        }).render();
    });
