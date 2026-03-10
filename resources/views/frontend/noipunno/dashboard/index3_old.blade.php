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
                    
                    <div class="col-lg-9 col-xl-9  col-md-6">
                        <div class="container mt-4 mb-5">
                            <div class="np-teacher-list row mb-2">
                                <div class="col-md-12">
                                    <h2 class="np-form-title">বোর্ড রেজিস্ট্রেশনের জন্য শ্রেণি নির্বাচন করুন</h2>
                                </div>
                            </div>
                            <div class="dashboard-section">
                                <div class="head-maseter-card-container">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 mt-2">

                                            <a href="{{ route('student.board_registration.payment_tab', 6) }}">
                                                <div class="card dashboard-card w-100 h-100 mb-4 py-5 card-bg-color">
                                                    <div class="d-flex align-items-center justify-content-center w-100 h-100">
                                                        <div class="w-50 d-flex justify-content-center">
                                                            <img src="{{ asset('assets/icons/teacher.png') }}" class="img-fluid w-75"
                                                                alt="School Icon" />
                                                        </div>
                                                        <div class="w-50 d-flex justify-content-start">
                                                            <div class="">
                                                                <div class="d-flex justify-content-center fs-3 text-white mb-3">
                                                                    বোর্ড রেজিস্ট্রেশন
                                                                </div>
                                                                <div class="d-flex justify-content-center align-items-center text-white"
                                                                    style="font-size: 40px; font-weight: 800; font-family: 'SolaimanLipi';">
                                                                    ষষ্ঠ শ্রেণি
                                                                </div>
                                                            
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            {{-- <a href="{{ route('student.board_registration.payment_tab', 6) }}">
                                                <div class="card1 card align-items-center bg-info">
                                                    <img src="../frontend/images/login-bg.png" alt="...">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <h2>ষষ্ঠ শ্রেণি</h2>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a> --}}
                                        </div>
                                        <div class="col-lg-6 col-md-6 mt-2">
                                            <a href="{{ route('student.board_registration.payment_tab', 7) }}">
                                                <div class="card dashboard-card w-100 h-100 mb-4 py-5 card-bg-color">
                                                    <div class="d-flex align-items-center justify-content-center w-100 h-100">
                                                        <div class="w-50 d-flex justify-content-center">
                                                            <img src="{{ asset('assets/icons/teacher.png') }}" class="img-fluid w-75"
                                                                alt="School Icon" />
                                                        </div>
                                                        <div class="w-50 d-flex justify-content-start">
                                                            <div class="">
                                                                <div class="d-flex justify-content-center fs-3 text-white mb-3">
                                                                    বোর্ড রেজিস্ট্রেশন
                                                                </div>
                                                                <div class="d-flex justify-content-center align-items-center text-white"
                                                                    style="font-size: 40px; font-weight: 800; font-family: 'SolaimanLipi';">
                                                                    সপ্তম শ্রেণি
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>

                                            {{-- <a href="{{ route('student.board_registration.payment_tab', 7) }}">
                                                <div class="card1 card align-items-center bg-info">
                                                    <img src="../frontend/images/login-bg.png" alt="...">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <h2>সপ্তম শ্রেণি</h2>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a> --}}
                                        </div>
                                        <div class="col-lg-6 col-md-6 mt-2">
                                            <a href="{{ route('student.board_registration.payment_tab', 8) }}">
                                                <div class="card dashboard-card w-100 h-100 mb-4 py-5 card-bg-color">
                                                    <div class="d-flex align-items-center justify-content-center w-100 h-100">
                                                        <div class="w-50 d-flex justify-content-center">
                                                            <img src="{{ asset('assets/icons/teacher.png') }}" class="img-fluid w-75"
                                                                alt="School Icon" />
                                                        </div>
                                                        <div class="w-50 d-flex justify-content-start">
                                                            <div class="">
                                                                <div class="d-flex justify-content-center fs-3 text-white mb-3">
                                                                    বোর্ড রেজিস্ট্রেশন
                                                                </div>
                                                                <div class="d-flex justify-content-center align-items-center text-white"
                                                                    style="font-size: 40px; font-weight: 800; font-family: 'SolaimanLipi';">
                                                                    অষ্টম শ্রেণি
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>

                                            {{-- <a href="{{ route('student.board_registration.payment_tab', 8) }}">
                                                <div class="card1 card align-items-center bg-info">
                                                    <img src="../frontend/images/login-bg.png" alt="...">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <h2>অষ্টম শ্রেণি</h2>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a> --}}
                                        </div>
                                        <div class="col-lg-6 col-md-6 mt-2">
                                            <a href="{{ route('student.board_registration.payment_tab', 9) }}">
                                                <div class="card dashboard-card w-100 h-100 mb-4 py-5 card-bg-color">
                                                    <div class="d-flex align-items-center justify-content-center w-100 h-100">
                                                        <div class="w-50 d-flex justify-content-center">
                                                            <img src="{{ asset('assets/icons/teacher.png') }}" class="img-fluid w-75"
                                                                alt="School Icon" />
                                                        </div>
                                                        <div class="w-50 d-flex justify-content-start">
                                                            <div class="">
                                                                <div class="d-flex justify-content-center fs-3 text-white mb-3">
                                                                    বোর্ড রেজিস্ট্রেশন
                                                                </div>
                                                                <div class="d-flex justify-content-center align-items-center text-white"
                                                                    style="font-size: 40px; font-weight: 800; font-family: 'SolaimanLipi';">
                                                                    নবম শ্রেণি
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>

                                            {{-- <a href="{{ route('student.board_registration.payment_tab', 9) }}">
                                                <div class="card1 card align-items-center bg-info">
                                                    <img src="../frontend/images/login-bg.png" alt="...">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <h2>নবম শ্রেণি</h2>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>

    <style>
        .card1 {
            margin: 5%;
            flex-direction: row;
        }
        .card-body {
            padding: 0.5em 1em;
        }
        .card1.card img {
            max-width: 10em;
            height: 100%;
            border-bottom-left-radius: calc(0.25rem - 1px);
            border-top-left-radius: calc(0.25rem - 1px);
        }

        .total {
            font-size: 20px;
            font-weight: 500;
            font-family: 'SolaimanLipi', sans-serif;
            color: #2d6668;
        }

        .total-institutions {
            background-image: url('https://preview.keenthemes.com/metronic8/demo1/assets/media/patterns/vector-1.png');
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            background-color: #D81A48;
            height: 100%;
            width: 100%;
        }

        .dashboard-card {
            border: none;
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
        }

        .total-teachers {
            background-image: url("./assets/images/abtract-1.png");
            background-repeat: repeat;
            background-position: center;
            background-size: cover;
        }

        .card-bg-color {
            background: radial-gradient(circle, rgba(5, 46, 122, 0.993) 14%, rgb(97, 51, 177) 100%);
            border-radius: 15px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            /* height: 269px !important; */
        }

        .card-bg-color:hover {
            background: radial-gradient(circle, rgba(143, 36, 182, 0.993) 14%, rgb(90, 41, 175) 100%);
            ;
        }
    </style>
@endsection
