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
    <div class="dashboard-section mb-5">
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
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <h2 class="np-form-title">প্রতিষ্ঠানের নতুন সেকশনে সংযুক্ত হওয়ার জন্য শিক্ষার্থীর তালিকা</h2>
                            </div>

                            <form method="get" action="{{ route('student.attached_institute_requested_student.list') }}" id="copyForm">
                                {{-- @csrf --}}
                                <div class="col-md-12">
                                    <div class="card np-card">
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table np-table">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                <input type="checkbox" id="checkAll">
                                                                <label for="checkAll">সবগুলো চেক করুন</label><br>
                                                            </th>
                                                            <th scope="col">শিক্ষার্থীর রোল</th>
                                                            <th scope="col">শিক্ষার্থীর নাম</th>
                                                            <th scope="col">পিতার নাম</th>
                                                            <th scope="col">বছর</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($students as $student)
                                                            <tr>
                                                                <td>
                                                                    <input type="checkbox" class="checkItem"
                                                                        data-student-uid={{ @$student->student_uid }}
                                                                        name="checkedStudents[]"
                                                                        value={{ @$student->student_uid }}>
                                                                </td>
                                                                <td scope="row">{{ @$student->roll }}</td>
                                                                <td scope="row"><span class="icon"><img
                                                                            src="{{ asset('frontend/noipunno/images/icons/user.svg') }}"
                                                                            alt=""></span>{{ @$student->studentInfo->student_name_en ?? @$student->studentInfo->student_name_bn }}
                                                                </td>
                                                                <td scope="row">
                                                                    {{ @$student->studentInfo->father_name_en ?? @$student->studentInfo->father_name_bn }}
                                                                </td>
                                                                <td scope="row">{{ @$student->session_year }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8"></div>
                                    <div class="col-md-4 col-sm-12">
                                        <div class="text-center ">
                                            <button type="submit"
                                                class="btn btn-primary np-btn-form-submit mt-3 bulk__import_btn">পরবর্তি
                                                ধাপ<img
                                                    src="{{ asset('frontend/noipunno/images/icons/arrow-right.svg') }}"
                                                    alt="logo"></button>
                                        </div>
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </form>
                        </div>
                    </section>

                    {{-- <div>
                        <div class="np-pagination-section d-flex justify-content-end align-items-center">
                            <div class="np-select-page-number d-flex align-items-center">
                                {{ $versionList->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#checkAll').click(function() {
            $(':checkbox.checkItem').prop('checked', this.checked);
        });
    </script>

@endsection
