@extends('frontend.layouts.noipunno')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .circleOrange {
        background: #428e92;
        border-radius: 3em;
        -moz-border-radius: 3em;
        -webkit-border-radius: 3em;
        color: #ffffff !important;
        height: 2em;
        width: 2em;
        display: inline-block;
        font-size: 16px;
        line-height: 2em;
    }

    .circleOrange:hover {
        background: #c94fa4;
        transition: all 200ms ease-in-out;
    }

    .np-table th,
    td {
        font-size: 11px;
    }

    .modal-content {
        height: auto !important;
    }

    .modal-header {
        background: #e4feff;
    }

    /* Apply fade-in animation */
    .modal.fade .modal-dialog {
        animation: fadeIn .3s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translate(0, -50px);
        }

        to {
            opacity: 1;
            transform: translate(0, 0);
        }
    }


    @media only screen and (max-width: 450px) {
        .modal-header img {
            display: none;
        }

        .modal-header {
            height: 85px;
        }
    }
</style>
@section('content')

    @if ($institute && !$institute->board_uid)
        <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog  modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <img class="" height="40px" src="{{ asset('assets/images/noipunno-new-logo.svg') }}"
                            alt="logo" />
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-5">
                        <h5 style="line-height: 36px;">{{ $institute->institute_name_bn ?? $institute->institute_name }} এর
                            জন্য কোন শিক্ষাবোর্ডের তথ্য পাওয়া যায় নি। প্রতিষ্ঠানের তথ্য হালনাগাদ করতে
                            <a href="/institutes/{{ @$user->eiin }}/edit">
                                এখানে
                            </a>
                            ক্লিক করুন।
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        </div>
    @endif

    <div class="dashboard-section">
        {{-- @include('frontend.layouts.notice') --}}
        <section class="container my-3">
            <div class="card-container">
                <div class="row g-3 ">
                    <div class="col-lg-3 col-xl-3 col-md-6">
                        <div class="card teacher-profile border-0">
                            <div class="card-header border-0">
                                <div class="edit-icon">
                                    @if (!empty(@$user->eiin))
                                        <a href="/institutes/{{ @$user->eiin }}/edit">
                                            <img src="/assets/images/dashboard/edit-2.svg" alt="">
                                        </a>
                                    @endif
                                </div>
                                {{-- <div class="profile-img">
                                    <img src="/assets/images/dashboard/60px.png" alt="">
                                </div> --}}

                                <div class="profile-img"
                                    style="{{ @$user->user_type_id == 3 ? 'border-radius:0px !important;' : '' }}">
                                    @if (@$user->user_type_id == 3)
                                        @if (@$institute->logo)
                                            <img src="{{ Storage::url(@$institute->logo) }}" class="img-fluid"
                                                alt="main logo" style="border-radius:0px !important;">
                                        @else
                                            <img src="/assets/images/dashboard/60px.png" alt=""
                                                style="border-radius:0px !important;">
                                        @endif
                                    @else
                                        <img src="/assets/images/dashboard/60px.png" alt="">
                                    @endif
                                </div>

                                @if (@$user->user_type_id == 3)
                                    <div class="teacher-title">
                                        <h2>প্রতিষ্ঠান</h2>
                                    </div>
                                @else
                                    <div class="teacher-title">
                                        <h2>প্রধান শিক্ষক</h2>
                                    </div>
                                @endif

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
                                        <small>{{ @$user->pdsid ?? (@$user->caid ?? @$user->suid) }}</small>
                                    @endif
                                </p>
                                <p class="card-text mt-2">
                                    {{ @$institute->board->board_name_bn ?? @$institute->board->board_name_en }}</p>

                                {{-- <div class="button">
                                    <img src="assets/images/dashboard/eye.svg" alt="">
                                    <a href="#" class="">আমার প্রোফাইল</a>
                                </div> --}}

                                @if (@$user->eiin)
                                    <div class="button">
                                        <a href="{{ route('institute.edit', @$user->eiin) }}" class="">
                                            <img src="assets/images/dashboard/eye.svg" alt="">
                                            প্রতিষ্ঠানের তথ্য
                                        </a>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-6  col-md-6">
                        <div class="all-teacher-student-card row" style="height: auto !important">
                            <div class="col-4">
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
                                                    সকল শ্রেণী
                                                </h6>
                                            </div>
                                            <div class="circle">
                                                <h5>
                                                    {{ count($students) }}
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-4">
                                <a href="#">
                                    <div class="card-container">
                                        <div class="total-student">
                                            <div class="title">
                                                <h3>
                                                    সর্বমোট
                                                    <br />
                                                    <span>শিক্ষক</span>
                                                </h3>
                                                <h6>
                                                    শিক্ষাবর্ষ - ২০২৪
                                                </h6>
                                            </div>
                                            <div class="circle">
                                                <h5>
                                                    {{ count($myTeachers) }}
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-4">
                                <a href="#">
                                    <div class="card-container">
                                        <div class="total-student">
                                            <div class="title">
                                                <h3>
                                                    সর্বমোট
                                                    <br />
                                                    <span>শ্রেণী কক্ষ </span>
                                                </h3>
                                                <h6>
                                                    শিক্ষাবর্ষ - ২০২৪
                                                </h6>
                                            </div>
                                            <div class="circle">
                                                <h5>
                                                    {{ $count_class_room }}
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                        </div>

                        @if (@auth()->user()->eiin_institute->board_uid || $user->user_type_id == 4)
                            <div class="head-maseter-card-container">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <a href="{{ route('noipunno.dashboard.branch.add') }}" class="head-master-card">
                                            <div class="d-flex align-items-center">
                                                <div class="number">
                                                    <h4>১</h4>
                                                </div>
                                                <h2>ব্রাঞ্চ ব্যবস্থাপনা</h2>
                                            </div>
                                            <div class="icon">
                                                <img src="assets/images/info-circle.svg" alt="">
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <a href="{{ route('noipunno.dashboard.shift.add') }}" class="head-master-card">
                                            <div class="d-flex align-items-center">
                                                <div class="number">
                                                    <h4>২</h4>
                                                </div>
                                                <h2>শিফট ব্যবস্থাপনা</h2>
                                            </div>
                                            <div class="icon">
                                                <img src="assets/images/info-circle.svg" alt="">
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <a href="{{ route('noipunno.dashboard.version.add') }}" class="head-master-card">
                                            <div class="d-flex align-items-center">
                                                <div class="number">
                                                    <h4>৩</h4>
                                                </div>
                                                <h2>ভার্সন ব্যবস্থাপনা</h2>
                                            </div>
                                            <div class="icon">
                                                <img src="assets/images/info-circle.svg" alt="">
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <a href="{{ route('noipunno.dashboard.section.add') }}" class="head-master-card">
                                            <div class="d-flex align-items-center">
                                                <div class="number">
                                                    <h4>৪</h4>
                                                </div>
                                                <h2>সেকশন ব্যবস্থাপনা</h2>
                                            </div>
                                            <div class="icon">
                                                <img src="assets/images/info-circle.svg" alt="">
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <a href="{{ route('teacher.index') }}" class="head-master-card">
                                            <div class="d-flex align-items-center">
                                                <div class="number">
                                                    <h4>৫</h4>
                                                </div>
                                                <h2>শিক্ষক ব্যবস্থাপনা</h2>
                                            </div>
                                            <div class="icon">
                                                <img src="assets/images/info-circle.svg" alt="">
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <a href="{{ route('student.index') }}" class="head-master-card">
                                            <div class="d-flex align-items-center">
                                                <div class="number">
                                                    <h4>৬</h4>
                                                </div>
                                                <h2>শিক্ষার্থী ব্যবস্থাপনা</h2>
                                            </div>
                                            <div class="icon">
                                                <img src="assets/images/info-circle.svg" alt="">
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <a href="{{ route('noipunno.dashboard.classroom.add') }}"
                                            class="head-master-card">
                                            <div class="d-flex align-items-center">
                                                <div class="number">
                                                    <h4>৭</h4>
                                                </div>
                                                <h2>বিষয় শিক্ষক নির্বাচন</h2>
                                            </div>
                                            <div class="icon">
                                                <img src="assets/images/info-circle.svg" alt="">
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p class="text-danger pt-3">বি.দ্র:
                                {{ $institute->institute_name_bn ?? $institute->institute_name }} এর জন্য কোন শিক্ষাবোর্ডের
                                তথ্য পাওয়া যায় নি। প্রতিষ্ঠানের তথ্য হালনাগাদ করতে
                                <a href="/institutes/{{ @$user->eiin }}/edit">
                                    এখানে
                                </a>
                                ক্লিক করুন।
                            </p>
                        @endif
                    </div>
                    {{-- <div class="col-lg-3  col-xl-2  col-md-6 ">
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
                                                {{  count($students) }}
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
                                                <span>শিক্ষক</span>
                                            </h3>
                                            <h6>
                                               আপনার স্কুলে
                                            </h6>
                                        </div>
                                        <div class="circle">
                                            <h5>
                                                {{ count($myTeachers) }}
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
                                                <span>শ্রেণী কক্ষ </span>
                                            </h3>
                                            <h6>
                                                আপনার স্কুলে
                                            </h6>
                                        </div>
                                        <div class="circle">
                                            <h5>
                                                {{ count($branchs) }}
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </a>

                        </div>
                    </div> --}}
                    <div class="col-md-6 col-xl-3 d-xl-block">
                        <div class="request-container">
                            <div class="header">
                                <div class="title">
                                    {{-- <h5 class="request">অনুরোধ</h5> --}}
                                    <h5 class="request">আবেদন</h5>
                                    <img src="assets/images/dashboard/dots-vertical.svg" alt="">
                                </div>
                                <p class="request_paragraph">
                                    বিষয়গুলি আপনার পর্যালোচনা করা দরকার
                                </p>
                            </div>
                            <div class="tab-bar">
                                <ul class="nav">
                                    {{-- <li class="nav-item">
                                        <a class="nav-link active" id="apply-tab" data-bs-toggle="tab"
                                            data-bs-target="#apply"><img src="assets/images/dashboard/alertico.png"
                                                alt="">
                                            <h2>
                                                আবেদন
                                            </h2>
                                        </a>
                                    </li> --}}
                                    {{-- <li class="nav-item">
                                        <a class="nav-link" id="notice-tab" data-bs-toggle="tab"
                                            data-bs-target="#notice"><img src="assets/images/dashboard/info-circle.png"
                                                alt="">
                                            <h2>
                                                বিজ্ঞপ্তি
                                            </h2>
                                        </a>
                                    </li> --}}
                                </ul>
                            </div>
                            <!-- Tab Content -->
                            <div class="tab-content" id="tabContent" style="height:80%; overflow-y: auto;">
                                <div class="tab-pane fade show active" id="apply" role="tabpanel"
                                    aria-labelledby="apply-tab">
                                    <div class="tab-container">
                                        @foreach ($subject_reviews as $review)
                                            <form class="approve_status"
                                                action="{{ route('change_pi_bi_approve_status_subject_wise', @$review['uid']) }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="remarkText" data-remark="{{ @$review['remark'] }}"></div>
                                                {{-- <a href="{{  route('change_pi_bi_approve_status', $review['uid'])  }}"> --}}
                                                <div class="heading">
                                                    <div class="icon">
                                                        <img src="assets/images/dashboard/ico2.svg" class="img-fluid"
                                                            alt="icon" />
                                                    </div>
                                                    <h2>
                                                        ক্লাস {{ @$review['class_room']['class_id'] }} এর
                                                        {{ @$review['subject']['name'] }} বিষয়টি পুনরায় মূল্যায়নের অনুরোধ
                                                        করেছেন।
                                                    </h2>
                                                    {{-- <h2>
                                                        ক্লাস {{ $review['class_room']['class_id'] }} এর বাংলা বিষয়ের {{ @$review['pi_uid'] ? @$review['pi_uid'] . ' PI' : 'BI' }} টি পুনরায় মূল্যায়নের অনুরোধ করেছেন।
                                                    </h2> --}}
                                                </div>
                                                <div class="teachers pt-1">
                                                    <h3>{{ @$review['teacher']['name_bn'] ?? @$review['teacher']['name_en'] }}
                                                    </h3>
                                                    <h3>|</h3>
                                                    <h3>{{ @$review['teacher']['designation'] }}</h3>
                                                </div>
                                                <div class="class-section">
                                                    <div class="class-day-section">
                                                        <h6>
                                                            ক্লাস {{ @$review['class_room']['class_id'] }}
                                                        </h6>
                                                        <h6>
                                                            {{ @$review['class_room']['shift']['shift_name'] }}
                                                        </h6>
                                                        <h6>
                                                            Section {{ @$review['class_room']['section']['section_name'] }}
                                                        </h6>
                                                        <h6>
                                                            {{ date('d F, Y', strtotime(@$review['created_at'])) }}
                                                        </h6>
                                                    </div>
                                                    {{-- <p>
                                                        অনুরোধ করেছেন {{date('d F, Y', strtotime($review['created_at']))}}
                                                    </p> --}}
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 d-flex justify-content-start">
                                                        <button type="button" title="Approve"
                                                            data-id="{{ @$review['uid'] }}"
                                                            data-route="{{ route('change_pi_bi_approve_status_subject_wise', @$review['uid']) }}"
                                                            class="btn btn-primary np-btn-form-submit py-1 review_btn_submit"
                                                            style="font-size: 12px;">অনুমোদন করুন
                                                            <img src="{{ asset('frontend/noipunno/images/icons/arrow-right.svg') }}"
                                                                alt="">
                                                        </button>
                                                    </div>
                                                </div>
                                                {{-- <a class="btn np-delete-btn-small review_btn_submit"
                                                                            title="Delete" data-id="{{ $branch->uid }}"
                                                                            data-token={{ csrf_token() }}
                                                                            data-route="{{ route('noipunno.dashboard.branch.delete') }}"><i
                                                                                class="fa fa-trash np-delete-btn-small-icon"></i></a> --}}
                                                {{-- </a> --}}
                                            </form>
                                        @endforeach

                                        @foreach ($reviews as $review)
                                            <form action="{{ route('change_pi_bi_approve_status', @$review['uid']) }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                {{-- <a href="{{  route('change_pi_bi_approve_status', $review['uid'])  }}"> --}}

                                                <div class="remarkText" data-remark="{{ @$review['remark'] }}">
                                                </div>
                                                <div class="heading">
                                                    <div class="icon">
                                                        <img src="assets/images/dashboard/ico2.svg" class="img-fluid"
                                                            alt="icon" />
                                                    </div>
                                                    <h2>
                                                        ক্লাস {{ @$review['class_room']['class_id'] }} এর
                                                        {{ @$review['pi_uid'] ? 'PI' : 'BI' }} টি পুনরায় মূল্যায়নের অনুরোধ
                                                        করেছেন।
                                                    </h2>
                                                    {{-- <h2>
                                                        ক্লাস {{ $review['class_room']['class_id'] }} এর বাংলা বিষয়ের {{ @$review['pi_uid'] ? @$review['pi_uid'] . ' PI' : 'BI' }} টি পুনরায় মূল্যায়নের অনুরোধ করেছেন।
                                                    </h2> --}}
                                                </div>
                                                <div class="teachers pt-1">
                                                    <h3>{{ @$review['teacher']['name_bn'] ?? @$review['teacher']['name_en'] }}
                                                    </h3>
                                                    <h3>|</h3>
                                                    <h3>{{ @$review['teacher']['designation'] }}</h3>
                                                </div>
                                                <div class="class-section">
                                                    <div class="class-day-section">
                                                        <h6>
                                                            ক্লাস {{ @$review['class_room']['class_id'] }}
                                                        </h6>
                                                        <h6>
                                                            {{ @$review['class_room']['shift']['shift_name'] }}
                                                        </h6>
                                                        <h6>
                                                            Section {{ @$review['class_room']['section']['section_name'] }}
                                                        </h6>
                                                        <h6>
                                                            {{ date('d F, Y', strtotime(@$review['created_at'])) }}
                                                        </h6>
                                                    </div>
                                                    {{-- <p>
                                                        অনুরোধ করেছেন {{date('d F, Y', strtotime($review['created_at']))}}
                                                    </p> --}}
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 d-flex justify-content-start">
                                                        <button type="button" data-id="{{ @$review['uid'] }}"
                                                            data-route="{{ route('change_pi_bi_approve_status', @$review['uid']) }}"
                                                            class="btn btn-primary np-btn-form-submit py-1 review_btn_submit"
                                                            style="font-size: 12px;">অনুমোদন করুন
                                                            <img src="{{ asset('frontend/noipunno/images/icons/arrow-right.svg') }}"
                                                                alt="">
                                                        </button>
                                                    </div>
                                                </div>
                                                {{-- </a> --}}
                                            </form>
                                        @endforeach
                                    </div>
                                    {{-- <div class="tab-container">
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
                                    </div> --}}
                                    {{-- <div class="button">
                                        <a href="#">সব অনুরোধগুলি দেখুন</a>
                                        <img src="assets/images/dashboard/arrow-right.svg" alt="">
                                    </div> --}}

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
                            {{-- <div class="teacher-profile"> --}}
                            <div class="row d-flex justify-content-center">
                                <div class="review-button mt-2">
                                    <a href="{{ route('change_pibi_approve_all') }}" class="text-dark">
                                        <img src="assets/images/dashboard/list.svg" alt="" style="height: 16px;">
                                        সকল আবেদন
                                    </a>
                                </div>
                            </div>
                            {{-- </div> --}}
                            {{-- <button type="submit" class="btn btn-primary np-btn-form-submit py-1 mt-2"
                                style="font-size: 12px;">সকল আবেদন
                                <img src="{{ asset('frontend/noipunno/images/icons/arrow-right.svg') }}" alt="">
                            </button> --}}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- report -->
        {{-- <section>
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
        </section> --}}

        <!-- subject info-->
        {{-- <section>
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
        </section> --}}




    </div>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(function() {
            $('#myModal').modal('show');
        });

        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();

            //approve script
            $(document).on('click', '.review_btn_submit', function(e) {
                e.preventDefault();
                var actionTo = $(this).attr('data-route');
                var id = $(this).attr('data-id');
                var remark = $(this).closest('form').find('.remarkText').data('remark');


                Swal.fire({
                    title: "আপনি কি অনুমোদন করতে চান ?",
                    html: "<strong>মন্তব্য: </strong>" + remark,
                    showCloseButton: false,
                    showCancelButton: true,
                    showDenyButton: true,
                    confirmButtonText: "হ্যাঁ",
                    cancelButtonText: "বাতিল",
                    denyButtonText: "না",
                    width: 600,
                    padding: "3em",
                }).then((result) => {
                    if (result.isConfirmed) {
                        var submit_status = 1;

                        $.ajax({
                            url: actionTo,
                            type: 'post',
                            data: {
                                id: id,
                                submit_status: submit_status,

                            },
                            success: function(data) {
                                Swal.fire({
                                    text: "ধন্যবাদ",
                                    icon: "success",
                                    title: "অনুমোদন টি সফল হয়েছে",
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                });

                            }
                        });

                    } else if (result.isDenied) {
                        var submit_status = 2;
                        //Swal.fire('Changes are not saved', '', 'info')
                        $.ajax({
                            url: actionTo,
                            type: 'post',
                            data: {
                                id: id,
                                submit_status: submit_status,

                            },
                            success: function(data) {
                                //console.log(data);
                                Swal.fire({
                                    title: "অনুমোদন টি বাতিল করা হয়েছে",
                                    icon: "error",
                                    confirmButtonText: "ধন্যবাদ",
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                });
                            }
                        });

                    }
                });

            });
        });
    </script>
@endsection
