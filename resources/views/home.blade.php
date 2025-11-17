@extends('layouts.template')

@push('css')
    <style>
        .apexcharts-menu {
            z-index: 9999 !important;
        }

        .apexcharts-toolbar {
            z-index: 50 !important;
        }

        .apexcharts-menu.open {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .stats-icon {
            width: 3rem;
            height: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem;
            color: white;
        }

        /* Warna custom */
        .stats-icon.tpa {
            background-color: #003A70;
            /* Biru gelap */
        }

        .stats-icon.tpst {
            background-color: #F7C904;
            /* Kuning */
            color: black !important;
        }

        .stats-icon.tps3r {
            background-color: #C1272D;
            /* Merah */
        }

        .stats-icon.iplt {
            background-color: #4A90E2;
            /* Biru */
        }

        .stats-icon.spald {
            background-color: #F5A623;
            /* Orange */
            color: black !important;
        }
    </style>
@endpush

@section('content')
    <section class="row">
        <div class="col-12 ">
            <div class="row" id="stat-cards">

            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <div id="chart-sanitasis"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <div id="chart-tpas"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <div id="chart-tpsts"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <div id="chart-tps3rs"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <div id="chart-iplts"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <div id="chart-spalds"></div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        {{-- <div class="col-12 col-lg-3">
            <div class="card">
                <div class="card-body py-4 px-5">

                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-xl">
                            <img src="./assets/compiled/jpg/1.jpg" alt="Face 1">
                        </div>
                        <div class="ms-3 name">
                            <h5 class="font-bold">John Duck</h5>
                            <h6 class="text-muted mb-0">@johnducky</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>Recent Messages</h4>
                </div>
                <div class="card-content pb-4">
                    <div class="recent-message d-flex px-4 py-3">
                        <div class="avatar avatar-lg">
                            <img src="./assets/compiled/jpg/4.jpg">
                        </div>
                        <div class="name ms-4">
                            <h5 class="mb-1">Hank Schrader</h5>
                            <h6 class="text-muted mb-0">@johnducky</h6>
                        </div>
                    </div>
                    <div class="recent-message d-flex px-4 py-3">
                        <div class="avatar avatar-lg">
                            <img src="./assets/compiled/jpg/5.jpg">
                        </div>
                        <div class="name ms-4">
                            <h5 class="mb-1">Dean Winchester</h5>
                            <h6 class="text-muted mb-0">@imdean</h6>
                        </div>
                    </div>
                    <div class="recent-message d-flex px-4 py-3">
                        <div class="avatar avatar-lg">
                            <img src="./assets/compiled/jpg/1.jpg">
                        </div>
                        <div class="name ms-4">
                            <h5 class="mb-1">John Dodol</h5>
                            <h6 class="text-muted mb-0">@dodoljohn</h6>
                        </div>
                    </div>
                    <div class="px-4">
                        <button class='btn btn-block btn-xl btn-light-primary font-bold mt-3'>Start
                            Conversation</button>
                    </div>
                </div>
            </div>
        </div> --}}
    </section>
@endsection
@push('js')
    <script src="{{ asset('assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/static/js/pages/dashboard.js') }}"></script> --}}

    <script>
        $(document).ready(function() {
            const cardConfig = {
                sanitasis_count: {
                    title: "Pembangunan",
                    color: "tps3r",
                    icon: "iconly-boldCategory"
                },
                tpas_count: {
                    title: "Total TPA",
                    color: "tpa",
                    icon: "iconly-boldCategory"
                },
                tpsts_count: {
                    title: "Total TPST",
                    color: "tpst",
                    icon: "iconly-boldCategory"
                },
                tps3rs_count: {
                    title: "Total TPS3R",
                    color: "tps3r",
                    icon: "iconly-boldCategory"
                },
                iplts_count: {
                    title: "Total IPLT",
                    color: "iplt",
                    icon: "iconly-boldCategory"
                },
                spalds_count: {
                    title: "Total SPALD",
                    color: "spald",
                    icon: "iconly-boldCategory"
                }
            };

            const baseOptionChart = {
                annotations: {
                    position: "back"
                },
                dataLabels: {
                    enabled: false
                },
                chart: {
                    type: "bar",
                    height: 300
                },
                fill: {
                    opacity: 1
                },
                series: [],
                colors: ["#003A70"],
                xaxis: {
                    categories: []
                },
            };

            var chartSanitasi = new ApexCharts(document.querySelector("#chart-sanitasis"), JSON.parse(JSON
                .stringify(
                    baseOptionChart)));
            var chartTpa = new ApexCharts(document.querySelector("#chart-tpas"), JSON.parse(JSON.stringify(
                baseOptionChart)));
            var chartTpst = new ApexCharts(document.querySelector("#chart-tpsts"), JSON.parse(JSON.stringify(
                baseOptionChart)));
            var chartTps3r = new ApexCharts(document.querySelector("#chart-tps3rs"), JSON.parse(JSON.stringify(
                baseOptionChart)));
            var chartIplt = new ApexCharts(document.querySelector("#chart-iplts"), JSON.parse(JSON.stringify(
                baseOptionChart)));
            var chartSpald = new ApexCharts(document.querySelector("#chart-spalds"), JSON.parse(JSON.stringify(
                baseOptionChart)));

            chartSanitasi.render();
            chartTpa.render();
            chartTpst.render();
            chartTps3r.render();
            chartIplt.render();
            chartSpald.render();

            getData()

            function getData() {
                $.ajax({
                    url: "{{ route('api.dashboards.index') }}",
                    type: "GET",
                    success: function(res) {

                        let data = res.data;
                        let html = "";

                        Object.keys(cardConfig).forEach(key => {
                            let cfg = cardConfig[key];
                            let total = sumField(data, key);

                            html += createStatCard(cfg.color, cfg.icon, cfg.title, total);
                        });

                        $("#stat-cards").html(html);

                        // x-axis = nama kecamatan
                        let categories = data.map(item => item.nama);

                        let sanitasisSeries = data.map(item => item.sanitasis_count);
                        let tpasSeries = data.map(item => item.tpas_count);
                        let tpstsSeries = data.map(item => item.tpsts_count);
                        let tps3rsSeries = data.map(item => item.tps3rs_count);
                        let ipltsSeries = data.map(item => item.iplts_count);
                        let spaldsSeries = data.map(item => item.spalds_count);

                        // ===================================================================

                        // CHART TPA
                        chartSanitasi.updateOptions({
                            title: {
                                text: "Pembangunan per Kecamatan",
                                align: "center"
                            },
                            xaxis: {
                                categories
                            },
                            colors: ["#C1272D"],
                            series: [{
                                name: "Pembangunan",
                                data: sanitasisSeries
                            }]
                        });

                        // CHART TPA
                        chartTpa.updateOptions({
                            title: {
                                text: "TPA per Kecamatan",
                                align: "center"
                            },
                            xaxis: {
                                categories
                            },
                            colors: ["#003A70"],
                            series: [{
                                name: "TPA",
                                data: tpasSeries
                            }]
                        });

                        // CHART TPST
                        chartTpst.updateOptions({
                            title: {
                                text: "TPST per Kecamatan",
                                align: "center"
                            },
                            xaxis: {
                                categories
                            },
                            colors: ["#F7C904"],
                            series: [{
                                name: "TPST",
                                data: tpstsSeries
                            }]
                        });

                        // CHART TPS3R
                        chartTps3r.updateOptions({
                            title: {
                                text: "TPS3R per Kecamatan",
                                align: "center"
                            },
                            xaxis: {
                                categories
                            },
                            colors: ["#C1272D"],
                            series: [{
                                name: "TPS3R",
                                data: tps3rsSeries
                            }]
                        });

                        // CHART IPLT
                        chartIplt.updateOptions({
                            title: {
                                text: "IPLT per Kecamatan",
                                align: "center"
                            },
                            xaxis: {
                                categories
                            },
                            colors: ["#4A90E2"],
                            series: [{
                                name: "IPLT",
                                data: ipltsSeries
                            }]
                        });

                        // CHART SPALD
                        chartSpald.updateOptions({
                            title: {
                                text: "SPALD per Kecamatan",
                                align: "center"
                            },
                            xaxis: {
                                categories
                            },
                            colors: ["#F5A623"],
                            series: [{
                                name: "SPALD",
                                data: spaldsSeries
                            }]
                        });

                    }
                });
            }

            function createStatCard(color, icon, title, value) {
                return `
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card mb-3">
                            <div class="card-body px-4 py-2-3">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                        <div class="stats-icon ${color} mb-2">
                                            <i class="${icon}"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                        <h6 class="text-muted font-semibold">${title}</h6>
                                        <h6 class="font-extrabold mb-0">${value}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            function sumField(data, field) {
                return data.reduce((acc, item) => acc + item[field], 0);
            }
        });
    </script>
@endpush
