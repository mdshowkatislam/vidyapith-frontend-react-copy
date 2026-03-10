@extends('frontend.layouts.noipunno')
@section('content')

<!-- student-chart -->
    <section class="container my-3">
        <div class="card-container">
            <div class="row g-3 ">
                <div class="col-lg-3 col-xl-2 col-md-6">
                    <div class="card teacher-profile border-0">
                        <div class="card-header border-0">
                            <div class="edit-icon">
                                <a href="institutes/{{ @$user->eiin }}/edit">
                                    <img src="/assets/images/dashboard/edit-2.svg" alt="">
                                </a>
                            </div>
                            <div class="profile-img">
                                <img src="/assets/images/dashboard/60px.png" alt="">
                            </div>
                            <div class="teacher-title">
                                <h2>প্রধান শিক্ষক</h2>
                            </div>
                            <div class="icon">
                                <div class="single-icon">
                                    <img src="assets/images/dashboard/ico.svg" alt="">
                                </div>
                                <div class="single-icon">
                                    <img src="assets/images/dashboard/message.svg" alt="">
                                </div>
                                <div class="single-icon">
                                    <img src="assets/images/dashboard/moon.svg" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="teacher-info">
                            <h2 class="card-title">{{ $user->name }}</h2>
                            <p class="card-text">
                                @if (@$user->user_type_id == 3)
                                    <small>{{ @$user->eiin ?? @$user->caid }}</small>
                                @else
                                    <small>{{ @$user->pdsid ?? (@$user->suid ?? @$user->caid) }}</small>
                                @endif
                            </p>
                            <p class="card-text">{{ @$institute->institute_name }}</p>
                            <div class="button">
                                <img src="assets/images/dashboard/eye.svg" alt="">
                                <a href="institutes/{{ @$user->eiin }}/edit" class="">আমার প্রোফাইল</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-5  col-md-6">
                    <div class="student-chart">
                        <div class="header">
                            <h3>শিক্ষার্থীর হাজিরা</h3>
                            <div class="timeline">
                                <h4>টাইমলাইন</h4>
                                <select class="form-select" aria-label="Default select example">
                                    <option selected>সাপ্তাহিক </option>
                                    <option value="1">মাসিক</option>
                                    <option value="2">বছর</option>
                                    <i class="fa-solid fa-chevron-down"></i>
                                </select>
                            </div>
                            <div class="all">
                                <h4>ক্লাস অনুসারে ফিল্টার</h4>
                                <select class="form-select" aria-label="Default select example">
                                    <option selected>সব</option>
                                    <option value="2">দিন</option>
                                    <option value="3">মাসিক</option>
                                </select>
                            </div>
                        </div>
                        <div class="chart">
                            <img src="assets/images/dashboard/Chart.png" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-3  col-xl-2  col-md-6 ">
                    <div class="all-teacher-student-card gy-5">
                        <a href="#">
                            <div class="card-container">
                                <div class="total-student">
                                    <div class="title">
                                        <h3>
                                            সর্বমোট
                                            <br />
                                            <span>শিক্ষার্থী</span>
                                        </h3>
                                        <h6>
                                            শ্রেণী - ষষ্ঠ - সপ্তম
                                        </h6>
                                    </div>
                                    <div class="circle">
                                        <h5>
                                            ৯২৩
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="#">
                            <div class="card-container">
                                <div class="total-student">
                                    <div class="title">
                                        <h3>
                                            সর্বমোট
                                            <br />
                                            <span>শিক্ষার্থী</span>
                                        </h3>
                                        <h6>
                                            শ্রেণী - ষষ্ঠ - সপ্তম
                                        </h6>
                                    </div>
                                    <div class="circle">
                                        <h5>
                                            ৯২৩
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="#">
                            <div class="card-container">
                                <div class="total-student">
                                    <div class="title">
                                        <h3>
                                            সর্বমোট
                                            <br />
                                            <span>শিক্ষার্থী</span>
                                        </h3>
                                        <h6>
                                            শ্রেণী - ষষ্ঠ - সপ্তম
                                        </h6>
                                    </div>
                                    <div class="circle">
                                        <h5>
                                            ৯২৩
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="#">
                            <div class="card-container">
                                <div class="total-student">
                                    <div class="title">
                                        <h3>
                                            সর্বমোট
                                            <br />
                                            <span>শিক্ষার্থী</span>
                                        </h3>
                                        <h6>
                                            শ্রেণী - ষষ্ঠ - সপ্তম
                                        </h6>
                                    </div>
                                    <div class="circle">
                                        <h5>
                                            ৯২৩
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-6 d-none  col-xl-3 d-xl-block">
                    <div class="request-container">
                        <div class="header">
                            <div class="title">
                                <h5 class="request">অনুরোধ</h5>
                                <img src="assets/images/dashboard/dots-vertical.svg" alt="">
                            </div>
                            <p class="request_paragraph">
                                বিষয়গুলি আপনার পর্যালোচনা করা দরকার
                            </p>
                        </div>
                        <div class="tab-bar">
                            <ul class="nav">
                                <li class="nav-item">
                                    <a class="nav-link active" id="apply-tab" data-bs-toggle="tab"
                                        data-bs-target="#apply"><img src="assets/images/dashboard/alertico.png" alt="">
                                        <h2>
                                            আবেদন
                                        </h2>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="notice-tab" data-bs-toggle="tab"
                                        data-bs-target="#notice"><img src="assets/images/dashboard/info-circle.png"
                                            alt="">
                                        <h2>
                                            বিজ্ঞপ্তি
                                        </h2>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- Tab Content -->
                        <div class="tab-content" id="tabContent">
                            <div class="tab-pane fade show active" id="apply" role="tabpanel"
                                aria-labelledby="apply-tab">
                                <div class="tab-container">
                                    <a href="#">
                                        <div class="heading">
                                            <div class="icon">
                                                <img src="assets/images/dashboard/ico2.svg" class="img-fluid"
                                                    alt="icon" />
                                            </div>
                                            <h2>
                                                ফোন নম্বর পরিবর্তনের অনুরোধ করেছেন
                                            </h2>
                                        </div>
                                        <div class="teachers">
                                            <h3>সামিনা চৌধুরী</h3>
                                            <h3>|</h3>
                                            <h3>সহকারী শিক্ষক</h3>
                                        </div>
                                        <div class="class-section">
                                            <div class="class-day-section">
                                                <h6>
                                                    ষষ্ঠ শ্রেণী
                                                </h6>
                                                <h6>
                                                    Day
                                                </h6>
                                                <h6>
                                                    Section A
                                                </h6>
                                            </div>
                                            <p>
                                                অনুরোধ করেছেন ৬ অক্টোবর ২০২৩
                                            </p>
                                        </div>
                                    </a>
                                </div>
                                <div class="tab-container">
                                    <a href="#">
                                        <div class="heading">
                                            <div class="icon">
                                                <img src="assets/images/dashboard/arrow-right2.svg" class="img-fluid"
                                                    alt="icon" />
                                            </div>
                                            <h2>
                                                বিষয় পরিবর্তনের অনুরোধ করেছেন
                                            </h2>
                                        </div>
                                        <div class="teachers">
                                            <h3>সামিনা চৌধুরী</h3>
                                            <h3>|</h3>
                                            <h3>সহকারী শিক্ষক</h3>
                                        </div>
                                        <div class="class-section">
                                            <div class="class-day-section">
                                                <h6>
                                                    ষষ্ঠ শ্রেণী
                                                </h6>
                                                <h6>
                                                    Day
                                                </h6>
                                                <h6>
                                                    Section A
                                                </h6>
                                            </div>
                                            <p>
                                                অনুরোধ করেছেন ৬ অক্টোবর ২০২৩
                                            </p>
                                        </div>
                                    </a>
                                </div>
                                <div class="tab-container">
                                    <a href="#">
                                        <div class="heading">
                                            <div class="icon">
                                                <img src="assets/images/dashboard/arrow-right2.svg" class="img-fluid"
                                                    alt="icon" />
                                            </div>
                                            <h2>
                                                ফোন নম্বর পরিবর্তনের অনুরোধ করেছেন
                                            </h2>
                                        </div>
                                    </a>
                                </div>
                                <div class="button">
                                    <a>সব অনুরোধগুলি দেখুন</a>
                                    <img src="assets/images/dashboard/arrow-right.svg" alt="">
                                </div>

                            </div>
                            <div class="tab-pane fade" id="notice" role="tabpanel" aria-labelledby="notice-tab">
                                <div class="tab-container">
                                    <a href="#">
                                        <div class="heading">
                                            <div class="icon">
                                                <img src="assets/images/dashboard/ico2.svg" class="img-fluid"
                                                    alt="icon" />
                                            </div>
                                            <h2>
                                                ফোন নম্বর পরিবর্তনের অনুরোধ করেছেন
                                            </h2>
                                        </div>
                                        <div class="teachers">
                                            <h3>সামিনা চৌধুরী</h3>
                                            <h3>|</h3>
                                            <h3>সহকারী শিক্ষক</h3>
                                        </div>
                                        <div class="class-section">
                                            <div class="class-day-section">
                                                <h6>
                                                    ষষ্ঠ শ্রেণী
                                                </h6>
                                                <h6>
                                                    Day
                                                </h6>
                                                <h6>
                                                    Section A
                                                </h6>
                                            </div>
                                            <p>
                                                অনুরোধ করেছেন ৬ অক্টোবর ২০২৩
                                            </p>
                                        </div>
                                    </a>
                                </div>
                                <div class="tab-container">
                                    <a href="#">
                                        <div class="heading">
                                            <div class="icon">
                                                <img src="assets/images/dashboard/arrow-right2.svg" class="img-fluid"
                                                    alt="icon" />
                                            </div>
                                            <h2>
                                                বিষয় পরিবর্তনের অনুরোধ করেছেন
                                            </h2>
                                        </div>
                                        <div class="teachers">
                                            <h3>সামিনা চৌধুরী</h3>
                                            <h3>|</h3>
                                            <h3>সহকারী শিক্ষক</h3>
                                        </div>
                                        <div class="class-section">
                                            <div class="class-day-section">
                                                <h6>
                                                    ষষ্ঠ শ্রেণী
                                                </h6>
                                                <h6>
                                                    Day
                                                </h6>
                                                <h6>
                                                    Section A
                                                </h6>
                                            </div>
                                            <p>
                                                অনুরোধ করেছেন ৬ অক্টোবর ২০২৩
                                            </p>
                                        </div>
                                    </a>
                                </div>
                                <div class="tab-container">
                                    <a href="#">
                                        <div class="heading">
                                            <div class="icon">
                                                <img src="assets/images/dashboard/arrow-right2.svg" class="img-fluid"
                                                    alt="icon" />
                                            </div>
                                            <h2>
                                                ফোন নম্বর পরিবর্তনের অনুরোধ করেছেন
                                            </h2>
                                        </div>
                                    </a>
                                </div>
                                <div class="button">
                                    <a>সব অনুরোধগুলি দেখুন</a>
                                    <img src="assets/images/dashboard/arrow-right.svg" alt="">
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- report -->
    <section>
        <div class="container report-container">
            <h2>রিপোর্ট</h2>
            <div class="row mt-2 mb-5">
                <div class="col col-sm-6 col-md-4 col-lg-3">
                    <a href="#" class="student-container">
                        <div class="icon">
                            <img src="assets/images/dashboard/document-text.svg" alt="">
                        </div>
                        <h2>শিক্ষার্থীদের প্রতিবেদন</h2>
                    </a>
                </div>
                <div class="col col-sm-6 col-md-4 col-lg-3">
                    <a href="#" class="student-container">
                        <div class="icon">
                            <img src="assets/images/dashboard/document-text.svg" alt="">
                        </div>
                        <h2>শিক্ষার্থীদের প্রতিবেদন</h2>
                    </a>
                </div>
                <div class="col col-sm-6 col-md-4 col-lg-3">
                    <a href="#" class="student-container">
                        <div class="icon">
                            <img src="assets/images/dashboard/document-text.svg" alt="">
                        </div>
                        <h2>শিক্ষার্থীদের প্রতিবেদন</h2>
                    </a>
                </div>
                <div class="col col-sm-6 col-md-4 col-lg-3">
                    <a href="#" class="student-container">
                        <div class="icon">
                            <img src="assets/images/dashboard/document-text.svg" alt="">
                        </div>
                        <h2>শিক্ষার্থীদের প্রতিবেদন</h2>
                    </a>
                </div>
                <div class="col col-sm-6 col-md-4 col-lg-3">
                    <a href="#" class="student-container">
                        <div class="icon">
                            <img src="assets/images/dashboard/document-text.svg" alt="">
                        </div>
                        <h2>শিক্ষার্থীদের প্রতিবেদন</h2>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- subject info-->
    <section>
        <div class="container subject-container">
            <h2>শ্রেণী বিষয়ক তথ্য</h2>
            <div class="row">
                <div class="col">
                    <a href="#" class="subject-number">
                        <div class="icon">
                            <img src="assets/images/dashboard/bicon.svg" alt="">
                        </div>
                        <h2 class="mt-3">শিক্ষার্থীদের প্রতিবেদন</h2>
                        <div class="total-student">
                            <p>মোট ছাত্র</p>
                            <div class="number">
                                <p class="">54</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="#" class="subject-number">
                        <div class="icon">
                            <img src="assets/images/dashboard/bicon.svg" alt="">
                        </div>
                        <h2 class="mt-3">শিক্ষার্থীদের প্রতিবেদন</h2>
                        <div class="total-student">
                            <p>মোট ছাত্র</p>
                            <div class="number">
                                <p class="">54</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="#" class="subject-number">
                        <div class="icon">
                            <img src="assets/images/dashboard/bicon.svg" alt="">
                        </div>
                        <h2 class="mt-3">শিক্ষার্থীদের প্রতিবেদন</h2>
                        <div class="total-student">
                            <p>মোট ছাত্র</p>
                            <div class="number">
                                <p class="">54</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="#" class="subject-number">
                        <div class="icon">
                            <img src="assets/images/dashboard/bicon.svg" alt="">
                        </div>
                        <h2 class="mt-3">শিক্ষার্থীদের প্রতিবেদন</h2>
                        <div class="total-student">
                            <p>মোট ছাত্র</p>
                            <div class="number">
                                <p class="">54</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="#" class="subject-number">
                        <div class="icon">
                            <img src="assets/images/dashboard/bicon.svg" alt="">
                        </div>
                        <h2 class="mt-3">শিক্ষার্থীদের প্রতিবেদন</h2>
                        <div class="total-student">
                            <p>মোট ছাত্র</p>
                            <div class="number">
                                <p class="">54</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection
