@extends('layouts.template')
@push('css')
    <link href="https://cdn.datatables.net/v/bs5/dt-2.3.4/b-3.2.5/datatables.min.css" rel="stylesheet"
        integrity="sha384-fyCqW8E+q5GvWtphxqXu3hs1lJzytfEh6S57wLlfvz5quj6jf5OKThV1K9+Iv8Xz" crossorigin="anonymous">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/inputmask@5.0.9/dist/colormask.min.css">
    <link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css') }}">
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
                            <th>Nama TPA</th>
                            <th>Kecamatan</th>
                            <th>Kelurahan</th>
                            <th>Koordinat</th>
                            <th>Sumber Anggaran</th>
                            <th>Tahun Konstruksi</th>
                            <th>Tahun Beroperasi</th>
                            <th>Rencana Umur Beroperasi (TH)</th>
                            <th>Kecamatan Terlayani</th>
                            <th>Luas Sarana (ha)</th>
                            <th>Luas Sel (ha)</th>
                            <th>Jenis Pengelola</th>
                            <th>Kondisi TPA</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    @include('tpa.modal')
@endsection

@push('js')
    <script src="https://cdn.datatables.net/v/bs5/dt-2.3.4/b-3.2.5/datatables.min.js"
        integrity="sha384-J9F84i7Emwbp64qQsBlK5ypWq7kFwSOGFfubmHHLjVviEnDpI5wpj+nNC3napXiF" crossorigin="anonymous">
    </script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/inputmask@5.0.9/dist/jquery.inputmask.min.js"></script>
    <script src="{{ asset('assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>

    <script>
        const URL_INDEX = "{{ route('tpas.index') }}"
        const URL_INDEX_API = "{{ route('api.tpas.index') }}"
        var id = 0;

        $(document).ready(function() {
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

            const sumber = new Choices(document.getElementById('sumber'), {
                searchEnabled: true,
                removeItemButton: true,
                allowHTML: true,
            });

            const kecamatanTerlayani = new Choices(document.getElementById('kecamatan_terlayani'), {
                searchEnabled: true,
                removeItemButton: true,
                allowHTML: true,
            });

            const pengelola = new Choices(document.getElementById('pengelola'), {
                searchEnabled: true,
                removeItemButton: true,
                allowHTML: true,
            });

            const kondisi = new Choices(document.getElementById('kondisi'), {
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
                pageLength: 10,
                lengthChange: false,
                order: [
                    [15, "desc"]
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
                }, {
                    data: "kelurahan.nama",
                    className: 'text-start',
                }, {
                    data: "lat",
                    className: 'text-start',
                    render: function(data, type, row, meta) {
                        return `${data||''} ${row.long||''}`
                    }
                }, {
                    data: "sumber",
                    className: 'text-center',
                }, {
                    data: "tahun_konstruksi",
                    className: 'text-center',
                }, {
                    data: "tahun_beroperasi",
                    className: 'text-center',
                }, {
                    data: "rencana",
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        if (type == 'display') {
                            return hrg(data)
                        }
                        return data
                    }
                }, {
                    data: "id",
                    sortable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        if (!row.kecamatan_terlayani || !row.kecamatan_terlayani.length)
                            return '-';
                        return row.kecamatan_terlayani
                            .map(item => item.kecamatan?.nama ?? '')
                            .filter(n => n !== '')
                            .join(', ');
                    }
                }, {
                    data: "luas_sarana",
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        if (type == 'display') {
                            return hrg(data)
                        }
                        return data
                    }
                }, {
                    data: "luas_sel",
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        if (type == 'display') {
                            return hrg(data)
                        }
                        return data
                    }
                }, {
                    data: "pengelola",
                    className: 'text-start',
                    render: function(data, type, row, meta) {
                        if (type == 'display') {
                            return `${data} ${row.pengelola_desc||''}`
                        }
                        return data
                    }
                }, {
                    data: "kondisi",
                    className: 'text-start',
                }, {
                    data: "pengelola_desc",
                    className: 'text-start',
                    visible: false,
                }, {
                    data: "id",
                    className: 'text-start',
                    visible: false,
                }, ],
                buttons: [{
                        text: '<i class="fas fa-plus me-1"></i>Add',
                        className: 'btn btn-sm btn-info',
                        attr: {
                            'data-toggle': 'tooltip',
                            'title': 'Add Data'
                        },
                        action: function(e, dt, node, config) {
                            modal_add()
                        }
                    },
                    {
                        extend: "pageLength",
                        attr: {
                            'data-toggle': 'tooltip',
                            'title': 'Page Length'
                        },
                        className: 'btn btn-sm'
                    }, {
                        text: '<i class="fa fa-tools"></i> Action',
                        className: 'btn btn-sm btn-warning bs-tooltip',
                        attr: {
                            'data-toggle': 'tooltip',
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
                let sum = sumber.getValue(true)
                if (sum == null || sum == '') {
                    sumber.showDropdown(true)
                    show_message('Select Sumber Anggaran!')
                    return
                }
                let peng = pengelola.getValue(true)
                if (peng == null || peng == '') {
                    pengelola.showDropdown(true)
                    show_message('Select Jenis Pengelola!')
                    return
                }
                let kon = kondisi.getValue(true)
                if (kon == null || kon == '') {
                    kondisi.showDropdown(true)
                    show_message('Select Kondisi TPA!')
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
                sumber.removeActiveItems();
                sumber.setChoiceByValue(data.sumber);
                $('#tahun_konstruksi').datepicker('setDate', new Date(data.tahun_konstruksi, 0, 1));
                $('#tahun_beroperasi').datepicker('setDate', new Date(data.tahun_beroperasi, 0, 1));
                $('#rencana').val(data.rencana)
                kecamatanTerlayani.removeActiveItems();
                if (Array.isArray(data.kecamatan_terlayani_ids)) {
                    data.kecamatan_terlayani_ids.forEach(id => {
                        kecamatanTerlayani.setChoiceByValue(id.toString());
                    });
                }
                $('#luas_sarana').val(data.luas_sarana)
                $('#luas_sel').val(data.luas_sel)
                pengelola.removeActiveItems();
                pengelola.setChoiceByValue(data.pengelola);
                $('#pengelola_desc').val(data.pengelola_desc)
                kondisi.removeActiveItems();
                kondisi.setChoiceByValue(data.kondisi);

                $('#tahun').datepicker('setDate', new Date(data.tahun, 0, 1));
                $('#lokasi').val(data.lokasi)
                $('#pagu').val(data.pagu)
                $('#jumlah').val(data.jumlah)
                $('#sumber').val(data.sumber).change()
                $('#lat').val(data.lat)
                $('#long').val(data.long)

                $('#form').attr('action', `${URL_INDEX_API}/${id}`)
                $('#form').attr('method', 'PUT')
                $('#modal_title').html('<i class="fas fa-edit me-1"></i>Edit Data')
                $('#modal_form').modal('show')

            });


            function modal_add() {
                $('#form').attr('action', URL_INDEX_API)
                $('#form').attr('method', 'POST')

                $('#nama').val('')
                kecamatan.removeActiveItems();
                kecamatan.setChoiceByValue('');
                $('#kecamatan_id').trigger('change')
                kelurahan.removeActiveItems();
                kelurahan.setChoiceByValue('');
                $('#latitude').val('')
                $('#longitude').val('')
                sumber.removeActiveItems();
                sumber.setChoiceByValue('');
                $('#tahun_konstruksi').val('');
                $('#tahun_beroperasi').val('');
                $('#rencana').val(0)
                kecamatanTerlayani.removeActiveItems();
                kecamatanTerlayani.setChoiceByValue([])
                $('#luas_sarana').val(0.0)
                $('#luas_sel').val(0.0)
                pengelola.removeActiveItems();
                pengelola.setChoiceByValue('');
                $('#pengelola_desc').val('')
                kondisi.removeActiveItems();
                kondisi.setChoiceByValue('');

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
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
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
