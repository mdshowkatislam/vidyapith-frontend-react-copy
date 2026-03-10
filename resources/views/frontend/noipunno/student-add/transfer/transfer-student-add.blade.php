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
                            </div>
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
