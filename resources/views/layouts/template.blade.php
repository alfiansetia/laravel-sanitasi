@php
    $user = auth()->user();
    $t = $title ?? '-';
    $td = $title_desc ?? '';
    $company = config('company.footer');
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $t }}{{ $td ? " ($td)" : '' }} - {{ $company }}</title>

    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/png">


    <link rel="stylesheet" crossorigin href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" crossorigin href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" crossorigin href="{{ asset('assets/compiled/css/iconly.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/@fortawesome/fontawesome-free/css/all.min.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css"
        integrity="sha512-O03ntXoVqaGUTAeAmvQ2YSzkCvclZEcPQu1eqloPaHfJ5RuNGiS4l+3duaidD801P50J28EHyonCV06CUlTSag=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        label.required::after {
            content: " *";
            color: red;
            cursor: help;
        }

        label.required:hover::after {
            content: " * (Required)";
        }
    </style>
    @stack('css')
</head>

<body>
    <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>

    <div id="app">
        @include('components.sidebar')

        <div id="main" class="layout-navbar navbar-fixed">
            @include('components.header')

            <div id="main-content" class="pt-1">

                <div class="page-heading mb-2">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-12 col-md-8 order-md-1 order-last">
                                <h3>{{ $t }}{{ $td ? " ($td)" : '' }}</h3>
                            </div>
                            <div class="col-12 col-md-4 order-md-2 order-first">
                                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                    <ol class="breadcrumb">

                                        @if ($t == 'Dashboard')
                                            <li class="breadcrumb-item {{ $t == 'Dashboard' ? 'active' : '' }}">
                                                Dashboard
                                            </li>
                                        @else
                                            <li class="breadcrumb-item ">
                                                <a href="{{ route('home') }}">Dashboard</a>
                                            </li>
                                            <li class="breadcrumb-item active" aria-current="page">{{ $t }}
                                            </li>
                                        @endif
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>

                @yield('content')

            </div>

            @include('components.footer')
        </div>
    </div>
    <script src="{{ asset('assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>

    <script src="{{ asset('assets/compiled/js/app.js') }}"></script>

    <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"
        integrity="sha512-Zq9o+E00xhhR/7vJ49mxFNJ0KQw1E1TMWkPTxrWcnpfEFDEXgUiwJHIKit93EW/XxE31HSI5GEOW06G6BF1AtA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/block-ui@2.70.1/jquery.blockUI.min.js"></script>

    <script>
        function multiCheck(tb_var) {
            tb_var.on("change", ".chk-parent", function() {
                    var e = $(this).closest("table").find("td:first-child .child-chk"),
                        a = $(this).is(":checked");
                    $(e).each(function() {
                        a ? ($(this).prop("checked", !0), $(this).closest("tr").addClass("active")) : ($(this)
                            .prop("checked", !1), $(this).closest("tr").removeClass("active"))
                    })
                }),
                tb_var.on("change", "tbody tr .new-control", function() {
                    $(this).parents("tr").toggleClass("active")
                })
        }

        function show_message(message = 'Kesalahan tidak diketahui!', type = 'error') {
            if (type == 'success') {
                iziToast.success({
                    title: 'Success',
                    message: message,
                    position: 'topCenter',
                });
            } else if (type == 'warning') {
                iziToast.warning({
                    title: 'Caution',
                    message: message,
                    position: 'topCenter',
                });
            } else if (type == 'info') {
                iziToast.info({
                    title: 'Hello',
                    message: message,
                    position: 'topCenter',
                });
            } else {
                iziToast.error({
                    title: 'Error',
                    message: message,
                    position: 'topCenter',
                });
            }

        }

        function confirmation(message = '', callback) {
            iziToast.question({
                timeout: 0,
                close: false,
                overlay: true,
                displayMode: 'once',
                id: 'question',
                zindex: 999,
                title: 'Konfirmasi',
                message: message,
                position: 'center',
                buttons: [
                    ['<button><i class="fas fa-thumbs-up mr-1"></i><b>Yes</b></button>', function(instance,
                        toast) {
                        instance.hide({
                            transitionOut: 'fadeOut'
                        }, toast, 'button');
                        if (callback) callback(true);
                    }, true],
                    ['<button><i class="fas fa-thumbs-down mr-1"></i>Cancel</button>', function(instance,
                        toast) {
                        instance.hide({
                            transitionOut: 'fadeOut'
                        }, toast, 'button');
                        if (callback) callback(false);
                    }]
                ]
            });
        }

        function selected() {
            let id = $('input[name="id[]"]:checked').length;
            if (id <= 0) {
                show_message("No Selected Data!")
                return false
            } else {
                return true
            }
        }

        function hrg(x) {
            return parseInt(x).toLocaleString('id-ID')
        }

        function hrd1(x) {
            if (x === null || x === undefined || x === '') return '0';
            return parseFloat(x).toLocaleString('id-ID', {
                minimumFractionDigits: 1,
                maximumFractionDigits: 1
            });
        }

        function hrd2(x) {
            if (x === null || x === undefined || x === '') return '0';
            return parseFloat(x).toLocaleString('id-ID', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        $(document).ready(function() {
            $(document).ajaxStart(function() {
                $.blockUI({
                    message: '<img src="{{ asset('images/loading.gif') }}" width="20px" height="20px" /> Just a moment...',
                    baseZ: 2000,
                });
            }).ajaxStop($.unblockUI);

            bsCustomFileInput.init()

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        })
    </script>

    @stack('js')

    @if (session()->has('success'))
        <script>
            show_message("{{ session('success') }}", "success");
        </script>
    @elseif(session()->has('error'))
        <script>
            show_message("{{ session('error') }}");
        </script>
    @endif

</body>

</html>
