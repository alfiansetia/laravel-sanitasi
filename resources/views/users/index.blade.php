@extends('layouts.template')
@push('css')
    <link href="https://cdn.datatables.net/v/bs5/dt-2.3.4/b-3.2.5/datatables.min.css" rel="stylesheet"
        integrity="sha384-fyCqW8E+q5GvWtphxqXu3hs1lJzytfEh6S57wLlfvz5quj6jf5OKThV1K9+Iv8Xz" crossorigin="anonymous">
@endpush

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-body">
                <table class="table table-hover" id="table" style="width: 100%;cursor: pointer;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    @include('users.modal')
@endsection

@push('js')
    <script src="https://cdn.datatables.net/v/bs5/dt-2.3.4/b-3.2.5/datatables.min.js"
        integrity="sha384-J9F84i7Emwbp64qQsBlK5ypWq7kFwSOGFfubmHHLjVviEnDpI5wpj+nNC3napXiF" crossorigin="anonymous">
    </script>
    <script>
        const URL_INDEX = "{{ route('users.index') }}"
        const URL_INDEX_API = "{{ route('api.users.index') }}"
        var id = 0;

        $(document).ready(function() {

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
                        data: "name",
                        className: 'text-start',
                    },
                    {
                        data: "email",
                        className: 'text-start',
                    }, {
                        data: "role",
                        className: 'text-center',
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
                $('#name').focus()
            });

            $('#form').submit(function(e) {
                e.preventDefault()
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
                $('#name').val(data.name)
                $('#email').val(data.email)
                $('#role').val(data.role).change()
                $('#password').val('')
                $('#password').prop('required', false)
                $('#password_helper').show()

                $('#form').attr('action', `${URL_INDEX_API}/${id}`)
                $('#form').attr('method', 'PUT')
                $('#modal_title').html('<i class="fas fa-pencil me-1"></i>Edit Data')
                $('#modal_form').modal('show')

            });


            function modal_add() {
                $('#form').attr('action', URL_INDEX_API)
                $('#form').attr('method', 'POST')
                $('#name').val('')
                $('#email').val('')
                $('#role').val('').change()
                $('#password').val('')
                $('#password').prop('required', true)
                $('#password_helper').hide()

                $('#modal_title').html('<i class="fas fa-plus me-1"></i>Add Data')
                $('#modal_form').modal('show')
            }

        })
    </script>
@endpush
