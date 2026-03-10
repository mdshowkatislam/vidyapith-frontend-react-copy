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
        @include('frontend.layouts.notice')
        <div class="container mt-4 mb-5">
            <div class="np-teacher-list row mb-4">
                <div class="col-md-8">
                    <h2 class="np-form-title">শিক্ষার্থীর তথ্য পরিবর্তন </h2>
                </div>
                <div class="col-md-4">
                    <a class="np-route" href="{{ route('student.index') }}">
                        <p class="btn np-btn-form-submit border-0 rounded-1"><i class="fa-solid fa-list"></i>
                            শিক্ষার্থীর তালিকা</p>
                    </a>
                </div>
            </div>
            <ul class="nav nav-tabs np-student-tab-container" id="myTabs" role="tablist">
                <li class="nav-item np-student-tab" role="presentation">
                    <a class="nav-link active np-student-tab-link" id="tab1-tab" data-bs-toggle="tab" href="#tab1"
                        role="tab" aria-controls="tab1" aria-selected="true">
                        <img src="{{ asset('frontend/noipunno/images/icons/student-tab1.svg') }}" alt="">
                        শিক্ষার্থীর তথ্য পরিবর্তন
                    </a>
                </li>
            </ul>

            <div class="tab-content" id="myTabsContent">
                <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
                    <!-- Content for Tab 1 -->

                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <!-- student add -->
                            <section class="np-teacher-add-form" id="edit-form">
                                <div class="np-input-form-bg">
                                    <div class="container">
                                        <form method="POST" action="{{ route('student.update', $student->uid) }}"
                                            enctype="multipart/form-data">
                                            @method('PUT')
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-4 col-sm-12 mt-3">
                                                    <div class="mb-3">
                                                        <label for="loginId" class="form-label">eSIF Serial No.<span
                                                                class="error">*</span></label>
                                                        <input type="number" class="form-control np-teacher-input"
                                                            name="scroll_num"
                                                            value="{{ old('scroll_num', $student_class_info->scroll_num) }}">
                                                        @if ($errors->has('scroll_num'))
                                                            <small
                                                                class="help-block form-text text-danger">{{ $errors->first('scroll_num') }}</small>
                                                        @endif
                                                    </div>
                                                </div>










                                                <div class="col-md-6 col-sm-12 mt-3">
                                                    <div>
                                                        <label for="class1" class="form-label">শ্রেণি <span
                                                                class="error">*</span></label>
                                                        <div class="input-group">
                                                            <select class="form-select np-teacher-input"
                                                                aria-label="Default select example" id="class1"
                                                                name="class">
                                                                <option value="">শ্রেণি নির্বাচন করুন</option>
                                                                {{-- @foreach ($classList as $class)
                                                                    <option value="{{ $class['class_id'] }}"
                                                                        @if ($student->class == $class['class_id']) selected @endif>
                                                                        {{ $class['name_en'] }}</option>
                                                                @endforeach --}}

                                                                @foreach ($classList as $key => $class)
                                                                    <option value="{{ $key }}"
                                                                        {{ $student_class_info->classRoom->class_id == $key ? 'selected' : '' }}>
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






                                            </div>
                                            <div class="row mt-3">


                                                <div class="col-md-6 col-sm-12">
                                                    <div class="mb-3">
                                                        <label for="studentName" class="form-label">শিক্ষার্থীর নাম
                                                            (বাংলা) </label>
                                                        <input type="text" class="form-control np-teacher-input"
                                                            id="studentName" name="student_name_bn"
                                                            value="{{ $student->student_name_bn }}">
                                                        @if ($errors->has('student_name_bn'))
                                                            <small
                                                                class="help-block form-text text-danger">{{ $errors->first('student_name_bn') }}</small>
                                                        @endif
                                                    </div>
                                                </div>


                                                <div class="col-md-6 col-sm-12">
                                                    <div class="mb-3">
                                                        <label for="fatherName" class="form-label">পিতার নাম
                                                            (বাংলা)</label>
                                                        <input type="text" class="form-control np-teacher-input"
                                                            id="fatherName" name="father_name_bn"
                                                            value="{{ $student->father_name_bn }}">
                                                        @if ($errors->has('father_name_bn'))
                                                            <small
                                                                class="help-block form-text text-danger">{{ $errors->first('father_name_bn') }}</small>
                                                        @endif
                                                    </div>
                                                </div>




                                                <div class="col-md-6 col-sm-12">
                                                    <div class="mb-3">
                                                        <label for="motherName" class="form-label">মাতার নাম
                                                            (বাংলা)</label>
                                                        <input type="text" class="form-control np-teacher-input"
                                                            id="motherName" name="mother_name_bn"
                                                            value="{{ $student->mother_name_bn }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-sm-12">
                                                    <div class="mb-3">
                                                        <label for="board_reg_no" class="form-label">বোর্ড রেজিস্ট্রেশন
                                                            নম্বর </label>
                                                        <input type="text" class="form-control np-teacher-input"
                                                            id="board_reg_no" name="board_reg_no"
                                                            value="{{ @$student->board_reg_no }}" readonly>
                                                        @if ($errors->has('board_reg_no'))
                                                            <small
                                                                class="help-block form-text text-danger">{{ $errors->first('board_reg_no') }}</small>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- </div>

                                            <div class="row"> --}}


                                                <div class="col-md-6 col-sm-12">
                                                    <div class="mb-3">
                                                        <label for="dhormo" class="form-label">ধর্ম <span
                                                                class="error">*</span></label>
                                                        {{-- <input type="text" class="form-control np-teacher-input" id="dhormo" name="religion" value="{{$student->religion}}"> --}}
                                                        <select class="form-select np-teacher-input"
                                                            aria-label="Default select example" id="dhormo"
                                                            name="religion">
                                                            <option value=""> ধর্ম নির্বাচন করুন</option>
                                                            <option value="Islam"
                                                                {{ @$student->religion == 'Islam' ? 'selected' : '' }}>
                                                                ইসলাম</option>
                                                            <option value="Hinduism"
                                                                {{ @$student->religion == 'Hinduism' ? 'selected' : '' }}>
                                                                হিন্দু</option>
                                                            <option value="Christianity"
                                                                {{ @$student->religion == 'Christianity' ? 'selected' : '' }}>
                                                                খ্রিষ্টান</option>
                                                            <option value="Buddhism"
                                                                {{ @$student->religion == 'Buddhism' ? 'selected' : '' }}>
                                                                বৌদ্ধ</option>
                                                            <option value="Other"
                                                                {{ @$student->religion == 'Other' ? 'selected' : '' }}>
                                                                Other</option>
                                                        </select>
                                                        @if ($errors->has('religion'))
                                                            <small
                                                                class="help-block form-text text-danger">{{ $errors->first('religion') }}</small>
                                                        @endif
                                                    </div>
                                                </div>



                                                <div class="col-md-6 col-sm-12">
                                                    <div class="mb-3">
                                                        <label for="farhersPhoneNumber" class="form-label">পিতার মোবাইল
                                                        </label>
                                                        <input type="number" class="form-control np-teacher-input num"
                                                            maxlength="11" id="farhersPhoneNumber"
                                                            name="father_mobile_no"
                                                            value="{{ $student->father_mobile_no }}"
                                                            placeholder="01xxxxxxxxx">
                                                        @if ($errors->has('father_mobile_no'))
                                                            <small
                                                                class="help-block form-text text-danger">{{ $errors->first('father_mobile_no') }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="mb-3">
                                                        <label for="mothersPhoneNumber" class="form-label">মাতার মোবাইল
                                                            নম্বর</label>
                                                        <input type="number" class="form-control np-teacher-input num"
                                                            maxlength="11" id="mothersPhoneNumber"
                                                            name="mother_mobile_no"
                                                            value="{{ $student->mother_mobile_no }}"
                                                            placeholder="01xxxxxxxxx">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="mb-3">
                                                        <label for="guardian_name_bn" class="form-label">অভিভাবকের নাম
                                                            <small>(যদি থাকে)</small></label>
                                                        <input type="text" class="form-control np-teacher-input"
                                                            id="guardian_name_bn" name="guardian_name_bn"
                                                            value="{{ $student->guardian_name_bn }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="mb-3">
                                                        <label for="guardian_mobile_no" class="form-label">অভিভাবকের
                                                            মোবাইল নম্বর </label>
                                                        <input type="number" class="form-control np-teacher-input num"
                                                            maxlength="11" id="guardian_mobile_no"
                                                            name="guardian_mobile_no"
                                                            value="{{ $student->guardian_mobile_no }}"
                                                            placeholder="01xxxxxxxxx">
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-sm-12 mb-3">
                                                    <div class="">
                                                        <label for="image" class="form-label">ছবি <small
                                                                class="text-danger">300px X 80px (সাইজ সর্বোচ্চ 100
                                                                KB)</small></label>
                                                        <input type="file" name="image"
                                                            class="form-control np-teacher-input"
                                                            value="{{ @$student->image }}">
                                                    </div>
                                                </div>

                                                @if (@$student->image)
                                                    <div class="col-md-6 col-sm-12 mb-3">
                                                        <div class="">
                                                            <img src="{{ Storage::url(@$student->image) }}"
                                                                class="img-fluid" alt="Main logo" style="height: 80px;">
                                                        </div>
                                                    </div>
                                                @endif



                                                <div class="col-md-6 col-sm-12">
                                                    <div>
                                                        <label for="branch1" class="form-label">ব্রাঞ্চ <span
                                                                class="error">*</span></label>
                                                        <div class="input-group">
                                                            <select class="form-select np-teacher-input"
                                                                aria-label="Default select example" id="branch1"
                                                                name="branch">
                                                                <option value="">ব্রাঞ্চ নির্বাচন করুন</option>
                                                                @foreach ($branchs as $branch)
                                                                    <option value="{{ @$branch->uid }}"
                                                                        @if ($student_class_info->classRoom->branch->uid == $branch->uid) selected @endif>
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



                                            </div>
                                            <div class="row">
                                                <div class="col-md-6"></div>
                                                <div class="col-md-3 col-sm-12">
                                                    <a href="{{ route('student.index') }}"
                                                        class="btn btn-primary np-btn-form-cancel mt-3">বাতিল করুন <img
                                                            src="{{ asset('frontend/noipunno/images/icons/arrow-right.svg') }}"
                                                            alt="logo"></a>
                                                </div>
                                                <div class="col-md-3 col-sm-12">
                                                    <button type="submit"
                                                        class="btn btn-primary np-btn-form-submit mt-3">তথ্য হালনাগাদ করুন
                                                        <img src="{{ asset('frontend/noipunno/images/icons/arrow-right.svg') }}"
                                                            alt="logo"></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

        $(document).on('change', '#shift1', function() {
            $('#class1').trigger('change');
        });
        $(document).on('change', '#branch1', function() {
            var branch_id = $('#branch1').val();
            var eiin = {{ auth()->user()->eiin }};
            $.ajax({
                url: "{{ route('branch_wise_version') }}",
                type: "GET",
                data: {
                    branch_id: branch_id,
                    eiin: eiin,
                },
                success: function(data) {
                    $('#version1').html('');
                    $('#version1').html('<option value ="">ভার্সন নির্বাচন করুন</option>');
                    $('#shift1').html('');
                    $('#shift1').html('<option value ="">শিফট নির্বাচন করুন</option>');

                    var vselected = "{{ $student_class_info['classRoom']['version']['uid'] }}";
                    var sselected = "{{ $student_class_info['classRoom']['shift']['uid'] }}";

                    if (data.versions) {
                        $.each(data.versions, function(index, category) {
                            $('#version1').append('<option value ="' + category.uid + '"' + ((
                                    category
                                    .uid == vselected) ? ('selected') : '') + '>' + category
                                .version_name +
                                '</option>');
                        });
                        $('#version1').trigger('change');
                    }
                    if (data.shifts) {
                        $.each(data.shifts, function(index, category) {
                            $('#shift1').append('<option value ="' + category.uid + '"' + ((
                                    category
                                    .uid == sselected) ? ('selected') : '') + '>' + category
                                .shift_name +
                                '</option>');
                        });
                        $('#shift1').trigger('change');
                    }
                }

            });
        });
        $(function() {
            $('#branch1').trigger('change');
        });
        $(document).on('change', '#class1', function() {
            var class_id = $('#class1').val();
            var branch_id = $('#branch1').val();
            var shift_id = $('#shift1').val();
            var version_id = $('#version1').val();
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
                    $('#section1').html('');
                    $('#section1').html('<option value ="">সেকশন নির্বাচন করুন</option>');
                    var selected = "{{ $student_class_info['classRoom']['section']['uid'] }}";
                    // var selected = "{{ @$student['section'] }}";

                    if (data.sections) {
                        $.each(data.sections, function(index, category) {
                            $('#section1').append('<option value ="' + category
                                .uid + '"' + ((category.uid == selected) ? ('selected') :
                                    '') + '>' + category.section_name +
                                '</option>');
                        });
                        $('#section1').trigger('change');
                    }
                }
            });
        });

        $(document).on('change', '#division_id', function() {
            var division_id = $('#division_id').val();
            $.ajax({
                url: "{{ route('division_wise_district') }}",
                type: "GET",
                data: {
                    division_id: division_id,
                },
                success: function(data) {
                    // $('#version_id').html('<option value ="">Please Select</option>');
                    $('#district_id').html('');
                    $('#district_id').html('<option value ="">জেলা নির্বাচন করুন</option>');

                    var selected = "{{ @$student->district_id }}";

                    if (data.districts) {
                        $.each(data.districts, function(index, category) {
                            $('#district_id').append('<option value ="' + category.uid + '"' + (
                                    (
                                        category
                                        .uid == selected) ? ('selected') : '') + '>' +
                                category
                                .district_name_bn +
                                '</option>');
                        });
                        $('#district_id').trigger('change');
                    }
                }
            });
        });

        $(document).on('change', '#district_id', function() {
            var district_id = $('#district_id').val();
            $.ajax({
                url: "{{ route('district_wise_upazila') }}",
                type: "GET",
                data: {
                    district_id: district_id,
                },
                success: function(data) {
                    // $('#version_id').html('<option value ="">Please Select</option>');
                    $('#upazila_id').html('');
                    $('#upazila_id').html('<option value ="">উপজেলা নির্বাচন করুন</option>');

                    var selected = "{{ @$student->upazilla_id }}";

                    if (data.upazilas) {
                        $.each(data.upazilas, function(index, category) {
                            $('#upazila_id').append('<option value ="' + category.uid + '"' + ((
                                    category
                                    .uid == selected) ? ('selected') : '') + '>' + category
                                .upazila_name_bn +
                                '</option>');
                        });
                    }
                }
            });
        });
        $(function() {
            $('#division_id').trigger('change');
        });
    </script>
@endsection
