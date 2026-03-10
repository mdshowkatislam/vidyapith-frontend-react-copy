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
                                        <h2 class="title">শিক্ষার্থী ব্যবস্থাপনা</h2>
                                        <nav aria-label="breadcrumb">
                                            <ol class="breadcrumb np-breadcrumb">
                                                <li class="breadcrumb-item"><a href="{{ route('home') }}">
                                                        <img src="{{ asset('frontend/noipunno/images/icons/home.svg') }}"
                                                            alt="">
                                                        ড্যাশবোর্ড
                                                    </a></li>
                                                <li class="breadcrumb-item active" aria-current="page"><a
                                                        href="{{ route('student.index') }}">
                                                        শিক্ষার্থী ব্যবস্থাপনা
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
                    <section class="np-teacher-list mt-4">
                        {{-- <div class="container"> --}}
                        <div class="row">
                            <div class="col-md-12 mb-2 d-flex justify-content-between">
                                <h2 class="np-form-title">পেমেন্ট লিস্ট</h2>
                                @if(count($payments)>0) <a href="{{ route('student.board_registration.payment.list_print') }}" target="_blanck" class="btn nav-right-dorpdown" > <i class="fas fa-print"></i> Print Payment List</a>@endif
                            </div>

                            <div class="col-md-12">
                                <div class="card np-card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table np-table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Depositor Name</th>
                                                        <th scope="col">Depositor Mobile</th>
                                                        <th scope="col">Amount</th>
                                                        <th scope="col">Class</th>
                                                        <th scope="col">No of Students</th>
                                                        <th scope="col">PDF</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($payments as $payment)
                                                        <tr>
                                                            <td scope="row"><span class="icon"> <img
                                                                        src="{{ asset('frontend/noipunno/images/icons/user.svg') }}"
                                                                        alt=""></span>{{ @$branch->branch_name }}
                                                                {{ @$payment->depositor_name }}</td>
                                                            {{-- <td scope="row">{{ @$branch->branch_id }}</td> --}}
                                                            <td scope="row">
                                                                {{ @$payment->depositor_mobile }}
                                                            </td>
                                                            <td scope="row">
                                                                {{ @$payment->amount }}
                                                            </td>
                                                            <td scope="row">
                                                                {{ @$payment->class }}
                                                            </td>
                                                            <td scope="row">
                                                                {{ @$payment->no_of_students }}
                                                            </td>
                                                            <td scope="row">
                                                                <a href="{{ route('student.board_registration.challan.generate', $payment->uid) }}"
                                                                    class="np-route" target="_blank">
                                                                    <button class="btn np-edit-btn-small"
                                                                        style="font-size: 12px; width: 85px !important; color: #fff;">DownLoad
                                                                    </button>
                                                                </a>
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
                        {{-- </div> --}}
                    </section>
                </div>
            </div>
        </div>
    </div>

    <script></script>
@endsection
