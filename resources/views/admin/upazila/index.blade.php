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
                                    <h2 class="title">ব্রাঞ্চ ব্যবস্থাপনা </h2>
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb np-breadcrumb">
                                            <li class="breadcrumb-item"><a href="{{route('home')}}">
                                                    <img src="{{ asset('frontend/noipunno/images/icons/home.svg') }}" alt="">
                                                    ড্যাশবোর্ড
                                                </a></li>
                                            <li class="breadcrumb-item active" aria-current="page">ব্রাঞ্চ ব্যবস্থাপনা</li>
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
                <section class="np-teacher-list mt-3">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <h2 class="title mb-3">ব্রাঞ্চ লিস্ট</h2>
                            </div>

                            <div class="col-md-12">
                                <div class="card np-card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table np-table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">ব্রাঞ্চের নাম
                                                            {{-- <span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span> --}}
                                                        </th>
                                                        <th scope="col">ব্রাঞ্চ লোকেশন
                                                            {{-- <span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span> --}}
                                                        </th>
                                                        <th scope="col">Action </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($branchList as $branch)
                                                    <tr>
                                                        <td scope="row"><span class="icon"> <img src="{{ asset('frontend/noipunno/images/icons/user.svg') }}" alt=""></span>{{ @$branch->branch_name }}
                                                            </td>
                                                        <td scope="row">{{ @$branch->branch_location }}</td>
                                                        {{-- <td scope="row">{{ @$branch->branch_id }}</td> --}}
                                                        <td scope="row">
                                                            {{ @$branch->branchHead->name_en ?? @$branch->branchHead->name_bn }}
                                                            </td>
                                                        {{-- <td scope="row">{{ @$branch->eiin }}</td> --}}
                                                        <td scope="row">
                                                            <div class="action-content">
                                                                <!-- <h2 class="created-date">{{ date('j F, Y', strtotime(@$branch->created_at)) }}</h2> -->

                                                                <a href="{{ route('noipunno.dashboard.branch.edit', ['id' => @$branch->uid]) }}" class="np-route">
                                                                    <button class="btn np-edit-btn-small">
                                                                        <img src="{{ asset('frontend/noipunno/images/icons/edit-white.svg') }}" alt="">
                                                                    </button>
                                                                </a>

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
                        </div>
                    </div>
                </section>

                {{-- <div>
                    <div class="np-pagination-section d-flex justify-content-end align-items-center">
                        <div class="np-select-page-number d-flex align-items-center">
                            {{ $branchList->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div> --}}

                <h3 class="mt-5 mb-3 np-form-title">ব্রাঞ্চ যোগ করুন</h3>

                <section class="section-teacher-add-form np-input-form-bg mb-5">
                    <div class="container">
                        <form action="{{ route('noipunno.dashboard.branch.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <!-- <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="beiin-psid" class="form-label">ব্রাঞ্চ আইডি </label>
                                            <input type="number" class="form-control np-teacher-input" id="loginId"
                                                placeholder="123" name="branch_id">
                                        </div>
                                    </div> -->

                                <div class="col-md-4 col-sm-12">
                                    <div>
                                        <label for="beiin-psid" class="form-label">ব্রাঞ্চের নাম <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control np-teacher-input" id="loginId" placeholder="ব্রাঞ্চের নাম"
                                        name="branch_name" value="{{ old('branch_name','')}}">
                                        @if ($errors->has('branch_name'))
                                        <small class="help-block form-text text-danger">{{ $errors->first('branch_name') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <div>
                                        <label for="beiin-psid" class="form-label">ঠিকানা <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control np-teacher-input" id="loginId" placeholder="ঠিকানা"
                                        name="branch_location" value="{{ old('branch_location','')}}">
                                        @if ($errors->has('branch_location'))
                                        <small class="help-block form-text text-danger">{{ $errors->first('branch_location') }}</small>
                                        @endif
                                    </div>
                                </div>

                                {{-- <div class="col-md-4 col-sm-12">
                                    <div>
                                        <label for="beiin-psid" class="form-label">ব্রাঞ্চ প্রধান <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input" aria-label="Default select example" name="head_of_branch_id">
                                                <option value="">ব্রাঞ্চ প্রধান নির্বাচন করুন</option>
                                                @foreach ($myTeachers as $teacher)
                                                <option value="{{ $teacher->uid }}" {{ old('head_of_branch_id','') == @$teacher->uid ? 'selected':'' }}>{{ $teacher->name_en }} - {{$teacher->pdsid ?? $teacher->index_number ?? $teacher->caid}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($errors->has('head_of_branch_id'))
                                        <small class="help-block form-text text-danger">{{ $errors->first('head_of_branch_id') }}</small>
                                        @endif
                                    </div>
                                </div> --}}
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 d-flex justify-content-start">
                                    <button type="submit" class="btn btn-primary np-btn-form-submit mt-3 d-flex align-items-center" style="width: fit-content;border: unset;column-gap: 10px;">তথ্য সংযোজন করুন
                                        <img src="{{ asset('frontend/noipunno/images/icons/arrow-right.svg') }}" alt=""></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<script></script>
@endsection
