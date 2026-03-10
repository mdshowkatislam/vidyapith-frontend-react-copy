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
                                    <img src="{{ asset('frontend/noipunno/images/icons/linear-book.svg') }}" alt="">
                                </div>
                                <div class="content">
                                    <h2 class="title">ব্রাঞ্চ সম্পাদনা </h2>
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb np-breadcrumb">
                                            <li class="breadcrumb-item"><a href="{{route('home')}}">
                                                    <img src="{{ asset('frontend/noipunno/images/icons/home.svg') }}" alt="">
                                                    ড্যাশবোর্ড
                                                </a></li>
                                            <li class="breadcrumb-item active" aria-current="page">ব্রাঞ্চ সম্পাদনা</li>
                                        </ol>
                                    </nav>

                                </div>
                            </div>
                            <div class="option-section">
                                <div class="fav-icon">
                                    <img src="{{ asset('frontend/noipunno/images/icons/fav-start-icon.svg') }}" alt="">
                                </div>
                                <div class="dots-icon">
                                    <img src="{{ asset('frontend/noipunno/images/icons/3-dot-vertical.svg') }}" alt="">
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
                <section class="np-teacher-list mt-5">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <h2 class="title">ব্রাঞ্চ লিস্ট</h2>
                            </div>

                            <div class="col-md-12">
                                <div class="card np-card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table np-table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">ব্রাঞ্চের নাম
                                                            {{-- <span class="icon"> <img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span> --}}
                                                        </th>
                                                        <th scope="col">ব্রাঞ্চ লোকেশন
                                                            {{-- <span class="icon"> <img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span> --}}
                                                        </th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($upazillaList as $upazila)
                                                    <tr>
                                                        <td scope="row">{{ @$upazila->upazila_name_bn }}</th>
                                                        <td scope="row">
                                                            {{ @$upazila->district->district_name_en }}
                                                            </th>
                                                        <td scope="row">
                                                            <div class="action-content">
                                                                <a href="{{ route('upazila-edit', ['id' => @$upazila->uid]) }}" class="np-route">
                                                                    <button class="btn np-edit-btn-small">
                                                                        <img src="{{ asset('frontend/noipunno/images/icons/edit-white.svg') }}" alt="">
                                                                    </button>
                                                                </a>
                                                            </div>
                                                            </th>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <h3 class="mt-5 mb-2 np-form-title">ব্রাঞ্চ তথ্য পরিবর্তন করুন</h3>

                <section class="section-teacher-add-form np-input-form-bg mb-5" id="edit-form">
                    <div class="container">
                        <form action="{{ @$upazilla_data ? route('upazila-update', ['id'=> @$upazilla_data->uid]) : '' }}" method="POST">
                            {{-- @method('PUT') --}}
                            @csrf
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <div>
                                        <label for="beiin-psid" class="form-label">Upazila <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control np-teacher-input" id="loginId" placeholder="Upazila" name="upazila_name_bn" value="{{ old('upazila_name_bn',@$upazilla_data->upazila_name_bn) }}">
                                    </div>
                                    @if ($errors->has('upazila_name_bn'))
                                    <small class="help-block form-text text-danger">{{ $errors->first('upazila_name_bn') }}</small>
                                    @endif
                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <div>
                                        <label for="beiin-psid" class="form-label">District <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input select2" aria-label="Default select example" name="district_id">
                                                <option value="">Please Select</option>
                                                @foreach ($districts as $district)
                                                <option value="{{ $district->uid }}" {{old('district_id',@$upazilla_data->district_id) == @$district->uid ? 'selected':''}}>{{ $district->district_name_en }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($errors->has('district_id'))
                                        <small class="help-block form-text text-danger">{{ $errors->first('district_id') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if(@$upazilla_data)
                            <div class="row">
                                <div class="col-md-12 col-sm-12 d-flex justify-content-start">
                                    <button type="submit" class="btn btn-primary np-btn-form-submit mt-3 d-flex align-items-center" style="width: fit-content;border: unset;column-gap: 10px;"> <a class="np-btn-cancel" href="{{ route('noipunno.dashboard.branch.add') }}">বাতিল করুন</a>
                                        <img src="{{ asset('frontend/noipunno/images/icons/arrow-right.svg') }}" alt=""></button>
                                    <button type="submit" class="btn btn-primary np-btn-form-submit mt-3 mx-5 d-flex align-items-center" style="width: fit-content;border: unset;column-gap: 10px;">তথ্য হালনাগদ করুন
                                        <img src="{{ asset('frontend/noipunno/images/icons/arrow-right.svg') }}" alt=""></button>
                                </div>
                            </div>
                            @endif
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        document.getElementById("edit-form").scrollIntoView({
            "behavior": "smooth"
        });
    });
</script>
@endsection
