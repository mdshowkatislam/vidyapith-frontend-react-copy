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
                    <h2 class="np-form-title">প্রতিষ্ঠানে যুক্ত করার জন্য নতুন শ্রেণির তথ্য প্রদান করুন </h2>
                </div>
            </div>
            <section class="np-teacher-add-form" id="edit-form">
                <div class="np-input-form-bg">
                    <div class="container">
                        <form method="POST" action="{{ route('student.attached_institute_request.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <div>
                                        <label for="branch_uid" class="form-label">ব্রাঞ্চ <span class="error">*</span></label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input" aria-label="Default select example"
                                                id="branch_uid" name="branch_uid">
                                                {{-- <option value="">ব্রাঞ্চ নির্বাচন করুন</option> --}}
                                                @foreach ($branchs as $branch)
                                                    <option value="{{ @$branch->uid }}">
                                                        {{ @$branch->branch_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($errors->has('branch_uid'))
                                            <small
                                                class="help-block form-text text-danger">{{ $errors->first('branch_uid') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <div>
                                        <label for="shift_uid" class="form-label">শিফট <span class="error">*</span></label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input" aria-label="Default select example"
                                                id="shift_uid" name="shift_uid">
                                                {{-- <option value="">শিফট নির্বাচন করুন</option> --}}
                                            </select>
                                        </div>
                                        @if ($errors->has('shift_uid'))
                                            <small
                                                class="help-block form-text text-danger">{{ $errors->first('shift_uid') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <div>
                                        <label for="version_uid" class="form-label">ভার্সন <span class="error">*</span></label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input" aria-label="Default select example"
                                                id="version_uid" name="version_uid">
                                                {{-- <option value="">ভার্সন নির্বাচন করুন</option> --}}
                                            </select>
                                        </div>
                                        @if ($errors->has('version_uid'))
                                            <small
                                                class="help-block form-text text-danger">{{ $errors->first('version_uid') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12 mt-3">
                                    <div>
                                        <label for="class_uid" class="form-label">শ্রেণি <span class="error">*</span></label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input" aria-label="Default select example"
                                                id="class_uid" name="class_uid">
                                                <option value="">শ্রেণি নির্বাচন করুন</option>
                                                @foreach ($classList as $key => $class)
                                                    <option value="{{ $key }}" {{$key==6 ? 'selected' : ''}}> {{ $class }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($errors->has('class_uid'))
                                            <small
                                                class="help-block form-text text-danger">{{ $errors->first('class_uid') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12 mt-3">
                                    <div>
                                        <label for="section_uid" class="form-label">সেকশন <span class="error">*</span></label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input" aria-label="Default select example"
                                                id="section_uid" name="section_uid">
                                                {{-- <option value="">সেকশন নির্বাচন করুন</option> --}}
                                            </select>
                                        </div>
                                        @if ($errors->has('section_uid'))
                                            <small
                                                class="help-block form-text text-danger">{{ $errors->first('section_uid') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </section>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <section class="np-teacher-list">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <h2 class="np-form-title">অপেক্ষমান শিক্ষার্থীর তালিকা</h2>
                            </div>
                            {{-- <form method="post" action="{{ route('student.promote.store') }}" id="copyForm"> --}}
                            {{-- @csrf --}}
                            <div class="col-md-12">
                                <div class="card np-card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table np-table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">শিক্ষার্থীর নাম</th>
                                                        <th scope="col">পিতার নাম</th>
                                                        <th scope="col">শ্রেণি</th>
                                                        <th scope="col">শিক্ষার্থীর রোল (সম্পাদনাযোগ্য)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($students as $student)
                                                        <tr>
                                                            <td scope="row"><span class="icon"><img
                                                                        src="{{ asset('frontend/noipunno/images/icons/user.svg') }}"
                                                                        alt=""></span>{{ @$student->studentInfo->student_name_en ?? @$student->studentInfo->student_name_bn }}
                                                            </td>
                                                            <td scope="row">{{ @$student->studentInfo->father_name_en ?? @$student->studentInfo->father_name_bn }}</td>
                                                            <td scope="row">{{ @$student->oldClassRoom->class_id }}</td>
                                                            <td scope="row">
                                                                <input style="border: 1px solid var(--primary-color-1);"
                                                                    type="number" class="form-control np-teacher-input"
                                                                    id="roll" name="roll[{{ @$student->student_uid }}]"
                                                                    value="{{ @$student->roll ?? old('roll') }}">
                                                            </td>
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
                                            class="btn btn-primary np-btn-form-submit mt-3 bulk__import_btn">তথ্য সংযোজন করুন
                                            <img src="{{ asset('frontend/noipunno/images/icons/arrow-right.svg') }}" alt="logo">
                                        </button>
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
                    $('#class_uid').trigger('change');
                }
            });
        });

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
        $(function() {
            $('#branch_uid').trigger('change');
        });
    </script>

@endsection
