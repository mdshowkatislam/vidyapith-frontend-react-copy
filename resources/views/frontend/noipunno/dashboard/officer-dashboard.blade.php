@extends('frontend.layouts.noipunno')

@section('content')

<div class="dashboard-section">
    <section class="pt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-4">
                            {{-- <a href="{{ route('teacher.index') }}" style="text-decoration: unset"> --}}
                                <div class="card np-card">
                                    <div class="card-body" style="text-align: center">
                                        <h2 style="margin: 0;font-size: 18px;color: rgba(45, 102, 104, 1)">Number of School: {{ count($upazila_institutes) }}</h2>
                                    </div>
                                </div>
                            {{-- </a> --}}
                        </div>
        
                        <div class="col-md-4">
                            {{-- <a href="{{ route('student.index') }}" style="text-decoration: unset"> --}}
                                <div class="card np-card">
                                    <div class="card-body" style="text-align: center">
                                        <h2 style="margin: 0;font-size: 18px;color: rgba(45, 102, 104, 1)">Number of Teacher: 45</h2>
                                    </div>
                                </div>
                            {{-- </a> --}}
                        </div>

                        <div class="col-md-4">
                            {{-- <a href="{{ route('student.index') }}" style="text-decoration: unset"> --}}
                                <div class="card np-card">
                                    <div class="card-body" style="text-align: center">
                                        <h2 style="margin: 0;font-size: 18px;color: rgba(45, 102, 104, 1)">Number of Student: 990</h2>
                                    </div>
                                </div>
                            {{-- </a> --}}
                        </div>
                    </div>
                </div>

                {{-- <div class="col-md-9 mt-5">
                </div> --}}
                
                {{-- <div class="col-md-3">
                    <div class="col-md-12">
                        <div class="card np-card head-teacher-card">
                            <div class="head-teacher-top w-100">
                                <div class="d-flex justify-content-end ">
                                    <button class="btn">
                                        <img src="{{ asset('frontend/images/edit.svg') }}" />
                                    </button>
                                </div>
                                <div class="d-flex flex-column justify-content-center align-items-center ">
                                    <img src="{{ asset('frontend/noipunno/images/avatar/teacher.png') }}"
                                        class="border rounded-circle p-3 bg-light" alt="">
                                    <p class="mt-3 p-2">প্রধান শিক্ষক</p>
                                </div>

                                <div class="head-teacher-top-icons d-flex justify-content-center align-items-center">
                                    <img src="{{ asset('frontend/noipunno/images/icons/star.svg') }}" />
                                    <img src="{{ asset('frontend/noipunno/images/icons/message.svg') }}" />
                                    <img src="{{ asset('frontend/noipunno/images/icons/moon.svg') }}" />

                                </div>
                            </div>
                            <div class="head-teacher-bottom d-flex flex-column ">
                                <div class="w-100 d-flex flex-column  align-items-center justify-content-center mt-3 ">
                                    <h5>
                                        {{$user->name}}
                                    </h5>
                                    <small>{{$user->caid}}</small>
                                    <small>{{@$institute->institute_name}}</small>
                                </div>
                                <button class="m-3 profile-button">
                                    <img src="{{ asset('frontend/noipunno/images/icons/eye.svg') }}" />

                                    <p class="m-0">
                                        আমার প্রোফাইল
                                    </p>
                                </button>

                            </div>
                        </div>


                    </div>
                </div> --}}
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
                                <h2 class="title">Institute লিস্ট</h2>
                            </div>

                            <div class="col-md-12">
                                <div class="card np-card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table np-table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">SL <span class="icon"><img
                                                                    src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}"
                                                                    alt=""></span></th>
                                                        <th scope="col">ইনস্টিটিউট নাম <span class="icon"><img
                                                                    src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}"
                                                                    alt=""></span></th>
                                                        <th scope="col">ইনস্টিটিউট EIIN <span class="icon"><img
                                                                    src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}"
                                                                    alt=""></span></th>
                                                        <th scope="col">ইনস্টিটিউট প্রধান শিক্ষক<span class="icon"><img
                                                                    src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}"
                                                                    alt=""></span></th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($upazila_institutes as $item)
                                                        <tr>
                                                            <td> {{ $loop->iteration }}</td>
                                                            <td scope="row"><span class="icon"><img
                                                                        src="{{ asset('frontend/noipunno/images/icons/user.svg') }}"
                                                                        alt=""></span>{{ @$item->institute_name }}
                                                            </td>
                                                            <td scope="row">{{ @$item->eiin }}</td>
                                                            <td scope="row">{{ @$item->headMaster->name_en }}</td>
                                                            {{-- <td scope="row">
                                                                <div class="action-content">
                                                                    <h2 class="created-date">{{ date('j F Y', strtotime(@$branch->created_at)) }}</h2>
                                                                    <a href="{{ route('noipunno.dashboard.branch.edit', ['id' => @$branch->uid]) }}"
                                                                        class="np-route">
                                                                        <button class="btn np-edit-btn-small">
                                                                            <img src="{{ asset('frontend/noipunno/images/icons/edit-white.svg') }}"
                                                                                alt="">
                                                                        </button>
                                                                    </a>
                                                                </div>
                                                                </td> --}}

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
                <section class="section-teacher-add-form mt-5 np-input-form-bg">
                    <div class="container">
                        <form action="{{ route('noipunno.dashboard.branch.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <div>
                                        <label for="beiin-psid" class="form-label">ইনস্টিটিউট নাম </label>
                                        <input type="number" class="form-control np-teacher-input" id="loginId"
                                            placeholder="" name="branch_id">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div>
                                        <label for="beiin-psid" class="form-label">ইনস্টিটিউট EIIN </label>
                                        <input type="number" class="form-control np-teacher-input" id="loginId"
                                            placeholder="" name="branch_id">
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <div>
                                        <label for="beiin-psid" class="form-label">ইনস্টিটিউট প্রধান শিক্ষক </label>
                                        <input type="text" class="form-control np-teacher-input" id="loginId"
                                            placeholder="" name="branch_name">
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 d-flex justify-content-start">
                                    <button type="submit"
                                        class="btn btn-primary np-btn-form-submit mt-3 d-flex align-items-center"
                                        style="width: fit-content;border: unset;column-gap: 10px;">ইনস্টিটিউট তৈরি করুন
                                        <img src="{{ asset('frontend/noipunno/images/icons/arrow-right.svg') }}"
                                            alt=""></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
<style>
    .np-table th,
    td {
        font-size: 11px;
    }
</style>

@endsection