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
                                <!-- <div class="option-section">
                                        <div class="fav-icon">
                                            <img src="{{ asset('frontend/noipunno/images/icons/fav-start-icon.svg') }}" alt="">
                                        </div>
                                        <div class="dots-icon">
                                            <img src="{{ asset('frontend/noipunno/images/icons/3-dot-vertical.svg') }}" alt="">
                                        </div>
                                    </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        {{-- @include('frontend.layouts.notice') --}}
        <div class="container mb-5">
            <section class="np-teacher-list">
                <div class="row my-4">
                    <div class="col-md-8">
                        <h2 class="np-form-title">শিক্ষকের তালিকা (মোট: {{ en2bn($myTeachers->total()) }})</h2>

                    </div>
                    <div class="col-md-4">
                        <a class="np-route" href="{{ route('teacher.add') }}">
                            <p class="btn np-btn-form-submit border-0 rounded-1"><i class="fa-solid fa-circle-plus"></i>
                                শিক্ষক যুক্ত করুন (PDS ধারী / PDS বিহীন)</p>
                        </a>
                    </div>
                </div>
                <div class="col-md-12 mb-3 np-input-form-bg ">

                    <form method="GET" action="{{ route('teacher.index') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row mt-2">
                            <label for="beiin-psid" class="form-label">শিক্ষকের পিডিএস আইডি / ইনডেক্স নম্বর দিয়ে
                                শিক্ষকের তথ্য খুঁজুন</label>
                            <div class="col-md-8 col-sm-8">
                                <div class="input-group">
                                    <input type="search" class="form-control" name="search" value="{{ @$search }}"
                                        class="form-control" placeholder="Search..." />
                                    {{-- <span class="search-icon">
                                        <span> <img src="{{ asset('frontend/noipunno/images/icons/close-red.svg') }}"
                                                class="np-search-field-icon clear-search" alt="logo"></span>
                                    </span> --}}
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <button type="submit" class="btn btn-primary np-btn-form-search">তথ্য খুঁজুন <img
                                        src="{{ asset('frontend/noipunno/images/icons/arrow-right.svg') }}"
                                        alt="logo"></button>
                            </div>
                        </div>

                    </form>
                </div>

                <div class="col-md-12">
                    <div class="card np-card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table np-table">
                                    {{-- <table class="table np-table" id="n_dataTable"> --}}
                                    <thead>
                                        <tr>
                                            <th scope="col">শিক্ষকের নাম
                                                {{-- <span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span> --}}
                                            </th>
                                            <th scope="col">পদবি
                                                {{-- <span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span> --}}
                                            </th>
                                            <th scope="col">ফোন নম্বর
                                                {{-- <span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span> --}}
                                            </th>
                                            {{-- <th scope="col">ইমেইল আইডি</th> --}}
                                            <th scope="col">PDS ID/Index Number/SGN
                                                {{-- <span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span> --}}
                                            </th>
                                            {{-- <th scope="col">একাউন্ট এর বর্তমান অবস্থা <span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span></th> --}}
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($myTeachers as $teacher)
                                            <tr>
                                                <td scope="row"><span class="icon"><img
                                                            src="{{ asset('frontend/noipunno/images/icons/user.svg') }}"
                                                            alt=""></span>{{ @$teacher->name_bn ?? @$teacher->name_en }}
                                                    </th>
                                                <td scope="row">
                                                    {{ @$teacher->designations->designation_name ?? @$teacher->designation }}
                                                    </th>
                                                <td scope="row">{{ @$teacher->mobile_no }}</th>
                                                    {{-- <td scope="row">{{@$teacher->email}}</th> --}}
                                                <td scope="row">
                                                    {{ @$teacher->pdsid ?? (@$teacher->index_number ?? $teacher->caid) }}
                                                    </th>
                                                    {{-- <td scope="row">{{@$teacher->isactive == 1 ? 'সক্রিয়' : 'সক্রিয় নয়'}}</th> --}}
                                                <td scope="row">
                                                    <div class="action-content">
                                                        @if ($teacher->uid)
                                                            <a href="{{ route('teacher.edit', @$teacher->uid) }}"
                                                                class="np-route">
                                                                <button class="btn np-edit-btn-small">
                                                                    <img src="{{ asset('frontend/noipunno/images/icons/edit-white.svg') }}" alt="">
                                                                </button>
                                                            </a>
                                                            @if ($teacher->caid != auth()->user()->caid)
                                                                <a class="btn np-delete-btn-small delete_module"
                                                                    title="Delete" data-id="{{ $teacher->uid }}"
                                                                    data-token={{ csrf_token() }}
                                                                    data-route="{{ route('teacher.delete') }}">
                                                                    <i class="fa fa-trash np-delete-btn-small-icon"></i></a>
                                                            @endif
                                                            {{-- <img src="{{ asset('frontend/noipunno/images/icons/3-dots-horizontal.svg') }}" alt=""> --}}
                                                        @endif
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
                            {{ $myTeachers->appends(request()->input())->links('pagination::bootstrap-5') }}
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

                                            <nav aria-label="Page navigation example">
                                                <ul class="np-pagination pagination justify-content-end">
                                                    <li class="page-item np-card">
                                                        <a class="page-link" href="#"><img src="{{ asset('frontend/noipunno/images/icons/chevron-left.svg') }}" alt=""></a>
                                                    </li>
                                                    <li class="page-item np-card"><a class="page-link" href="#">1</a></li>
                                                    <li class="page-item np-card"><a class="page-link active" href="#">2</a></li>
                                                    <li class="page-item np-card"><a class="page-link" href="#">3</a></li>
                                                    <li class="page-item np-card">
                                                        <a class="page-link" href="#">
                                                            <img src="{{ asset('frontend/noipunno/images/icons/chevron-right.svg') }}" alt="">
                                                        </a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div> --}}

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
