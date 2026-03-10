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
                <div class="col-md-6">
                    <h2 class="np-form-title">বোর্ড রেজিস্ট্রেশনের জন্য শিক্ষার্থীর তথ্য অনুসন্ধান করুন</h2>
                </div>

                <div class="col-md-3">
                    @if($temp_count > 0)
                    <a class="np-route" href="{{ route('student.board_registration.temp.list', $class_id) }}">
                        <p class="btn np-btn-form-submit border-0 rounded-1"><i class="fa-solid fa-list"></i>
                            অস্থায়ী শিক্ষার্থী তালিকা</p>
                    </a>
                    @endif
                </div>
                <div class="col-md-3">
                    @if($reg_count > 0)
                    <a class="np-route" href="{{ route('student.board_registration.registered.list', $class_id) }}">
                        <p class="btn np-btn-form-submit border-0 rounded-1"><i class="fa-solid fa-list"></i>
                            নিবন্ধিত শিক্ষার্থী তালিকা</p>
                    </a>
                    @endif
                </div>
            </div>
            <section class="np-teacher-add-form" id="edit-form">
                <div class="np-input-form-bg">
                    <div class="container">
                        <form method="GET" action="{{ route('student.board_registration.list') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <div>
                                        <label for="branch" class="form-label">ব্রাঞ্চ <span class="error">*</span></label>
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
                                        <label for="version" class="form-label">ভার্সন <span class="error">*</span></label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input" aria-label="Default select example"
                                                id="version" name="version">
                                                {{-- <option value="">ভার্সন নির্বাচন করুন</option> --}}
                                            </select>
                                        </div>
                                    </div>
                                    @if ($errors->has('version'))
                                            <small
                                                class="help-block form-text text-danger">{{ $errors->first('version') }}</small>
                                        @endif
                                </div>

                                <div class="col-md-4 col-sm-12 mt-3">
                                    <div>
                                        <label for="class" class="form-label">শ্রেণি <span class="error">*</span></label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input" aria-label="Default select example"
                                                id="class" name="class">
                                                <option value="">শ্রেণি নির্বাচন করুন</option>
                                                @foreach ($classList as $key => $class)
                                                    <option value="{{ $key }}"
                                                        {{ @$request_data['class'] == $key ? 'selected' : '' }}>{{ $class }}</option>
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
