@extends('frontend.layouts.noipunno')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .tab-nav-container .nav-link.active {
        border-bottom: 2px solid #428F92;
        color: #000 !important;
    }

    .tab-nav-container .tab-content ul li {
        list-style-type: none;
        font-size: 14px;
        font-weight: 500;
        padding: 5px 0px;
    }

    .tab-nav-container .tab-content ul li span {
        font-size: 14px;
        color: #000000b8;
        font-weight: 500;
        padding: 5px 0px;
    }

    .tab-nav-container .tab-content ul li button {
        font-size: 14px;
        font-weight: 500;
        color: #428F92;
        background-color: #428F92;
    }

    .tab-nav-container .tab-content ul li button:hover {
        color: #fff;
        background-color: #fff;
    }

    .tab-nav-container .btn.login-button {
        --rotate: 180deg;
        background: linear-gradient(var(--rotate), #428F92 0%, #428F92 100%);
        border: 0px solid rgba(0, 0, 0, 0);
        line-height: 15px;
        transform: translatey(0px);
        transition: all .75s cubic-bezier(0.72, 0.11, 0.22, 0.92);
        position: relative;
        z-index: 10;
        --hover-radius: 60px;
        overflow: hidden;
        padding: 10px 5px;
        color: #fff !important;
    }

    .tab-nav-container .btn.login-button::after {
        content: "";
        display: block;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, .3);
        position: absolute;
        inset: 0;
        z-index: -10;
        opacity: 1;
        transition: all .75s cubic-bezier(0.72, 0.11, 0.22, 0.92);
        clip-path: circle(0% at 0% 0%)
    }

    .tab-nav-container .btn.login-button:is(:hover) {
        transform: translatey(-1px)
    }

    .tab-nav-container .btn.login-button:is(:hover)::after {
        clip-path: circle(150% at 0 0)
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
                                        <h2 class="title">প্রতিষ্ঠানের তথ্য পরিবর্তন</h2>
                                        <nav aria-label="breadcrumb">
                                            <ol class="breadcrumb np-breadcrumb">
                                                <li class="breadcrumb-item"><a href="{{ route('home') }}">
                                                        <img src="{{ asset('frontend/noipunno/images/icons/home.svg') }}"
                                                            alt="">
                                                        ড্যাশবোর্ড
                                                    </a></li>
                                                <li class="breadcrumb-item active" aria-current="page">
                                                    আবেদনের লিস্টগুলো
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
    </div>

    <div class="container tab-nav-container">
        <div class="row justify-content-between my-5 card shadow-lg border-0">
            <div class="">
                <ul class="nav text-white">
                    <li class="nav-item d-flex justify-content-center" style="width: 33.33%;">
                        <a style="font-size: 16px" class="nav-link py-3 link-secondary text-center active" id="home-tab"
                            data-bs-toggle="tab" data-bs-target="#home" href="#">অপেক্ষমান আবেদনের তালিকা</a>
                    </li>
                    <li class="nav-item d-flex justify-content-center" style="width: 33.33%;">
                        <a style="font-size: 16px" class="nav-link py-3 link-secondary text-center" id="about-tab"
                            data-bs-toggle="tab" data-bs-target="#about" href="#">অনুমোদিত আবেদনের তালিকা</a>
                    </li>
                    <li class="nav-item d-flex justify-content-center" style="width: 33.33%;">
                        <a style="font-size: 16px" class="nav-link py-3 link-secondary text-center" id="album-tab"
                            data-bs-toggle="tab" data-bs-target="#album" href="#">অননুমোদিত আবেদনের তালিকা</a>
                    </li>
                </ul>
            </div>

            <div class="tab-content" style="background-color: #e4feff;" id="tabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="row">
                        @foreach (@$subject_reviews as $review)
                            @if (@$review['is_approved'] == 0)
                                <div class="col-sm-12 col-md-6">
                                    <div style="padding:12px 6px;">
                                        <ul class="card shadow-lg border-0 py-4"
                                            style="background-color: #e4feff; padding:10px;">
                                            <li>পদবি: <span class="ps-2">
                                                    {{ @$review['teacher']['designation'] }}</span></li>
                                            <li>নাম : <span
                                                    class="ps-2">{{ @$review['teacher']['name_bn'] ?? @$review['teacher']['name_en'] }}</span>
                                            </li>
                                            <li>মন্তব্য : <span class="ps-2 remarkTxt">{{ @$review['remark'] }}</span></li>
                                            <li><span class="badge py-1 px-2 shadow-lg"
                                                    style="background-color: #fff; color: #428F92;">ক্লাস {{ @$review['class_room']['class_id'] }}</span>
                                                <span class="badge py-1 px-2 shadow-lg"
                                                    style="background-color: #fff; color: #428F92;">{{ @$review['class_room']['shift']['shift_name'] }}
                                                    6</span>
                                                <span class="badge py-1 px-2 shadow-lg"
                                                    style="background-color: #fff; color: #428F92;">{{ @$review['class_room']['section']['section_name'] }}
                                                    9</span>

                                                <span class="badge py-1 px-2 shadow-lg"
                                                    style="background-color: #fff; color: #428F92;"><i
                                                        class="fas fa-calendar-alt"></i>
                                                    {{ date('d F, Y', strtotime(@$review['created_at'])) }}
                                                </span>
                                            </li>
                                            <li style="width: 120px;">
                                                <button style="background-color: var(--bg_primary); color: white"
                                                    type="button" data-id="{{ $review['uid'] }}"
                                                    data-route="{{ route('change_pi_bi_approve_status_subject_wise', $review['uid']) }}"
                                                    class="btn login-button review_btn_submit mt-2">অনুমোদন করুন</button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        @foreach (@$reviews as $review)
                            @if (@$review['is_approved'] == 0)
                                <div class="col-sm-12 col-md-6">
                                    <div style="padding:12px 6px;">
                                        <ul class="card shadow-lg border-0 py-4"
                                            style="background-color: #e4feff; padding:10px;">
                                            <li>পদবি: <span class="ps-2">
                                                    {{ @$review['teacher']['designation'] }}</span></li>
                                            <li>নাম : <span
                                                    class="ps-2">{{ @$review['teacher']['name_bn'] ?? @$review['teacher']['name_en'] }}</span>
                                            </li>
                                            <li>মন্তব্য : <span class="ps-2 remarkTxt">{{ @$review['remark'] }}</span></li>
                                            <li>
                                                <span class="badge py-1 px-2 shadow-lg"
                                                    style="background-color: #fff; color: #428F92;">ক্লাস {{ @$review['class_room']['class_id'] }}</span>
                                                <span class="badge py-1 px-2 shadow-lg"
                                                    style="background-color: #fff; color: #428F92;">{{ @$review['class_room']['shift']['shift_name'] }}
                                                </span>
                                                <span class="badge py-1 px-2 shadow-lg"
                                                    style="background-color: #fff; color: #428F92;">{{ @$review['class_room']['section']['section_name'] }}
                                                </span>

                                            </li>
                                            <li>
                                                <span class="badge py-1 px-2 shadow-lg"
                                                    style="background-color: #fff; color: #428F92;"><i
                                                        class="fas fa-calendar-alt"></i>
                                                    {{ date('d F, Y', strtotime(@$review['created_at'])) }}
                                                </span>
                                            </li>
                                            <li style="width: 120px;">
                                                <button style="background-color: var(--bg_primary); color: white"
                                                    type="button" data-id="{{ $review['uid'] }}"
                                                    data-route="{{ route('change_pi_bi_approve_status', $review['uid']) }}"
                                                    class="btn login-button review_btn_submit mt-2">অনুমোদন
                                                    করুন</button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="tab-pane fade py-3" id="about" role="tabpanel" aria-labelledby="about-tab">
                    <div class="row">
                        @foreach (@$subject_reviews as $review)
                            @if (@$review['is_approved'] == 1)
                                <div class="col-sm-12 col-md-6">
                                    <div style="padding:12px 6px;">
                                        <ul class="card shadow-lg border-0 py-4"
                                            style="background-color: #e4feff; padding:10px;">
                                            <li>পদবি: <span class="ps-2">
                                                    {{ @$review['teacher']['designation'] }}</span></li>
                                            <li>নাম : <span
                                                    class="ps-2">{{ @$review['teacher']['name_bn'] ?? @$review['teacher']['name_en'] }}</span>
                                            </li>
                                            <li>মন্তব্য : <span class="ps-2">{{ @$review['remark'] }}</span></li>
                                            <li><span class="badge py-1 px-2 shadow-lg"
                                                    style="background-color: #fff; color: #428F92;">ক্লাস {{ @$review['class_room']['class_id'] }}</span>
                                                <span class="badge py-1 px-2 shadow-lg"
                                                    style="background-color: #fff; color: #428F92;">{{ @$review['class_room']['shift']['shift_name'] }}
                                                </span>
                                                <span class="badge py-1 px-2 shadow-lg"
                                                    style="background-color: #fff; color: #428F92;">{{ @$review['class_room']['section']['section_name'] }}
                                                </span>
                                            </li>

                                            <li>
                                                <span class="badge py-1 px-2 shadow-lg"
                                                    style="background-color: #fff; color: #428F92;"><i
                                                        class="fas fa-calendar-alt"></i>
                                                    @if (!empty($review['updated_at']))
                                                        {{ date('d F, Y', strtotime($review['updated_at'])) }}
                                                    @else
                                                       
                                                    @endif
                                                </span>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        @foreach (@$reviews as $review)
                            @if (@$review['is_approved'] == 1)
                                <div class="col-sm-12 col-md-6">
                                    <div style="padding:12px 6px;">
                                        <ul class="card shadow-lg border-0 py-4"
                                            style="background-color: #e4feff; padding:10px;">
                                            <li> পদবি: <span class="ps-2">
                                                    {{ @$review['teacher']['designation'] }}</span></li>
                                            <li>নাম : <span
                                                    class="ps-2">{{ @$review['teacher']['name_bn'] ?? @$review['teacher']['name_en'] }}</span>
                                            </li>
                                            <li>মন্তব্য : <span class="ps-2">{{ @$review['remark'] }}</span></li>
                                            <li><span class="badge py-1 px-2 shadow-lg"
                                                    style="background-color: #fff; color: #428F92;">ক্লাস {{ @$review['class_room']['class_id'] }}</span>
                                                <span class="badge py-1 px-2 shadow-lg"
                                                    style="background-color: #fff; color: #428F92;">{{ @$review['class_room']['shift']['shift_name'] }}
                                                </span>
                                                <span class="badge py-1 px-2 shadow-lg"
                                                    style="background-color: #fff; color: #428F92;">{{ @$review['class_room']['section']['section_name'] }}
                                                </span>
                                            </li>
                                            <li>
                                                <span class="badge py-1 px-2 shadow-lg"
                                                    style="background-color: #fff; color: #428F92;"><i
                                                        class="fas fa-calendar-alt"></i>
                                                        @if (!empty($review['updated_at']))
                                                        {{ date('d F, Y', strtotime($review['updated_at'])) }}
                                                    @else
                                                       
                                                    @endif
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="tab-pane fade py-3" id="album" role="tabpanel" aria-labelledby="album-tab">
                    <div class="row">
                        @foreach (@$subject_reviews as $review)
                            @if (@$review['is_approved'] == 2)
                                <div class="col-sm-12 col-md-6">
                                    <div style="padding:12px 6px;">
                                        <ul class="card shadow-lg border-0 py-4"
                                            style="background-color: #e4feff; padding:10px;">
                                            <li>পদবি: <span class="ps-2">
                                                    {{ @$review['teacher']['designation'] }}</span></li>
                                            <li>নাম : <span
                                                    class="ps-2">{{ @$review['teacher']['name_bn'] ?? @$review['teacher']['name_en'] }}</span>
                                            </li>
                                            <li>মন্তব্য : <span class="ps-2">{{ @$review['remark'] }}</span></li>
                                            <li><span class="badge py-1 px-2 shadow-lg"
                                                    style="background-color: #fff; color: #428F92;">ক্লাস {{ @$review['class_room']['class_id'] }}</span>
                                                <span class="badge py-1 px-2 shadow-lg"
                                                    style="background-color: #fff; color: #428F92;">{{ @$review['class_room']['shift']['shift_name'] }}
                                                </span>
                                                <span class="badge py-1 px-2 shadow-lg"
                                                    style="background-color: #fff; color: #428F92;">{{ @$review['class_room']['section']['section_name'] }}
                                                </span>
                                            </li>

                                            <li>
                                                <span class="badge py-1 px-2 shadow-lg"
                                                    style="background-color: #fff; color: #428F92;"><i
                                                        class="fas fa-calendar-alt"></i>
                                                        @if (!empty($review['updated_at']))
                                                        {{ date('d F, Y', strtotime($review['updated_at'])) }}
                                                    @else
                                                       
                                                    @endif
                                                </span>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        @foreach (@$reviews as $review)
                            @if (@$review['is_approved'] == 2)
                                <div class="col-sm-12 col-md-6">
                                    <div style="padding:12px 6px;">
                                        <ul class="card shadow-lg border-0 py-4"
                                            style="background-color: #e4feff; padding:10px;">
                                            <li>পদবি: <span class="ps-2">
                                                    {{ @$review['teacher']['designation'] }}</span></li>
                                            <li>নাম : <span
                                                    class="ps-2">{{ @$review['teacher']['name_bn'] ?? @$review['teacher']['name_en'] }}</span>
                                            </li>
                                            <li>মন্তব্য : <span class="ps-2">{{ @$review['remark'] }}</span></li>
                                            <li><span class="badge py-1 px-2 shadow-lg"
                                                    style="background-color: #fff; color: #428F92;">ক্লাস {{ @$review['class_room']['class_id'] }}</span>
                                                <span class="badge py-1 px-2 shadow-lg"
                                                    style="background-color: #fff; color: #428F92;">{{ @$review['class_room']['shift']['shift_name'] }}
                                                </span>
                                                <span class="badge py-1 px-2 shadow-lg"
                                                    style="background-color: #fff; color: #428F92;">{{ @$review['class_room']['section']['section_name'] }}
                                                </span>
                                            </li>

                                            <li>
                                                <span class="badge py-1 px-2 shadow-lg"
                                                    style="background-color: #fff; color: #428F92;"><i
                                                        class="fas fa-calendar-alt"></i>
                                                    @if (!empty($review['updated_at']))
                                                        {{ date('d F, Y', strtotime($review['updated_at'])) }}
                                                    @else
                                                       
                                                    @endif
                                                </span>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom-js')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            //approve script
            $(document).on('click', '.review_btn_submit', function(e) {
                e.preventDefault();
                var actionTo = $(this).attr('data-route');
                var id = $(this).attr('data-id');
                var remarkText = $(this).closest('ul').children('li').find('span.remarkTxt').text();
                //var remarkText = $(this).closest('ul').children('li').find('span');
                //console.log(remarkText);
                Swal.fire({
                    title: "আপনি কি অনুমোদন করতে চান?",
                    html: "<strong>মন্তব্য: </strong>" + remarkText,
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
                                    title: "সফলভাবে অনুমোদন করা হয়েছে।",
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
                                Swal.fire({
                                    title: "অনুমোদনের আবেদন বাতিল করা হয়েছে।",
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
