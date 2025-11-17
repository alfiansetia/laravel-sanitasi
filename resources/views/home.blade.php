@extends('layouts.template')

@section('content')
    <section class="row">
        <div class="col-12 col-lg-9">
            <div class="row">
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon purple mb-2">
                                        <i class="iconly-boldShow"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Profile Views</h6>
                                    <h6 class="font-extrabold mb-0">112.000</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon blue mb-2">
                                        <i class="iconly-boldProfile"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Followers</h6>
                                    <h6 class="font-extrabold mb-0">183.000</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon green mb-2">
                                        <i class="iconly-boldAdd-User"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Following</h6>
                                    <h6 class="font-extrabold mb-0">80.000</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon red mb-2">
                                        <i class="iconly-boldBookmark"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Saved Post</h6>
                                    <h6 class="font-extrabold mb-0">112</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        {{-- <div class="card-header">
                            <h4>TPA</h4>
                        </div> --}}
                        <div class="card-body">
                            <div id="chart-tpas"></div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <div class="col-12 col-lg-3">
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
        </div>
    </section>
@endsection
@push('js')
    <script src="{{ asset('assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/static/js/pages/dashboard.js') }}"></script> --}}

    <script>
        $(document).ready(function() {
            var optionsProfileVisit = {
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
                colors: ["#435ebe"],
                xaxis: {
                    categories: []
                },
                title: {
                    text: "Jumlah Data per Kecamatan",
                    align: "center", // left, center, right
                    margin: 10,
                    offsetY: 0,
                    style: {
                        fontSize: "16px",
                        fontWeight: "bold",
                    }
                },
            }

            var chartProfileVisit = new ApexCharts(
                document.querySelector("#chart-tpas"),
                optionsProfileVisit
            )

            chartProfileVisit.render()
            getData()

            function getData() {
                $.ajax({
                    url: "{{ route('api.dashboards.index') }}",
                    type: "GET",
                    success: function(res) {

                        let data = res.data;

                        // ambil nama kecamatan untuk x-axis
                        let categories = data.map(item => item.nama)

                        // ambil jumlah tpa
                        let tpasSeries = data.map(item => item.tpas_count);
                        let tpstsSeries = data.map(item => item.tpsts_count);
                        let tps3rsSeries = data.map(item => item.tps3rs_count);
                        let ipltsSeries = data.map(item => item.iplts_count);
                        let spaldsSeries = data.map(item => item.spalds_count);

                        // update chart
                        chartProfileVisit.updateOptions({
                            xaxis: {
                                categories: categories
                            },
                            colors: [
                                "#003A70", // TPAS
                                "#F7C904", // TPSTS
                                "#C1272D", // TPS3R
                                "#4A90E2", // IPLT
                                "#F5A623" // SPALD
                            ],
                            series: [{
                                    name: "TPAS",
                                    data: tpasSeries
                                },
                                {
                                    name: "TPSTS",
                                    data: tpstsSeries
                                },
                                {
                                    name: "TPS3R",
                                    data: tps3rsSeries
                                },
                                {
                                    name: "IPLT",
                                    data: ipltsSeries
                                },
                                {
                                    name: "SPALD",
                                    data: spaldsSeries
                                }
                            ]
                        })
                    }
                });
            }
        });
    </script>
@endpush
