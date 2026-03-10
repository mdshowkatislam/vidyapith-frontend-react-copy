@extends('frontend.layouts.noipunno')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        <div class="container pt-5 pb-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div style="background-color: #ffffff" class="box-header card-header">
                            <h6 class="float-end"> আবেদন: বিষয়গুলি আপনার পর্যালোচনা করা দরকার</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach (@$subject_reviews as $review)
                                    <div class="col-lg-12 mb-4">
                                        <div style="background-color: #e4feff" class="shadow p-3 rounded review_data">
                                            <h4 class="fs-5 common-font-color content-title">
                                                ক্লাস {{ @$review['class_room']['class_id'] }} এর
                                                {{ @$review['subject']['name'] }} বিষয়টি পুনরায় মূল্যায়নের অনুরোধ
                                                করেছেন।

                                            </h4>
                                            <span>{{ @$review['teacher']['name_bn'] ?? @$review['teacher']['name_en'] }} |
                                                {{ @$review['teacher']['designation'] }}</span>

                                            <p class="mt-2 mb-2 comment_text">মন্তব্য : {{ @$review['remark'] }}</p>
                                            <p class="fs-7 font-work-sans"><i class="fas fa-calendar-alt"></i>
                                                {{ date('d F, Y', strtotime(@$review['created_at'])) }}</p>
                                            <div class="badge-grp mt-1 mb-1">
                                                <span
                                                    class="badge rounded-pill bg-dark">{{ @$review['class_room']['class_id'] }}</span>
                                                <span
                                                    class="badge rounded-pill bg-dark">{{ @$review['class_room']['shift']['shift_name'] }}</span>
                                                <span
                                                    class="badge rounded-pill bg-dark">{{ @$review['class_room']['section']['section_name'] }}</span>
                                                <span
                                                    class="badge rounded-pill bg-dark">{{ date('d F, Y', strtotime(@$review['created_at'])) }}</span>
                                                </br>

                                            </div>
                                            @if (@$review['is_approved'] == 0)
                                                <button style="background-color: var(--bg_primary); color: white"
                                                    type="button" data-id="{{ $review['uid'] }}"
                                                    data-route="{{ route('change_pi_bi_approve_status_subject_wise', $review['uid']) }}"
                                                    class="btn btn-outline-primary review_btn_submit mt-2">অনুমোদন
                                                    করুন</button>
                                            @elseif(@$review['is_approved'] == 1)
                                                <p class="text-success">অনুমোদন করা হয়েছে</p>
                                            @elseif(@$review['is_approved'] == 2)
                                                <p class="text-danger">অনুমোদন টি বাতিল করা হয়েছে</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach

                                @foreach (@$reviews as $review)
                                    <div class="col-lg-12 mb-4">
                                        <div style="background-color: #e4feff" class="shadow p-3 rounded review_data">
                                            <h4 class="fs-5 common-font-color content-title">
                                                ক্লাস {{ @$review['class_room']['class_id'] }} এর
                                                {{ @$review['subject']['name'] }} বিষয়টি পুনরায় মূল্যায়নের অনুরোধ
                                                করেছেন।

                                            </h4>
                                            <span>{{ @$review['teacher']['name_bn'] ?? @$review['teacher']['name_en'] }} |
                                                {{ @$review['teacher']['designation'] }}</span>
                                            <p class="mt-2 mb-2 comment_text">মন্তব্য : {{ @$review['remark'] }}</p>
                                            <p class="fs-7 font-work-sans"><i class="fas fa-calendar-alt"></i>
                                                {{ date('d F, Y', strtotime(@$review['created_at'])) }}
                                            </p>
                                            <div class="badge-grp mt-1 mb-1">
                                                <span
                                                    class="badge rounded-pill bg-dark">{{ @$review['class_room']['class_id'] }}</span>
                                                <span
                                                    class="badge rounded-pill bg-dark">{{ @$review['class_room']['shift']['shift_name'] }}</span>
                                                <span
                                                    class="badge rounded-pill bg-dark">{{ @$review['class_room']['section']['section_name'] }}</span>
                                                <span
                                                    class="badge rounded-pill bg-dark">{{ date('d F, Y', strtotime(@$review['created_at'])) }}</span>
                                                </br>

                                            </div>


                                            @if (@$review['is_approved'] == 0)
                                                <button style="background-color: var(--bg_primary); color: white"
                                                    type="button" data-id="{{ $review['uid'] }}"
                                                    data-route="{{ route('change_pi_bi_approve_status_subject_wise', $review['uid']) }}"
                                                    class="btn btn-outline-primary review_btn_submit mt-2">অনুমোদন
                                                    করুন</button>
                                            @elseif(@$review['is_approved'] == 1)
                                                <p>অনুমোদন করা হয়েছে</p>
                                            @elseif(@$review['is_approved'] == 2)
                                                <p>অনুমোদন টি বাতিল করা হয়েছে</p>
                                            @endif

                                        </div>
                                    </div>
                                @endforeach
                            </div>



                        </div>


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
                var commentText = $(this).closest('.review_data').find('.comment_text').text();
                Swal.fire({
                    title: "আপনি কি অনুমোদন করতে চান ?",
                    html: "<strong>মন্তব্য: </strong>" + commentText,
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
