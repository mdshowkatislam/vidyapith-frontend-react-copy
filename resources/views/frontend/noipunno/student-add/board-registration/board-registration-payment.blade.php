@extends('frontend.layouts.noipunno')
<style>
    .np-table th,
    td {
        font-size: 11px;
    }

    span.error {
        color: red;
    }

    input[type="number"]:read-only {
        cursor: normal;
        background-color: rgb(240, 240, 240);
    }
</style>
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
        {{-- @include('frontend.layouts.notice') --}}

        <div class="container mt-4 mb-5">
            <div class="np-teacher-list row mb-2">
                <div class="col-md-12">
                    <h2 class="np-form-title">পেমেন্ট করুন</h2>
                </div>
            </div>
            <section class="np-teacher-add-form" id="edit-form">
                <div class="np-input-form-bg">
                    <div class="container">
                        <form method="POST" action="{{ route('student.board_registration.payment.store') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="class" value="{{$payment_config->class}}">
                            <div class="row">
                                <div class="col-md-6 col-sm-12 mb-3">
                                    <div class="">
                                        <label for="depositor_name" class="form-label">Depositor Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="depositor_name" data-toggle="tooltip"
                                            data-placement="top" title="Depositor Name"
                                            class="form-control np-teacher-input"
                                            value="">
                                    </div>
                                    @if ($errors->has('depositor_name'))
                                        <small class="help-block form-text text-danger">{{ $errors->first('depositor_name') }}</small>
                                    @endif
                                </div>
                                <div class="col-md-6 col-sm-12 mb-3">
                                    <div class="">
                                        <label for="depositor_mobile" class="form-label">Depositor Mobile <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="depositor_mobile" data-toggle="tooltip"
                                            data-placement="top" title="Depositor Mobile"
                                            class="form-control np-teacher-input"
                                            value="">
                                    </div>
                                    @if ($errors->has('depositor_mobile'))
                                        <small
                                            class="help-block form-text text-danger">{{ $errors->first('depositor_mobile') }}</small>
                                    @endif
                                </div>
                                <div class="col-md-6 col-sm-12 mb-3">
                                    <div class="">
                                        <label for="no_of_students" class="form-label">Number of Student <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="no_of_students" data-toggle="tooltip"
                                            data-placement="top" title="Number of Student"
                                            class="form-control np-teacher-input"
                                            value="">
                                    </div>
                                    @if ($errors->has('no_of_students'))
                                        <small class="help-block form-text text-danger">{{ $errors->first('no_of_students') }}</small>
                                    @endif
                                </div>
                                <div class="col-md-6 col-sm-12 mb-3">
                                    <div class="">
                                        <label for="amount" class="form-label">Amount <span
                                                class="text-danger"><small>(Per Student {{$payment_config->amount}} TK.)</small> *</span></label>
                                        <input type="text" name="amount" data-toggle="tooltip"
                                            data-placement="top" title="Amount"
                                            class="form-control np-teacher-input"
                                            value="" readonly>
                                    </div>
                                    @if ($errors->has('amount'))
                                        <small
                                            class="help-block form-text text-danger">{{ $errors->first('amount') }}</small>
                                    @endif
                                </div>
                                <div class="col-md-4 col-sm-12 mt-3">
                                    <div>
                                        <div class="text-center ">
                                            <button type="submit"
                                                class="btn btn-primary np-btn-form-submit mt-3">পেমেন্ট করুন</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('input[name="no_of_students"]').on('input', function() {
                var noOfStudents = $(this).val();
                var perUnitAmount = {{ $payment_config->amount }};
                var amount = noOfStudents * perUnitAmount;
                $('input[name="amount"]').val(amount);
            });
        });
    </script>
@endsection
