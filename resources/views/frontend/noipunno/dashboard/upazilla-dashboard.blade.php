@extends('frontend.layouts.noipunno')

@section('content')
    <div class="dashboard-section">
        <section class="np-breadcumb-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-2">
                        <div class="col-md-12 mt-5">
                            <div class="card np-card head-teacher-card">
                                <div class="head-teacher-top w-100">
                                    <div class="d-flex justify-content-end ">
                                        <button class="btn">
                                            <img src="{{ asset('frontend/images/edit.svg') }}" />
                                        </button>
                                    </div>
                                    <div class="d-flex flex-column justify-content-center align-items-center ">
                                        <img src="{{ asset('frontend/noipunno/images/avatar/teacher.png') }}"
                                            class="border rounded-circle p-3 bg-light" alt="">
                                        <p class="mt-3 p-2">প্রধান শিক্ষক</p>
                                    </div>

                                    <div class="head-teacher-top-icons d-flex justify-content-center align-items-center">
                                        <img src="{{ asset('frontend/noipunno/images/icons/star.svg') }}" />
                                        <img src="{{ asset('frontend/noipunno/images/icons/message.svg') }}" />
                                        <img src="{{ asset('frontend/noipunno/images/icons/moon.svg') }}" />

                                    </div>
                                </div>
                                <div class="head-teacher-bottom d-flex flex-column ">
                                    <div class="w-100 d-flex flex-column  align-items-center justify-content-center mt-3 ">
                                        <h5>
                                            {{-- {{$user->name}} --}}
                                        </h5>
                                        <small>
                                            {{-- {{$user->caid}} --}}
                                        </small>
                                        <small>
                                            {{-- {{@$institute->institute_name}} --}}
                                        </small>
                                    </div>
                                    <button class="m-3 profile-button">
                                        <img src="{{ asset('frontend/noipunno/images/icons/eye.svg') }}" />

                                        <p class="m-0">
                                            আমার প্রোফাইল
                                        </p>
                                    </button>

                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-12 mt-5">
                                <div class="card np-card" style="padding: 10px">
                                    <div class="header d-flex justify-content-between">
                                        <h2 class="title" style="font-size: 16px;"> শিক্ষার্থীর হাজিরা</h2>
                                        <div class="filters d-flex align-items-center" style="column-gap: 10px">
                                            <div class="input-group d-flex align-items-center np-selection-group"
                                                style="column-gap: 10px">
                                                <label for="">সময়</label>
                                                <select class="form-select" aria-label="Default select example">
                                                    <option value="1">সাপ্তাহিক</option>
                                                </select>
                                            </div>

                                            <div class="input-group d-flex align-items-center np-selection-group"
                                                style="column-gap: 10px">
                                                <label for="">বিদ্যালয়</label>
                                                <select class="form-select" aria-label="Default select example">
                                                    <option>সব</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="student-attendance"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="row mt-5" style="row-gap: 10px;">
                            <div class="col-md-12 card np-card np-total-count-card">
                                <div class="card-body d-flex align-items-center">
                                    <div class="content-details d-flex">
                                        <div class="contents">
                                            <h2 class="sub-title">সর্বমোট</h2>
                                            <h2 class="title">বিদ্যালয়ের</h2>
                                        </div>
                                        <div class="np-badge np-badge-count-info">
                                            আপনার স্কুল এ
                                        </div>
                                    </div>
                                    <div class="count-details">
                                        ৪১
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 card np-card np-total-count-card">
                                <div class="card-body d-flex align-items-center">
                                    <div class="content-details d-flex">
                                        <div class="contents">
                                            <h2 class="sub-title">সর্বমোট</h2>
                                            <h2 class="title">শিক্ষার্থী</h2>
                                        </div>
                                        <div class="np-badge np-badge-count-info">
                                            শ্রেণী - ষষ্ঠ - সপ্তম
                                        </div>
                                    </div>
                                    <div class="count-details">
                                        ৯২৩
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 card np-card np-total-count-card">
                                <div class="card-body d-flex align-items-center">
                                    <div class="content-details d-flex">
                                        <div class="contents">
                                            <h2 class="sub-title">সর্বমোট</h2>
                                            <h2 class="title">শিক্ষক</h2>
                                        </div>
                                        <div class="np-badge np-badge-count-info">
                                            আপনার স্কুল এ
                                        </div>
                                    </div>
                                    <div class="count-details">
                                        ৫২
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    {{-- upazilla tab starts --}}
                    <div class="col-md-3 ">
                        <div class="row mt-5 upazila-tab ms-1">
                            {{-- tab container  starts --}}
                            <div class="upazila-tab-header ">
                                <div class="p-3 mb-3">
                                    <div>
                                        <h5>অনুরোধ</h5>
                                    </div>
                                    <div>
                                        <small>
                                            বিষয়গুলি আপনার পর্যালোচনা করা দরকার
                                        </small>
                                    </div>

                                </div>

                                <ul class="nav nav-tabs upazilla-tab-container" id="myTabs" role="tablist">
                                    <li class="nav-item upazilla-tab" role="presentation">
                                        <a class="nav-link active upazilla-tab-link" id="tab1-tab" data-bs-toggle="tab"
                                            href="#tab1" role="tab" aria-controls="tab1" aria-selected="true">
                                            <img src="{{ asset('frontend/noipunno/images/icons/information.svg') }}"
                                                alt="">
                                            আবেদন
                                        </a>
                                    </li>
                                    <li class="nav-item upazilla-tab" role="presentation">
                                        <a class="nav-link upazilla-tab-link" id="tab2-tab" data-bs-toggle="tab"
                                            href="#tab2" role="tab" aria-controls="tab2" aria-selected="false">
                                            <img src="{{ asset('frontend/noipunno/images/icons/advertise.svg') }}">
                                            বিজ্ঞপ্তি
                                        </a>
                                    </li>
                                </ul>
                                {{-- tab container ends --}}
                            </div>
                            {{-- Requsets start --}}
                            <div class="tab-content" id="myTabsContent">
                                <div class="tab-pane fade show active" id="tab1" role="tabpanel"
                                    aria-labelledby="tab1-tab">
                                    <div class="requests py-3">
                                        <div class="request py-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <img src="{{ asset('frontend/noipunno/images/icons/autobrightness.svg') }}"
                                                    alt="" class="" />
                                                <p class="p-0 m-0">
                                                    ফোন নম্বর পরিবর্তনের অনুরোধ করেছেন
                                                </p>
                                            </div>
                                            <div>
                                                <small>
                                                    সামিনা চৌধুরী | সহকারী শিক্ষক
                                                </small>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span>
                                                        <small class="badge text-dark">
                                                            Day
                                                        </small>
                                                    </span>
                                                    <span>
                                                        <small class="badge text-dark">
                                                            Section A
                                                        </small>
                                                    </span>
                                                    <span>
                                                        <small class="badge text-dark">
                                                            Class 6
                                                        </small>
                                                    </span>
                                                </div>

                                                <div>
                                                    <small>অনুরোধ করেছেন ৬ অক্টোবর ২০২৩</small>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="request py-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <img src="{{ asset('frontend/noipunno/images/icons/autobrightness.svg') }}"
                                                    alt="" class="" />
                                                <p class="p-0 m-0">
                                                    ফোন নম্বর পরিবর্তনের অনুরোধ করেছেন
                                                </p>
                                            </div>
                                            <div>
                                                <small>
                                                    সামিনা চৌধুরী | সহকারী শিক্ষক
                                                </small>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span>
                                                        <small class="badge text-dark">
                                                            Day
                                                        </small>
                                                    </span>
                                                    <span>
                                                        <small class="badge text-dark">
                                                            Section A
                                                        </small>
                                                    </span>
                                                    <span>
                                                        <small class="badge text-dark">
                                                            Class 6
                                                        </small>
                                                    </span>
                                                </div>

                                                <div>
                                                    <small>অনুরোধ করেছেন ৬ অক্টোবর ২০২৩</small>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="request py-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <img src="{{ asset('frontend/noipunno/images/icons/autobrightness.svg') }}"
                                                    alt="" class="" />
                                                <p class="p-0 m-0">
                                                    ফোন নম্বর পরিবর্তনের অনুরোধ করেছেন
                                                </p>
                                            </div>
                                            <div>
                                                <small>
                                                    সামিনা চৌধুরী | সহকারী শিক্ষক
                                                </small>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span>
                                                        <small class="badge text-dark">
                                                            Day
                                                        </small>
                                                    </span>
                                                    <span>
                                                        <small class="badge text-dark">
                                                            Section A
                                                        </small>
                                                    </span>
                                                    <span>
                                                        <small class="badge text-dark">
                                                            Class 6
                                                        </small>
                                                    </span>
                                                </div>

                                                <div>
                                                    <small>অনুরোধ করেছেন ৬ অক্টোবর ২০২৩</small>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Requsets end --}}

                            {{-- Buttons starts --}}
                            <div class="px-3 py-3">
                                <button class="requests-button">
                                    সব অনুরোধগুলি দেখুন
                                </button>

                            </div>
                            {{-- Buttons ends --}}
                        </div>
                    </div>
                    {{-- upazilla tab ends --}}

                </div>
            </div>
        </section>

        <!-- tab -->
        <div class="container mt-5">
            <div class="col-md-12 np-card card" style="padding: 20px;">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">বিদ্যালয়ের তালিকা</h5>
                    </div>
                    <div class="col-md-6 d-flex align-items-center justify-content-end">
                        <a class="np-btn np-btn-secondary np-btn-with-icon" href="#">
                            <img src="{{ asset('frontend/noipunno/images/icons/add-white.svg') }}" alt="">
                            <span>বিদ্যালয় যোগ করুন</span>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <ul class="nav nav-tabs np-student-tab-container" id="myTabs" role="tablist">
                            <li class="nav-item np-student-tab" role="presentation">
                                <a class="nav-link active upazilla-tab-link" id="tab1-tab" data-bs-toggle="tab"
                                    href="#tab1" role="tab" aria-controls="tab1" aria-selected="true">
                                    <img src="{{ asset('frontend/noipunno/images/icons/student-tab1.svg') }}"
                                        alt=""> সব
                                </a>
                            </li>
                            {{-- <li class="nav-item np-student-tab" role="presentation">
                                <a class="nav-link np-student-tab-link" id="tab2-tab" data-bs-toggle="tab" href="#tab2"
                                    role="tab" aria-controls="tab2" aria-selected="false">
                                    <img src="{{ asset('frontend/noipunno/images/icons/student-tab1.svg') }}"> প্রধান শিক্ষক আছে
                                </a>
                            </li> --}}
                        </ul>

                        <div class="tab-content" id="myTabsContent">
                            <div class="tab-pane fade show active" id="tab1" role="tabpanel"
                                aria-labelledby="tab1-tab">
                                <!-- Content for Tab 1 -->
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <section class="np-teacher-list">
                                            <div class="container" style="padding: 0">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="card np-card">
                                                            <div class="card-body">
                                                                <div class="table-responsive">
                                                                    <table class="table np-table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th scope="col" class="np-flag-header">
                                                                                    বিদ্যালয়ের নাম</th>
                                                                                <th scope="col" class="np-flag-header">
                                                                                    বিদ্যালয়ের আইডি</th>
                                                                                <th scope="col" class="np-flag-header">
                                                                                    বর্তমান অবস্থা</th>
                                                                                <th scope="col" class="np-flag-header">
                                                                                    বিদ্যালয়ের ঠিকানা</th>
                                                                                <th scope="col" class="np-flag-header">
                                                                                    প্রধান শিক্ষকের নাম</th>
                                                                                <th scope="col" class="np-flag-header">
                                                                                    ফোন নম্বর</th>
                                                                                <th scope="col" class="np-flag-header">
                                                                                    বিদ্যালয়ের ইমেইল</th>
                                                                                <th scope="col" class="np-flag-header">
                                                                                </th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td scope="row" class="np-flag-header">
                                                                                    পাবনা জেলা স্কুল</th>
                                                                                <td scope="row">১১১-২২২-৩৩৩</th>
                                                                                <td scope="row">
                                                                                    <div class="np-badge np-badge-info">সব
                                                                                        ঠিক আছে </div>
                                                                                    </th>
                                                                                <td scope="row">আব্দুল হামিদ রোড, পাবনা
                                                                                    </th>
                                                                                <td scope="row">তুষার কুমার দাশ</th>
                                                                                <td scope="row">১১১-২২২-৩৩৩</th>
                                                                                <td scope="row">school@email.com</th>
                                                                                <td scope="row">
                                                                                    <div class="actions">
                                                                                        <a href="#"
                                                                                            class="np-badge np-badge-info">
                                                                                            <img src="{{ asset('frontend/noipunno/images/icons/eye-info.svg') }}"
                                                                                                alt="">
                                                                                            View
                                                                                        </a>
                                                                                        <a href="#"
                                                                                            class="np-badge np-badge-warning">
                                                                                            <img src="{{ asset('frontend/noipunno/images/icons/edit-2.svg') }}"
                                                                                                alt="">
                                                                                            Edit
                                                                                        </a>
                                                                                        <a href="#"
                                                                                            class="np-badge np-badge-error">
                                                                                            <img src="{{ asset('frontend/noipunno/images/icons/trash.svg') }}"
                                                                                                alt="">
                                                                                            Delete
                                                                                        </a>
                                                                                    </div>
                                                                                    </th>
                                                                            </tr>

                                                                            <tr>
                                                                                <td scope="row" class="np-flag-header">
                                                                                    পাবনা জেলা স্কুল</th>
                                                                                <td scope="row">১১১-২২২-৩৩৩</th>
                                                                                <td scope="row">
                                                                                    <div type="button"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#editAccountModal"
                                                                                        class="np-badge np-badge-error  text-center ">
                                                                                        প্রধান শিক্ষক নির্বাচন করুন </div>
                                                                                    <div class="modal fade"
                                                                                        id="editAccountModal"
                                                                                        tabindex="-1"
                                                                                        aria-labelledby="editAccountModalLabel"
                                                                                        aria-hidden="true">
                                                                                        <div
                                                                                            class="modal-dialog modal-dialog-centered np-modal">
                                                                                            <div
                                                                                                class="modal-content rounded-5 ">
                                                                                                <div
                                                                                                    class="modal-header rounded-top-5 np-modal-header justify-content-center">
                                                                                                    <h5 class="modal-title text-light "
                                                                                                        id="editAccountModalLabel">
                                                                                                        প্রধান শিক্ষক
                                                                                                        নির্বাচন
                                                                                                    </h5>
                                                                                                </div>
                                                                                                <div
                                                                                                    class="modal-body d-flex flex-column justify-content-center align-items-center p-4 gap-2">
                                                                                                    <div>
                                                                                                        <h4>পাবনা জিলা স্কুল
                                                                                                        </h4>
                                                                                                        <h5>পাবনা সদর, পাবনা
                                                                                                        </h5>
                                                                                                    </div>

                                                                                                    <div
                                                                                                        class="input-group my-5">
                                                                                                        <select
                                                                                                            class="form-select np-teacher-input"
                                                                                                            aria-label="Default select example"
                                                                                                            id="gender"
                                                                                                            name="gender">
                                                                                                            <option
                                                                                                                value="">
                                                                                                                প্রধান
                                                                                                                শিক্ষক
                                                                                                                নির্বাচন
                                                                                                                করুন
                                                                                                            </option>
                                                                                                            <option>এ কে এম
                                                                                                                ফজলুল হক
                                                                                                            </option>
                                                                                                            <option> তপন
                                                                                                                কুমার দাস
                                                                                                            </option>
                                                                                                        </select>

                                                                                                    </div>

                                                                                                    <div
                                                                                                        class="d-flex w-100 gap-2">
                                                                                                        <button
                                                                                                            class=" text-light p-3 border-0 rounded-1 w-25 d-flex justify-content-center align-items-center np-modal-decline">
                                                                                                            <img
                                                                                                                src="{{ asset('frontend/noipunno/images/icons/close.svg') }}">
                                                                                                            <span
                                                                                                                class="px-1">
                                                                                                                বাতিল
                                                                                                            </span>
                                                                                                        </button>
                                                                                                        <button
                                                                                                            class="text-light p-3 border-0 rounded-1 w-75 d-flex justify-content-center align-items-center np-modal-accept">
                                                                                                            <span
                                                                                                                class="px-1">
                                                                                                                সংরক্ষন করুন
                                                                                                            </span>
                                                                                                            <img
                                                                                                                src="{{ asset('frontend/noipunno/images/icons/double-check.svg') }}">
                                                                                                        </button>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        {{-- modal ends --}}
                                                                                    </div>
                                                                                    </th>
                                                                                <td scope="row">আব্দুল হামিদ রোড, পাবনা
                                                                                    </th>
                                                                                <td scope="row">তুষার কুমার দাশ</th>
                                                                                <td scope="row">১১১-২২২-৩৩৩</th>
                                                                                <td scope="row">school@email.com</th>
                                                                                <td scope="row">
                                                                                    <div class="actions">
                                                                                        <a href="#"
                                                                                            class="np-badge np-badge-info">
                                                                                            <img src="{{ asset('frontend/noipunno/images/icons/eye-info.svg') }}"
                                                                                                alt="">
                                                                                            View
                                                                                        </a>
                                                                                        <a href="#"
                                                                                            class="np-badge np-badge-warning">
                                                                                            <img src="{{ asset('frontend/noipunno/images/icons/edit-2.svg') }}"
                                                                                                alt="">
                                                                                            Edit
                                                                                        </a>
                                                                                        <a href="#"
                                                                                            class="np-badge np-badge-error">
                                                                                            <img src="{{ asset('frontend/noipunno/images/icons/trash.svg') }}"
                                                                                                alt="">
                                                                                            Delete
                                                                                        </a>
                                                                                    </div>
                                                                                    </th>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
                                <!-- Content for Tab 2 -->
                                <div class="">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <section class="np-teacher-list">
                                                <div class="container" style="padding: 0">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="card np-card">
                                                                <div class="card-body">
                                                                    <div class="table-responsive">
                                                                        <table class="table np-table">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th scope="col"></th>
                                                                                    <th scope="col">8:00AM - 9:00AM</th>
                                                                                    <th scope="col">9:00AM - 10:00AM
                                                                                    </th>
                                                                                    <th scope="col">10:00AM - 11:00AM
                                                                                    </th>
                                                                                    <th scope="col">11:00AM - 12:00PM
                                                                                    </th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td scope="row">Sunday</th>
                                                                                    <td scope="row">Math</th>
                                                                                    <td scope="row">Endlish</th>
                                                                                    <td scope="row">Science</th>
                                                                                    <td scope="row">Bangla</th>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td scope="row">Monday</th>
                                                                                    <td scope="row">Math</th>
                                                                                    <td scope="row">Endlish</th>
                                                                                    <td scope="row">Science</th>
                                                                                    <td scope="row">Bangla</th>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td scope="row">Tuesday</th>
                                                                                    <td scope="row">Math</th>
                                                                                    <td scope="row">Endlish</th>
                                                                                    <td scope="row">Science</th>
                                                                                    <td scope="row">Bangla</th>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td scope="row">Wednesday</th>
                                                                                    <td scope="row">Math</th>
                                                                                    <td scope="row">Endlish</th>
                                                                                    <td scope="row">Science</th>
                                                                                    <td scope="row">Bangla</th>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td scope="row">Thrusday</th>
                                                                                    <td scope="row">Math</th>
                                                                                    <td scope="row">Endlish</th>
                                                                                    <td scope="row">Science</th>
                                                                                    <td scope="row">Bangla</th>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </section>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end tab -->

    </div>
    <style>
        .np-table th,
        td {
            font-size: 11px;
        }
    </style>

    <script>
        const ctx = document.getElementById('student-attendance');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['শনিবার', 'রবিবার ', 'সোমবার ', 'মঙ্গলবার ', 'বুধবার ', 'বৃহস্পতিবার '],
                datasets: [{
                    label: '',
                    data: [12, 19, 3, 5, 10, 20],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const teacherAttdnc = document.getElementById('teacher-attendance');

        new Chart(teacherAttdnc, {
            type: 'doughnut',
            data: {
                labels: ['Saturday', 'Sunday', 'Monday', 'Tue', 'Thu'],
                datasets: [{
                    label: 'Dataset 1',
                    data: [12, 19, 3, 5, 10]
                }]
            }
        });

        function setupPiCountChart() {
            var densityCanvas = document.getElementById("pi-count-chart");

            var piToCompleteData = {
                label: 'PI Need to Complete',
                data: [5427, 5243, 5514, 3933, 1326, 687, 1271, 1638],
                backgroundColor: 'rgba(94, 225, 233, 1)',
                borderColor: 'rgba(94, 225, 233, 1)',
                yAxisID: "y-axis-density"
            };

            var piDoneData = {
                label: 'PI Done',
                data: [3.7, 8.9, 9.8, 3.7, 23.1, 9.0, 8.7, 11.0],
                backgroundColor: 'rgba(149, 214, 119, 1)',
                borderColor: 'rgba(149, 214, 119, 1)',
                yAxisID: "y-axis-gravity"
            };

            var planetData = {
                labels: ["Class 6", "Class 7", "Class 8", "Class 9", "Class 10"],
                datasets: [piToCompleteData, piDoneData]
            };

            var chartOptions = {
                scales: {
                    xAxes: [{
                        barPercentage: 1,
                        categoryPercentage: 0.6
                    }],
                    yAxes: [{
                        id: "y-axis-density"
                    }, {
                        id: "y-axis-gravity"
                    }]
                }
            };

            var barChart = new Chart(densityCanvas, {
                type: 'bar',
                data: planetData,
                options: chartOptions
            });

        }

        setupPiCountChart();
    </script>
@endsection
