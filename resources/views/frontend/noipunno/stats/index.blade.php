@extends('frontend.layouts.noipunno-stat')
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
                                    <h2 class="title">পরিসংখ্যান ব্যবস্থাপনা </h2>

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
                                {{-- <h2 class="title mb-3">পরিসংখ্যান লিস্ট</h2> --}}
                            </div>

                            <div class="col-md-4">
                                <h5>Teacher Stats</h5>
                                <div class="table-responsive mt-2">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Total Teacher</th>
                                                <th scope="col">Today Teacher</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="text-align: center;">{{$total_teacher_count}}</td>
                                                <td style="text-align: center;">{{$today_teacher_count}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h5>Student Stats</h5>
                                <div class="table-responsive mt-2">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Total Student</th>
                                                <th scope="col">Today Student</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="text-align: center;">{{$total_student_count}}</td>
                                                <td style="text-align: center;">{{$today_student_count}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h5>Institute Stats</h5>
                                <div class="table-responsive mt-2">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Total Institute</th>
                                                <th scope="col">Today Institute</th>
                                                <th scope="col">School</th>
                                                <th scope="col">College</th>
                                                <th scope="col">School & College</th>
                                                <th scope="col">Madrasha</th>
                                                <th scope="col">Technical</th>
                                                <th scope="col">Primary</th>
                                                <th scope="col">Has EIIN</th>
                                                <th scope="col">No EIIN</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="text-align: center;">{{$total_institute_count}}</td>
                                                <td style="text-align: center;">{{$today_institute_count}}</td>
                                                <td style="text-align: center;">{{$school_institute_count}}</td>
                                                <td style="text-align: center;">{{$college_institute_count}}</td>
                                                <td style="text-align: center;">{{$school_college_institute_count}}</td>
                                                <td style="text-align: center;">{{$madrasah_institute_count}}</td>
                                                <td style="text-align: center;">{{$primary_institute_count}}</td>
                                                <td style="text-align: center;">{{$technical_institute_count}}</td>
                                                <td style="text-align: center;">{{$has_eiin_institute_count}}</td>
                                                <td style="text-align: center;">{{$no_eiin_institute_count}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5>PI Submission Stats</h5>
                                <div class="table-responsive mt-2">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Total PI Submission</th>
                                                <th scope="col">Last 24 Hour PI Submission</th>
                                                <th scope="col">Submission Per Hour</th>
                                                <th scope="col">Submission Per minute</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="text-align: center;">{{$total_pi_submission}}</td>
                                                <td style="text-align: center;">{{$today_pi_submission}}</td>
                                                <td style="text-align: center;">{{intval($today_pi_submission/24)}}</td>
                                                <td style="text-align: center;">{{intval($today_pi_submission/1440)}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5>BI Submission Stats</h5>
                                <div class="table-responsive mt-2">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Total BI Submission</th>
                                                <th scope="col">Last 24 Hour BI Submission</th>
                                                <th scope="col">Submission Per Hour</th>
                                                <th scope="col">Submission Per minute</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="text-align: center;">{{$total_bi_submission}}</td>
                                                <td style="text-align: center;">{{$today_bi_submission}}</td>
                                                <td style="text-align: center;">{{intval($today_bi_submission/24)}}</td>
                                                <td style="text-align: center;">{{intval($today_bi_submission/1440)}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
