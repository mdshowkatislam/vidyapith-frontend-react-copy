<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- bootstrap 5.0.2 min.css -->
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-5.min.css') }}"> --}}
    <!-- fontawesome -->
    {{-- <link rel="stylesheet" href="{{ asset('assets/fonts/font-awesome_6.4.2.min.css') }}"> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.6.0/css/all.min.css" integrity="sha512-ykRBEJhyZ+B/BIJcBuOyUoIxh0OfdICfHPnPfBy7eIiyJv536ojTCsgX8aqrLQ9VJZHGz4tvYyzOM0lkgmQZGw==" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}
    <!-- google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;700&family=Noto+Sans+Bengali:wght@400;500;600&family=Public+Sans:wght@400;500;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet"> --}}
    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
        crossorigin="anonymous"></script>

    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" crossorigin="anonymous"></script>

    <script src="{{ asset('plugin/sweetalert/sweetalert.js') }}"></script>

    <link href="{{ asset('plugin/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- custom css start -->
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}" />
    <link id="themeStylesheet" rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <link id="themeStylesheet" rel="stylesheet" href="{{ asset('assets/css/dark.css') }}" />
    <link href="{{ asset('frontend/noipunno/css/app.css') }}" rel="stylesheet" />
    <title>Bidyapith Dashboard</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/icons/favicon.ico') }}">

</head>

<style>
    
    .sweet-alert h2 {
        font-size: 16px !important;
    }

    .sweet-alert p {
        font-size: 14px !important;
    }

    .sa-button-container .confirm {
        padding: 5px 10px !important;
        font-size: 14px !important;
    }

    .sa-button-container .cancel {
        padding: 5px 10px !important;
        font-size: 14px !important;
    }

    .select2-container .select2-selection--single {
        height: 36px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px;
        padding-left: 12px;
    }

    .select2-container--default .select2-results__option--selected {
        background: none;
        color: #000;
        font-size: 16px;
    }

    .select2-results__option--selectable {
        background: none;
        color: #000;
        font-size: 16px;
        padding-top: 10px;
        padding-bottom: 10px;
        padding-left: 12px;
    }

    .delete-button-yes {
        display: flex;
        padding: 14px 112px 14px 111px;
        justify-content: center;
        align-items: center;
        border-radius: 6px;
        background: rgba(66, 143, 146, 1);
        margin: 0 auto;
        width: 20%;
        color: #fff;
    }

    .delete-button-yes:hover {
        background: #428F92;
        color: #fff;
    }

    .sa-confirm-button-container {
        width: 20% !important;
    }

    .sa-confirm-button-container .btn-danger {
        width: 100% !important;
    }
</style>
<body>
    <!-- topnav -->
    @include('frontend.layouts.top-nav')

    <!-- navbar -->
    @include('frontend.layouts.nav-menu')

    <div class="content">
        @yield('content')
    </div>

    <!-- footer start -->
    @include('frontend.layouts.footer')

    <!-- bootstrap 5.0.2 min.js -->
    <script src="{{ asset('assets/js/bootstrap-5.0.2.bundle.min.js') }}"></script>
 
    <!-- data table  -->
    <script src="{{ asset('assets/js/datatables.net_1.13.6_js_jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/js/datatables.net_1.13.6_js_dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('plugin/jquery_bangla/jquery.bangla.js') }}"></script>

    @yield('custom-js')

    <script>
        @if (Session::get('alert-type') == 'info')
            toastr.info("{{ Session::get('message') }}");
        @endif
        @if (Session::get('alert-type') == 'warning')
            toastr.warning("{{ Session::get('message') }}");
        @endif
        @if (Session::get('alert-type') == 'success')
            toastr.success("{{ Session::get('message') }}");
        @endif
        @if (Session::get('alert-type') == 'error')
            toastr.error("{{ Session::get('message') }}");
        @endif

        @if (Session::get('alert-swal-type') == 'info')
            swal({
                title: "{{ Session::get('message') }}",
                type: "info",
                showCancelButton: false,
                confirmButtonText: "ধন্যবাদ",
            });
        @endif
        @if (Session::get('alert-swal-type') == 'warning')
            swal({
                title: "{{ Session::get('message') }}",
                type: "warning",
                showCancelButton: false,
                confirmButtonText: "ধন্যবাদ",
            });
        @endif
        @if (Session::get('alert-swal-type') == 'success')
            swal({
                title: "{{ Session::get('message') }}",
                type: "success",
                showCancelButton: false,
                confirmButtonText: "ধন্যবাদ",
            });
        @endif
        @if (Session::get('alert-swal-type') == 'error')
            swal({
                title: "{{ Session::get('message') }}",
                type: "error",
                showCancelButton: false,
                confirmButtonText: "ধন্যবাদ",
            });
        @endif
    </script>

    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
            
            $(document).on('click', '.delete_module', function() {
                var actionTo = $(this).attr('data-route');
                var token = $(this).attr('data-token');
                var id = $(this).attr('data-id');

                swal({
                        title: "আপনি কি তথ্যটি মুছে ফেলতে চান?",
                        type: "warning",
                        showCancelButton: true,
                        cancelButtonText: "না", 
                        cancelButtonClass: "delete-button-yes",
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "হ্যাঁ",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: actionTo,
                                type: 'post',
                                data: {
                                    id: id,
                                    _token: token
                                },
                                success: function(data) {
                                    if (data.status == 'success') {
                                        swal({
                                                html: true,
                                                title: data.message,
                                                type: "success",
                                                showCancelButton: false,
                                                confirmButtonText: "ধন্যবাদ",
                                            },
                                            function(isConfirm) {
                                                if (isConfirm) {
                                                    location.reload();
                                                }
                                            });
                                    } else {
                                        swal({
                                            html: true,
                                            title: data.message,
                                            type: "error",
                                            showCancelButton: false,
                                            confirmButtonText: "ধন্যবাদ",
                                        });
                                    }
                                }
                            });
                        } else {
                            swal("Cancelled", "বাতিল করা হয়েছে।", "success");
                        }
                    });
                return false;
            });

           

        });

        $(function() {
            $('#n_dataTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                // "ordering": false,
                "info": true,
                "autoWidth": true,
            });

            $('.select2').select2();
        });
    </script>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-DWQ7HV5S2J"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-DWQ7HV5S2J');
    </script>
 
</body>

</html>
