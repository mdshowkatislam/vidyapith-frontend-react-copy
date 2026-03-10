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
                                        <h2 class="title">প্রতিষ্ঠানের তথ্য পরিবর্তন</h2>
                                        <nav aria-label="breadcrumb">
                                            <ol class="breadcrumb np-breadcrumb">
                                                <li class="breadcrumb-item"><a href="{{ route('home') }}">
                                                        <img src="{{ asset('frontend/noipunno/images/icons/home.svg') }}"
                                                            alt="">
                                                        ড্যাশবোর্ড
                                                    </a></li>
                                                <li class="breadcrumb-item active" aria-current="page"><a
                                                        href="#">
                                                        প্রতিষ্ঠানের তথ্য পরিবর্তন
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
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <section class="np-teacher-add-form" id="edit-form">
                        <div class="np-input-form-bg mt-3">
                            <div class="container">
                                <form method="POST" action="{{ route('student.issue.transfer.store') }}"
                                    enctype="multipart/form-data">
                                    {{-- @method('PUT') --}}
                                    @csrf
                                    <input type="hidden" name="student_uid" value="{{ @$student->student_uid }}">
                                    <input type="hidden" name="class_room_uid" value="{{ @$student->class_room_uid }}">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <div class="">
                                                <label for="issue_date" class="form-label">প্রদানের তারিখ</label>
                                                <input type="date" name="issue_date" class="form-control np-teacher-input" data-toggle="tooltip" data-placement="top" title="প্রদানের তারিখ" value="">
                                            </div>

                                            @if ($errors->has('issue_date'))
                                                    <small class="help-block form-text text-danger">{{ $errors->first('issue_date') }}</small>
                                            @endif
                                        </div>

                                        <div class="col-md-12 col-sm-12 mb-3">
                                            <div class="">
                                                <label for="reason" class="form-label">ছাড়পত্র প্রদানের কারন</label>
                                                <textarea type="text" name="reason" class="form-control np-teacher-input" data-toggle="tooltip" data-placement="top" title="ছাড়পত্র প্রদানের কারন"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12 col-sm-12 mb-3">
                                            <div class="">
                                                <label for="comment" class="form-label">মন্তব্য</label>
                                                <textarea type="text" name="comment" class="form-control np-teacher-input" data-toggle="tooltip" data-placement="top" title="মন্তব্য"></textarea>
                                            </div>
                                        </div>

                                    <div class="row">
                                        <div class="col-md-6"></div>
                                        <div class="col-md-3">
                                            <a href="/" class="btn btn-primary np-btn-form-submit mt-3">বাতিল করুন
                                                <img src="{{ asset('frontend/noipunno/images/icons/arrow-right.svg') }}" alt="logo"></a>
                                        </div>
                                        <div class="col-sm-3">
                                            <button type="submit" class="btn btn-primary np-btn-form-submit mt-3">তথ্য
                                                হালনাগাদ করুন <img src="{{ asset('frontend/noipunno/images/icons/arrow-right.svg') }}" alt="logo"></button>
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
@endsection
@section('custom-js')
    <script>
            $('.bangla-input').bangla();
    $('.bangla-input').bangla('toggle'); // toggle current language (default: en)

    // $('#text-area').bangla();
    // $('#text-area').bangla('enable', true); // enable bangla typing


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

                    var selected = "{{ @$institute->district_uid }}";

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

                    var selected = "{{ @$institute->upazila_uid }}";

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
