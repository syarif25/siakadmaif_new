$(function () {
  "use strict";
  var e = {
    series: [
      {
        name: "Sessions",
        data: [14, 3, 10, 9, 29, 19, 22, 9, 12, 7, 19, 5],
      },
    ],
    chart: {
      foreColor: "#9ba7b2",
      height: 310,
      type: "area",
      zoom: {
        enabled: !1,
      },
      toolbar: {
        show: !0,
      },
      dropShadow: {
        enabled: !0,
        top: 3,
        left: 14,
        blur: 4,
        opacity: 0.1,
      },
    },
    stroke: {
      width: 5,
      curve: "smooth",
    },
    xaxis: {
      type: "datetime",
      categories: ["1/11/2000", "2/11/2000", "3/11/2000", "4/11/2000", "5/11/2000", "6/11/2000", "7/11/2000", "8/11/2000", "9/11/2000", "10/11/2000", "11/11/2000", "12/11/2000"],
    },
    title: {
      text: "Sessions",
      align: "left",
      style: {
        fontSize: "16px",
        color: "#666",
      },
    },
    fill: {
      type: "gradient",
      gradient: {
        shade: "light",
        gradientToColors: ["#0d6efd"],
        shadeIntensity: 1,
        type: "vertical",
        opacityFrom: 0.7,
        opacityTo: 0.2,
        stops: [0, 100, 100, 100],
      },
    },
    markers: {
      size: 5,
      colors: ["#0d6efd"],
      strokeColors: "#fff",
      strokeWidth: 2,
      hover: {
        size: 7,
      },
    },
    dataLabels: {
      enabled: !1,
    },
    colors: ["#0d6efd"],
    yaxis: {
      title: {
        text: "Sessions",
      },
    },
  };
  new ApexCharts(document.querySelector("#chart1"), e).render();

  Highcharts.chart("chart8", {
    chart: {
      type: "bar",
      styledMode: !0,
    },
    credits: {
      enabled: !1,
    },
    exporting: {
      buttons: {
        contextButton: {
          enabled: !1,
        },
      },
    },
    title: {
      text: "Statistik Kehadiran",
    },
    xAxis: {
      categories: ["Jan", "Feb", "Mar", "Apr", "May"],
    },
    yAxis: {
      min: 0,
      title: {
        text: "-",
        style: {
          display: "none",
        },
      },
    },
    legend: {
      reversed: !1,
    },
    plotOptions: {
      series: {
        stacking: "normal",
      },
    },
    series: [
      {
        name: "Hadir",
        data: [5, 3, 4, 7, 2],
      },
      {
        name: "Izin",
        data: [2, 2, 3, 2, 1],
      },
      {
        name: "Alpha",
        data: [3, 4, 4, 2, 5],
      },
    ],
  });
  e = {
    series: [60, 47, 52, 58, 40],
    chart: {
      height: 340,
      type: "polarArea",
    },
    labels: ["Nilai A", "Nilai B", "Nilai C", "Nilai D", "Nilai E"],
    fill: {
      opacity: 1,
    },
    stroke: {
      width: 1,
      colors: void 0,
    },
    colors: ["#17a00e", "#0dcaf0", "#0d6efd", "#ffc107", "#f41127"],
    yaxis: {
      show: !1,
    },
    dataLabels: {
      enabled: !1,
    },
    legend: {
      show: !1,
      position: "bottom",
    },
    plotOptions: {
      polarArea: {
        rings: {
          strokeWidth: 0,
        },
      },
    },
  };
  new ApexCharts(document.querySelector("#chart9"), e).render(),
    jQuery("#geographic-map").vectorMap({
      map: "nilai_matakuliah",
      backgroundColor: "transparent",
      borderColor: "#818181",
      borderOpacity: 0.25,
      borderWidth: 1,
      zoomOnScroll: !1,
      color: "#009efb",
      regionStyle: {
        initial: {
          fill: "#6c757d",
        },
      },
      markerStyle: {
        initial: {
          r: 9,
          fill: "#fff",
          "fill-opacity": 1,
          stroke: "#000",
          "stroke-width": 5,
          "stroke-opacity": 0.4,
        },
      },
      enableZoom: !0,
      hoverColor: "#009efb",
      markers: [
        {
          latLng: [21, 78],
          name: "I Love My India",
        },
      ],
      series: {
        regions: [
          {
            values: {
              IN: "#0d6efd",
              US: "#15b70a",
              RU: "#f41127",
              AU: "#ffb207",
            },
          },
        ],
      },
      hoverOpacity: null,
      normalizeFunction: "linear",
      scaleColors: ["#b6d6ff", "#005ace"],
      selectedColor: "#c9dfaf",
      selectedRegions: [],
      showTooltip: !0,
      onRegionClick: function (e, t, o) {
        var r = 'You clicked "' + o + '" which has the code: ' + t.toUpperCase();
        alert(r);
      },
    }),
    new PerfectScrollbar(".dashboard-top-countries");
});
