function map_chart(data) {
    google.charts.load("current", {
        packages: ["geochart"],
    });
    google.charts.setOnLoadCallback(drawRegionsMap);
    var map_data = [["Country", "Popularity"]];
    $.each(data, function (k, val) {
        map_data[k + 1] = val;
    });

    function drawRegionsMap() {
        var data = google.visualization.arrayToDataTable(map_data);

        var options = {};

        var chart = new google.visualization.GeoChart(
            document.getElementById("mapcontainer")
        );

        chart.draw(data, options);
    }
}

function dash_site_detail() {

    var siteId   = $("#site-list option:selected").val();
    var siteName = $("#site-list option:selected").text();
    $("#current_site").html(siteName);
    $("#current_site").attr("data-siteid", siteId);
    $("#share_site").val(siteId);
    $(".share_link").attr("id", siteId);
    dashboard_load_data();
}

function dashboard_load_data() {

    var siteid = $("#current_site").attr("data-siteid");

    if ($("#usersChart").length) {
        get_chart_data("get_user_data", "dashboard", "year", siteid);
    }
    if ($("#bounceRateChart").length) {
        get_chart_data("bounceRateChart", "dashboard", "year", siteid);
    }
    if ($("#sessionDuration").length) {
        get_chart_data("sessionDuration", "dashboard", "year", siteid);
    }
    if ($("#session_by_device").length) {
        get_chart_data("session_by_device", "dashboard", "year", siteid);
    }
    if ($("#user-timeline-chart-year").length) {
        get_chart_data("user-timeline-chart", "dashboard", "year", siteid);
    }
    if ($("#user-timeline-chart-month").length) {
        get_chart_data("user-timeline-chart", "dashboard", "15daysago", siteid);
    }
    if ($("#user-timeline-chart-week").length) {
        get_chart_data("user-timeline-chart", "dashboard", "week", siteid);
    }
    if ($("#live_users").length > 0) {
        get_live_user(siteid);
    }
    if ($("#active_pages").length > 0) {
        get_active_pages(siteid);
    }
    if ($(".mapcontainer").length) {
        get_chart_data("mapcontainer", "dashboard", "year", siteid);
    }
}

function get_chart_data(type, chart_page, chart_duration, siteid) {
    var token = $('meta[name="csrf-token"]').attr("content");

    $.ajax({
        url: get_chart_data_url,
        method: "POST",
        data: {
            _token: token,
            type: type,
            chart_page: chart_page,
            chart_duration: chart_duration,
            siteid: siteid,
        },
        success: function (data) {
            if (data.is_success == 1) {
                var label = data.data.labels;
                var datasets = data.data.datasets;
                if (type == "get_user_data") {
                    area_chart(label, datasets, type, "#ff3a6e", "Users");
                }
                if (type == "bounceRateChart") {
                    area_chart(label, datasets, type, "#ffa21d", "Bounce Rate");
                }
                if (type == "sessionDuration") {
                    area_chart(label, datasets, type, "#3ec9d6", "Bounce Rate");
                }
                if (type == "session_by_device") {
                    pie_chart(label, datasets);
                }
                if (type == "user-timeline-chart") {
                    $("#total_Active_Users_" + chart_duration).html(
                        data.total.Active_Users
                    );
                    $("#total_New_Users_" + chart_duration).html(
                        data.total.New_Users
                    );

                    user_timeline_chart(label, datasets, chart_duration);
                }
                if (type == "mapcontainer") {
                    map_chart(datasets);
                }
            } else {
                var label = data.data.labels;
                var datasets = data.data.datasets;
                if (type == "get_user_data") {
                    area_chart(label, datasets, type, "#ff3a6e", "Users");
                }
                if (type == "bounceRateChart") {
                    area_chart(label, datasets, type, "#ffa21d", "Bounce Rate");
                }
                if (type == "sessionDuration") {
                    area_chart(label, datasets, type, "#3ec9d6", "Bounce Rate");
                }
                if (type == "session_by_device") {
                    pie_chart(label, datasets);
                }
                show_toastr("Error", data.message, "error");
            }
        },
    });
}

function get_live_user(siteid) {
    var token = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
        url: get_live_user_url,
        method: "POST",
        data: {
            _token: token,
            siteid: siteid,
        },
        success: function (data) {
            var parsed = JSON.parse(data);
            if (parsed.is_success) {
                $("#live_users").html(parsed.liveUser);
            }
        },
    });
}

function get_active_pages(siteid) {
    var token = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
        url: get_active_pages_url,
        method: "POST",
        data: {
            _token: token,
            siteid: siteid,
        },
        success: function (data) {
            if (data.is_success == 1) {
                var html = "";

                $.each(data.data, function (i, item) {
                    html += "<tr>";
                    html += '<td scope="row">' + (i + 1) + "</td>";
                    html += "<td>" + item.PageUrl + "</td>";
                    html += "<td>" + item.screenPageViews + "</td>";
                    html += "<td>" + item.screenPageViewsPerUser + "%</td>";
                    html += "</tr>";
                });
                $("#active_pages").html(html);
                const dataTable = new simpleDatatables.DataTable(
                    "#pc-dt-simple"
                );
            }
        },
    });
}

