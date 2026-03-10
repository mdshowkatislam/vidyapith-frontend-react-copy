@extends('frontend.layouts.noipunno')
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
    .np-table th,td {
        font-size: 11px;
    }
</style>
@section('content')
    <div class="dashboard-section">
        <section class="np-breadcumb-section pt-5" style="height: 80vh">
            <div class="container">
                <div class="row">
                    <div class="col-md-9">
                        <div class="row" style="row-gap: 10px;">
                            <div class="col-md-6">
                                <a href="{{ route('noipunno.dashboard.branch.add') }}" style="text-decoration: unset">
                                    <div class="card np-card">
                                        <div class="card-body" style="text-align: center">
                                            <h2 style="margin: 0;font-size: 18px;color: rgba(45, 102, 104, 1)"><span
                                                    class="circleOrange">1</span>&emsp;ব্রাঞ্চ ব্যবস্থাপনা</h2>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('noipunno.dashboard.shift.add') }}" style="text-decoration: unset">
                                    <div class="card np-card">
                                        <div class="card-body" style="text-align: center">
                                            <h2 style="margin: 0;font-size: 18px;color: rgba(45, 102, 104, 1)"><span
                                                    class="circleOrange">2</span>&emsp;শিফট ব্যবস্থাপনা</h2>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('noipunno.dashboard.version.add') }}" style="text-decoration: unset">
                                    <div class="card np-card">
                                        <div class="card-body" style="text-align: center">
                                            <h2 style="margin: 0;font-size: 18px;color: rgba(45, 102, 104, 1)"><span
                                                    class="circleOrange">3</span>&emsp;ভার্সন ব্যবস্থাপনা</h2>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('noipunno.dashboard.section.add') }}" style="text-decoration: unset">
                                    <div class="card np-card">
                                        <div class="card-body" style="text-align: center">
                                            <h2 style="margin: 0;font-size: 18px;color: rgba(45, 102, 104, 1)"><span
                                                    class="circleOrange">4</span>&ensp;সেকশন ব্যবস্থাপনা</h2>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('teacher.index') }}" style="text-decoration: unset">
                                    <div class="card np-card">
                                        <div class="card-body" style="text-align: center">
                                            <h2 style="margin: 0;font-size: 18px;color: rgba(45, 102, 104, 1)"><span
                                                    class="circleOrange">5</span>&emsp;শিক্ষক ব্যবস্থাপনা</h2>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('student.index') }}" style="text-decoration: unset">
                                    <div class="card np-card">
                                        <div class="card-body" style="text-align: center">
                                            <h2 style="margin: 0;font-size: 18px;color: rgba(45, 102, 104, 1)"><span
                                                    class="circleOrange">6</span>&emsp;শিক্ষার্থী ব্যবস্থাপনা</h2>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-12">
                                <a href="{{ route('noipunno.dashboard.classroom.add') }}" style="text-decoration: unset">
                                    <div class="card np-card">
                                        <div class="card-body" style="text-align: center">
                                            <h2 style="margin: 0;font-size: 18px;color: rgba(45, 102, 104, 1)"><span
                                                    class="circleOrange">7</span>&emsp;বিষয় শিক্ষক নির্বাচন</h2>
                                        </div>
                                    </div>
                                </a>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="col-md-12">
                            <div class="card np-card head-teacher-card">
                                <div class="head-teacher-top w-100">
                                    <div class="d-flex justify-content-end ">
                                        <a href="{{ @$user->eiin }}">
                                            <button class="btn">
                                                <img src="{{ asset('frontend/images/edit.svg') }}" />
                                            </button>
                                        </a>
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
                                            {{ $user->name }}
                                        </h5>
                                        @if (@$user->user_type_id == 3)
                                            <small>{{ @$user->eiin ?? @$user->caid }}</small>
                                        @else
                                            <small>{{ @$user->pdsid ?? (@$user->suid ?? @$user->caid) }}</small>
                                        @endif
                                        <small>{{ @$institute->institute_name }}</small>
                                    </div>
                                    {{-- <button class="m-3 profile-button">
                                    <img src="{{ asset('frontend/noipunno/images/icons/eye.svg') }}" />

                                    <p class="m-0">
                                        আমার প্রোফাইল
                                    </p>
                                </button> --}}

                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
