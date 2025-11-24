@extends('layouts.template')
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.85.1/dist/L.Control.Locate.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.fullscreen@4.0.0/Control.FullScreen.min.css">
    <style>
        .choices__list--dropdown,
        .choices__list[aria-expanded] {
            z-index: 1051 !important;
        }
    </style>
@endpush

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="kecamatan_id" class="required">Kecamatan</label>
                            <select id="kecamatan_id" name="kecamatan_id" class="choices form-select">
                                <option value="">--Select Kecamatan--</option>
                                @foreach ($kecamatans as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="kelurahan_id" class="required">Kelurahan/Desa</label>
                            <select id="kelurahan_id" name="kelurahan_id" class="choices form-select">
                                <option value="">--Select Kelurahan--</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div id="map" style="height: 500px"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script src="{{ asset('assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.85.1/dist/L.Control.Locate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet.fullscreen@4.0.0/Control.FullScreen.min.js"></script>

    {{-- <script type="text/javascript" src="https://simspam.id/maps/akses/kabupaten-desa/6107/data.geojson.js"></script> --}}
    {{-- <script type="text/javascript" src="https://simspam.id/maps/akses/kabupaten/6107/data.geojson.js"></script> --}}

    <script>
        const URL_INDEX = "{{ route('tpas.index') }}"
        const URL_INDEX_API = "{{ route('api.tpas.index') }}"
        var id = 0;


        $(document).ready(function() {

            const default_lat = '0.0632612';
            const default_long = '111.4862054';

            // Inisialisasi Map
            var map = L.map('map').setView([default_lat, default_long], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            // Control tambahan
            L.control.locate({
                drawCircle: false,
                follow: false,
                position: 'topright',
                icon: 'fa fa-location-arrow',
                iconLoading: 'fa fa-spinner fa-spin',
            }).addTo(map);

            L.control.fullscreen({
                position: 'topleft',
                forceSeparateButton: true,
            }).addTo(map);

            // ====================================================
            // =============== Ambil API Kecamatan =================
            // ====================================================
            let kecamatanData = {};

            fetch("{{ route('api.dashboards.index') }}")
                .then(res => res.json())
                .then(res => {
                    kecamatanData = res.data; // simpan untuk lookup
                })
                .catch(err => console.error("Gagal load API kecamatan:", err));


            // ====================================================
            // =============== Load GeoJSON ========================
            // ====================================================
            fetch("{{ asset('geojson/6105/61.05_kecamatan.geojson') }}") // Ubah ke path file kamu
                .then(res => res.json())
                .then(json => {

                    // Tambahkan ke Leaflet
                    var geoLayer = L.geoJSON(json, {
                        style: {
                            color: "#0066ff",
                            weight: 2,
                            fillOpacity: 0.3
                        },
                        onEachFeature: function(feature, layer) {

                            let namaKecamatan = (
                                feature.properties.nm_kecamatan ||
                                "-"
                            ).toString();

                            layer.on("click", function() {

                                // Cocokkan nama kecamatan dengan API Laravel
                                let kec = kecamatanData.find(x =>
                                    x.nama.toLowerCase() === namaKecamatan.toLowerCase()
                                );

                                if (!kec) {
                                    layer.bindPopup(`<b>${namaKecamatan}</b><br>
                            Data kecamatan tidak ditemukan di API`).openPopup();
                                    return;
                                }

                                layer.bindPopup(`
                        <b>${kec.nama}</b><br>
                        Sanitasi: ${kec.sanitasis_count}<br>
                        TPA: ${kec.tpas_count}<br>
                        TPST: ${kec.tpsts_count}<br>
                        TPS3R: ${kec.tps3rs_count}<br>
                        IPLT: ${kec.iplts_count}<br>
                        SPALD: ${kec.spalds_count}<br>
                    `).openPopup();
                            });
                        }
                    }).addTo(map);

                    // Zoom berdasarkan batas GeoJSON
                    map.fitBounds(geoLayer.getBounds());
                })
                .catch(err => console.error("Gagal load GeoJSON:", err));


            const kecamatan = new Choices(document.getElementById('kecamatan_id'), {
                searchEnabled: true,
                removeItemButton: true,
                allowHTML: true,
            });

            const kelurahan = new Choices(document.getElementById('kelurahan_id'), {
                searchEnabled: true,
                removeItemButton: true,
                allowHTML: true,
            });

            $('#kecamatan_id').on('change', function() {
                let kecamatan_id = $(this).val();
                kelurahan.clearChoices();
                kelurahan.setChoices([{
                    value: '',
                    label: 'Select Kelurahan',
                    disabled: true,
                    selected: true,
                }], 'value', 'label', true);
                kelurahan.setChoiceByValue('');
                if (!kecamatan_id) {
                    return;
                }
                $.ajax({
                    url: `{{ route('api.kelurahans.index') }}?kecamatan_id=${kecamatan_id}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        kelurahan.clearChoices();
                        kelurahan.setChoices(
                            response.data.map(item => ({
                                value: item.id,
                                label: item.nama,
                            })),
                            'value',
                            'label',
                            false
                        );
                    },
                    error: function() {
                        kelurahan.clearChoices();
                    }
                });
            });




        })
    </script>
@endpush