function area_chart(label, datasets, type, color, title) {
    var options = {
        chart: {
            height: 250,
            type: "area",
            toolbar: {
                show: false,
            },
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            width: 2,
            curve: "smooth",
        },
        series: [
            {
                name: title,
                data: datasets,
            },
        ],
        xaxis: {
            categories: label,
        },
        colors: [color],
        grid: {
            strokeDashArray: 4,
        },
        legend: {
            show: false,
        },

        yaxis: {
            tickAmount: 7,
            min: 0,
            max: Math.max.apply(Math, datasets),
        },
    };
    if (type == "bounceRateChart") {
        $("#bounceRateChart").empty();

        var chart = new ApexCharts(
            document.querySelector("#bounceRateChart"),
            options
        );
    }
    if (type == "get_user_data") {
        $("#usersChart").empty();

        var chart = new ApexCharts(
            document.querySelector("#usersChart"),
            options
        );
    }
    if (type == "sessionDuration") {
        $("#sessionDuration").empty();

        var chart = new ApexCharts(
            document.querySelector("#sessionDuration"),
            options
        );
    }
    chart.render();
}

function saveAsPDF(view) {
    var filename = $("#current_site").html();
    if (typeof filename === "undefined") {
        filename = $("#site-list option:selected").html();
    }

    var element = document.getElementById("printableArea");
    var opt = {
        margin: 0.1,
        filename: filename + " " + view,
        image: {
            type: "jpeg",
            quality: 1,
        },
        html2canvas: {
            scale: 2,
            dpi: 72,
            letterRendering: true,
        },
        jsPDF: {
            unit: "in",
            format: "A2",
        },
    };
    html2pdf().set(opt).from(element).save();
}

function pie_chart(label, datasets) {
    var options = {
        series: datasets,
        chart: {
            // width: 600,
            width: 400,
            type: "donut",
        },
        stroke: {
            width: 0,
        },
        plotOptions: {
            pie: {
                donut: {
                    labels: {
                        show: true,
                        total: {
                            showAlways: false,
                            show: false,
                        },
                    },
                },
            },
        },
        labels: label,
        dataLabels: {
            dropShadow: {
                blur: 3,
                opacity: 0.8,
            },
        },
        fill: {
            type: "solid",
            opacity: 1,
        },
        states: {
            hover: {
                filter: "none",
            },
        },

        responsive: [
            {
                breakpoint: 740,
                options: {
                    chart: {
                        width: 400,
                    },
                    legend: {
                        position: "top",
                    },
                },
            },
            {
                breakpoint: 480,
                options: {
                    chart: {
                        width: 230,
                    },
                    legend: {
                        position: "top",
                    },
                },
            },
            {
                breakpoint: 320,
                options: {
                    chart: {
                        width: 200,
                    },
                    legend: {
                        position: "top",
                    },
                },
            },
        ],
    };
    $("#session_by_device").empty();
    var chart = new ApexCharts(
        document.querySelector("#session_by_device"),
        options
    );
    chart.render();
}

function user_timeline_chart(label, datasets, type) {
    var data_arr = JSON.stringify(datasets);
    var parsed = JSON.parse(data_arr);

    var options = {
        series: [
            {
                name: parsed[0].label,
                data: parsed[0].data,
            },
            {
                name: parsed[1].label,
                data: parsed[1].data,
            },
        ],
        chart: {
            height: 350,
            type: "area",
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            curve: "smooth",
        },
        xaxis: {
            categories: label,
            labels: {
                rotate: -45,
            },
        },
    };
    if (type == "week") {
        $("#user-timeline-chart-week").empty();
        var chart = new ApexCharts(
            document.querySelector("#user-timeline-chart-week"),
            options
        );
    }
    if (type == "year") {
        $("#user-timeline-chart-year").empty();
        var chart = new ApexCharts(
            document.querySelector("#user-timeline-chart-year"),
            options
        );
    }
    if (type == "15daysago") {
        $("#user-timeline-chart-month").empty();
        var chart = new ApexCharts(
            document.querySelector("#user-timeline-chart-month"),
            options
        );
    }

    chart.render();
}

// Validation Code
function validation() {
    var forms = document.querySelectorAll(".needs-validation");
    Array.prototype.forEach.call(forms, function (form) {
        form.addEventListener(
            "submit",
            function (event) {
                var submitButton = form.querySelector(
                    'button[type="submit"], input[type="submit"]'
                );

                if (submitButton) {
                    submitButton.disabled = true;
                }
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                    if (submitButton) {
                        submitButton.disabled = false;
                    }
                }

                form.classList.add("was-validated");
            },
            false
        );
    });
}

$(document).ready(function () {
    if ($(".needs-validation").length > 0) {
        validation();
    }
});
