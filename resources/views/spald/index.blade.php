@extends('layouts.template')
@push('css')
    <link href="https://cdn.datatables.net/v/bs5/dt-2.3.4/b-3.2.5/b-colvis-3.2.5/datatables.min.css" rel="stylesheet"
        integrity="sha384-b7CCWUkHYYyObRWK8dDxH6PCbeH3SHTbH+TzwIoEUU/Ol75XipyzcYbfdNWmNWFF" crossorigin="anonymous">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/inputmask@5.0.9/dist/colormask.min.css">
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
                <table class="table table-hover" id="table" style="width: 100%;cursor: pointer;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Instalasi</th>
                            <th>Kecamatan</th>
                            <th>Kelurahan/Desa</th>
                            <th>Alamat</th>
                            <th>Koordinat</th>
                            <th>Skala Pelayanan</th>
                            <th>Tahun Konstruksi</th>
                            <th>Sumber Dana</th>
                            <th>Status Keberfungsian</th>
                            <th>Keterangan Kondisi</th>
                            <th>Status Lahan</th>
                            <th>Kapasitas Desain (m3/hari)</th>
                            <th>Jenis Pengelolaan</th>
                            <th>Opsi Teknologi</th>
                            <th>Jumlah Pemanfaat Jiwa</th>
                            <th>Jumlah Rumah Terlayani</th>
                            <th>Jumlah Unit Tangki Septik</th>
                            <th>Jumlah Unit Bilik</th>
                            <th>Penyedotan Lumpur Tinja</th>
                            <th>Tanggal Update</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    @include('spald.modal')
@endsection

