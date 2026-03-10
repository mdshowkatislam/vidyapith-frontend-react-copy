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

        <div class="container mt-4 mb-5">
            <div class="np-teacher-list row mb-2">
                <div class="col-md-12">
                    <h2 class="np-form-title">পূর্ববর্তী শিক্ষার্থীর তথ্য </h2>
                </div>
            </div>
            <section class="np-teacher-add-form" id="edit-form">
                <div class="np-input-form-bg">
                    <div class="container">
                        <form method="GET" action="{{ route('student.promote.list') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <div>
                                        <label for="branch" class="form-label">ব্রাঞ্চ</label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input" aria-label="Default select example"
                                                id="branch" name="branch">
                                                <option value="">Select Branch</option>
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
                                            <select class="form-select np-teacher-input" aria-label="Default select example"
                                                id="shift" name="shift">
                                                <option value="">Select Shift</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <div>
                                        <label for="version" class="form-label">ভার্সন</label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input" aria-label="Default select example"
                                                id="version" name="version">
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12 mt-3">
                                    <div>
                                        <label for="class" class="form-label">ক্লাস <span class="error">*</span></label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input" aria-label="Default select example"
                                                id="class" name="class">
                                                <option value="">Select Class</option>

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
                                            <select class="form-select np-teacher-input" aria-label="Default select example"
                                                id="section" name="section">
                                                <option value="">Select Section</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12 mt-3">
                                    <div>
                                        <div class="text-center ">
                                            <button type="submit"
                                                class="btn btn-primary np-btn-form-submit mt-3">Search</button>
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="row">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4 col-sm-12">

                                    </div>
                                    <div class="col-md-4"></div>
                                </div> --}}
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <section class="np-teacher-list">
                        <div class="row">
                            {{-- <div class="col-md-12 mb-2">
                                <h2 class="np-form-title">ভার্সন লিস্ট</h2>
                            </div> --}}

                            <form method="post" action="{{ route('student.promote.store') }}" id="copyForm">
                                @csrf
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
                                                            <th scope="col">শিক্ষার্থীর রোল (সম্পাদনাযোগ্য)</th>
                                                            <th scope="col">শিক্ষার্থীর নাম</th>
                                                            <th scope="col">পিতার নাম</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($students as $student)
                                                            <tr>
                                                                <td>
                                                                    <input type="checkbox" class="checkItem"
                                                                        data-student-uid={{ @$student->uid }}
                                                                        name="checkedStudents[]"
                                                                        value={{ @$student->uid }}>
                                                                </td>
                                                                <td scope="row">
                                                                    <input style="border: 1px solid var(--primary-color-1);" type="number" class="form-control np-teacher-input"
                                                                        id="roll" name="roll[{{ @$student->uid }}]"
                                                                        value="{{ @$student->roll ?? old('roll') }}"></td>
                                                                <td scope="row"><span class="icon"><img
                                                                            src="{{ asset('frontend/noipunno/images/icons/user.svg') }}"
                                                                            alt=""></span>{{ @$student->student_name_bn ?? @$student->student_name_en }}
                                                                </td>
                                                                <td scope="row">{{ @$student->father_name_bn }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 my-3">
                                    <h2 class="np-form-title">নির্বাচিত শিক্ষার্থীদের জন্য নতুন একাডেমিক তথ্য </h2>
                                </div>


                                <div class="row m-3">
                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="branch_uid" class="form-label">ব্রাঞ্চ</label>
                                            <div class="input-group">
                                                <select class="form-select np-teacher-input"
                                                    aria-label="Default select example" id="branch_uid"
                                                    name="branch_uid">
                                                    <option value="">Select Branch</option>
                                                    @foreach ($branchs as $branch)
                                                        <option value="{{ @$branch->uid }}">
                                                            {{ @$branch->branch_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="shift_uid" class="form-label">শিফট</label>
                                            <div class="input-group">
                                                <select class="form-select np-teacher-input"
                                                    aria-label="Default select example" id="shift_uid" name="shift_uid">
                                                    <option value="">Select Shift</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="version_uid" class="form-label">ভার্সন</label>
                                            <div class="input-group">
                                                <select class="form-select np-teacher-input"
                                                    aria-label="Default select example" id="version_uid"
                                                    name="version_uid">
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-12 mt-3">
                                        <div>
                                            <label for="class_uid" class="form-label">ক্লাস <span
                                                    class="error">*</span></label>
                                            <div class="input-group">
                                                <select class="form-select np-teacher-input"
                                                    aria-label="Default select example" id="class_uid" name="class_uid">
                                                    <option value="">Select Class</option>

                                                    @foreach ($classList as $key => $class)
                                                        <option value="{{ $key }}">
                                                            {{ $class }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @if ($errors->has('class_uid'))
                                                <small
                                                    class="help-block form-text text-danger">{{ $errors->first('class') }}</small>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-12 mt-3">
                                        <div>
                                            <label for="section_uid" class="form-label">সেকশন </label>
                                            <div class="input-group">
                                                <select class="form-select np-teacher-input"
                                                    aria-label="Default select example" id="section_uid"
                                                    name="section_uid">
                                                    <option value="">Select Section</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-8"></div>
                                    <div class="col-md-4 col-sm-12">
                                        <div class="text-center ">
                                            <button type="submit"
                                                class="btn btn-primary np-btn-form-submit mt-3 bulk__import_btn">তথ্য
                                                সংযোজন করুন<img
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

@endsection
