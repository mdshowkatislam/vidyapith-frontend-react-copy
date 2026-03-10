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
                                        <h2 class="title">শিক্ষক ব্যবস্থাপনা</h2>

                                        <nav aria-label="breadcrumb">
                                            <ol class="breadcrumb np-breadcrumb">
                                                <li class="breadcrumb-item"><a href="{{ route('home') }}">
                                                        <img src="{{ asset('frontend/noipunno/images/icons/home.svg') }}"
                                                            alt="">
                                                        ড্যাশবোর্ড
                                                    </a></li>
                                                <li class="breadcrumb-item active" aria-current="page">শিক্ষক ব্যবস্থাপনা
                                                </li>
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
        <div class="container mb-5">
            {{-- <div class="row"> --}}
            {{-- <div class="col-md-12"> --}}
            <section class="np-teacher-add-form">
                <div class="row my-4">
                    <div class="col-md-8">
                        <h2 class="np-form-title">শিক্ষক যুক্ত করুন</h2>

                    </div>
                    <div class="col-md-4">
                        <a class="np-route" href="{{ route('teacher.index') }}">
                            <p class="btn np-btn-form-submit border-0 rounded-1"><i class="fa-solid fa-list"></i> শিক্ষকের
                                তালিকা</p>
                        </a>
                    </div>

                </div>
                <div class="container mb-3">

                    <form method="GET" action="{{ route('teacher.add') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row np-input-form-bg mt-2">
                            <label for="beiin-psid" class="form-label">শিক্ষকের পিডিএস আইডি / ইনডেক্স নম্বর দিয়ে শিক্ষকের
                                তথ্য খুঁজুন</label>
                            <div class="col-md-8 col-sm-8">
                                <div>
                                    <div class="input-group">
                                        <input type="text" class="form-control np-teacher-input" id="searchInput"
                                            name="pds_index" placeholder="201395984..">
                                    </div>
                                    <div id="suggestedResults" class="list-group np-search-suggesition-list"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <button type="submit" class="btn btn-primary np-btn-form-search teacher_exists">তথ্য খুঁজুন
                                    <img src="{{ asset('frontend/noipunno/images/icons/arrow-right.svg') }}"
                                        alt="logo"></button>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="np-input-form-bg" id="addTeacher">
                    <div class="container">

                        <form method="POST" action="{{ route('teacher.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <!-- <div class="col-md-6 col-sm-12">
                                            <div class="mb-3">
                                                <label for="loginId" class="form-label">শিক্ষকের Login ID (System auto generated)</label>
                                                <input type="text" class="form-control np-teacher-input" id="loginId" readonly>
                                            </div>
                                        </div> -->
                            </div>
                            <input type="hidden" name="pdsid" value="{{ @$teacher_found->pdsid }}">
                            <div class="row">
                                <input type="hidden" name="is_foreign" id="is_foreign" value="{{ @$institute->is_foreign ?? 0 }}">
                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label for="teacherName" class="form-label">শিক্ষকের নাম (বাংলা)</label>
                                        <input type="text" name="name_bn" class="form-control np-teacher-input"
                                            id="teacherName" value="{{ @$teacher_found->fullname ?? (@$teacher_found->employee_name ?? old('name_en')) }}">
                                    </div>
                                    @if ($errors->has('name_bn'))
                                        <div class="text-danger">
                                            {{ $errors->first('name_bn') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label for="teacherName" class="form-label">শিক্ষকের নাম (ইংরেজি)<span
                                                class="error">*</span></label>
                                        <input type="text" name="name_en" class="form-control np-teacher-input"
                                            id="teacherName"
                                            value="{{ @$teacher_found->fullname ?? (@$teacher_found->employee_name ?? old('name_en')) }}">
                                        @if ($errors->has('name_en'))
                                            <div class="text-danger">
                                                {{ $errors->first('name_en') }}
                                            </div>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label for="teacherDesignation" class="form-label">শিক্ষকের পদবি <span
                                                class="error">*</span></label>
                                        {{-- <input type="text" name="designation" class="form-control np-teacher-input" id="teacherDesignation"> --}}
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input" aria-label="Default select example"
                                                name="designation" id="teacherDesignation">
                                                <option value="">শিক্ষকের পদবি নির্বাচন করুন</option>
                                                @foreach ($designations as $designation)
                                                    <option value="{{ $designation->uid }}"
                                                        @if (old('designation') == $designation->uid) selected @endif>
                                                        {{ $designation->designation_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($errors->has('designation'))
                                            <div class="text-danger">
                                                {{ $errors->first('designation') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label for="teacherPhone" class="form-label">শিক্ষকের ফোন নম্বর <span
                                                class="error">*</span></label>
                                        <input type="number" name="mobile_no" class="form-control np-teacher-input"
                                            id="teacherPhone"
                                            value="{{ @$teacher_found->mobileno ?? (@$teacher_found->mobile_number ?? old('mobile_no')) }}">
                                        @if ($errors->has('mobile_no'))
                                            <div class="text-danger">
                                                {{ $errors->first('mobile_no') }}
                                            </div>
                                        @endif
                                    </div>

                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label for="teacherEmail" class="form-label">ইমেইল আইডি</label>
                                        <input type="email" name="email" class="form-control np-teacher-input"
                                            id="teacherEmail" value="{{ @$teacher_found->email ?? old('email') }}">
                                    </div>
                                    @if ($errors->has('email'))
                                        <div class="text-danger">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label for="teacherNid" class="form-label">এনআইডি</label>
                                        <input type="number" name="nid" class="form-control np-teacher-input"
                                            id="teacherNid" value="{{ @$teacher_found->nid ?? old('nid') }}">
                                    </div>
                                    @if ($errors->has('nid'))
                                        <div class="text-danger">
                                            {{ $errors->first('nid') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-6 col-sm-12 d-none">
                                    <div class="mb-3">
                                        <label for="teacher_type" class="form-label">শিক্ষকের ধরন <span
                                                class="error">*</span></label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input"
                                                aria-label="Default select example" name="teacher_type"
                                                id="teacher_type">
                                                <option value="">শিক্ষকের ধরন নির্বাচন করুন</option>
                                                <option value="1" @if (old('teacher_type') == 1) selected @endif>
                                                    PDS ধারী নিয়মিত শিক্ষক</option>
                                                <option value="2" @if (old('teacher_type') == 2) selected @endif>
                                                    PDS বিহীন নিয়মিত শিক্ষক</option>
                                            </select>
                                        </div>
                                        @if ($errors->has('teacher_type'))
                                            <small
                                                class="help-block form-text text-danger">{{ $errors->first('teacher_type') }}</small>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 d-none">
                                    <div class="mb-3">
                                        <label for="access_type" class="form-label">অ্যাক্সেসের ধরন <span
                                                class="error">*</span></label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input mutiple-select2"
                                                aria-label="Default select example" name="access_type[]" id="access_type"
                                                multiple="multiple">
                                                <option value="1" @if (is_array(old('access_type')) && in_array('1', old('access_type'))) selected @endif>
                                                    সহকারী প্রধান শিক্ষক</option>
                                                <option value="2" @if (is_array(old('access_type')) && in_array('2', old('access_type'))) selected @endif>
                                                    শ্রেণী শিক্ষক</option>
                                                <option value="3" @if (is_array(old('access_type')) && in_array('3', old('access_type'))) selected @endif>
                                                    বিষয় শিক্ষক</option>
                                            </select>
                                        </div>
                                        @if ($errors->has('access_type'))
                                            <small
                                                class="help-block form-text text-danger">{{ $errors->first('access_type') }}</small>
                                        @endif
                                    </div>
                                </div>
                                @if ( @$institute->is_foreign == 1)
                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label for="country_uid" class="form-label">দেশ <span
                                                class="error">*</span></label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input select2"
                                                aria-label="Default select example" name="country_uid" id="country_uid">
                                                <option value="">দেশ নির্বাচন করুন</option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->uid }}"
                                                        @if (old('country_uid') == $country->uid) selected @endif>
                                                        {{ $country->countryname }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($errors->has('country_uid'))
                                            <div class="text-danger">
                                                {{ $errors->first('country_uid') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label for="city" class="form-label">শহর </label>
                                        <div class="input-group">
                                            <input type="text" name="city" class="form-control np-teacher-input"
                                            id="city" value="{{ old('city') }}">
                                        </div>
                                        @if ($errors->has('city'))
                                            <div class="text-danger">
                                                {{ $errors->first('city') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label for="state" class="form-label">প্রদেশ </label>
                                        <div class="input-group">
                                            <input type="text" name="state" class="form-control np-teacher-input"
                                            id="state" value="{{ old('state') }}">
                                        </div>
                                        @if ($errors->has('state'))
                                            <div class="text-danger">
                                                {{ $errors->first('state') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label for="zip_code" class="form-label">জিপ কোড </label>
                                        <div class="input-group">
                                            <div class="input-group">
                                                <input type="text" name="zip_code" class="form-control np-teacher-input"
                                                id="zip_code" value="{{ old('zip_code') }}">
                                            </div>
                                        </div>
                                        @if ($errors->has('zip_code'))
                                            <div class="text-danger">
                                                {{ $errors->first('zip_code') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @else
                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label for="division_id" class="form-label">বিভাগ <span
                                                class="error">*</span></label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input"
                                                aria-label="Default select example" name="division_id" id="division_id">
                                                <option value="">বিভাগ নির্বাচন করুন</option>
                                                @foreach ($divisions as $division)
                                                    <option value="{{ $division->uid }}"
                                                        @if (old('division_id') == $division->uid) selected @endif>
                                                        {{ $division->division_name_bn ?? $division->division_name_en }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($errors->has('division_id'))
                                            <div class="text-danger">
                                                {{ $errors->first('division_id') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label for="district_id" class="form-label">জেলা <span
                                                class="error">*</span></label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input"
                                                aria-label="Default select example" name="district_id" id="district_id">
                                                <option value="">জেলা নির্বাচন করুন</option>
                                                {{-- @foreach ($districts as $district)
                                                    <option class="district-option division-{{$district->division_id}}" value="{{ $district->uid }}" @if (old('district_id') == $district->uid) selected @endif>{{ $district->district_name_bn ?? $district->district_name_en }}
                                                    </option>
                                                    @endforeach --}}
                                            </select>
                                        </div>
                                        @if ($errors->has('district_id'))
                                            <div class="text-danger">
                                                {{ $errors->first('district_id') }}
                                            </div>
                                        @endif

                                        {{-- @if ($errors->has('district_id'))
                                            <small class="help-block form-text text-danger">{{ $errors->first('district_id') }}</small>
                                            @endif --}}
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label for="upazila_id" class="form-label">উপজেলা <span
                                                class="error">*</span></label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input"
                                                aria-label="Default select example" name="upazila_id" id="upazila_id">
                                                <option value="">উপজেলা নির্বাচন করুন</option>
                                                {{--    @foreach ($upazilas as $upazila)
                                                <option class="upazila-option district-{{$upazila->district_id}}" value="{{ $upazila->uid }}" @if (old('upazila_id') == $upazila->uid) selected @endif>{{ $upazila->upazila_name_bn ?? $upazila->upazila_name_en }}
                                                </option>
                                                @endforeach --}}
                                            </select>
                                        </div>
                                        @if ($errors->has('upazila_id'))
                                            <div class="text-danger">
                                                {{ $errors->first('upazila_id') }}
                                            </div>
                                        @endif

                                        {{-- @if ($errors->has('upazila_id'))
                                            <small class="help-block form-text text-danger">{{ $errors->first('upazila_id') }}</small>
                                            @endif --}}
                                    </div>
                                </div>
                                @endif

                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label for="blood_group" class="form-label">রক্তের গ্রুপ</label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input"
                                                aria-label="Default select example" name="blood_group" id="blood_group">
                                                <option value="">রক্তের গ্রুপ নির্বাচন করুন</option>
                                                <option value="A+" @if (old('blood_group') == 'A+') selected @endif>A+</option>
                                                <option value="A-" @if (old('blood_group') == 'A-') selected @endif>A-</option>
                                                <option value="B+" @if (old('blood_group') == 'B+') selected @endif>B+</option>
                                                <option value="B-" @if (old('blood_group') == 'B-') selected @endif>B-</option>
                                                <option value="AB+" @if (old('blood_group') == 'AB+') selected @endif>AB+</option>
                                                <option value="AB-" @if (old('blood_group') == 'AB-') selected @endif>AB-</option>
                                                <option value="O+" @if (old('blood_group') == 'O+') selected @endif>O+</option>
                                                <option value="O-" @if (old('blood_group') == 'O-') selected @endif>O-</option>
                                            </select>
                                        </div>
                                        @if ($errors->has('blood_group'))
                                            <div class="text-danger">
                                                {{ $errors->first('blood_group') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label for="emergency_contact" class="form-label">জরুরী যোগাযোগের নম্বর</label>
                                        <input type="number" name="emergency_contact" class="form-control np-teacher-input"
                                            id="emergency_contact">
                                        @if ($errors->has('emergency_contact'))
                                            <div class="text-danger">
                                                {{ $errors->first('emergency_contact') }}
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                {{-- <div class="col-md-6"></div> --}}
                                <div class="col-md-6 col-sm-12"></div>
                                <div class="col-md-3 col-sm-12">
                                    <a href="{{ route('teacher.index') }}"
                                        class="btn btn-primary np-btn-form-cancel mt-3">বাতিল করুন <img
                                            src="{{ asset('frontend/noipunno/images/icons/arrow-right.svg') }}"
                                            alt="logo"></a>
                                </div>
                                {{-- <div class="col-md-3 col-sm-12"></div> --}}
                                {{-- <div class="col-md-3 col-sm-12"></div> --}}
                                <div class="col-md-3 col-sm-12">
                                    <button type="submit" class="btn btn-primary np-btn-form-submit mt-3">তথ্য সংযোজন
                                        করুন <img src="{{ asset('frontend/noipunno/images/icons/arrow-right.svg') }}"
                                            alt="logo"></button>
                                </div>

                            </div>
                            {{-- <div class="row">
                                    <div class="col-md-8"></div>
                                    <div class="col-md-4 col-sm-12">
                                        <button type="submit" class="btn btn-primary np-btn-form-submit mt-3">তথ্য সংযোজন করুন <img src="{{ asset('frontend/noipunno/images/icons/arrow-right.svg') }}" alt="logo"></button>
                                    </div>
                                </div> --}}
                        </form>
                    </div>
                </div>
            </section>
        </div>

    </div>
    <style>
        span.error {
            color: red;
        }
    </style>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).on('change', '#division_id', function() {
        var division_id = $('#division_id').val();
        $.ajax({
            url: "{{ route('division_wise_district') }}",
            type: "GET",
            data: {
                division_id: division_id,
            },
            success: function(data) {
                $('#district_id').html('');
                $('#district_id').html('<option value ="">জেলা নির্বাচন করুন</option>');

                var selected = "{{ @$editData->district_id }}";

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
                $('#upazila_id').html('');
                $('#upazila_id').html('<option value ="">উপজেলা নির্বাচন করুন</option>');

                var selected = "{{ @$editData->upazila_id }}";

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
</script>

<script>
    $(document).ready(function() {
        $('.mutiple-select2').select2();
    });

    const suggestedResultsData = [
        "Suggested Result 1",
        "Suggested Result 2",
        "Suggested Result 3",
        "Suggested Result 4",
        "Suggested Result 5",
    ];
    /*
         // for dropdown sorting
         document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('division_id').addEventListener('change', function() {
                var divisionId = this.value;
                var districtOptions = document.querySelectorAll('.district-option');

                // Hide all subject options
                districtOptions.forEach(function(option) {
                    option.style.display = 'none';
                });

                // Show subject options that belong to the selected class
                var divisionDistrictOptions = document.querySelectorAll('.district-option.division-' + divisionId);
                divisionDistrictOptions.forEach(function(option) {
                    option.style.display = 'block';
                });
            });

            document.getElementById('district_id').addEventListener('change', function() {
                var districtId = this.value;
                var upazilaOptions = document.querySelectorAll('.upazila-option');

                // Hide all subject options
                upazilaOptions.forEach(function(option) {
                    option.style.display = 'none';
                });

                // Show subject options that belong to the selected class
                var districtUpazilaOptions = document.querySelectorAll('.upazila-option.district-' + districtId);
                districtUpazilaOptions.forEach(function(option) {
                    option.style.display = 'block';
                });
            });

            addPDSTeacherSearchEventListener();
        });
    */
    function updateSuggestedResults(query) {
        const suggestedResults = document.getElementById("suggestedResults");
        suggestedResults.innerHTML = "";

        const filteredResults = suggestedResultsData.filter(result =>
            result.toLowerCase().includes(query.toLowerCase())
        );

        filteredResults.forEach(result => {
            const listItem = document.createElement("a");
            listItem.classList.add("list-group-item");
            listItem.textContent = result;
            listItem.addEventListener("click", function() {
                document.getElementById("searchInput").value = result;
                suggestedResults.innerHTML = "";
            });
            suggestedResults.appendChild(listItem);
        });
    }
    // Event listener for the search input
    // const searchInput = document.getElementById("searchInput");
    // searchInput.addEventListener("input", function() {
    //     const query = this.value.trim();
    //     updateSuggestedResults(query);
    // });

    function fetchDataForPdsidTeacher(pdsid) {
        $.ajax({
            url: '{{ route('teacher.getAllTeachersByPdsID') }}',
            type: 'GET',
            data: {
                id: pdsid,
                '_token': $('input[name="_token"]').val(),
            },
            success: function(data) {
                document.querySelector('.teacher-data-list').style.display = 'none';
                document.querySelector('.pdsid-teacher-search-result').innerHTML = "";

                for (const teacher of data) {
                    let url = `{{ route('teacher.index') }}`;
                    url = `${url}/${teacher.pdsid}/emis`;
                    const teacherElem = `
                    <a href="${url}" class="np-route">
                                    <div class="card np-card np-profile-card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-auto np-card-image">
                                                    <img src="{{ asset('frontend/noipunno/images/avatar/teacher.png') }}" alt="Profile Image" class="rounded-circle" width="50">
                                                </div>
                                                <div class="col np-card-details">
                                                    <p class="np-card-title">${teacher?.fullname_bn ?? teacher?.fullname} </p>
                                                    <p class="np-card-subtitle">${teacher?.designation}</p>
                                                    <p class="np-card-subtitle">ID: ${teacher?.pdsid}</p>
                                                    <p class="btn np-btn-form-submit border-0 rounded-1"><i class="fa-solid fa-circle-plus"></i> যুক্ত করুন</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                    `;

                    document.querySelector('.pdsid-teacher-search-result').insertAdjacentHTML('beforeend',
                        teacherElem);
                }

            }
        }, "json");
    }

    function debounce(cb, interval, immediate) {
        var timeout;

        return function() {
            var context = this,
                args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) cb.apply(context, args);
            };

            var callNow = immediate && !timeout;

            clearTimeout(timeout);
            timeout = setTimeout(later, interval);

            if (callNow) cb.apply(context, args);
        };
    };

    function searchKeyPressCallback() {
        const searchInputElem = document.querySelector('#searchInput');
        fetchDataForPdsidTeacher(searchInputElem.value);
    }

    function clearSearchInput() {
        const searchInputElem = document.querySelector('#searchInput');
        searchInputElem.value = "";
        fetchDataForPdsidTeacher("");
    }

    function addPDSTeacherSearchEventListener() {
        const searchInputElem = document.querySelector('#searchInput');
        if (searchInputElem) {
            searchInputElem.addEventListener('keydown', debounce(searchKeyPressCallback, 1000));
            document.querySelector('.clear-search').addEventListener('click', clearSearchInput);
        }
    }
</script>
