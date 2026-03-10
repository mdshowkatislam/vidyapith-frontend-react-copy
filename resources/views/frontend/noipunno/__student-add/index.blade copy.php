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
                  <h2 class="title">শিক্ষার্থী ব্যবস্থাপনা</h2>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb np-breadcrumb">
                      <li class="breadcrumb-item"><a href="{{ route('home') }}">
                          <img src="{{ asset('frontend/noipunno/images/icons/home.svg') }}" alt="">
                          ড্যাশবোর্ড
                        </a></li>
                      <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('student.index') }}">
                          শিক্ষার্থী ব্যবস্থাপনা
                        </a></li>
                    </ol>
                  </nav>

                </div>
              </div>
              <!-- <div class="option-section">
                <div class="fav-icon">
                  <img src="{{ asset('frontend/noipunno/images/icons/fav-start-icon.svg') }}" alt="">
                </div>
                <div class="dots-icon">
                  <img src="{{ asset('frontend/noipunno/images/icons/3-dot-vertical.svg') }}" alt="">
                </div>
              </div> -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="np-teacher-list mt-5">
    <div class="container">
      @if (Session::has('success'))
      <div class="alert alert-success">
        {{ Session::get('success') }}
      </div>
      @endif

      @if (Session::has('import_errors'))

      @foreach (Session::get('import_errors') as $failure)
      <div class="alert alert-danger" role="alert">
        {{ $failure->errors()[0] }} at line no-{{ $failure->row() }}
      </div>
      @endforeach
      @endif

      <style>
        .progress, .progress-stacked{
          width: 100px;
          height: 100px;
          border-radius: 50%;
        }
        .progress_summery tr{
          display: flex;
          align-items: center;
          justify-content: center;
          flex-direction: column
        }
      </style>
      @if(session()->has('lastBatch'))
        <div class="row">
          <div class="col-md-8 mx-auto my-2">
            <div class="" id="ImportResult">
              <div class=" bg-white shadow-lg p-3 rounded-3">
                <h5 class=" text-center">একাধিক শিক্ষার্থী আপলোড করার তথ্য</h5>
                <hr>
                <div class="row">
                  <div class="col-md-6">
                    <table class=" table table-borderless progress_summery">
                      <tbody>
                        <tr>
                          <td style="" class="progress_bar">
                            <h4 class=" text-center"><span class="show_parcent"></span></h4>
                            <div class="progress mx-auto">
                              <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="col-md-6 d-flex align-content-center flex-column">
                    <div>
                      <table class=" table table-borderless">
                        <tbody>
                          <tr>
                            <th>মোট আপলোড ডাটা</th>
                            <td class="totalJob text-center" style="font-weight:bold">
                              {{session('total')}}
                            </td>
                          </tr>
                          <tr>
                            <th>মোট পেন্ডিং ডাটা</th>
                            <td class="pandingJob text-center" style="width: 50%;font-weight:bold">
                              {{session('total')}}
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="download_btn d-flex align-items-center justify-content-between">
                  <div>
                    <a href="{{route('student.export.data',session('lastBatch'))}}" class=" btn btn-info">ডাউনলোড ত্রুটিপুর্ণ ডাটা</a>
                    <small class="failedData"></small><br>
                    <small class="failedDataNote pt-3"></small>
                  </div>
                  <div>
                    <button class=" btn btn-warning confirm__download">বাতিল করুন</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endif
      <div class="row mb-3">
        <div class="col-md-7">
          <h2 class="title">শিক্ষার্থীর তালিকা </h2>
        </div>
        <div class="col-md-5 np-student-form-download-btn">
          <button class="np-btn np-btn-primary np-btn-with-icon np-student-form-download-btn">
            <a href="{{ route('student.download') }}" target="_blank" class="np-file-upload-demo-file-btn" download>একাধিক শিক্ষার্থী আপলোড করার নমুনা ডাউনলোড করুন</a>
            <img src="{{ asset('frontend/noipunno/images/icons/pdf-export-icon.svg') }}" alt="">
          </button>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="card np-card">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table np-table" id="std_dataTable">
                  <thead>
                    <tr>
                      <th scope="col">শিক্ষার্থীর রোল
                        <!-- <span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span> -->
                      </th>
                      <th scope="col">শিক্ষার্থীর নাম
                        <!-- <span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span> -->
                      </th>
                      <th scope="col">শ্রেণী
                        <!-- <span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span> -->
                      </th>
                      <th scope="col">সেকশন
                        <!-- <span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span> -->
                      </th>
                      <th scope="col">শিফট
                        <!-- <span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span> -->
                      </th>
                      <th scope="col">ভার্সন
                        <!-- <span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span> -->
                      </th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($students as $student)
                    <tr>
                      <td scope="row"><span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/user.svg') }}" alt=""></span>{{@$student->roll}}</td>
                      <td scope="row">{{ @$student->student_name_en ?? @$student->student_name_bn }}</td>
                      <td scope="row">
                        @if(@$student->class == 6)
                        Six
                        @elseif(@$student->class == 7)
                        Seven
                        @elseif(@$student->class == 8)
                        Eight
                        @elseif(@$student->class == 9)
                        Nine
                        @else
                        Ten
                        @endif
                        </td>
                      <td scope="row">{{@$student->section_details->section_name}} </td>
                      <td scope="row">{{@$student->shift_details->shift_name}}</td>
                      <td scope="row">{{@$student->version_details->version_name}}</td>
                      {{-- <td scope="row">
                        @if($student->date_of_birth)
                        {{ date('j F, Y', strtotime(@$student->date_of_birth)) }}</td>
                        @endif --}}
                      <td scope="row">
                        <div class="action-content">
                          <a href="{{ route('student.edit', $student->uid) }}" class="np-route">
                            <button class="btn np-edit-btn-small">
                              <img src="{{ asset('frontend/noipunno/images/icons/edit-white.svg') }}" alt="">
                            </button>
                          </a>
                          <a class="btn np-delete-btn-small" id="delete" title="Delete" data-id="{{ $student->uid }}" data-token={{ csrf_token() }} data-route="{{ route('student.delete') }}">
                            <i class="fa fa-trash np-delete-btn-small-icon"></i></a>
                          {{-- <img src="{{ asset('frontend/noipunno/images/icons/3-dots-horizontal.svg') }}" alt=""> --}}
                        </div>
                        </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        {{-- <div>
          <div class="np-pagination-section d-flex justify-content-end align-items-center">
            <div class="np-select-page-number d-flex align-items-center">
              {{ $students->links('pagination::bootstrap-5') }}
            </div>
          </div>
        </div> --}}
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
            </div>
          </div>
        </div> -->
      </div>
    </div>

  </section>



  <!-- tab -->
  <div class="container mt-5">
    <div class="np-teacher-list row mb-3">
      <div class="col-md-7">
        <h2 class="title">শিক্ষার্থী যুক্ত করুন </h2>
      </div>
    </div>
    <h5 class="mb-3">{{ @$institute->institute_name }}</h5>
    <ul class="nav nav-tabs np-student-tab-container" id="myTabs" role="tablist">
      <li class="nav-item np-student-tab" role="presentation">
        <a class="nav-link {{ session('active_tab') === 'tab1' ? 'active' : '' }} np-student-tab-link" id="tab1-tab" data-bs-toggle="tab" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true">
          <img src="{{ asset('frontend/noipunno/images/icons/student-tab1.svg') }}" alt=""> একজন ছাত্র যোগ করুন
        </a>
      </li>
      <li class="nav-item np-student-tab" role="presentation">
        <a class="nav-link {{ session('active_tab') === 'tab2' ? 'active' : '' }} np-student-tab-link" id="tab2-tab" data-bs-toggle="tab" href="#tab2" role="tab" aria-controls="tab2" aria-selected="false">
          <img src="{{ asset('frontend/noipunno/images/icons/student-tab1.svg') }}"> একাধিক শিক্ষার্থী যুক্ত করুন
        </a>
      </li>
    </ul>

    <div class="tab-content" id="myTabsContent">
      <div class="tab-pane fade {{ session('active_tab') === 'tab1' ? 'show active' : '' }}" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
        <!-- Content for Tab 1 -->

        <div class="row">
          <div class="col-md-12 col-sm-12">
            <!-- @if($errors->any())
            <div class="alert alert-danger">
              <ul>
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
              </ul>
            </div>
            @endif -->
            <!-- student add -->
            <section class="np-teacher-add-form">
              <div class="np-input-form-bg">
                <div class="container">
                  <form action="{{ route('student.store') }}" id="importStudent" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                      <div class="col-md-4 col-sm-12">
                        <div>
                          <label for="branch1" class="form-label">ব্রাঞ্চ</label>
                          <div class="input-group">
                            <select class="form-select np-teacher-input" aria-label="Default select example" id="branch1" name="branch">
                              <option value="">Select Branch</option>
                              @foreach ($branchs as $branch)
                              <option value="{{ @$branch->uid }}">{{ @$branch->branch_name }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-4 col-sm-12">
                        <div>
                          <label for="shift1" class="form-label">শিফট</label>
                          <div class="input-group">
                            <select class="form-select np-teacher-input" aria-label="Default select example" id="shift1" name="shift">
                              <option value="">Select Shift</option>
                              @foreach ($shifts as $shift)
                              <option value="{{ @$shift->uid }}">{{ @$shift->shift_name }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-4 col-sm-12">
                        <div>
                          <label for="version1" class="form-label">ভার্সন</label>
                          <div class="input-group">
                            <select class="form-select np-teacher-input" aria-label="Default select example" id="version1" name="version">
                              <option value="">Select Version</option>
                              @foreach ($versions as $version)
                              <option value="{{ @$version->uid }}">{{ @$version->version_name }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-4 col-sm-12 mt-3">
                        <div>
                          <label for="class1" class="form-label">ক্লাস <span class="error">*</span> </label>
                          <div class="input-group">
                            <select class="form-select np-teacher-input" aria-label="Default select example" id="class1" name="class">
                              <option value="">Select Class</option>
                              @foreach ($classList as $class)
                              <option value="{{ $class['class_id'] }}">{{ $class['name_en'] }}</option>
                              @endforeach
                            </select>
                          </div>
                          @if ($errors->has('class'))
                          <small class="help-block form-text text-danger">{{ $errors->first('class') }}</small>
                          @endif
                        </div>
                      </div>

                      <div class="col-md-4 col-sm-12 mt-3">
                        <div>
                          <label for="section1" class="form-label">সেকশন </label>
                          <div class="input-group">
                            <select class="form-select np-teacher-input" aria-label="Default select example" id="section1" name="section">
                              <option value="">Select Section</option>
                              @foreach ($sections as $section)
                              <option value="{{ @$section->uid }}">{{ @$section->section_name }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-4 col-sm-12 mt-3">
                        <div>
                          <label for="registration_year1" class="form-label">সন <span class="error">*</span> </label>
                          <div class="input-group">
                            <input type="number" class="form-control np-teacher-input" id="registration_year1" name="registration_year" value="<?php echo date('Y'); ?>">
                            <!-- <select class="form-select np-teacher-input" aria-label="Default select example" id="registration_year1" name="registration_year">
                              <option value="">Select Year</option>
                              <option value="2022" @if(old("registration_year")==2022) selected @endif>2022</option>
                              <option value="2023" @if(old("registration_year")==2023) selected @endif>2023</option>
                              <option value="2024" @if(old("registration_year")==2024) selected @endif>2024</option>
                            </select> -->
                          </div>
                          @if ($errors->has('registration_year'))
                          <small class="help-block form-text text-danger">{{ $errors->first('registration_year') }}</small>
                          @endif
                        </div>
                      </div>

                    </div>
                    <div class="row mt-3">
                      <div class="col-md-3 col-sm-12">
                        <div class="mb-3">
                          <label for="loginId" class="form-label">রোল নাম্বার <span class="error">*</span></label>
                          <input type="number" class="form-control np-teacher-input" id="loginId" name="roll" value="{{old('roll')}}">
                          @if ($errors->has('roll'))
                          <small class="help-block form-text text-danger">{{ $errors->first('roll') }}</small>
                          @endif
                        </div>
                      </div>
                      <div class="col-md-3 col-sm-12">
                        <div class="mb-3">
                          <label for="studentName" class="form-label">শিক্ষার্থীর নাম (বাংলা) </label>
                          <input type="text" class="form-control np-teacher-input" id="studentName" name="student_name_bn" value="{{old('student_name_bn')}}">
                          @if ($errors->has('student_name_bn'))
                          <small class="help-block form-text text-danger">{{ $errors->first('student_name_bn') }}</small>
                          @endif
                        </div>
                      </div>
                      <div class="col-md-3 col-sm-12">
                        <div class="mb-3">
                          <label for="studentNametEnglish" class="form-label">শিক্ষার্থীর নাম (English) <span class="error">*</span> </label>
                          <input type="text" class="form-control np-teacher-input" id="studentNametEnglish" name="student_name_en" value="{{old('student_name_en')}}">
                          @if ($errors->has('student_name_en'))
                          <small class="help-block form-text text-danger">{{ $errors->first('student_name_en') }}</small>
                          @endif
                        </div>
                      </div>
                      <div class="col-md-3 col-sm-12">
                        <div class="mb-3">
                          <label for="BRID" class="form-label">জন্ম নিবন্ধন নং</label>
                          <input type="text" class="form-control np-teacher-input" id="BRID" name="brid" placeholder="20XXXXXXXXXXXXXXX" value="{{old('brid')}}">
                          @if ($errors->has('brid'))
                          <small class="help-block form-text text-danger">{{ $errors->first('brid') }}</small>
                          @endif
                        </div>
                      </div>

                    </div>
                    <div class="row">
                      <div class="col-md-3 col-sm-12">
                        <label for="birthday" class="form-label">জন্ম তারিখ</label>
                        <input type="date" class="form-control np-teacher-input" id="birthday" name="date_of_birth" max="<?php echo date('Y-m-d'); ?>" value="{{old('date_of_birth')}}">
                        @if ($errors->has('date_of_birth'))
                        <small class="help-block form-text text-danger">{{ $errors->first('date_of_birth') }}</small>
                        @endif
                      </div>
                      <div class="col-md-3 col-sm-12">
                        <div class="mb-3">
                          <label for="gender" class="form-label">লিঙ্গ</label>
                          <div class="input-group">
                            <select class="form-select np-teacher-input" aria-label="Default select example" id="gender" name="gender">
                              <option value=""> নির্বাচন করুন</option>
                              <option value="Male" @if(old("gender")=='Male' ) selected @endif>বালক</option>
                              <option value="Female" @if(old("gender")=='Female' ) selected @endif>বালিকা</option>
                              <option value="Other" @if(old("gender")=='Other' ) selected @endif> অন্যান্য</option>
                            </select>
                            @if ($errors->has('gender'))
                            <small class="help-block form-text text-danger">{{ $errors->first('gender') }}</small>
                            @endif
                          </div>
                        </div>
                      </div>

                      <div class="col-md-3 col-sm-12">
                        <div class="mb-3">
                          <label for="dhormo" class="form-label">ধর্ম </label>
                          <!-- <input type="text" class="form-control np-teacher-input" id="dhormo" name="religion" value="{{old('religion')}}"> -->
                          <select class="form-select np-teacher-input" aria-label="Default select example" id="dhormo" name="religion">
                            <option value="">নির্বাচন করুন</option>
                            <option value="Islam" @if(old("religion")=='Islam' ) selected @endif>ইসলাম</option>
                            <option value="Hinduism" @if(old("religion")=='Hinduism' ) selected @endif> হিন্দু</option>
                            <option value=" Christianity" @if(old("religion")=='Christianity' ) selected @endif> খ্রিষ্টান</option>
                            <option value="Buddhism" @if(old("religion")=='Buddhism' ) selected @endif> বৌদ্ধ</option>
                            <option value="Other" @if(old("religion")=='Other' ) selected @endif> অন্যান্য</option>
                          </select>
                          @if ($errors->has('religion'))
                          <small class="help-block form-text text-danger">{{ $errors->first('religion') }}</small>
                          @endif
                        </div>
                      </div>
                      <div class="col-md-3 col-sm-12">
                        <div class="mb-3">
                          <label for="phone" class="form-label">মোবাইল নাম্বার </label>
                          <input type="number" class="form-control np-teacher-input num" maxlength="11" id="phone" name="student_mobile_no" value="{{old('student_mobile_no')}}" placeholder="01xxxxxxxxx">
                          @if ($errors->has('student_mobile_no'))
                          <small class="help-block form-text text-danger">{{ $errors->first('student_mobile_no') }}</small>
                          @endif
                        </div>
                      </div>

                    </div>
                    <div class="row">
                      <div class="col-md-3 col-sm-12">
                        <div class="mb-3">
                          <label for="motherName" class="form-label">মাতার নাম (বাংলা)</label>
                          <input type="text" class="form-control np-teacher-input" id="motherName" name="mother_name_bn" value="{{old('mother_name_bn')}}">
                          @if ($errors->has('mother_name_bn'))
                          <small class="help-block form-text text-danger">{{ $errors->first('mother_name_bn') }}</small>
                          @endif
                        </div>
                      </div>
                      <div class="col-md-3 col-sm-12">
                        <div class="mb-3">
                          <label for="motherNameEnglish" class="form-label">মাতার নাম (English)</label>
                          <input type="text" class="form-control np-teacher-input" id="motherNameEnglish" name="mother_name_en" value="{{old('mother_name_en')}}">
                          @if ($errors->has('mother_name_en'))
                          <small class="help-block form-text text-danger">{{ $errors->first('mother_name_en') }}</small>
                          @endif
                        </div>
                      </div>
                      <div class="col-md-3 col-sm-12">
                        <div class="mb-3">
                          <label for="fatherName" class="form-label">পিতার নাম (বাংলা) <span class="error">*</span></label>
                          <input type="text" class="form-control np-teacher-input" id="fatherName" name="father_name_bn" value="{{old('father_name_bn')}}">
                          @if ($errors->has('father_name_bn'))
                          <small class="help-block form-text text-danger">{{ $errors->first('father_name_bn') }}</small>
                          @endif
                        </div>
                      </div>
                      <div class="col-md-3 col-sm-12">
                        <div class="mb-3">
                          <label for="fatherNameEnglish" class="form-label">পিতার নাম (English )</label>
                          <input type="text" class="form-control np-teacher-input" id="fatherNameEnglish" name="father_name_en" value="{{old('father_name_en')}}">
                          @if ($errors->has('father_name_en'))
                          <small class="help-block form-text text-danger">{{ $errors->first('father_name_en') }}</small>
                          @endif
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-3 col-sm-12">
                        <div class="mb-3">
                          <label for="farhersPhoneNumber" class="form-label">পিতার মোবাইল নাম্বার </label>
                          <input type="number" class="form-control np-teacher-input num" maxlength="11" id="farhersPhoneNumber" name="father_mobile_no" value="{{old('father_mobile_no')}}" placeholder="01xxxxxxxxx">
                          @if ($errors->has('father_mobile_no'))
                          <small class="help-block form-text text-danger">{{ $errors->first('father_mobile_no') }}</small>
                          @endif
                        </div>
                      </div>
                      <div class="col-md-3 col-sm-12">
                        <div class="mb-3">
                          <label for="mothersPhoneNumber" class="form-label">মাতার মোবাইল নাম্বার</label>
                          <input type="number" class="form-control np-teacher-input num" maxlength="11" id="mothersPhoneNumber" name="mother_mobile_no" value="{{old('mother_mobile_no')}}" placeholder="01xxxxxxxxx">
                          @if ($errors->has('mother_mobile_no'))
                          <small class="help-block form-text text-danger">{{ $errors->first('mother_mobile_no') }}</small>
                          @endif
                        </div>
                      </div>
                      <div class="col-md-3 col-sm-12">
                        <div class="mb-3">
                          <label for="guardian_name_bn" class="form-label">অভিভাবকের নাম <small>(যদি থাকে)</small></label>
                          <input type="text" class="form-control np-teacher-input" id="guardian_name_bn" name="guardian_name_bn" value="{{old('guardian_name_bn')}}">
                          @if ($errors->has('guardian_name_bn'))
                          <small class="help-block form-text text-danger">{{ $errors->first('guardian_name_bn') }}</small>
                          @endif
                        </div>
                      </div>
                      <div class="col-md-3 col-sm-12">
                        <div class="mb-3">
                          <label for="guardian_mobile_no" class="form-label">অভিভাবকের মোবাইল নাম্বার </label>
                          <input type="number" class="form-control np-teacher-input num" maxlength="11" id="guardian_mobile_no" name="guardian_mobile_no" value="{{old('guardian_mobile_no')}}" placeholder="01xxxxxxxxx">
                          @if ($errors->has('guardian_mobile_no'))
                          <small class="help-block form-text text-danger">{{ $errors->first('guardian_mobile_no') }}</small>
                          @endif
                        </div>
                      </div>

                      <!-- <div class="col-md-3 col-sm-12">
                        <div class="mb-3">
                          <label for="teacherImage" class="form-label">শিক্ষার্থীর ছবি আপলোড করুন </label>
                          <input type="file" class="form-control np-teacher-input" id="teacherImage">
                        </div>
                      </div> -->
                    </div>
                    <div class="row">
                      <div class="col-md-8"></div>
                      <div class="col-md-4 col-sm-12">
                        <button type="submit" class="btn btn-primary np-btn-form-submit mt-3">তথ্য সংযোজন করুন <img src="{{ asset('frontend/noipunno/images/icons/arrow-right.svg') }}" alt="logo"></button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </section>
          </div>
        </div>
      </div>
      <div class="tab-pane fade {{ session('active_tab') === 'tab2' ? 'show active' : '' }}" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
        <!-- Content for Tab 2 -->
        <div class="">
          <div class="row">
            <div class="col-md-12">
              <!-- @if($errors->any())
              <div class="alert alert-danger">
                <ul>
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
              @endif -->
              <section class="np-teacher-add-form">
                <div class="np-input-form-bg">
                  <form action="{{ route('student.import') }}" method="POST" enctype="multipart/form-data">
                    <div class="row">
                      <div class="col-md-4 col-sm-12">
                        <div>
                          <label for="branch2" class="form-label">ব্রাঞ্চ</label>
                          <div class="input-group">
                            <select class="form-select np-teacher-input" aria-label="Default select example" id="branch2" name="branch">
                              <option value="">Select Branch</option>
                              @foreach ($branchs as $branch)
                              <option value="{{ @$branch->uid }}">{{ @$branch->branch_name }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-4 col-sm-12">
                        <div>
                          <label for="shift2" class="form-label">শিফট</label>
                          <div class="input-group">
                            <select class="form-select np-teacher-input" aria-label="Default select example" id="shift2" name="shift">
                              <option value="">Select Shift</option>
                              @foreach ($shifts as $shift)
                              <option value="{{ @$shift->uid }}">{{ @$shift->shift_name }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-4 col-sm-12">
                        <div>
                          <label for="version2" class="form-label">ভার্সন</label>
                          <div class="input-group">
                            <select class="form-select np-teacher-input" aria-label="Default select example" id="version2" name="version">
                              <option value="">Select Version</option>
                              @foreach ($versions as $version)
                              <option value="{{ @$version->uid }}">{{ @$version->version_name }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-4 col-sm-12 mt-3">
                        <div>
                          <label for="class2" class="form-label">ক্লাস <span class="error">*</span> </label>
                          <div class="input-group">
                            <select class="form-select np-teacher-input" aria-label="Default select example" id="class2" name="class">
                              <option value="">Select Class</option>
                              @foreach ($classList as $class)
                              <option value="{{ $class['class_id'] }}" {{ old('class') == $class['class_id'] ? 'selected' : '' }}>{{ $class['name_en'] }}</option>
                              @endforeach
                            </select>
                          </div>
                          @if ($errors->has('class'))
                          <small class="help-block form-text text-danger">{{ $errors->first('class') }}</small>
                          @endif
                        </div>
                      </div>

                      <div class="col-md-4 col-sm-12 mt-3">
                        <div>
                          <label for="section2" class="form-label">সেকশন</label>
                          <div class="input-group">
                            <select class="form-select np-teacher-input" aria-label="Default select example" id="section2" name="section">
                              <option value="">Select Section</option>
                              @foreach ($sections as $section)
                              <option value="{{ @$section->uid }}">{{ @$section->section_name }}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-4 col-sm-12 mt-3">
                        <div>
                          <label for="registration_year2" class="form-label">সন <span class="error">*</span> </label>
                          <div class="input-group">
                            <input type="number" class="form-control np-teacher-input" id="registration_year2" name="registration_year" value="<?php echo date('Y'); ?>">
                            <!-- <select class="form-select np-teacher-input" aria-label="Default select example" id="registration_year2" name="registration_year">
                              <option value=""> নির্বাচন করুন</option>
                              <option value="2022" @if(old("registration_year")==2022) selected @endif>2022</option>
                              <option value="2023" @if(old("registration_year")==2023) selected @endif>2023</option>
                              <option value="2024" @if(old("registration_year")==2024) selected @endif>2024</option>
                            </select> -->
                          </div>
                          @if ($errors->has('registration_year'))
                          <small class="help-block form-text text-danger">{{ $errors->first('registration_year') }}</small>
                          @endif
                        </div>
                      </div>

                    </div>
                    <div class="row mt-3">
                      <div class="col-md-12">
                        @csrf
                        <input id="file-upload" class="np-file-upload-hidden" type="file" name="file" />
                        <label for="file-upload" id="file-drag">
                          <img src="{{ asset('frontend/noipunno/images/icons/draganddrop.svg') }}" alt=""><br />
                          Drag and Drop or <span id="file-upload-btn">Browse</span>
                          <br /> <small>Supports: Excel, CSV</small>
                          <progress id="file-progress" value="0">
                            <span>0</span>%
                          </progress>
                          <output for="file-upload" id="messages"></output>
                        </label>
                      </div>
                    </div>
                    <div class="row np-file-upload-demo-file-btn-container">
                      {{-- <div class="col-md-2"></div> --}}
                      <div class="col-md-12">
                        <p class="text-center text-danger">বিঃদ্রঃ ফাইল আপলোড করতে অবশ্যই নমুনা ফাইলটি ডাউনলোড করে ডাটা এন্ট্রি করুন। ডাউনলোড করতে <a href="{{ route('student.download') }}" target="_blank" class="np-file-upload-demo-file-btn" download>এখানে</a> ক্লিক করুন।
                        </p>
                        {{-- <p class="text-center">ফাইল আপলোড  করতে সমস্যা হচ্ছে ?</p> --}}
                      </div>
                      {{-- <div class="col-md-12 text-center">
                        <a href="{{ route('student.download') }}" target="_blank" class="np-file-upload-demo-file-btn" download>একাধিক শিক্ষার্থী আপলোড করার নমুনা ডাউনলোড করুন</a>
                      </div> --}}
                      {{-- <div class="col-md-2"></div> --}}
                    </div>
                    <div class="row">
                      <div class="col-md-4"></div>
                      <div class="col-md-4 col-sm-12">
                        <div class="text-center ">
                          <button type="submit" class="btn btn-primary np-btn-form-submit mt-3 bulk__import_btn">তথ্য সংযোজন করুন<img src="{{ asset('frontend/noipunno/images/icons/arrow-right.svg') }}" alt="logo"></button>
                        </div>
                      </div>
                      <div class="col-md-4"></div>
                    </div>
                  </form>
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

  span.error {
    color: red;
  }
</style>

{{-- <script>
  $('#importStudent').submit(function() {
      swal({
        icon: "warning",
          title: "বর্তমানে একাধিক শিক্ষার্থী যুক্ত করার সুবিধাটি সাময়িক বন্ধ রয়েছে। ",
          confirmButtonText: "ধন্যবাদ",
          type: "warning"
      });
      return false;
  });
</script> --}}

<script>
  function fetchDataForBranch(id) {
    $.ajax({
      url: '{{ route("student.getBranchData") }}',
      type: 'GET',
      data: {
        id: id,
        '_token': $('input[name="_token"]').val(),
      },
      success: function(data) {
        $('#version1').empty();
        $.each(data.versions, function(key, value) {
          $('#version1').append('<option value="' + value.uid + '">' + value.version_name + '</option>');
        });
        $('#shift1').empty();
        $.each(data.shifts, function(key, value) {
          $('#shift1').append('<option value="' + value.uid + '">' + value.shift_name + '</option>');
        });
      }
    }, "json");
  }

  function fetchDataForSection(id) {
    var branch_id = document.getElementById('branch1').value,
      version_id = document.getElementById('version1').value,
      shift_id = document.getElementById('shift1').value,
      class_id = id;
    $.ajax({
      url: '{{ route("student.getSectionData") }}',
      type: 'GET',
      data: {
        branch_id,
        version_id,
        shift_id,
        class_id,
        '_token': $('input[name="_token"]').val(),
      },
      success: function(data) {
        if(data.length >0) {
          $('#section1').empty();
          $('#registration_year1').empty();
        }
        $.each(data, function(key, value) {
          $('#section1').append('<option value="' + value.uid + '">' + value.section_name + '</option>');
          $('#registration_year1').val(value.section_year)
        });
      }
    }, "json");
  }

  function fetchDataForBranch2(id) {
    $.ajax({
      url: '{{ route("student.getBranchData") }}',
      type: 'GET',
      data: {
        id: id,
        '_token': $('input[name="_token"]').val(),
      },
      success: function(data) {
        $('#version2').empty();
        $.each(data.versions, function(key, value) {
          $('#version2').append('<option value="' + value.uid + '">' + value.version_name + '</option>');
        });
        $('#shift2').empty();
        $.each(data.shifts, function(key, value) {
          $('#shift2').append('<option value="' + value.uid + '">' + value.shift_name + '</option>');
        });
      }
    }, "json");
  }

  function fetchDataForSection2(id) {
    var branch_id = document.getElementById('branch2').value,
      version_id = document.getElementById('version2').value,
      shift_id = document.getElementById('shift2').value,
      class_id = id;
    $.ajax({
      url: '{{ route("student.getSectionData") }}',
      type: 'GET',
      data: {
        branch_id,
        version_id,
        shift_id,
        class_id,
        '_token': $('input[name="_token"]').val(),
      },
      success: function(data) {
        $('#section2').empty();
        $('#registration_year2').empty();
        $.each(data, function(key, value) {
          $('#section2').append('<option value="' + value.uid + '">' + value.section_name + '</option>');
          $('#registration_year2').val(value.section_year)
        });
      }
    }, "json");
  }

  function addEventListeners() {
    $('#branch1').on('change', (event) => {
      fetchDataForBranch(event.target.value);
    });

    $('#class1').on('change', (event) => {
      fetchDataForSection(event.target.value);
    });

    $('#branch2').on('change', (event) => {
      fetchDataForBranch2(event.target.value);
    });

    $('#class2').on('change', (event) => {
      fetchDataForSection2(event.target.value);
    });
  }
  addEventListeners();

  (function() {
    function Init() {
      var fileSelect = document.getElementById('file-upload'),
        fileDrag = document.getElementById('file-drag'),
        submitButton = document.getElementById('submit-button');

      fileSelect.addEventListener('change', fileSelectHandler, false);

      // Is XHR2 available?
      var xhr = new XMLHttpRequest();
      if (xhr.upload) {
        // File Drop
        fileDrag.addEventListener('dragover', fileDragHover, false);
        fileDrag.addEventListener('dragleave', fileDragHover, false);
        fileDrag.addEventListener('drop', fileSelectHandler, false);
      }
    }

    function fileDragHover(e) {
      var fileDrag = document.getElementById('file-drag');

      e.stopPropagation();
      e.preventDefault();

      fileDrag.className = (e.type === 'dragover' ? 'hover' : 'modal-body file-upload');
    }

    function fileSelectHandler(e) {
      // Fetch FileList object
      var files = e.target.files || e.dataTransfer.files;

      // Cancel event and hover styling
      fileDragHover(e);

      // Process all File objects
      for (var i = 0, f; f = files[i]; i++) {
        parseFile(f);
        uploadFile(f);
      }
    }

    function output(msg) {
      var m = document.getElementById('messages');
      m.innerHTML = msg;
    }

    function parseFile(file) {
      output(
        '<ul>' +
        '<li>Name: <strong>' + encodeURI(file.name) + '</strong></li>' +
        '<li>Type: <strong>' + file.type + '</strong></li>' +
        '<li>Size: <strong>' + (file.size / (1024 * 1024)).toFixed(2) + ' MB</strong></li>' +
        '</ul>'
      );
    }

    function setProgressMaxValue(e) {
      var pBar = document.getElementById('file-progress');

      if (e.lengthComputable) {
        pBar.max = e.total;
      }
    }

    function updateFileProgress(e) {
      var pBar = document.getElementById('file-progress');

      if (e.lengthComputable) {
        pBar.value = e.loaded;
      }
    }

    function uploadFile(file) {

      var xhr = new XMLHttpRequest(),
        fileInput = document.getElementById('class-roster-file'),
        pBar = document.getElementById('file-progress'),
        fileSizeLimit = 1024; // In MB
      if (xhr.upload) {
      console.log(file.size);
        // Check if file is less than x MB
        if (file.size <= fileSizeLimit * 1024 * 1024) {
          // Progress bar
          pBar.style.display = 'inline';
          xhr.upload.addEventListener('loadstart', setProgressMaxValue, false);
          xhr.upload.addEventListener('progress', updateFileProgress, false);

          // File received / failed
          xhr.onreadystatechange = function(e) {
            if (xhr.readyState == 4) {
              // Everything is good!

              // progress.className = (xhr.status == 200 ? "success" : "failure");
              // document.location.reload(true);
            }
          };

          // Start upload
          xhr.open('POST', document.getElementById('file-upload-form').action, true);
          xhr.setRequestHeader('X-File-Name', file.name);
          xhr.setRequestHeader('X-File-Size', file.size);
          xhr.setRequestHeader('Content-Type', 'multipart/form-data');
          xhr.send(file);
        } else {
          output('Please upload a smaller file (< ' + fileSizeLimit + ' MB).');
        }
      }
    }

    // Check for the various File API support.
    if (window.File && window.FileList && window.FileReader) {
      Init();
    } else {
      document.getElementById('file-drag').style.display = 'none';
    }
  })();

  $(document).ready(function() {
    $(".num").keypress(function() {
      if ($(this).val().length == $(this).attr("maxlength")) {
        return false;
      }
    });
  });

  $(function(){
    $('#std_dataTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            // "ordering": true,
            "order": [[ 3, "desc"]],
            "info": true,
            "autoWidth": true,

        });

        $('.select2').select2();
    });
</script>

@endsection

@section('custom-js')
<script type="text/javascript">
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
  </script>
  @if(session()->has('lastBatch'))
  <script>
    $(document).ready(function () {
      $('#ImportResult').show();
      $(".download_btn").hide();
      $('.bulk__import_btn').prop('disabled', true);
      var batchId = "{!! session('lastBatch') !!}";
      setInterval(() => {
        $.ajax({
        type: "post",
        url: "{{route('student.import.result')}}",
        data: {
          'batchId' : batchId,
        },
        dataType: "json",
        success: function (response) {
          if (response.progress == 100) {
              if(response.show_dnldBtn){
                $(".download_btn").show();
                $('.failedData').text('মোট ত্রুটিপুর্ণ ডাটাঃ '+response.failedData)
                $('.failedDataNote').text('বিঃদ্রঃ ত্রুটিপুর্ণ ডাটা ফাইলটি ডাউনলোড করুন এবং সংশোধন করে পুনরায় আপলোড করুন।')
              }else{
                $('#ImportResult').hide();
                // Make an AJAX request to remove the session variable
                $.ajax({
                    url: '/remove-session-variable', // Replace with the actual route or URL
                    method: 'POST',
                    data: { variableName: 'lastBatch' },
                    success: function(response) {
                        console.log('Session variable removed successfully');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error removing session variable:', error);
                    }
                });
                $('.bulk__import_btn').prop('disabled', false);
              }
          }
          $('.progress_bar .progress-bar').css('width', response.progress+'%');
          $('.show_parcent').text(response.progress+'%');
          $('.progress_bar .progress-bar').attr('aria-valuenow', response.progress);
          $('.totalJob').text( response.totalJobs);
          $('.pandingJob').text( response.pendingJobs);
        }
      });
      }, 1000);
    });
  </script>

  <script>
    $(document).ready(function () {
      $('.confirm__download').click(function (e) {
        e.preventDefault();
        if(confirm('আপনি কি ত্রুটিপুর্ণ ডাটা ফাইলটি ডাউনলোড করেছেন?')){
          $('#ImportResult').hide();
          // Make an AJAX request to remove the session variable
          $.ajax({
              url: '/remove-session-variable', // Replace with the actual route or URL
              method: 'POST',
              data: { variableName: 'lastBatch' },
              success: function(response) {
                  console.log('Session variable removed successfully');
              },
              error: function(xhr, status, error) {
                  console.error('Error removing session variable:', error);
              }
          });
          $('.bulk__import_btn').prop('disabled', false);
          location.reload()
        }
      })
    });
  </script>
  @endif
@endsection