@push('js')
    <script src="https://cdn.datatables.net/v/bs5/dt-2.3.4/b-3.2.5/b-colvis-3.2.5/datatables.min.js"
        integrity="sha384-xG3wtUztKuiMDc6KvJmHObtCdZH2nNroJUmqIcJtoBSfXI79Cx0WXXkqU27HFe9Q" crossorigin="anonymous">
    </script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/inputmask@5.0.9/dist/jquery.inputmask.min.js"></script>
    <script src="{{ asset('assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.85.1/dist/L.Control.Locate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet.fullscreen@4.0.0/Control.FullScreen.min.js"></script>

    <script>
        const URL_INDEX = "{{ route('spalds.index') }}"
        const URL_INDEX_API = "{{ route('api.spalds.index') }}"
        var id = 0;

        $(document).ready(function() {
            const default_lat = '0.0632612';
            const default_long = '111.4862054';
            var map = L.map('map').setView([default_lat, default_long], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            L.control.locate({
                drawCircle: false,
                follow: false,
                position: 'topright',
                icon: 'fa fa-location-arrow',
                iconLoading: 'fa fa-spinner fa-spin',
            }).addTo(map);
            L.control
                .fullscreen({
                    position: 'topleft',
                    title: 'Show me the fullscreen !',
                    titleCancel: 'Exit fullscreen mode',
                    content: null,
                    forceSeparateButton: true,
                    forcePseudoFullscreen: true,
                    fullscreenElement: false
                })
                .addTo(map);

            var marker = L.marker([default_lat, default_long], {
                draggable: 'true'
            }).addTo(map);

            map.on('locationfound', function(e) {
                var latitude = e.latlng.lat;
                var longitude = e.latlng.lng;
                marker.setLatLng([latitude, longitude]);
                // map.setView([latitude, longitude], 8);
                map.fitBounds([
                    [latitude, longitude]
                ]);
                // console.log(`lat : ${latitude}, Log : ${longitude}`);
                fill_input(latitude, longitude)
            });

            map.on('click', function(e) {
                var latitude = e.latlng.lat;
                var longitude = e.latlng.lng;
                marker.setLatLng([latitude, longitude]);
                fill_input(latitude, longitude);
            });

            marker.on('dragend', function(event) {
                var marker = event.target;
                var position = marker.getLatLng();
                var latitude = position.lat;
                var longitude = position.lng;
                fill_input(latitude, longitude)
                // console.log(`lat : ${latitude}, Log : ${longitude}`);
            });

            $('#modal_map').on('shown.bs.modal', function() {
                let lat = $('#latitude').val()
                let long = $('#longitude').val()
                if (lat == '' || long == '') {
                    set_map(default_lat, default_long)
                } else {
                    set_map(lat, long)
                }
                map.invalidateSize(); // Refresh the map to fill the modal
            });

            function fill_input(lat, long) {
                $('#latitude').val(lat)
                $('#longitude').val(long)
                show_message(`Location Set to : ${lat} , ${long}!`, 'info')
            }

            function set_map(lat, long) {
                try {
                    marker.setLatLng([lat, long]);
                    // map.setView([lat, long], 15);
                    map.fitBounds([
                        [lat, long]
                    ]);
                } catch (error) {
                    console.log(error);
                }
            }

            $('#btn_map').click(function() {
                $('#modal_map').modal('show')
            })


            $('#tahun_konstruksi').datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years",
                autoclose: true
            });

            $('#tahun_beroperasi').datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years",
                autoclose: true
            });

            $('#tanggal_update').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true
            });

            $('.mask_angka').inputmask({
                alias: 'numeric',
                groupSeparator: '.',
                autoGroup: true,
                digits: 0,
                rightAlign: false,
                removeMaskOnSubmit: true,
                autoUnmask: true,
                min: 0,
            });

            $('.mask_decimal').inputmask({
                alias: 'numeric',
                groupSeparator: '.',
                autoGroup: true,
                digits: 1,
                rightAlign: false,
                removeMaskOnSubmit: true,
                autoUnmask: true,
                digitsOptional: false,
                min: 0,
            });

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

            var table = $('#table').DataTable({
                rowId: 'id',
                serverSide: true,
                ajax: URL_INDEX_API,
                dom: "<'dt--top-section'<'row mb-2'<'col-sm-12 col-md-6 d-flex justify-content-md-start justify-content-center'B><'col-sm-12 col-md-6 d-flex justify-content-md-end justify-content-center mt-md-0'f>>>" +
                    "<'table-responsive'tr>" +
                    "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
                oLanguage: {
                    "sSearchPlaceholder": "Search...",
                    "sLengthMenu": "Results :  _MENU_",
                },
                lengthMenu: [
                    [10, 50, 100, 500, 1000],
                    ['10 rows', '50 rows', '100 rows', '500 rows', '1000 rows']
                ],
                language: {
                    buttons: {
                        pageLength: {
                            _: '%d rows',
                        },
                        colvis: {
                            _: 'Colvis',
                        }
                    }
                },
                pageLength: 10,
                lengthChange: false,
                order: [
                    [21, "desc"]
                ],
                columns: [{
                    data: 'id',
                    className: "text-center",
                    searchable: false,
                    width: '30px',
                    sortable: false,
                    render: function(data, type, row, meta) {
                        return `<input type="checkbox" name="id[]" value="${data}" class="form-check-input child-chk">`
                    }
                }, {
                    data: "nama",
                    className: 'text-start',
                }, {
                    data: "kecamatan.nama",
                    className: 'text-start',
                    sortable: false,
                }, {
                    data: "kelurahan.nama",
                    className: 'text-start',
                    sortable: false,
                }, {
                    data: "alamat",
                    className: 'text-start',
                }, {
                    data: "lat",
                    className: 'text-start',
                    render: function(data, type, row, meta) {
                        return `${data||''} ${row.long||''}`
                    }
                }, {
                    data: "skala",
                    className: 'text-center',
                }, {
                    data: "tahun_konstruksi",
                    className: 'text-center',
                }, {
                    data: "sumber",
                    className: 'text-center',
                }, {
                    data: "status_keberfungsian",
                    className: 'text-center',
                }, {
                    data: "kondisi",
                    className: 'text-center',
                }, {
                    data: "status_lahan",
                }, {
                    data: "kapasitas",
                    className: 'text-center',
                }, {
                    data: "jenis",
                    className: 'text-start',
                }, {
                    data: "teknologi",
                    className: 'text-start',
                }, {
                    data: "pemanfaat_jiwa",
                    className: 'text-start',
                }, {
                    data: "rumah_terlayani",
                    className: 'text-start',
                }, {
                    data: "unit_tangki",
                    className: 'text-start',
                }, {
                    data: "unit_bilik",
                    className: 'text-start',
                }, {
                    data: "status_penyedotan",
                    className: 'text-start',
                }, {
                    data: "tanggal_update",
                    className: 'text-start',
                }, {
                    data: "id",
                    className: 'text-start',
                    visible: false,
                }, ],
                buttons: [{
                        text: '<i class="fas fa-plus me-1"></i>Add',
                        className: 'btn btn-sm btn-info',
                        attr: {
                            'data-bs-toggle': 'tooltip',
                            'title': 'Add Data'
                        },
                        action: function(e, dt, node, config) {
                            modal_add()
                        }
                    },
                    {
                        extend: "pageLength",
                        attr: {
                            'data-bs-toggle': 'tooltip',
                            'title': 'Page Length'
                        },
                        className: 'btn btn-sm'
                    }, {
                        extend: "colvis",
                        attr: {
                            'data-toggle': 'tooltip',
                            'title': 'Column Visible'
                        },
                        className: 'btn btn-sm btn-primary'
                    }, {
                        text: '<i class="fa fa-tools"></i> Action',
                        className: 'btn btn-sm btn-warning bs-tooltip',
                        attr: {
                            'data-bs-toggle': 'tooltip',
                            'title': 'Action'
                        },
                        extend: 'collection',
                        autoClose: true,
                        buttons: [{
                            text: 'Refresh Data',
                            className: 'dt-button btn-sm',
                            action: function(e, dt, node, config) {
                                table.ajax.reload()
                            }
                        }, {
                            text: 'Import Data',
                            className: 'dt-button btn-sm',
                            action: function(e, dt, node, config) {
                                importData()
                            }
                        }, {
                            text: 'Delete Selected Data',
                            className: 'dt-button btn-sm',
                            action: function(e, dt, node, config) {
                                deleteBatch()
                            }
                        }, ]
                    },
                ],
                headerCallback: function(e, a, t, n, s) {
                    e.getElementsByTagName("th")[0].innerHTML =
                        '<input type="checkbox" class="form-check-input chk-parent">'
                },
            });

            multiCheck(table)

            function deleteBatch() {
                if (selected()) {
                    confirmation('Delete Selected?', function(confirm) {
                        if (confirm) {
                            selectedIds = $('input[name="id[]"]:checked')
                                .map(function() {
                                    return $(this).val();
                                }).get();
                            $.ajax({
                                url: URL_INDEX_API,
                                type: "DELETE",
                                data: {
                                    ids: selectedIds,
                                },
                                success: function(res) {
                                    table.ajax.reload();
                                    show_message(res.message, 'success')
                                },
                                error: function(xhr) {
                                    show_message(xhr.responseJSON.message || 'Error!')
                                }
                            });
                        }
                    })
                }
            }

            $('#modal_form').on('shown.bs.modal', function() {
                $('#nama').focus()
            });

            $('#form').submit(function(e) {
                e.preventDefault()
                let kec = kecamatan.getValue(true)
                if (kec == null || kec == '') {
                    kecamatan.showDropdown(true)
                    show_message('Select Lokasi Kecamatan!')
                    return
                }
                let kel = kelurahan.getValue(true)
                if (kel == null || kel == '') {
                    kelurahan.showDropdown(true)
                    show_message('Select Lokasi Desa!')
                    return
                }
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: $(this).serializeArray(),
                    beforeSend: function() {},
                    success: function(res) {
                        table.ajax.reload()
                        show_message(res.message, 'success')
                        $('#modal_form').modal('hide');
                    },
                    error: function(xhr, status, error) {
                        show_message(xhr.responseJSON.message || 'Error!')
                    }
                });
            })

            $('#table tbody').on('click', 'tr td:not(:first-child)', function() {
                data = table.row(this).data()
                id = data.id

                $('#nama').val(data.nama)
                $('#alamat').val(data.alamat)
                kecamatan.removeActiveItems();
                if (data.kecamatan_id != null) {
                    kecamatan.setChoiceByValue(data.kecamatan_id.toString());
                    $('#kecamatan_id').trigger('change')
                }

                kelurahan.removeActiveItems();
                if (data.kelurahan_id != null) {
                    kelurahan.setChoices([{
                        value: data.kelurahan_id.toString(),
                        label: data.kelurahan.nama,
                        selected: false
                    }], 'value', 'label', true);
                    kelurahan.setChoiceByValue(data.kelurahan_id.toString());
                }
                $('#latitude').val(data.lat)
                $('#longitude').val(data.long)
                $('#skala').val(data.skala).change()
                $('#tahun_konstruksi').val(data.tahun_konstruksi)
                $('#sumber').val(data.sumber).change()
                $('#status_keberfungsian').val(data.status_keberfungsian).change()
                $('#kondisi').val(data.kondisi).change()
                $('#status_lahan').val(data.status_lahan).change()
                $('#kapasitas').val(data.kapasitas)
                $('#jenis').val(data.jenis).change()
                $('#teknologi').val(data.teknologi).change()
                $('#pemanfaat_jiwa').val(data.pemanfaat_jiwa)
                $('#rumah_terlayani').val(data.rumah_terlayani)
                $('#unit_tangki').val(data.unit_tangki)
                $('#unit_bilik').val(data.unit_bilik)
                $('#status_penyedotan').val(data.status_penyedotan).change()
                if (data.tanggal_update) {
                    $('#tanggal_update').datepicker('setDate', data.tanggal_update);
                } else {
                    $('#tanggal_update').datepicker('clearDates');
                }

                $('#form').attr('action', `${URL_INDEX_API}/${id}`)
                $('#form').attr('method', 'PUT')
                $('#modal_title').html('<i class="fas fa-edit me-1"></i>Edit Data')
                $('#modal_form').modal('show')

            });


            function modal_add() {
                $('#form').attr('action', URL_INDEX_API)
                $('#form').attr('method', 'POST')

                $('#nama').val('')
                $('#alamat').val('')
                kecamatan.removeActiveItems();
                kecamatan.setChoiceByValue('');
                $('#kecamatan_id').trigger('change')
                kelurahan.removeActiveItems();
                kelurahan.setChoiceByValue('');
                $('#latitude').val('')
                $('#longitude').val('')
                $('#skala').val('').change()
                $('#tahun_konstruksi').val('')
                $('#sumber').val('').change()
                $('#status_keberfungsian').val('').change()
                $('#kondisi').val('').change()
                $('#status_lahan').val('').change()
                $('#kapasitas').val(0)
                $('#jenis').val('').change()
                $('#teknologi').val('').change()
                $('#pemanfaat_jiwa').val(0)
                $('#rumah_terlayani').val(0)
                $('#unit_tangki').val(0)
                $('#unit_bilik').val(0)
                $('#status_penyedotan').val('').change()
                $('#tanggal_update').val('')


                $('#modal_title').html('<i class="fas fa-plus me-1"></i>Add Data')
                $('#modal_form').modal('show')
            }

            function importData() {
                $('#form_import')[0].reset()
                $('#modal_import').modal('show')
            }

            $('#form_import').submit(function(e) {
                e.preventDefault()
                let form = $(this)[0];
                let formData = new FormData(form);
                $.ajax({
                    url: "{{ route('api.spalds.import') }}",
                    type: "POST",
                    contentType: false,
                    processData: false,
                    data: formData,
                    beforeSend: function() {},
                    success: function(res) {
                        table.ajax.reload()
                        show_message(res.message, 'success')
                        $('#modal_import').modal('hide');
                    },
                    error: function(xhr, status, error) {
                        show_message(xhr.responseJSON.message || 'Error!')
                    }
                });
            })

        })
    </script>
@endpush
