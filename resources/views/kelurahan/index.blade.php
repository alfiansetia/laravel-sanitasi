@extends('layouts.template')
@push('css')
    <link href="https://cdn.datatables.net/v/bs5/dt-2.3.4/b-3.2.5/b-colvis-3.2.5/datatables.min.css" rel="stylesheet"
        integrity="sha384-b7CCWUkHYYyObRWK8dDxH6PCbeH3SHTbH+TzwIoEUU/Ol75XipyzcYbfdNWmNWFF" crossorigin="anonymous">

    <link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css') }}">
@endpush

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-body">
                <table class="table table-hover" id="table" style="width: 100%;cursor: pointer;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode Kelurahan</th>
                            <th>Nama Kelurahan</th>
                            <th>Nama Kecamatan</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    @include('kelurahan.modal')
@endsection

@push('js')
    <script src="https://cdn.datatables.net/v/bs5/dt-2.3.4/b-3.2.5/b-colvis-3.2.5/datatables.min.js"
        integrity="sha384-xG3wtUztKuiMDc6KvJmHObtCdZH2nNroJUmqIcJtoBSfXI79Cx0WXXkqU27HFe9Q" crossorigin="anonymous">
    </script>
    <script src="{{ asset('assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>
    <script>
        const URL_INDEX = "{{ route('kelurahans.index') }}"
        const URL_INDEX_API = "{{ route('api.kelurahans.index') }}"
        var id = 0;

        $(document).ready(function() {
            var kecamatan = new Choices(document.getElementById('kecamatan_id'), {
                allowHTML: true,
                searchEnabled: true,
                removeItemButton: true,
            });

            refreshKecamatanOptions();

            function refreshKecamatanOptions() {
                $.ajax({
                    url: "{{ route('api.kecamatans.index') }}",
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        kecamatan.clearChoices();
                        const data = [{
                            value: '',
                            label: 'Select Kecamatan',
                            disabled: true,
                            selected: true,
                        }].concat(
                            (response.data ?? response).map(item => ({
                                value: item.id,
                                label: item.nama,
                            }))
                        );

                        kecamatan.setChoices(data, 'value', 'label', true);
                    },
                    error: function(xhr) {
                        console.error('Gagal memuat data kecamatan:', xhr.responseText);
                    }
                });
            }

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
                    [4, "desc"]
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
                        data: "kode",
                        className: 'text-start',
                    }, {
                        data: "nama",
                        className: 'text-start',
                    },
                    {
                        data: "kecamatan.nama",
                        className: 'text-start',
                        sortable: false,
                    }, {
                        data: "id",
                        className: 'text-start',
                        visible: false,
                    },
                ],
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
                $('#kode').focus()
            });

            $('#form').submit(function(e) {
                e.preventDefault()
                let kec = kecamatan.getValue(true)
                if (kec == null || kec == '') {
                    kecamatan.showDropdown(true)
                    show_message('Select Kecamatan!')
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
                $('#kode').val(data.kode)
                $('#nama').val(data.nama)
                kecamatan.removeActiveItems();
                if (data.kecamatan_id) {
                    kecamatan.setChoiceByValue(data.kecamatan_id);
                }

                $('#form').attr('action', `${URL_INDEX_API}/${id}`)
                $('#form').attr('method', 'PUT')
                $('#modal_title').html('<i class="fas fa-edit me-1"></i>Edit Data')
                $('#modal_form').modal('show')

            });


            function modal_add() {
                $('#form').attr('action', URL_INDEX_API)
                $('#form').attr('method', 'POST')
                $('#kode').val('')
                $('#nama').val('')
                kecamatan.removeActiveItems();
                kecamatan.setChoiceByValue('')
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
                        refreshKecamatanOptions()
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
