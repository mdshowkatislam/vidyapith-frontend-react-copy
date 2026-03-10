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
                                        <h2 class="title">বিষয় শিক্ষক নির্বাচন </h2>
                                        <nav aria-label="breadcrumb">
                                            <ol class="breadcrumb np-breadcrumb">
                                                <li class="breadcrumb-item"><a href="/">
                                                        <img src="{{ asset('frontend/noipunno/images/icons/home.svg') }}"
                                                            alt=""> ড্যাশবোর্ড
                                                    </a></li>
                                                <li class="breadcrumb-item active" aria-current="page">বিষয় শিক্ষক নির্বাচন
                                                </li>
                                            </ol>
                                        </nav>
                                    </div>
                                </div>
                                {{-- <div class="option-section">
                                    <div class="fav-icon">
                                        <img src="{{ asset('frontend/noipunno/images/icons/fav-start-icon.svg') }}"
                                            alt="">
                                    </div>
                                    <div class="dots-icon">
                                        <img src="{{ asset('frontend/noipunno/images/icons/3-dot-vertical.svg') }}"
                                            alt="">
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        {{-- @include('frontend.layouts.notice') --}}
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <section class="np-teacher-list mt-4">
                        {{-- <div class="container"> --}}
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <h2 class="np-form-title">সেকশন ভিত্তিক বিষয় শিক্ষকের তথ্য</h2>
                                {{-- <div class="class-info-list d-flex">
                                    <span class="info">ব্রাঞ্চ নাম | </span>
                                    <span class="info">ভার্সন নাম | </span>
                                    <span class="info">শিফট নাম | </span>
                                    <span class="info">শিক্ষাবর্ষ | </span>
                                    <span class="info">Class 6 | </span>
                                    <span class="info">Year | </span>
                                    <span class="info">Class Teacher</span>
                                </div> --}}
                            </div>

                            <div class="col-md-12">
                                <div class="card np-card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table np-table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" class="fw-bold">ব্রাঞ্চ
                                                            {{-- <span class="icon">
                                                                    <img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}"
                                                                        alt=""></span> --}}
                                                        </th>
                                                        <th scope="col" class="fw-bold">ভার্সন
                                                            {{-- <span class="icon"><img
                                                                        src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}"
                                                                        alt=""></span> --}}
                                                        </th>
                                                        <th scope="col" class="fw-bold">শিফট
                                                            {{-- <span class="icon"><img
                                                                        src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}"
                                                                        alt=""></span> --}}
                                                        </th>
                                                        <th scope="col" class="fw-bold">শ্রেণি
                                                            {{-- <span class="icon"><img
                                                                        src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}"
                                                                        alt=""></span> --}}
                                                        </th>
                                                        <th scope="col" class="fw-bold">সেকশন
                                                            {{-- <span class="icon"><img
                                                                        src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}"
                                                                        alt=""></span> --}}
                                                        </th>
                                                        <th scope="col" class="fw-bold">শিক্ষাবর্ষ
                                                        </th>
                                                        <th scope="col" class="fw-bold">শ্রেণী শিক্ষক
                                                            {{-- <span class="icon"><img
                                                                        src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}"
                                                                        alt=""></span> --}}
                                                        </th>
                                                        <th scope="col" class="fw-bold">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($class_rooms as $item)
                                                        <tr>
                                                            <td scope="row">{{ @$item->branch->branch_name }}</th>
                                                            <td scope="row">{{ @$item->version->version_name }}</th>
                                                            <td scope="row">{{ @$item->shift->shift_name }}</th>
                                                            <td scope="row">{{ @$item->class_id }}</th>
                                                            <td scope="row">{{ @$item->section->section_name }}</th>
                                                            <td scope="row">{{ @$item->session_year }}</th>
                                                            <td scope="row">{{ @$item->class_teacher->name_en }}
                                                                </th>
                                                            <td scope="row">
                                                                <div class="action-content">
                                                                    {{-- <h2 class="created-date">
                                                                    {{ date('d M, Y', strtotime(@$item->created_at)) }}
                                                                </h2> --}}
                                                                    <a href="{{ route('noipunno.dashboard.classroom.edit', ['id' => $item->uid]) }}"
                                                                        class="np-route">
                                                                        <button class="btn np-edit-btn-small">
                                                                            <img src="{{ asset('frontend/noipunno/images/icons/edit-white.svg') }}"
                                                                                alt="">
                                                                        </button>
                                                                    </a>

                                                                    <a class="btn np-delete-btn-small delete_module"
                                                                        title="Delete" data-id="{{ $item->uid }}"
                                                                        data-token={{ csrf_token() }}
                                                                        data-route="{{ route('noipunno.dashboard.classroom.delete') }}"><i
                                                                            class="fa fa-trash np-delete-btn-small-icon"></i></a>
                                                                    {{-- <button
                                                                    class="btn np-edit-btn-small np-edit-btn-small-lg">
                                                                    <img src="{{ asset('frontend/noipunno/images/icons/edit-white.svg') }}"
                                                                        alt="">
                                                                    Add Student
                                                                </button> --}}
                                                                </div>
                                                                </th>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
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
                                                <img src="{{ asset('frontend/noipunno/images/icons/pdf-export-icon.svg') }}"
                                                    alt="">
                                                PDF
                                            </button>

                                            <button class="np-btn np-btn-primary np-btn-with-icon">
                                                <img src="{{ asset('frontend/noipunno/images/icons/export-excel-icon.svg') }}"
                                                    alt="">
                                                Excel
                                            </button>
                                        </div>

                                        <nav aria-label="Page navigation example">
                                            <ul class="np-pagination pagination justify-content-end">
                                                <li class="page-item np-card">
                                                    <a class="page-link" href="#"><img
                                                            src="{{ asset('frontend/noipunno/images/icons/chevron-left.svg') }}"
                                                            alt=""></a>
                                                </li>
                                                <li class="page-item np-card"><a class="page-link" href="#">1</a></li>
                                                <li class="page-item np-card"><a class="page-link active" href="#">2</a>
                                                </li>
                                                <li class="page-item np-card"><a class="page-link" href="#">3</a></li>
                                                <li class="page-item np-card">
                                                    <a class="page-link" href="#">
                                                        <img src="{{ asset('frontend/noipunno/images/icons/chevron-right.svg') }}"
                                                            alt="">
                                                    </a>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                                </div> --}}
                        </div>
                        {{-- </div> --}}
                    </section>
                </div>
            </div>
        </div>

            {{-- <div>
                <div class="np-pagination-section d-flex justify-content-end align-items-center">
                    <div class="np-select-page-number d-flex align-items-center">
                        {{ $class_rooms->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div> --}}
        <div class="container">
            <div class="row">
                <h2 class="mt-4 mb-2 np-form-title">সেকশন ভিত্তিক বিষয় শিক্ষকের তথ্য যুক্ত করুন</h2>
                <div class="col-md-12">
                    <form action="{{ route('noipunno.dashboard.classroom.store') }}" method="POST">
                        <section class="section-teacher-add-form np-input-form-bg mb-3">
                            <div class="container">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="beiin-psid" class="form-label">ব্রাঞ্চ <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <select class="form-select np-teacher-input" name="branch_id" id="branch_id"
                                                    aria-label="Default select example">
                                                    {{-- <option value="">Select Branch Name</option> --}}
                                                    @foreach ($branches as $item)
                                                        <option value="{{ $item->uid }}">
                                                            {{ $item->branch_name }}
                                                        </option>
                                                    @endforeach
                                                    {{-- <option value="1">Branch One</option>
                                                <option value="2">Branch Two</option>
                                                <option value="3">Branch Three</option> --}}
                                                </select>
                                            </div>
                                        </div>
                                        @if ($errors->has('branch_id'))
                                            <small
                                                class="help-block form-text text-danger">{{ $errors->first('branch_id') }}</small>
                                        @endif
                                    </div>

                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="beiin-psid" class="form-label">ভার্সন <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <select class="form-select np-teacher-input" name="version_id"
                                                    id="version_id" aria-label="Default select example">
                                                    {{-- <option value="">Select Version</option> --}}
                                                    {{-- <option value="1">Version One</option>
                                                <option value="2">Version Two</option>
                                                <option value="3">Version Three</option> --}}
                                                </select>
                                            </div>
                                        </div>
                                        @if ($errors->has('version_id'))
                                            <small
                                                class="help-block form-text text-danger">{{ $errors->first('version_id') }}</small>
                                        @endif
                                    </div>

                                    <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="beiin-psid" class="form-label">শিফট <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <select class="form-select np-teacher-input" name="shift_id"
                                                    id="shift_id" aria-label="Default select example">
                                                    {{-- <option value="">Select Shift</option> --}}
                                                    {{-- <option value="1">Shift One</option>
                                                <option value="2">Shift Two</option>
                                                <option value="3">Shift Three</option> --}}
                                                </select>
                                            </div>
                                        </div>
                                        @if ($errors->has('shift_id'))
                                            <small
                                                class="help-block form-text text-danger">{{ $errors->first('shift_id') }}</small>
                                        @endif
                                    </div>

                                    <div class="col-md-4 col-sm-12 mt-3">
                                        <div>
                                            <label for="beiin-psid" class="form-label">শ্রেণি <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <select class="form-select np-teacher-input" id="class_id"
                                                    name="class_id" aria-label="Default select example">
                                                    <option value="">শ্রেণি নির্বাচন করুন</option>
                                                    {{-- <option value="1">Class One</option>
                                                <option value="2">Class Two</option>
                                                <option value="3">Class Three</option> --}}
                                                    {{-- @foreach ($classes as $item)
                                                        <option value="{{ $item['class_id'] }}">{{ $item['name_en'] }}
                                                        </option>
                                                    @endforeach --}}
                                                    @foreach (App\Helper\ClassEnum::values() as $key => $value)
                                                        <option value="{{ $key }}">
                                                            {{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @if ($errors->has('class_id'))
                                            <small
                                                class="help-block form-text text-danger">{{ $errors->first('class_id') }}</small>
                                        @endif
                                    </div>

                                    <div class="col-md-4 col-sm-12 mt-3">
                                        <div>
                                            <label for="beiin-psid" class="form-label">সেকশন <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <select class="form-select np-teacher-input" id="section_id"
                                                    name="section_id" aria-label="Default select example">
                                                    {{-- <option value="">Please Select</option> --}}
                                                    {{-- <option value="1">Class One</option>
                                                <option value="2">Class Two</option>
                                                <option value="3">Class Three</option> --}}
                                                    {{-- @foreach ($classes as $item)
                                                <option value="{{ $item['id'] }}">{{ $item['name_en'] }}</option>
                                                @endforeach --}}
                                                </select>
                                            </div>
                                        </div>
                                        @if ($errors->has('section_id'))
                                            <small
                                                class="help-block form-text text-danger">{{ $errors->first('section_id') }}</small>
                                        @endif
                                    </div>

                                    <div class="col-md-4 col-sm-12 mt-3">
                                        <div>
                                            <label for="beiin-psid" class="form-label">শিক্ষাবর্ষ <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control np-teacher-input"
                                                    name="session_year" id="session_year" value="{{ date('Y') }}" readonly>
                                                {{-- <select class="form-select np-teacher-input" name="session_year"
                                                aria-label="">
                                                <option value="">Year</option>
                                                @for ($year = date('Y'); $year > '2019'; $year--)
                                                <option value={{ $year }}>{{ $year }}</option>
                                                @endfor
                                            </select> --}}
                                            </div>

                                            @if ($errors->has('session_year'))
                                            <small
                                                class="help-block form-text text-danger">{{ $errors->first('session_year') }}</small>
                                        @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-12 mt-3">
                                        <div>
                                            <label for="beiin-psid" class="form-label">শ্রেণি শিক্ষক <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                {{-- @dd($teachers) --}}
                                                <select class="form-select np-teacher-input select2"
                                                    name="class_teacher_id" aria-label="Default select example">
                                                    <option value="">শ্রেণি শিক্ষক নির্বাচন করুন</option>
                                                    {{-- <option value="1">Teacher One</option>
                                                <option value="2">Teacher Two</option>
                                                <option value="3">Teacher Three</option> --}}

                                                    @foreach ($teachers as $item)
                                                        <option value="{{ $item->uid }}">
                                                            {{ $item->name_en }} -
                                                            {{ @$item->pdsid ?? (@$item->index_number ?? @$item->caid) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @if ($errors->has('class_teacher_id'))
                                            <small
                                                class="help-block form-text text-danger">{{ $errors->first('class_teacher_id') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </section>
                        <!-- teacher add -->
                        <section class="np-teacher-add-form np-input-form-bg mb-5">
                            <div class="">
                                <div class="container">
                                    {{-- <h5 class="mb-3">বিষয় ও শিক্ষক যোগ করুন</h5> --}}
                                    <form>
                                        <div id="subject_list" class="mb-3"></div>

                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 d-flex justify-content-start">
                                                <button type="submit"
                                                    class="btn btn-primary np-btn-form-submit d-flex align-items-center"
                                                    style="width: fit-content;border: unset;column-gap: 10px;">তথ্য
                                                    সংরক্ষন করুন <img
                                                        src="{{ asset('frontend/noipunno/images/icons/arrow-right.svg') }}"
                                                        alt=""></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </section>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).on('change', '#branch_id', function() {
            var branch_id = $('#branch_id').val();
            var eiin = {{ auth()->user()->eiin }};
            $.ajax({
                url: "{{ route('branch_wise_version') }}",
                type: "GET",
                data: {
                    branch_id: branch_id,
                    eiin: eiin,
                },
                success: function(data) {
                    // $('#version_id').html('<option value ="">Please Select</option>');
                    // $('#shift_id').html('<option value ="">Please Select</option>');
                    $('#version_id').html('');
                    $('#shift_id').html('');

                    var vselected = "{{ @$editData->version_id }}";
                    var sselected = "{{ @$editData->shift_id }}";

                    if (data.versions) {
                        $.each(data.versions, function(index, category) {
                            $('#version_id').append('<option value ="' + category.uid + '"' + ((
                                    category
                                    .uid == vselected) ? ('selected') : '') + '>' + category
                                .version_name +
                                '</option>');
                        });
                        $('#version_id').trigger('change');
                    }
                    if (data.shifts) {
                        $.each(data.shifts, function(index, category) {
                            $('#shift_id').append('<option value ="' + category.uid + '"' + ((
                                    category
                                    .uid == sselected) ? ('selected') : '') + '>' + category
                                .shift_name +
                                '</option>');
                        });
                        $('#shift_id').trigger('change');
                    }
                }
            });
        });
        $(function() {
            $('#branch_id').trigger('change');
        });
    </script>
    <script>
        $(document).on('change', '#class_id', function() {
            var class_id = $('#class_id').val();
            var branch_id = $('#branch_id').val();
            var shift_id = $('#shift_id').val();
            var version_id = $('#version_id').val();
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
                    $('#section_id').html('');
                    var selected = "{{ @$editData->section_id }}";

                    if (data.sections) {
                        $.each(data.sections, function(index, category) {
                            $('#section_id').append('<option value ="' + category
                                .uid + '"' + ((category.uid == selected) ? ('selected') :
                                    '') + '>' + category.section_name +
                                '</option>');
                        });
                        $('#section_id').trigger('change');
                    }
                }
            });
        });

        $(document).on('change', '#class_id', function() {
            var class_id = $('#class_id').val();
            // var eiin = {{ auth()->user()->eiin }};
            $.ajax({
                url: "{{ route('class_wise_subject') }}",
                type: "GET",
                data: {
                    class_id: class_id
                },
                success: function(data) {
                    var html = '<h5 class="mb-3">বিষয় ও শিক্ষক যোগ করুন</h5>';
                    $.each(data.subjects, function(index, subject) {

                        html += `
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <div class="mb-3">
                                        <label for="loginId" class="form-label">বিষয়</label>

                                        <input class="form-control" value="${subject.name}" readonly/>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <div class="mb-3">
                                        <label for="teacherImage" class="form-label">বিষয় ভিত্তিক শিক্ষক</label>
                                        <div class="input-group">
                                            <select name="teacher_ids[${subject.uid}]" class="form-select np-teacher-input select2" aria-label="">
                                                <option value="">শিক্ষক নির্বাচন করুন</option>
                                                @foreach ($teachers as $teacher)
                                                <option value="{{ $teacher->uid }}">{{ $teacher->name_en }} - {{ @$teacher->pdsid ?? (@$teacher->index_number ?? @$teacher->caid) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                    `;

                    });
                    $('#subject_list').html(html);
                    $('.select2').select2();
                }
            });
        });
    </script>

    {{-- <script>
        $(document).on('change', '#section_id', function() {
            var section_id = $('#section_id').val();
            var eiin = {{ auth()->user()->eiin }};
            $.ajax({
                url: "{{ route('section_wise_year') }}",
                type: "GET",
                data: {
                    section_id: section_id
                },
                success: function(data) {
                    $('#session_year').val(data.section.section_year);
                }
            });
        });
    </script> --}}
@endsection
