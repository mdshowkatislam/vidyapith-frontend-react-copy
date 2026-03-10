@extends('frontend.layouts.noipunno')

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

        <section class="np-teacher-list mt-4">

            <div class="container">
                <div class="row mb-4">
                    <div class="col-md-8">
                        <h2 class="np-form-title">শিক্ষার্থীর তালিকা (মোট: {{ en2bn($students->total()) }}) - শিক্ষাবর্ষ ২০২৪</h2>
                    </div>

                    <div class="col-md-4 np-student-form-download-btn">
                        <a class="np-route" href="{{ route('student.add') }}">
                            <p class="btn np-btn-form-submit border-0 rounded-1"><i class="fa-solid fa-circle-plus"></i>
                                শিক্ষার্থী যুক্ত করুন</p>
                        </a>
                        {{-- <button class="np-btn np-btn-primary np-btn-with-icon np-student-form-download-btn">
                            <a href="{{ asset('student/eiin_students.xlsx') }}" target="_blank"
                                class="np-file-upload-demo-file-btn" download>একাধিক শিক্ষার্থী আপলোড করার নমুনা ডাউনলোড করুন</a>
                            <img src="{{ asset('frontend/noipunno/images/icons/pdf-export-icon.svg') }}" alt="">
                        </button> --}}
                        {{-- <form class="form-inline mt-5" method="get" action="{{ route('student.index') }}"
                            enctype="multipart/form-data">
                            <div class="input-group">
                                <input type="search" class="form-control" name="search" value="{{ @$search }}"
                                    class="form-control" placeholder="Search..." />
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form> --}}
                    </div>
                </div>
                <section class="np-teacher-add-form" id="edit-form">
                    <div class="np-input-form-bg">
                        <div class="container">
                            <form method="GET" action="{{ route('student.index') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="branch" class="form-label">ব্রাঞ্চ</label>
                                            <div class="input-group">
                                                <select class="form-select np-teacher-input"
                                                    aria-label="Default select example" id="branch" name="branch">
                                                    @if (count($branchs) > 1)
                                                        <option value="">ব্রাঞ্চ নির্বাচন করুন</option>
                                                    @endif
                                                    @foreach ($branchs as $branch)
                                                        <option value="{{ @$branch->uid }}"
                                                            @if (@$request_data['branch'] == $branch->uid) selected @endif>
                                                            {{ @$branch->branch_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="shift" class="form-label">শিফট</label>
                                            <div class="input-group">
                                                <select class="form-select np-teacher-input"
                                                    aria-label="Default select example" id="shift" name="shift">
                                                    <option value="">শিফট নির্বাচন করুন</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="version" class="form-label">ভার্সন</label>
                                            <div class="input-group">
                                                <select class="form-select np-teacher-input"
                                                    aria-label="Default select example" id="version" name="version">
                                                    <option value="">ভার্সন নির্বাচন করুন</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-12 mt-3">
                                        <div>
                                            <label for="class" class="form-label">শ্রেণি</label>
                                            <div class="input-group">
                                                <select class="form-select np-teacher-input"
                                                    aria-label="Default select example" id="class" name="class">
                                                    <option value="">শ্রেণি নির্বাচন করুন</option>

                                                    @foreach ($classList as $key => $class)
                                                        <option value="{{ $key }}"
                                                            {{ @$request_data['class'] == $key ? 'selected' : '' }}>
                                                            {{ $class }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @if ($errors->has('class'))
                                                <small
                                                    class="help-block form-text text-danger">{{ $errors->first('class') }}</small>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-12 mt-3">
                                        <div>
                                            <label for="section" class="form-label">সেকশন </label>
                                            <div class="input-group">
                                                <select class="form-select np-teacher-input"
                                                    aria-label="Default select example" id="section" name="section">
                                                    <option value="">সেকশন নির্বাচন করুন</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 mt-3">
                                        {{-- <div> --}}
                                        <div class="text-center">
                                            <button type="submit"
                                                class="btn btn-primary np-btn-form-submit mt-4">খুঁজুন</button>
                                        </div>
                                        {{-- </div> --}}
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
                @if (@$request_data)
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <p>
                                @if (@$request_data['branch'])
                                    ব্রাঞ্চঃ <span id="s_branch"></span>&emsp;
                                @endif
                                @if (@$request_data['shift'])
                                    শিফটঃ <span id="s_shift"></span>&emsp;
                                @endif
                                @if (@$request_data['version'])
                                    ভার্সনঃ <span id="s_version"></span>&emsp;
                                @endif
                                @if (@$request_data['class'])
                                    শ্রেণিঃ <span id="s_class"></span>&emsp;
                                @endif
                                @if (@$request_data['section'])
                                    সেকশনঃ <span id="s_section"></span>&emsp;
                                @endif
                                এর জন্য ফলাফল দেখানো হচ্ছে।
                            </p>
                        </div>
                    </div>
                @endif
                <div class="row mb-2">
                    <div class="col-md-6">
                        {{-- <h2 class="np-form-title">শিক্ষার্থীর তালিকা </h2> --}}
                    </div>

                    <div class="col-md-6 np-student-form-download-btn">
                        <form class="form-inline mt-4" method="get" action="{{ route('student.index') }}"
                            enctype="multipart/form-data">
                            <div class="input-group">
                                <input type="search" class="form-control" name="search" value="{{ @$search }}"
                                    class="form-control" placeholder="Search..." />
                                <button type="submit" class="btn btn-primary np-btn-form-search-box">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card np-card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table np-table">
                                        {{-- <table class="table np-table" id="std_dataTable"> --}}
                                        <thead>
                                            <tr>
                                                <th scope="col">শিক্ষার্থীর রোল
                                                    <!-- <span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span> -->
                                                </th>
                                                <th scope="col">শিক্ষার্থীর নাম
                                                    <!-- <span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span> -->
                                                </th>
                                                <th scope="col">শ্রেণি
                                                    <!-- <span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span> -->
                                                </th>
                                                <th scope="col">সেকশন
                                                    <!-- <span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span> -->
                                                </th>
                                                <th scope="col">শিফট
                                                    <!-- <span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span> -->
                                                </th>
                                                <th scope="col">ভার্সন
                                                    <!-- <span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span> -->
                                                </th>
                                                <th scope="col">লিঙ্গ
                                                    <!-- <span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span> -->
                                                </th>
                                                <th scope="col">ধর্ম
                                                    <!-- <span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span> -->
                                                </th>
                                                <th scope="col">অ্যাকশন</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($students as $student)
                                                <tr>
                                                    <td scope="row"><span class="icon"><img
                                                                src="{{ asset('frontend/noipunno/images/icons/user.svg') }}"
                                                                alt=""></span>{{ @$student->roll }}</td>
                                                    <td scope="row">
                                                        {{ @$student->studentInfo->student_name_en ?? @$student->studentInfo->student_name_bn }}
                                                    </td>
                                                    <td scope="row">
                                                        @if (@$student->classRoom->class_id == 6)
                                                            Six
                                                        @elseif(@$student->classRoom->class_id == 7)
                                                            Seven
                                                        @elseif(@$student->classRoom->class_id == 8)
                                                            Eight
                                                        @elseif(@$student->classRoom->class_id == 9)
                                                            Nine
                                                        @elseif(@$student->classRoom->class_id == 10)
                                                            Ten
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td scope="row">{{ @$student->classRoom->section->section_name }}
                                                    </td>
                                                    <td scope="row">{{ @$student->classRoom->shift->shift_name }}</td>
                                                    <td scope="row">{{ @$student->classRoom->version->version_name }}
                                                    </td>
                                                    <td scope="row">
                                                        @if (@$student->studentInfo->gender == 'Male')
                                                            ছাত্র
                                                        @elseif(@$student->studentInfo->gender == 'Female')
                                                            ছাত্রী
                                                        @else
                                                        <span class="btn np-delete-btn-small bg-warning" style="font-size: 12px; width: 90px !important; color: #000;">আপডেট করুন</span>
                                                        @endif
                                                    </td>
                                                    <td scope="row">
                                                        @if (@$student->studentInfo->religion == 'Islam')
                                                            ইসলাম
                                                        @elseif(@$student->studentInfo->religion == 'Hinduism')
                                                            হিন্দু
                                                        @elseif(@$student->studentInfo->religion == 'Christianity')
                                                            খ্রিষ্টান
                                                        @elseif(@$student->studentInfo->religion == 'Buddhism')
                                                            বৌদ্ধ
                                                        @else
                                                        <span class="btn np-delete-btn-small bg-warning" style="font-size: 12px; width: 90px !important; color: #000;">আপডেট করুন</span>
                                                        @endif
                                                    </td>
                                                    <td scope="row">
                                                        <div class="action-content">
                                                            @if ($student->rec_status != 2)
                                                                <a href="{{ route('student.edit', $student->student_uid) }}"
                                                                    class="np-route">
                                                                    <button class="btn np-edit-btn-small">
                                                                        <img src="{{ asset('frontend/noipunno/images/icons/edit-white.svg') }}"
                                                                            alt="">
                                                                    </button>
                                                                </a>
                                                                <a class="btn np-delete-btn-small delete_student"
                                                                    title="Delete" data-id="{{ $student->student_uid }}"
                                                                    data-token={{ csrf_token() }}
                                                                    data-route="{{ route('student.delete') }}">
                                                                    <i class="fa fa-trash np-delete-btn-small-icon"></i>
                                                                </a>

                                                                @if ($student->rec_status == 1)
                                                                    <button class="btn np-edit-btn-small text-black"
                                                                        onclick="statusChange({{ $student->rec_status }}, '{{ $student->student_uid }}', '{{ csrf_token() }}', '{{ route('student.rec_status') }}')"
                                                                        data-toggle="tooltip" data-placement="top"
                                                                        title="সক্রিয় শিক্ষার্থী">
                                                                        <i class="fa-solid fa-toggle-on"></i>
                                                                    </button>
                                                                @elseif ($student->rec_status == 0)
                                                                    <button class="btn np-edit-btn-small text-white"
                                                                        onclick="statusChange(0, '{{ $student->student_uid }}', '{{ csrf_token() }}', '{{ route('student.rec_status') }}')"
                                                                        data-toggle="tooltip" data-placement="top"
                                                                        title="নিস্ক্রিয় শিক্ষার্থী">
                                                                        <i class="fa-solid fa-toggle-on"></i>
                                                                    </button>
                                                                @endif
                                                            @else
                                                                <a href="{{ route('student.issue.transfer.certificate.generate', $student->student_uid) }}"
                                                                    class="np-route" target="_blank">
                                                                    <button class="btn np-edit-btn-small"
                                                                        style="font-size: 12px; width: 85px !important; background: #662d91; color: #fff;">ছাড়পত্র
                                                                        দেখুন
                                                                    </button>
                                                                </a>
                                                            @endif

                                                            {{-- <img src="{{ asset('frontend/noipunno/images/icons/3-dots-horizontal.svg') }}" alt=""> --}}
                                                        </div>
                                                    </td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="np-pagination-section d-flex justify-content-end align-items-center">
                            <div class="np-select-page-number d-flex align-items-center">
                                {{ $students->appends(request()->input())->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-md-12">
                          <div class="np-pagination-section d-flex justify-content-between align-items-center">
                            <div class="np-select-page-number d-flex align-items-center">
                              <select class="form-select" aria-label="Default select example">
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                              </select>

                              <h2 class="showing">
                                Rows Showing 1 to 10 of 100 entries
                              </h2>
                            </div>
                            <div class="pages">
                              <div class="export-types">
                                <button class="np-btn np-btn-primary np-btn-with-icon">
                                  <img src="{{ asset('frontend/noipunno/images/icons/pdf-export-icon.svg') }}" alt="">
                                  PDF
                                </button>

                                <button class="np-btn np-btn-primary np-btn-with-icon">
                                  <img src="{{ asset('frontend/noipunno/images/icons/export-excel-icon.svg') }}" alt="">
                                  Excel
                                </button>
                              </div>
                            </div>
                          </div>
                        </div> --}}
                </div>
            </div>

        </section>
    </div>
    <style>
        .np-table th,
        td {
            font-size: 11px;
        }

        span.error {
            color: red;
        }
    </style>

    <script>
        $(function() {
            $('#std_dataTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                // "ordering": true,
                "order": [
                    [3, "desc"]
                ],
                "info": true,
                "autoWidth": true,

            });

            $('.select2').select2();
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
                    // $('#version').html('<option value ="">ভার্সন নির্বাচন করুন</option>');
                    $('#shift').html('');
                    // $('#shift').html('<option value ="">শিফট নির্বাচন করুন</option>');

                    var vselected = "{{ @$request_data['version'] }}";
                    var sselected = "{{ @$request_data['shift'] }}";

                    if (data.versions) {
                        if (data.versions.length > 1) {
                            $('#version').html('<option value ="">ভার্সন নির্বাচন করুন</option>');
                        }

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
                        if (data.shifts.length > 1) {
                            $('#shift').html('<option value ="">শিফট নির্বাচন করুন</option>');
                        }
                        $.each(data.shifts, function(index, category) {
                            $('#shift').append('<option value ="' + category.uid + '"' + ((
                                    category.uid == sselected) ? ('selected') : '') + '>' +
                                category
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
                    $('#section').html('');
                    // $('#section').html('<option value ="">সেকশন নির্বাচন করুন</option>');
                    var selected = "{{ @$request_data['section'] }}";

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

        $(document).on('click', '.transfer_student', function() {
            var actionTo = $(this).attr('data-route');
            var token = $(this).attr('data-token');
            var id = $(this).attr('data-id');

            swal({
                    title: "আপনি কি এই শিক্ষার্থীকে ছাড়পত্র প্রদান করতে চান?",
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

        $(document).on('click', '.delete_student', function() {
            var actionTo = $(this).attr('data-route');
            var token = $(this).attr('data-token');
            var id = $(this).attr('data-id');

            swal({
                    title: "আপনি কি তথ্যটি মুছে ফেলতে চান ?",
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

        //Status Change
        function statusChange(status, id, token, actionTo) {
            //console.log(token,actionTo,id,status);
            let title;
            if (status == 1) {
                title = "আপনি কি নিষ্ক্রিয় করতে চান ?";
            } else {
                title = "আপনি কি সক্রিয় করতে চান ?";
            }

            swal({
                    title: title,
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
                                _token: '{{ csrf_token() }}',
                                rec_status: status
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

        }
    </script>
    <script>
        $(document).on('change', '#section', function() {
            $('#s_branch').html($('#branch').find("option:selected").text());
            $('#s_shift').html($('#shift').find("option:selected").text());
            $('#s_version').html($('#version').find("option:selected").text());
            $('#s_class').html($('#class').find("option:selected").text());
            $('#s_section').html($('#section').find("option:selected").text());
        })
    </script>
@endsection

@section('custom-js')
@endsection
