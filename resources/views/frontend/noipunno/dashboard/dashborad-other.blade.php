@extends('frontend.layouts.noipunno')

@section('content')
    <div class="dashboard-section">
        <section class="np-breadcumb-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-12 mt-5">
                                <div class="card np-card" style="padding: 10px">
                                    <div class="header d-flex justify-content-between">
                                        <h2 class="title" style="font-size: 16px;"> প্রতিদিনের শিক্ষার্থীর হাজিরা</h2>
                                        <div class="filters d-flex align-items-center" style="column-gap: 10px">
                                            <div class="input-group d-flex align-items-center" style="column-gap: 10px">
                                                <label for="">TimeLine</label>
                                                <select class="form-select" aria-label="Default select example">
                                                    <option selected>Weekly</option>
                                                    <option value="1">One</option>
                                                    <option value="2">Two</option>
                                                    <option value="3">Three</option>
                                                </select>
                                            </div>

                                            <div class="input-group d-flex align-items-center" style="column-gap: 10px">
                                                <label for="">Filter by Class</label>
                                                <select class="form-select" aria-label="Default select example">
                                                    <option selected>All</option>
                                                    <option value="1">One</option>
                                                    <option value="2">Two</option>
                                                    <option value="3">Three</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="student-attendance"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-5">
                                <div class="card np-card" style="padding: 10px">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="title-contents">
                                                    <h2 class="title" style="font-size: 16px;"> শিক্ষকের উপস্থিতি</h2>
                                                    <h2 class="title" style="font-size: 12px;"> আজ মোট ৮৮.৫% শিক্ষক
                                                        উপস্থিতি</h2>
                                                </div>
                                                <div class="teacher-shift-card-list">
                                                    <div class="teacher-shift-card-section">
                                                        <h2 class="title" style="font-size: 14px">Morning</h2>
                                                        <div class="teacher-shift-card-section-card-list d-flex align-items-center"
                                                            style="flex-wrap: wrap;row-gap: 8px;column-gap: 8px;">
                                                            <div class="shift-teacher-card np-card d-flex align-items-center"
                                                                style="width: fit-content !important;column-gap: 8px;padding: 5px;">
                                                                <p style="margin: 0">98%</p>
                                                                <p style="margin: 0">Class 6</p>
                                                            </div>
                                                            <div class="shift-teacher-card np-card d-flex align-items-center"
                                                                style="width: fit-content !important;column-gap: 8px;padding: 5px;">
                                                                <p style="margin: 0">98%</p>
                                                                <p style="margin: 0">Class 6</p>
                                                            </div>
                                                            <div class="shift-teacher-card np-card d-flex align-items-center"
                                                                style="width: fit-content !important;column-gap: 8px;padding: 5px;">
                                                                <p style="margin: 0">98%</p>
                                                                <p style="margin: 0">Class 6</p>
                                                            </div>
                                                            <div class="shift-teacher-card np-card d-flex align-items-center"
                                                                style="width: fit-content !important;column-gap: 8px;padding: 5px;">
                                                                <p style="margin: 0">98%</p>
                                                                <p style="margin: 0">Class 6</p>
                                                            </div>
                                                            <div class="shift-teacher-card np-card d-flex align-items-center"
                                                                style="width: fit-content !important;column-gap: 8px;padding: 5px;">
                                                                <p style="margin: 0">98%</p>
                                                                <p style="margin: 0">Class 6</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="teacher-shift-card-section mt-5">
                                                        <h2 class="title" style="font-size: 14px">Day</h2>
                                                        <div class="teacher-shift-card-section-card-list d-flex align-items-center"
                                                            style="flex-wrap: wrap;row-gap: 8px;column-gap: 8px;">
                                                            <div class="shift-teacher-card np-card d-flex align-items-center"
                                                                style="width: fit-content !important;column-gap: 8px;padding: 5px;">
                                                                <p style="margin: 0">98%</p>
                                                                <p style="margin: 0">Class 6</p>
                                                            </div>
                                                            <div class="shift-teacher-card np-card d-flex align-items-center"
                                                                style="width: fit-content !important;column-gap: 8px;padding: 5px;">
                                                                <p style="margin: 0">98%</p>
                                                                <p style="margin: 0">Class 6</p>
                                                            </div>
                                                            <div class="shift-teacher-card np-card d-flex align-items-center"
                                                                style="width: fit-content !important;column-gap: 8px;padding: 5px;">
                                                                <p style="margin: 0">98%</p>
                                                                <p style="margin: 0">Class 6</p>
                                                            </div>
                                                            <div class="shift-teacher-card np-card d-flex align-items-center"
                                                                style="width: fit-content !important;column-gap: 8px;padding: 5px;">
                                                                <p style="margin: 0">98%</p>
                                                                <p style="margin: 0">Class 6</p>
                                                            </div>
                                                            <div class="shift-teacher-card np-card d-flex align-items-center"
                                                                style="width: fit-content !important;column-gap: 8px;padding: 5px;">
                                                                <p style="margin: 0">98%</p>
                                                                <p style="margin: 0">Class 6</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="teacher-shift-card-section mt-5">
                                                        <h2 class="title" style="font-size: 14px">Evening</h2>
                                                        <div class="teacher-shift-card-section-card-list d-flex align-items-center"
                                                            style="flex-wrap: wrap;row-gap: 8px;column-gap: 8px;">
                                                            <div class="shift-teacher-card np-card d-flex align-items-center"
                                                                style="width: fit-content !important;column-gap: 8px;padding: 5px;">
                                                                <p style="margin: 0">98%</p>
                                                                <p style="margin: 0">Class 6</p>
                                                            </div>
                                                            <div class="shift-teacher-card np-card d-flex align-items-center"
                                                                style="width: fit-content !important;column-gap: 8px;padding: 5px;">
                                                                <p style="margin: 0">98%</p>
                                                                <p style="margin: 0">Class 6</p>
                                                            </div>
                                                            <div class="shift-teacher-card np-card d-flex align-items-center"
                                                                style="width: fit-content !important;column-gap: 8px;padding: 5px;">
                                                                <p style="margin: 0">98%</p>
                                                                <p style="margin: 0">Class 6</p>
                                                            </div>
                                                            <div class="shift-teacher-card np-card d-flex align-items-center"
                                                                style="width: fit-content !important;column-gap: 8px;padding: 5px;">
                                                                <p style="margin: 0">98%</p>
                                                                <p style="margin: 0">Class 6</p>
                                                            </div>
                                                            <div class="shift-teacher-card np-card d-flex align-items-center"
                                                                style="width: fit-content !important;column-gap: 8px;padding: 5px;">
                                                                <p style="margin: 0">98%</p>
                                                                <p style="margin: 0">Class 6</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="header d-flex justify-content-between">
                                                    <div class="filters d-flex align-items-center"
                                                        style="column-gap: 10px">
                                                        <button class="btn btn-primary">শিক্ষকদের সব দেখুন </button>
                                                    </div>
                                                </div>

                                                <canvas id="teacher-attendance"></canvas>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="col-md-12 mt-5">
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
                    </div>
                </div>

                <div class="row" style="row-gap: 10px">
                    <div class="col-md-12 mt-5">
                        <h2 class="title" style="font-size: 16px">রিপোর্ট</h2>
                    </div>

                    <div class="col-md-2">
                        <div class="card np-card">
                            <div class="card-body">
                                <p style="margin: 0">Class Report</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="card np-card">
                            <div class="card-body">
                                <p style="margin: 0">Class Report</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="card np-card">
                            <div class="card-body">
                                <p style="margin: 0">Class Report</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="card np-card">
                            <div class="card-body">
                                <p style="margin: 0">Class Report</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="card np-card">
                            <div class="card-body">
                                <p style="margin: 0">Class Report</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="card np-card">
                            <div class="card-body">
                                <p style="margin: 0">Class Report</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card np-card">
                            <div class="card-body">
                                <p style="margin: 0">Class Report</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card np-card">
                            <div class="card-body">
                                <p style="margin: 0">Class Report</p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row mt-5" style="row-gap: 10px;">
                    <div class="col-md-12">
                        <h2 class="title" style="font-size: 16px">Status Class Room</h2>
                    </div>

                    <div class="col-md-3">
                        <div class="card np-card">
                            <div class="card-body" style="text-align: center">
                                <h2 style="margin: 0;font-size: 18px">Class 6</h2>
                                <p style="margin: 0;font-size: 14px">Total Student <strong>54</strong></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card np-card">
                            <div class="card-body" style="text-align: center">
                                <h2 style="margin: 0;font-size: 18px">Class 7</h2>
                                <p style="margin: 0;font-size: 14px">Total Student <strong>54</strong></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card np-card">
                            <div class="card-body" style="text-align: center">
                                <h2 style="margin: 0;font-size: 18px">Class 8</h2>
                                <p style="margin: 0;font-size: 14px">Total Student <strong>54</strong></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card np-card">
                            <div class="card-body" style="text-align: center">
                                <h2 style="margin: 0;font-size: 18px">Class 9</h2>
                                <p style="margin: 0;font-size: 14px">Total Student <strong>54</strong></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card np-card">
                            <div class="card-body" style="text-align: center">
                                <h2 style="margin: 0;font-size: 18px">Class 10</h2>
                                <p style="margin: 0;font-size: 14px">Total Student <strong>54</strong></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-5" style="row-gap: 10px">
                    <div class="col-md-12 mt-5">
                        <div class="card np-card" style="padding: 10px">
                            <div class="header d-flex justify-content-between">
                                <h2 class="title" style="font-size: 16px;"> PI Count Status</h2>

                            </div>
                            <div class="card-body">
                                <canvas id="pi-count-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- tab -->
        <div class="container mt-5">
            <h5 class="mb-3">ক্লাস রুটিন</h5>
            <ul class="nav nav-tabs np-student-tab-container" id="myTabs" role="tablist">
                <li class="nav-item np-student-tab" role="presentation">
                    <a class="nav-link active np-student-tab-link" id="tab1-tab" data-bs-toggle="tab" href="#tab1"
                        role="tab" aria-controls="tab1" aria-selected="true">
                        <img src="{{ asset('frontend/noipunno/images/icons/student-tab1.svg') }}" alt=""> Class 6
                    </a>
                </li>
                <li class="nav-item np-student-tab" role="presentation">
                    <a class="nav-link np-student-tab-link" id="tab2-tab" data-bs-toggle="tab" href="#tab2"
                        role="tab" aria-controls="tab2" aria-selected="false">
                        <img src="{{ asset('frontend/noipunno/images/icons/student-tab1.svg') }}"> Class 7
                    </a>
                </li>
            </ul>

            <div class="tab-content" id="myTabsContent">
                <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
                    <!-- Content for Tab 1 -->
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <section class="np-teacher-list">
                                <div class="container" style="padding: 0">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card np-card">
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table np-table">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col"></th>
                                                                    <th scope="col">8:00AM - 9:00AM</th>
                                                                    <th scope="col">9:00AM - 10:00AM</th>
                                                                    <th scope="col">10:00AM - 11:00AM</th>
                                                                    <th scope="col">11:00AM - 12:00PM</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td scope="row">Sunday</th>
                                                                    <td scope="row">Math</th>
                                                                    <td scope="row">Endlish</th>
                                                                    <td scope="row">Science</th>
                                                                    <td scope="row">Bangla</th>
                                                                </tr>
                                                                <tr>
                                                                    <td scope="row">Monday</th>
                                                                    <td scope="row">Math</th>
                                                                    <td scope="row">Endlish</th>
                                                                    <td scope="row">Science</th>
                                                                    <td scope="row">Bangla</th>
                                                                </tr>
                                                                <tr>
                                                                    <td scope="row">Tuesday</th>
                                                                    <td scope="row">Math</th>
                                                                    <td scope="row">Endlish</th>
                                                                    <td scope="row">Science</th>
                                                                    <td scope="row">Bangla</th>
                                                                </tr>
                                                                <tr>
                                                                    <td scope="row">Wednesday</th>
                                                                    <td scope="row">Math</th>
                                                                    <td scope="row">English</th>
                                                                    <td scope="row">Science</th>
                                                                    <td scope="row">Bangla</th>
                                                                </tr>
                                                                <tr>
                                                                    <td scope="row">Thrusday</th>
                                                                    <td scope="row">Math</th>
                                                                    <td scope="row">English</th>
                                                                    <td scope="row">Science</th>
                                                                    <td scope="row">Bangla</th>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
                    <!-- Content for Tab 2 -->
                    <div class="">
                        <div class="row">
                            <div class="col-md-12">
                                <section class="np-teacher-list">
                                    <div class="container" style="padding: 0">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card np-card">
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <table class="table np-table">
                                                                <thead>
                                                                    <tr>
                                                                        <th scope="col"></th>
                                                                        <th scope="col">8:00AM - 9:00AM</th>
                                                                        <th scope="col">9:00AM - 10:00AM</th>
                                                                        <th scope="col">10:00AM - 11:00AM</th>
                                                                        <th scope="col">11:00AM - 12:00PM</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td scope="row">Sunday</th>
                                                                        <td scope="row">Math</th>
                                                                        <td scope="row">Endlish</th>
                                                                        <td scope="row">Science</th>
                                                                        <td scope="row">Bangla</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <td scope="row">Monday</th>
                                                                        <td scope="row">Math</th>
                                                                        <td scope="row">Endlish</th>
                                                                        <td scope="row">Science</th>
                                                                        <td scope="row">Bangla</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <td scope="row">Tuesday</th>
                                                                        <td scope="row">Math</th>
                                                                        <td scope="row">Endlish</th>
                                                                        <td scope="row">Science</th>
                                                                        <td scope="row">Bangla</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <td scope="row">Wednesday</th>
                                                                        <td scope="row">Math</th>
                                                                        <td scope="row">Endlish</th>
                                                                        <td scope="row">Science</th>
                                                                        <td scope="row">Bangla</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <td scope="row">Thrusday</th>
                                                                        <td scope="row">Math</th>
                                                                        <td scope="row">Endlish</th>
                                                                        <td scope="row">Science</th>
                                                                        <td scope="row">Bangla</th>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end tab -->

    </div>
    <style>
        .np-table th,
        td {
            font-size: 11px;
        }
    </style>

    <script>
        const ctx = document.getElementById('student-attendance');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Saturday', 'Sunday', 'Monday', 'Tue', 'Thu'],
                datasets: [{
                    label: 'Student Attendance',
                    data: [12, 19, 3, 5, 10],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const teacherAttdnc = document.getElementById('teacher-attendance');

        new Chart(teacherAttdnc, {
            type: 'doughnut',
            data: {
                labels: ['Saturday', 'Sunday', 'Monday', 'Tue', 'Thu'],
                datasets: [{
                    label: 'Dataset 1',
                    data: [12, 19, 3, 5, 10]
                }]
            }
        });

        function setupPiCountChart() {
            var densityCanvas = document.getElementById("pi-count-chart");

            var piToCompleteData = {
                label: 'PI Need to Complete',
                data: [5427, 5243, 5514, 3933, 1326, 687, 1271, 1638],
                backgroundColor: 'rgba(94, 225, 233, 1)',
                borderColor: 'rgba(94, 225, 233, 1)',
                yAxisID: "y-axis-density"
            };

            var piDoneData = {
                label: 'PI Done',
                data: [3.7, 8.9, 9.8, 3.7, 23.1, 9.0, 8.7, 11.0],
                backgroundColor: 'rgba(149, 214, 119, 1)',
                borderColor: 'rgba(149, 214, 119, 1)',
                yAxisID: "y-axis-gravity"
            };

            var planetData = {
                labels: ["Class 6", "Class 7", "Class 8", "Class 9", "Class 10"],
                datasets: [piToCompleteData, piDoneData]
            };

            var chartOptions = {
                scales: {
                    xAxes: [{
                        barPercentage: 1,
                        categoryPercentage: 0.6
                    }],
                    yAxes: [{
                        id: "y-axis-density"
                    }, {
                        id: "y-axis-gravity"
                    }]
                }
            };

            var barChart = new Chart(densityCanvas, {
                type: 'bar',
                data: planetData,
                options: chartOptions
            });

        }

        setupPiCountChart();
    </script>
@endsection
