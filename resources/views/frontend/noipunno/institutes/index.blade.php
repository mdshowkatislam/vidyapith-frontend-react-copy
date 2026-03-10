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
                                                        href="{{ route('institute.edit', $eiinId) }}">
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
                            {{-- <img src="{{ Storage::url(@$institute->logo) }}" class="img-fluid" alt="main logo"> --}}
                            <div class="container">
                                <form method="POST" action="{{ route('institute.update', @$institute->eiin) }}"
                                    enctype="multipart/form-data">
                                    @method('PUT')
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <div class="">
                                                <label for="teacherName" class="form-label">EIIN </label>
                                                <input type="text" name="eiin" class="form-control np-teacher-input"
                                                    data-toggle="tooltip" data-placement="top" title="EIIN"
                                                    value="{{ @$institute->eiin }}" readonly>
                                            </div>
                                            @if ($errors->has('eiin'))
                                                <small
                                                    class="help-block form-text text-danger">{{ $errors->first('eiin') }}</small>
                                            @endif
                                        </div>
                                        <div class="col-md-6 col-sm-12 mb-3 d-none">
                                            <div class="">
                                                <label for="pdsid" class="form-label">CAID</label>
                                                <input type="text" name="caid" class="form-control np-teacher-input"
                                                    data-toggle="tooltip" data-placement="top" title="CAID"
                                                    value="{{ @$institute->caid }}" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <div class="">
                                                <label for="teacherNameBn" class="form-label">প্রতিষ্ঠানের নাম (বাংলা) <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="institute_name_bn" data-toggle="tooltip"
                                                    data-placement="top" title="প্রতিষ্ঠানের নাম (বাংলা)"
                                                    class="form-control np-teacher-input bangla-input"
                                                    value="{{ @$institute->institute_name_bn }}">
                                            </div>
                                            @if ($errors->has('institute_name_bn'))
                                                <small
                                                    class="help-block form-text text-danger">{{ $errors->first('institute_name_bn') }}</small>
                                            @endif
                                        </div>

                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <div class="">
                                                <label for="teacherName" class="form-label">প্রতিষ্ঠানের নাম (ইংরেজি) <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="institute_name" data-toggle="tooltip"
                                                    data-placement="top" title="প্রতিষ্ঠানের নাম (ইংরেজি)"
                                                    class="form-control np-teacher-input"
                                                    value="{{ @$institute->institute_name }}">
                                            </div>
                                            @if ($errors->has('institute_name'))
                                                <small
                                                    class="help-block form-text text-danger">{{ $errors->first('institute_name') }}</small>
                                            @endif
                                        </div>
                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <div class="">
                                                <label for="teacherDesignation" class="form-label">প্রতিষ্ঠানের ক্যাটাগরি
                                                    <span class="text-danger">*</span></label>
                                                {{-- <input type="text" class="form-control np-teacher-input" value="{{ @$institute->category }}" disabled> --}}

                                                <select class="form-select np-teacher-input" data-toggle="tooltip"
                                                    data-placement="top" title="প্রতিষ্ঠানের ক্যাটাগরি"
                                                    aria-label="Default select example" id="category" name="category">
                                                    <option value="">নির্বাচন করুন</option>
                                                    <option value="College"
                                                        {{ @$institute->category == 'College' ? 'selected' : '' }}>
                                                        College</option>
                                                    <option value="School"
                                                        {{ @$institute->category == 'School' ? 'selected' : '' }}>School
                                                    </option>
                                                    <option value="School and College"
                                                        {{ @$institute->category == 'School and College' ? 'selected' : '' }}>
                                                        School and
                                                        College</option>
                                                    <option value="Madrasah"
                                                        {{ @$institute->category == 'Madrasah' ? 'selected' : '' }}>
                                                        Madrasah</option>
                                                    <option value="Primary"
                                                        {{ @$institute->category == 'Primary' ? 'selected' : '' }}>
                                                        Primary</option>
                                                    <option value="Technical"
                                                        {{ @$institute->category == 'Technical' ? 'selected' : '' }}>
                                                        Technical</option>
                                                    <option value="Kindergarten"
                                                        {{ @$institute->category == 'Kindergarten' ? 'selected' : '' }}>
                                                        Kindergarten</option>
                                                </select>
                                                @if ($errors->has('category'))
                                                    <small
                                                        class="help-block form-text text-danger">{{ $errors->first('category') }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <div class="">
                                                <label for="teacherPhone" class="form-label">মোবাইল নম্বর
                                                    @if ($institute->is_foreign == 0)
                                                        <span class="text-danger">*</span>
                                                    @endif
                                                </label>
                                                <input type="text" name="phone"
                                                    class="form-control np-teacher-input" data-toggle="tooltip"
                                                    data-placement="top" title="মোবাইল নম্বর"
                                                    value="{{ @$institute->phone }}">
                                                @if ($errors->has('phone'))
                                                    <small
                                                        class="help-block form-text text-danger">{{ $errors->first('phone') }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <div class="">
                                                <label for="teacherEmail" class="form-label">ইমেইল আইডি
                                                    @if ($institute->is_foreign == 1)
                                                        <span class="text-danger">*</span>
                                                    @endif
                                                </label>
                                                <input type="text" name="email"
                                                    class="form-control np-teacher-input" data-toggle="tooltip"
                                                    data-placement="top" title="ইমেইল"
                                                    value="{{ @$institute->email }}">
                                                @if ($errors->has('email'))
                                                    <small
                                                        class="help-block form-text text-danger">{{ $errors->first('email') }}</small>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <div class="">
                                                <label for="teacherNid" class="form-label">ঠিকানা</label>
                                                <input type="text" name="address"
                                                    class="form-control np-teacher-input" data-toggle="tooltip"
                                                    data-placement="top" title="ঠিকানা"
                                                    value="{{ @$institute->address }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <div class="">
                                                <label for="board_uid" class="form-label">বোর্ড <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <select class="form-select np-teacher-input"
                                                        aria-label="বোর্ড নির্বাচন করুন" name="board_uid" id="board_uid"
                                                        data-toggle="tooltip" data-placement="top"
                                                        title="বোর্ড নির্বাচন করুন">
                                                        <option value="">বোর্ড নির্বাচন করুন</option>
                                                        @foreach ($boards as $board)
                                                            <option value="{{ $board->uid }}"
                                                                @if (@$institute->board_uid == $board->uid) selected @endif>
                                                                {{ $board->board_name_bn ?? $board->board_name_en }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @if ($errors->has('board_uid'))
                                                    <small
                                                        class="help-block form-text text-danger">{{ $errors->first('board_uid') }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        <input name="is_foreign" type="hidden"
                                            value="{{ @$institute->is_foreign ?? 0 }}">
                                        @if ($institute->is_foreign == 1)
                                            <div class="col-md-6 col-sm-12 mb-3">
                                                <div class="">
                                                    <label for="country" class="form-label">দেশ <span
                                                            class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <select class="form-select np-teacher-input" data-toggle="tooltip"
                                                            data-placement="top" title="দেশ"
                                                            aria-label="Default select example" name="country"
                                                            id="country">
                                                            <option value="">দেশ নির্বাচন করুন</option>
                                                            @foreach ($countries as $country)
                                                                <option value="{{ $country->uid }}"
                                                                    @if (@$institute->country == $country->uid) selected @endif>
                                                                    {{ $country->countryname }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @if ($errors->has('country'))
                                                        <small
                                                            class="help-block form-text text-danger">{{ $errors->first('country') }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-12 mb-3">
                                                <div class="">
                                                    <label for="state" class="form-label">প্রদেশ</label>
                                                    <input type="text" name="state"
                                                        class="form-control np-teacher-input" data-toggle="tooltip"
                                                        data-placement="top" title="প্রদেশ"
                                                        value="{{ @$institute->state }}">
                                                    @if ($errors->has('state'))
                                                        <small
                                                            class="help-block form-text text-danger">{{ $errors->first('state') }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-12 mb-3">
                                                <div class="">
                                                    <label for="city" class="form-label">শহর </label>
                                                    <input type="text" name="city"
                                                        class="form-control np-teacher-input" data-toggle="tooltip"
                                                        data-placement="top" title="শহর"
                                                        value="{{ @$institute->city }}">
                                                    @if ($errors->has('city'))
                                                        <small
                                                            class="help-block form-text text-danger">{{ $errors->first('city') }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-12 mb-3">
                                                <div class="">
                                                    <label for="zip_code" class="form-label">জিপ কোড </label>
                                                    <input type="text" name="zip_code"
                                                        class="form-control np-teacher-input" data-toggle="tooltip"
                                                        data-placement="top" title="জিপ কোড"
                                                        value="{{ @$institute->zip_code }}">
                                                    @if ($errors->has('zip_code'))
                                                        <small
                                                            class="help-block form-text text-danger">{{ $errors->first('zip_code') }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-6 col-sm-12 mb-3">
                                                <div class="">
                                                    <label for="division_id" class="form-label">বিভাগ <span
                                                            class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <select class="form-select np-teacher-input"
                                                            aria-label="Default select example" name="division_id"
                                                            data-toggle="tooltip" data-placement="top" title="বিভাগ"
                                                            id="division_id">
                                                            <option value="">বিভাগ নির্বাচন করুন</option>
                                                            @foreach ($divisions as $division)
                                                                <option value="{{ $division->uid }}"
                                                                    @if (@$institute->division_uid == $division->uid) selected @endif>
                                                                    {{ $division->division_name_bn ?? $division->division_name_en }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @if ($errors->has('division_id'))
                                                        <small
                                                            class="help-block form-text text-danger">{{ $errors->first('division_id') }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-12 mb-3">
                                                <div class="">
                                                    <label for="district_id" class="form-label">জেলা <span
                                                            class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <select class="form-select np-teacher-input" data-toggle="tooltip"
                                                            data-placement="top" title="জেলা"
                                                            aria-label="Default select example" name="district_id"
                                                            id="district_id">
                                                            <option value="">জেলা নির্বাচন করুন</option>
                                                        </select>
                                                    </div>
                                                    @if ($errors->has('district_id'))
                                                        <small
                                                            class="help-block form-text text-danger">{{ $errors->first('district_id') }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-12 mb-3">
                                                <div class="">
                                                    <label for="upazila_id" class="form-label">উপজেলা <span
                                                            class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <select class="form-select np-teacher-input" data-toggle="tooltip"
                                                            data-placement="top" title="উপজেলা"
                                                            aria-label="Default select example" name="upazila_id"
                                                            id="upazila_id">
                                                            <option value="">উপজেলা নির্বাচন করুন</option>
                                                        </select>
                                                    </div>
                                                    @if ($errors->has('upazila_id'))
                                                        <small
                                                            class="help-block form-text text-danger">{{ $errors->first('upazila_id') }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <div class="">
                                                <label for="head_caid" class="form-label">প্রতিষ্ঠান প্রধান</label>
                                                <div class="input-group">
                                                    <select class="form-select np-teacher-input" data-toggle="tooltip"
                                                        data-placement="top" title="প্রতিষ্ঠান প্রধান"
                                                        aria-label="Default select example" name="head_caid"
                                                        id="head_caid">
                                                        <option value="">প্রতিষ্ঠান প্রধান নির্বাচন করুন</option>
                                                        @foreach ($myTeachers as $teacher)
                                                            <option value="{{ $teacher->caid }}"
                                                                @if (@$teacher->caid == $institute->head_caid) selected @endif>
                                                                {{ $teacher->name_en }}
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                                @if ($errors->has('head_caid'))
                                                    <small
                                                        class="help-block form-text text-danger">{{ $errors->first('head_caid') }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 mb-3">
                                            <div class="">
                                                <label for="logo" class="form-label">লোগো <small
                                                        class="text-danger">120px X 120px (সাইজ সর্বোচ্চ 300
                                                        KB)</small></label>
                                                <input type="file" name="logo"
                                                    class="form-control np-teacher-input"
                                                    value="{{ @$institute->logo }}">
                                            </div>
                                        </div>

                                        @if (@$institute->logo)
                                            <div class="col-md-6 col-sm-12 mb-3">
                                                <div class="">
                                                    <img src="{{ Storage::url(@$institute->logo) }}" class="img-fluid"
                                                        alt="Main logo" style="height: 80px;">
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6"></div>
                                        <div class="col-md-3">
                                            <a href="/" class="btn btn-primary np-btn-form-submit mt-3">বাতিল করুন
                                                <img src="{{ asset('frontend/noipunno/images/icons/arrow-right.svg') }}"
                                                    alt="logo"></a>
                                        </div>
                                        <div class="col-sm-3">
                                            <button type="submit" class="btn btn-primary np-btn-form-submit mt-3">তথ্য
                                                হালনাগাদ করুন <img
                                                    src="{{ asset('frontend/noipunno/images/icons/arrow-right.svg') }}"
                                                    alt="logo"></button>
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
