@extends('frontend.layouts.noipunno')
<style>
    .np-table th,
    td {
        font-size: 11px;
    }

    span.error {
        color: red;
    }

    input[type="number"]:read-only {
        cursor: normal;
        background-color: rgb(240, 240, 240);
    }
</style>
@section('content')
    <div class="dashboard-section">
        <section class="np-breadcumb-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card np-breadcrumbs-card">
                            <div class="card-body">
                                <div class="title-section">
                                    <div class="icon">
                                        <img src="{{ asset('frontend/noipunno/images/icons/linear-book.svg') }}"
                                            alt="">
                                    </div>
                                    <div class="content">
                                        <h2 class="title">শিক্ষার্থী ব্যবস্থাপনা</h2>
                                        <nav aria-label="breadcrumb">
                                            <ol class="breadcrumb np-breadcrumb">
                                                <li class="breadcrumb-item"><a href="{{ route('home') }}">
                                                        <img src="{{ asset('frontend/noipunno/images/icons/home.svg') }}"
                                                            alt="">
                                                        ড্যাশবোর্ড
                                                    </a></li>
                                                <li class="breadcrumb-item active" aria-current="page"><a
                                                        href="{{ route('student.index') }}">
                                                        শিক্ষার্থী ব্যবস্থাপনা
                                                    </a></li>
                                            </ol>
                                        </nav>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        {{-- @include('frontend.layouts.notice') --}}

        <div class="container mt-4 mb-5">
            <div class="np-teacher-list row mb-2">
                <div class="col-md-12">
                    <h2 class="np-form-title">বোর্ড রেজিস্ট্রেশনের জন্য শ্রেণি নির্বাচন করুন</h2>
                </div>
            </div>
            <div class="dashboard-section">
                <div class="head-maseter-card-container">
                    <div class="row">
                        <div class="col-lg-3 col-md-3">
                            <a href="{{ route('student.board_registration.class', 6) }}" class="head-master-card">
                                <div class="d-flex align-items-center">
                                    <h2>ষষ্ঠ শ্রেণি</h2>
                                </div>
                                <div class="icon">
                                    <img src="../assets/images/info-circle.svg" alt="">
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <a href="{{ route('student.board_registration.class', 7) }}" class="head-master-card">
                                <div class="d-flex align-items-center">
                                    <h2>সপ্তম শ্রেণি</h2>
                                </div>
                                <div class="icon">
                                    <img src="../assets/images/info-circle.svg" alt="">
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <a href="{{ route('student.board_registration.class', 8) }}" class="head-master-card">
                                <div class="d-flex align-items-center">
                                    <h2>অষ্টম শ্রেণি</h2>
                                </div>
                                <div class="icon">
                                    <img src="../assets/images/info-circle.svg" alt="">
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <a href="{{ route('student.board_registration.class', 9) }}" class="head-master-card">
                                <div class="d-flex align-items-center">
                                    <h2>নবম শ্রেণি</h2>
                                </div>
                                <div class="icon">
                                    <img src="../assets/images/info-circle.svg" alt="">
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>




    </div>
    </div>
    </div>

    <script>
        $('#checkAll').click(function() {
            $(':checkbox.checkItem').prop('checked', this.checked);
        });
        $(document).on('change', '#shift', function() {
            $('#class').trigger('change');
        });
        $(document).on('change', '#branch', function() {
            var branch_id = $('#branch').val();
            var eiin = {{ auth()->user()->eiin }};
            $.ajax({
                url: "{{ route('branch_wise_version') }}",
                type: "GET",
                data: {
                    branch_id: branch_id,
                    eiin: eiin,
                },
                success: function(data) {
                    $('#version').html('');
                    $('#shift').html('');

                    var vselected = "{{ @$editData->version }}";
                    var sselected = "{{ @$editData->shift }}";

                    if (data.versions) {
                        $.each(data.versions, function(index, category) {
                            $('#version').append('<option value ="' + category.uid + '"' + ((
                                    category
                                    .uid == vselected) ? ('selected') : '') + '>' + category
                                .version_name +
                                '</option>');
                        });
                        $('#version').trigger('change');
                    }
                    if (data.shifts) {
                        $.each(data.shifts, function(index, category) {
                            $('#shift').append('<option value ="' + category.uid + '"' + ((
                                    category
                                    .uid == sselected) ? ('selected') : '') + '>' + category
                                .shift_name +
                                '</option>');
                        });
                        $('#shift').trigger('change');
                    }
                }

            });
        });
        $(function() {
            $('#branch').trigger('change');
        });
    </script>
    <script>
        $(document).on('change', '#class', function() {
            var class_id = $('#class').val();
            var branch_id = $('#branch').val();
            var shift_id = $('#shift').val();
            var version_id = $('#version').val();
            var eiin = {{ auth()->user()->eiin }};
            $.ajax({
                url: "{{ route('class_wise_section') }}",
                type: "GET",
                data: {
                    class_id: class_id,
                    branch_id: branch_id,
                    shift_id: shift_id,
                    eiin: eiin,
                    version_id: version_id
                },
                success: function(data) {
                    // $('#section_id').html('<option value ="">Please Select</option>');
                    $('#section').html('');
                    var selected = "{{ @$editData->section }}";

                    if (data.sections) {
                        $.each(data.sections, function(index, category) {
                            $('#section').append('<option value ="' + category
                                .uid + '"' + ((category.uid == selected) ? ('selected') :
                                    '') + '>' + category.section_name +
                                '</option>');
                        });
                        $('#section').trigger('change');
                    }
                }
            });
        });
    </script>
@endsection
