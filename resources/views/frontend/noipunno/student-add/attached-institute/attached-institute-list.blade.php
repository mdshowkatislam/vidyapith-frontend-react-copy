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
    <div class="dashboard-section mb-5">
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

        <div class="container mt-4 mb-3">
            <div class="np-teacher-list row mb-2">
                <div class="col-md-12">
                    <h2 class="np-form-title">শিক্ষার্থীর তথ্য অনুসন্ধান করুন</h2>
                </div>
            </div>
            <section class="np-teacher-add-form" id="edit-form">
                <div class="np-input-form-bg">
                    <div class="container">
                        <form method="GET" action="{{ route('student.attached_institute.list') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <div>
                                        <label for="branch" class="form-label">ব্রাঞ্চ <span
                                                class="error">*</span></label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input" aria-label="Default select example"
                                                id="branch" name="branch">
                                                {{-- <option value="">ব্রাঞ্চ নির্বাচন করুন</option> --}}
                                                @foreach ($branchs as $branch)
                                                    <option value="{{ @$branch->uid }}"
                                                        @if (@$request_data['branch'] == $branch->uid) selected @endif>
                                                        {{ @$branch->branch_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($errors->has('branch'))
                                            <small
                                                class="help-block form-text text-danger">{{ $errors->first('branch') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <div>
                                        <label for="shift" class="form-label">শিফট <span class="error">*</span></label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input" aria-label="Default select example"
                                                id="shift" name="shift">
                                                {{-- <option value="">শিফট নির্বাচন করুন</option> --}}
                                            </select>
                                        </div>
                                        @if ($errors->has('shift'))
                                            <small
                                                class="help-block form-text text-danger">{{ $errors->first('shift') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <div>
                                        <label for="version" class="form-label">ভার্সন <span
                                                class="error">*</span></label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input" aria-label="Default select example"
                                                id="version" name="version">
                                                {{-- <option value="">ভার্সন নির্বাচন করুন</option> --}}
                                            </select>
                                        </div>
                                        @if ($errors->has('version'))
                                            <small
                                                class="help-block form-text text-danger">{{ $errors->first('version') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12 mt-3">
                                    <div>
                                        <label for="class" class="form-label">শ্রেণি <span
                                                class="error">*</span></label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input" aria-label="Default select example"
                                                id="class" name="class">
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
                                        <label for="section" class="form-label">সেকশন <span class="error">*</span></label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input" aria-label="Default select example"
                                                id="section" name="section">
                                                {{-- <option value="">সেকশন নির্বাচন করুন</option> --}}
                                            </select>
                                        </div>
                                        @if ($errors->has('section'))
                                            <small
                                                class="help-block form-text text-danger">{{ $errors->first('section') }}</small>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12 mt-3">
                                    <div>
                                        <div class="text-center ">
                                            <button type="submit"
                                                class="btn btn-primary np-btn-form-submit mt-3">খুঁজুন</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
        <div class="container mb-2">
            <div class="row">
                <div class="col-md-12">
                    @if (@$request_data)
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
                    @endif
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <section class="np-teacher-list">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <h2 class="np-form-title">প্রতিষ্ঠানে সংযুক্ত সংযুক্ত হওয়ার জন্য শিক্ষার্থীর তালিকা</h2>
                            </div>

                            <form method="get" action="{{ route('student.attached_institute_d.list') }}" id="copyForm">
                                {{-- @csrf --}}
                                <div class="col-md-12">
                                    <div class="card np-card">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table np-table">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                <input type="checkbox" id="checkAll">
                                                                <label for="checkAll">সবগুলো চেক করুন</label><br>
                                                            </th>
                                                            <th scope="col">শিক্ষার্থীর রোল</th>
                                                            <th scope="col">শিক্ষার্থীর নাম</th>
                                                            <th scope="col">পিতার নাম</th>
                                                            <th scope="col">বছর</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($students as $student)
                                                            <tr>
                                                                <td>
                                                                    <input type="checkbox" class="checkItem"
                                                                        data-student-uid={{ @$student->student_uid }}
                                                                        name="checkedStudents[]"
                                                                        value={{ @$student->student_uid }}>
                                                                </td>
                                                                <td scope="row">{{ @$student->roll }}</td>
                                                                <td scope="row"><span class="icon"><img
                                                                            src="{{ asset('frontend/noipunno/images/icons/user.svg') }}"
                                                                            alt=""></span>{{ @$student->studentInfo->student_name_en ?? @$student->studentInfo->student_name_bn }}
                                                                </td>
                                                                <td scope="row">
                                                                    {{ @$student->studentInfo->father_name_en ?? @$student->studentInfo->father_name_bn }}
                                                                </td>
                                                                <td scope="row">{{ @$student->session_year }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8"></div>
                                    <div class="col-md-4 col-sm-12">
                                        <div class="text-center ">
                                            <button type="submit"
                                                class="btn btn-primary np-btn-form-submit mt-3 bulk__import_btn">পরবর্তি
                                                ধাপ<img
                                                    src="{{ asset('frontend/noipunno/images/icons/arrow-right.svg') }}"
                                                    alt="logo"></button>
                                        </div>
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </form>
                        </div>
                    </section>

                    {{-- <div>
                        <div class="np-pagination-section d-flex justify-content-end align-items-center">
                            <div class="np-select-page-number d-flex align-items-center">
                                {{ $versionList->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#checkAll').click(function() {
            $(':checkbox.checkItem').prop('checked', this.checked);
        });

        $(document).on('change', '#branch_uid', function() {
            var branch_id = $('#branch_uid').val();
            var eiin = {{ auth()->user()->eiin }};
            $.ajax({
                url: "{{ route('branch_wise_version') }}",
                type: "GET",
                data: {
                    branch_id: branch_id,
                    eiin: eiin,
                },
                success: function(data) {
                    $('#version_uid').html('');
                    $('#shift_uid').html('');

                    var vselected = "";
                    var sselected = "";

                    if (data.versions) {
                        $.each(data.versions, function(index, category) {
                            $('#version_uid').append('<option value ="' + category.uid + '"' + (
                                    (
                                        category
                                        .uid == vselected) ? ('selected') : '') + '>' +
                                category
                                .version_name +
                                '</option>');
                        });
                    }
                    if (data.shifts) {
                        $.each(data.shifts, function(index, category) {
                            $('#shift_uid').append('<option value ="' + category.uid + '"' + ((
                                    category
                                    .uid == sselected) ? ('selected') : '') + '>' + category
                                .shift_name +
                                '</option>');
                        });
                    }
                }

            });
        });
    </script>
    <script>
        $(document).on('change', '#class_uid', function() {
            var class_id = $('#class_uid').val();
            var branch_id = $('#branch_uid').val();
            var shift_id = $('#shift_uid').val();
            var version_id = $('#version_uid').val();
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
                    $('#section_uid').html('');
                    var selected = "";

                    if (data.sections) {
                        $.each(data.sections, function(index, category) {
                            $('#section_uid').append('<option value ="' + category
                                .uid + '"' + ((category.uid == selected) ? ('selected') :
                                    '') + '>' + category.section_name +
                                '</option>');
                        });
                        $('#section_uid').trigger('change');
                    }
                }
            });
        });
    </script>
    <script>
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

                    var vselected = "{{ @$request_data['version'] }}";
                    var sselected = "{{ @$request_data['shift'] }}";

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
