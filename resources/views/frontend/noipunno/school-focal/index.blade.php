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
                                        <h2 class="title">শিক্ষার্থীদের মূল্যায়ন প্রতিবেদন </h2>
                                        <nav aria-label="breadcrumb">
                                            <ol class="breadcrumb np-breadcrumb">
                                                <li class="breadcrumb-item"><a href="#">
                                                        <img src="{{ asset('frontend/noipunno/images/icons/home.svg') }}"
                                                            alt="">
                                                        Dashboard
                                                    </a></li>
                                                <li class="breadcrumb-item active" aria-current="page">মূল্যায়ন প্রতিবেদন
                                                </li>
                                            </ol>
                                        </nav>

                                    </div>
                                </div>
                                <div class="option-section">
                                    <div class="fav-icon">
                                        <img src="{{ asset('frontend/noipunno/images/icons/fav-start-icon.svg') }}"
                                            alt="">
                                    </div>
                                    <div class="dots-icon">
                                        <img src="{{ asset('frontend/noipunno/images/icons/3-dot-vertical.svg') }}"
                                            alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- table starts --}}
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="card np-card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table np-table">
                                    <thead>
                                        <tr>
                                            <th scope="col">ক্রমিক </th>
                                            <th scope="col">বিদ্যালয়ের নাম <span class="icon"><img
                                                        src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}"
                                                        alt=""></span></th>
                                            <th scope="col">বিদ্যালয়ের EIIN <span class="icon"><img
                                                        src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}"
                                                        alt=""></span></th>
                                            <th scope="col">বিদ্যালয়ের মুখ্যপাত্র<span class="icon"><img
                                                        src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}"
                                                        alt=""></span></th>
                                            <th scope="col">পদবী <span class="icon"><img
                                                        src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}"
                                                        alt=""></span></th>
                                            <th scope="col">ফোন নম্বর <span class="icon"><img
                                                        src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}"
                                                        alt=""></span></th>
                                            <th scope="col">মেইল <span class="icon"><img
                                                        src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}"
                                                        alt=""></span></th>
                                            <th scope="col">অপারেশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr>
                                            <td scope="row">1
                                            </td>
                                            <td scope="row">পাবনা জেলা স্কুল
                                            </td>
                                            <td scope="row">
                                                2016705265
                                            </td>
                                            <td scope="row">2016705265</td>
                                            <td scope="row">প্রধান শিক্ষক (ভারঃ)</th>
                                            <td scope="row">xxxxx-xxxxxx</td>
                                            <td scope="row">xxxxx-xxxxxx</td>
                                            <td scope="row">
                                                <div class="action-content">
                                                    <h2 class="created-date">

                                                    </h2>
                                                    <a href=""#" class="np-route">
                                                        <button class="btn np-edit-btn-small">
                                                            <img src="{{ asset('frontend/noipunno/images/icons/edit-white.svg') }}"
                                                                alt="">
                                                        </button>
                                                    </a>
                                                    <img src="{{ asset('frontend/noipunno/images/icons/3-dots-horizontal.svg') }}"
                                                        alt="">
                                                </div>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        {{-- table ends --}}

    </div>
    <style>
        .np-table th,
        td {
            font-size: 11px;
        }

        span.error {
            color: red;
        }
    </style>

    <script></script>
@endsection
