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
                                    <h2 class="title">Create Session </h2>
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb np-breadcrumb">
                                            <li class="breadcrumb-item"><a href="#">
                                                    <img src="{{ asset('frontend/noipunno/images/icons/home.svg') }}" alt="">
                                                    Session
                                                </a></li>
                                            <li class="breadcrumb-item active" aria-current="page">Data</li>
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
                                <h2 class="title">Session List</h2>
                            </div>

                            <div class="col-md-12">
                                <div class="card np-card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table np-table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">সেশনের নাম <span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span></th>
                                                        <th scope="col">সেশনের বিস্তারিত তথ্য<span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span></th>
                                                        <th scope="col">Class Name<span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span></th>
                                                        <th scope="col">Section Name<span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span></th>
                                                        <th scope="col">সেশন শুরুর সময়<span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span></th>
                                                        <th scope="col">সেশন শেষ সময়<span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span></th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td scope="row"><span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/user.svg') }}" alt=""></span>2022-2023</th>
                                                        <td scope="row">Started in new nctb rules</th>
                                                        <td scope="row">Class Name</th>
                                                        <td scope="row">Section Name</th>
                                                        <td scope="row">8:30 AM</th>
                                                        <td scope="row">12:30 PM</th>
                                                        <td scope="row">
                                                            <div class="action-content">
                                                                <h2 class="created-date">Update 24/11/2018</h2>
                                                                <a href="{{ route('noipunno.dashboard.session.edit',['id'=> '2013959840']) }}" class="np-route">
                                                                    <button class="btn np-edit-btn-small">
                                                                        <img src="{{ asset('frontend/noipunno/images/icons/edit-white.svg') }}" alt="">
                                                                    </button>
                                                                </a>
                                                            </div>
                                                            </th>

                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="col-md-12">
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
        </div> --}}
    </div>
</div>
</section>

<section class="section-teacher-add-form mt-5 np-input-form-bg">
    <div class="container">
        <form>
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <div>
                        <label for="beiin-psid" class="form-label">সেশনের নাম*</label>
                        <input type="text" class="form-control np-teacher-input" id="loginId" placeholder="প্রভাতি">
                    </div>
                </div>

                <div class="col-md-4 col-sm-12">
                    <div>
                        <label for="beiin-psid" class="form-label">সেশনের বিস্তারিত তথ্য</label>
                        <input type="text" class="form-control np-teacher-input" id="loginId" placeholder="সেশনের বিস্তারিত তথ্য">
                    </div>
                </div>

                <div class="col-md-4 col-sm-12">
                    <div>
                        <label for="beiin-psid" class="form-label">Section</label>
                        <div class="input-group">
                            <select class="form-select np-teacher-input" aria-label="Default select example">
                                <option selected>Select Section</option>
                                <option value="1">Section One</option>
                                <option value="2">Section Two</option>
                                <option value="3">Section Three</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12 mt-5">
                    <div>
                        <label for="beiin-psid" class="form-label">Class*</label>
                        <div class="input-group">
                            <select class="form-select np-teacher-input" aria-label="Default select example">
                                <option selected>Select Class</option>
                                <option value="1">Class One</option>
                                <option value="2">Class Two</option>
                                <option value="3">Class Three</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-12 mt-5">
                    <div>
                        <label for="beiin-psid" class="form-label">সেশন শুরুর সময়*</label>
                        <input type="text" class="form-control np-teacher-input np-time-picker" id="loginId" placeholder="সেশন শুরুর সময়">
                    </div>
                </div>

                <div class="col-md-4 col-sm-12 mt-5">
                    <div>
                        <label for="beiin-psid" class="form-label">সেশন শেষ সময়*</label>
                        <input type="datetime" class="form-control np-teacher-input np-time-picker" id="loginId" placeholder="সেশন শেষ সময়">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 d-flex justify-content-start">
                    <button type="submit" class="btn btn-primary np-btn-form-submit mt-3 d-flex align-items-center" style="width: fit-content;border: unset;column-gap: 10px;">তথ্য সংযোজন করুন  <img src="{{ asset('frontend/noipunno/images/icons/arrow-right.svg') }}" alt=""></button>
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
    (function($) {
        $(function() {
            $('input.np-time-picker').timepicker();
        });
    })(jQuery);
</script>

@endsection