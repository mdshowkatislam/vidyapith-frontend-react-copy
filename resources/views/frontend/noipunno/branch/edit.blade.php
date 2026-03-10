@extends('frontend.layouts.noipunno')

@section('content')

<div class="dashboard-section">
    <section class="np-breadcumb-section">
        <div class="container">
            <div class="row">
                {{-- @if(Session::has('version_items') && count(Session::get('version_items')) > 0)
                <div class="col-md-12 mt-5">
                  <div class="alert alert-danger" role="alert">
                    <strong>ইতিমধ্যে এই শাখার অধীনে {{ count(Session::get('version_items')) }} টি ভার্সন এর তথ্য রয়েছে। অনুগ্রহপূর্বক ভার্সন এর তথ্য হালনাগাদ করুন।</strong>
                   </div>
                </div>
              @endif

              @if(Session::has('shift_items') && count(Session::get('shift_items')) > 0)
                <div class="col-md-12 mt-2">
                  <div class="alert alert-danger" role="alert">
                    <strong>ইতিমধ্যে এই শাখার অধীনে {{ count(Session::get('shift_items')) }} টি শিফট এর তথ্য রয়েছে। অনুগ্রহপূর্বক শিফট এর তথ্য হালনাগাদ করুন।</strong>
                  </div>
                </div>
              @endif

              @if(Session::has('section_items') && count(Session::get('section_items')) > 0)
                <div class="col-md-12 mt-2">
                  <div class="alert alert-danger" role="alert">
                    <strong>ইতিমধ্যে এই শাখার অধীনে {{ count(Session::get('section_items')) }} টি সেকশন এর তথ্য রয়েছে। অনুগ্রহপূর্বক সেকশন এর তথ্য হালনাগাদ করুন।</strong>
                  </div>
                </div>
              @endif

              @if(Session::has('student_items') && count(Session::get('student_items')) > 0)
                <div class="col-md-12 mt-2">
                  <div class="alert alert-danger" role="alert">
                    <strong>ইতিমধ্যে এই শাখার অধীনে {{ count(Session::get('student_items')) }} জন শিক্ষার্থী এর তথ্য রয়েছে। অনুগ্রহপূর্বক শিক্ষার্থী এর তথ্য হালনাগাদ করুন।</strong>
                  </div>
                </div>
              @endif
              @if(Session::has('subject_teachers') && count(Session::get('subject_teachers')) > 0)
                <div class="col-md-12 mt-2">
                  <div class="alert alert-danger" role="alert">
                    <strong>ইতিমধ্যে এই শাখার অধীনে {{ count(Session::get('subject_teachers')) }} টি সেকশন এ বিষয় শিক্ষক এর তথ্য রয়েছে। অনুগ্রহপূর্বক বিষয় শিক্ষক এর তথ্য হালনাগাদ করুন।</strong>
                  </div>
                </div>
              @endif --}}

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
                <section class="np-teacher-list mt-4">
                    {{-- <div class="container"> --}}
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <h2 class="np-form-title">ব্রাঞ্চ লিস্ট</h2>
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
                                                        <th scope="col">ব্রাঞ্চের ঠিকানা
                                                            {{-- <span class="icon"> <img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span> --}}
                                                        </th>
                                                        {{-- <th scope="col">ব্রাঞ্চ আইডি<span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span></th> --}}
                                                        <th scope="col">ব্রাঞ্চ/প্রতিষ্ঠান প্রধান
                                                            {{-- <span class="icon"> <img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span> --}}
                                                        </th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($branchList as $branch)
                                                    <tr>
                                                        <td scope="row"><span class="icon"> <img src="{{ asset('frontend/noipunno/images/icons/user.svg') }}" alt=""></span>{{ @$branch->branch_name }}
                                                            </th>
                                                        <td scope="row">{{ @$branch->branch_location }}</th>
                                                            {{-- <td scope="row">{{ @$branch->branch_id }}</th> --}}
                                                        <td scope="row">
                                                            {{ @$branch->branchHead->name_en ?? @$branch->branchHead->name_bn }}
                                                            </th>
                                                        {{-- <td scope="row">{{ @$branch->eiin }}</th> --}}
                                                        <td scope="row">
                                                            <div class="action-content">
                                                                <!-- <h2 class="created-date">{{ date('j F Y', strtotime(@$branch->created_at)) }}</h2> -->
                                                                <a href="{{ route('noipunno.dashboard.branch.edit', ['id' => @$branch->uid]) }}" class="np-route">
                                                                    <button class="btn np-edit-btn-small">
                                                                        <img src="{{ asset('frontend/noipunno/images/icons/edit-white.svg') }}" alt="">
                                                                    </button>
                                                                </a>

                                                                <a class="btn np-delete-btn-small delete_module"
                                                                            title="Delete" data-id="{{ $branch->uid }}"
                                                                            data-token={{ csrf_token() }}
                                                                            data-route="{{ route('noipunno.dashboard.branch.delete') }}"><i
                                                                                class="fa fa-trash np-delete-btn-small-icon"></i></a>

                                                                {{-- <form action="{{ route('noipunno.dashboard.branch.delete', ['id' => @$branch->uid]) }}" method="POST">
                                                                  @method('delete')
                                                                  @csrf
                                                                  <button class="btn np-delete-btn-small" type="submit">
                                                                    <i class="fa fa-trash np-delete-btn-small-icon"></i>
                                                                  </button>
                                                                </form> --}}
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

                            <!-- <div class="col-md-12">
                                            <div class="np-pagination-section d-flex justify-content-between align-items-center">
                                                <div class="np-select-page-number d-flex align-items-center">
                                                    <select class="form-select" aria-label="Default select example">
                                                        <option value="10">10</option>
                                                        <option value="15">15</option>
                                                        <option value="25">25</option>
                                                        <option value="50">50</option>
                                                    </select>

                                                    <h2 class="showing">
                                                        Rows Showing 1 to 10 of 100 entries
                                                    </h2>
                                                </div>
                                                <div class="pages">
                                                    <div class="export-types">
                                                        <button class="np-btn np-btn-primary np-btn-with-icon">
                                                            <img src="{{ asset('frontend/noipunno/images/icons/pdf-export-icon.svg') }}" alt="">
                                                            PDF
                                                        </button>

                                                        <button class="np-btn np-btn-primary np-btn-with-icon">
                                                            <img src="{{ asset('frontend/noipunno/images/icons/export-excel-icon.svg') }}" alt="">
                                                            Excel
                                                        </button>
                                                    </div>

                                                    <nav aria-label="Page navigation example">
                                                        <ul class="np-pagination pagination justify-content-end">
                                                            <li class="page-item np-card">
                                                                <a class="page-link" href="#"><img src="{{ asset('frontend/noipunno/images/icons/chevron-left.svg') }}" alt=""></a>
                                                            </li>
                                                            <li class="page-item np-card"><a class="page-link" href="#">1</a></li>
                                                            <li class="page-item np-card"><a class="page-link active" href="#">2</a></li>
                                                            <li class="page-item np-card"><a class="page-link" href="#">3</a></li>
                                                            <li class="page-item np-card">
                                                                <a class="page-link" href="#">
                                                                    <img src="{{ asset('frontend/noipunno/images/icons/chevron-right.svg') }}" alt="">
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </nav>
                                                </div>
                                            </div>
                                        </div> -->
                        </div>
                    {{-- </div> --}}
                </section>

                {{-- <div>
                    <div class="np-pagination-section d-flex justify-content-end align-items-center">
                        <div class="np-select-page-number d-flex align-items-center">
                            {{ $branchList->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div> --}}

                <h3 class="mt-4 mb-2 np-form-title">ব্রাঞ্চ তথ্য পরিবর্তন করুন</h3>

                <section class="section-teacher-add-form np-input-form-bg mb-5" id="edit-form">
                    <div class="container">
                        <form action="{{ route('noipunno.dashboard.branch.update', ['id'=> @$branchData->uid]) }}" method="POST">
                            @method('PUT')
                            @csrf

                            <div class="row">
                                <!-- <div class="col-md-4 col-sm-12">
                                        <div>
                                            <label for="beiin-psid" class="form-label">ব্রাঞ্চ আইডি </label>
                                            <input type="number" class="form-control np-teacher-input" id="loginId"
                                                placeholder="123" name="branch_id" value="{{ @$branch->branch_id }}">
                                        </div>
                                    </div> -->

                                <div class="col-md-4 col-sm-12">
                                    <div>
                                        <label for="beiin-psid" class="form-label">ব্রাঞ্চের নাম <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control np-teacher-input" id="loginId" placeholder="ব্রাঞ্চের নাম" name="branch_name" value="{{ old('branch_name',@$branchData->branch_name) }}">
                                    </div>
                                    @if ($errors->has('branch_name'))
                                    <small class="help-block form-text text-danger">{{ $errors->first('branch_name') }}</small>
                                    @endif
                                </div>

                                <!-- <div class="col-md-4 col-sm-12">
                                    <div>
                                        <label for="beiin-psid" class="form-label">EIIN নম্বর</label>
                                        <input type="text" class="form-control np-teacher-input" id="loginId" placeholder="EIIN" name="eiin" value="{{ $eiinId }}" readonly>
                                    </div>
                                </div> -->

                                <div class="col-md-4 col-sm-12">
                                    <div>
                                        <label for="beiin-psid" class="form-label">ব্রাঞ্চের ঠিকানা <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control np-teacher-input" id="loginId" placeholder="ঠিকানা" name="branch_location" value="{{ old('branch_location', @$branchData->branch_location) }}">
                                        @if ($errors->has('branch_location'))
                                        <small class="help-block form-text text-danger">{{ $errors->first('branch_location') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <div>
                                        <label for="beiin-psid" class="form-label">ব্রাঞ্চ/প্রতিষ্ঠান প্রধান <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <select class="form-select np-teacher-input select2" aria-label="Default select example" name="head_of_branch_id">
                                                <option value="">ব্রাঞ্চ/প্রতিষ্ঠান প্রধান নির্বাচন করুন</option>
                                                @foreach ($myTeachers as $teacher)
                                                <option value="{{ $teacher->uid }}" {{old('head_of_branch_id',@$branchData->head_of_branch_id) == @$teacher->uid ? 'selected':''}}>{{ $teacher->name_en }} - {{$teacher->pdsid ?? $teacher->index_number ?? $teacher->caid}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($errors->has('head_of_branch_id'))
                                        <small class="help-block form-text text-danger">{{ $errors->first('head_of_branch_id') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 d-flex justify-content-start">
                                    <button type="submit" class="btn btn-primary np-btn-form-submit mt-3 d-flex align-items-center" style="width: fit-content;border: unset;column-gap: 10px;"> <a class="np-btn-cancel" href="{{ route('noipunno.dashboard.branch.add') }}">বাতিল করুন</a>
                                        <img src="{{ asset('frontend/noipunno/images/icons/arrow-right.svg') }}" alt=""></button>
                                    <button type="submit" class="btn btn-primary np-btn-form-submit mt-3 mx-5 d-flex align-items-center" style="width: fit-content;border: unset;column-gap: 10px;">তথ্য হালনাগদ করুন
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

<script>
    $(document).ready(function() {
        document.getElementById("edit-form").scrollIntoView({
            "behavior": "smooth"
        });
    });
</script>
@endsection
