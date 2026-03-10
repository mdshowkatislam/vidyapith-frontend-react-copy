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
                  <h2 class="title">শিক্ষার্থীর তথ্য পরিবর্তন করুন</h2>
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
      <div class="row mb-3">
        <div class="col-md-7">
          <h2 class="title">শিক্ষার্থীর তালিকা </h2>
        </div>
        <div class="col-md-5 np-student-form-download-btn">
          <!-- <button class="np-btn np-btn-primary np-btn-with-icon np-student-form-download-btn">একাধিক শিক্ষার্থী আপলোড করার নমুনা ডাউনলোড করুন
            <img src="{{ asset('frontend/noipunno/images/icons/pdf-export-icon.svg') }}" alt="">

          </button> -->
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="card np-card">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table np-table">
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
                      <th scope="col">মাতার নাম 
                        <!-- <span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span> -->
                      </th>
                      <th scope="col">পিতার নাম 
                        <!-- <span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span> -->
                      </th>
                      <th scope="col">জন্ম নিবন্ধন নং 
                        <!-- <span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span> -->
                      </th>
                      <th scope="col">জন্ম তারিখ 
                        <!-- <span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/filter.svg') }}" alt=""></span> -->
                      </th>
                      <th scope="col">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($allStudents as $allstudent)
                    <tr>
                      <td scope="row"><span class="icon"><img src="{{ asset('frontend/noipunno/images/icons/user.svg') }}" alt=""></span>{{@$allstudent->roll}}</th>
                      <td scope="row">{{ @$allstudent->student_name_en ?? @$allstudent->student_name_bn }}</th>
                      <td scope="row">
                        @if(@$allstudent->class == 6)
                        Class Six
                        @elseif(@$allstudent->class == 7)
                        Class Seven
                        @elseif(@$allstudent->class == 8)
                        Class Eight
                        @elseif(@$allstudent->class == 9)
                        ClassNine
                        @elseif(@$allstudent->class == 10)
                        Class Ten
                        @endif
                        </th>
                      <td scope="row">{{@$allstudent->mother_name_bn}} </th>
                      <td scope="row">{{@$allstudent->father_name_bn}}</th>
                      <td scope="row">{{@$allstudent->brid}}</th>
                      <td scope="row">
                        @if($allstudent->date_of_birth)
                        {{ date('j F, Y', strtotime(@$allstudent->date_of_birth)) }}</th>
                        @endif
                        </th>

                      <td scope="row">
                        <div class="action-content">
                          <a href="{{ route('student.edit',$allstudent->uid) }}" class="np-route">
                            <button class="btn np-edit-btn-small">
                              <img src="{{ asset('frontend/noipunno/images/icons/edit-white.svg') }}" alt="">
                            </button>
                          </a>
                          {{-- <img src="{{ asset('frontend/noipunno/images/icons/3-dots-horizontal.svg') }}" alt=""> --}}
                        </div>
                        </th>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-12">
          <div class="np-pagination-section d-flex justify-content-end align-items-center">
            <div class="np-select-page-number d-flex align-items-center">
              {{ $allStudents->links( "pagination::bootstrap-5") }}
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

<!-- tab -->
<div class="container mt-5">
  <div class="np-teacher-list row mb-3">
    <div class="col-md-7">
      <h2 class="title">শিক্ষার্থীর তথ্য পরিবর্তন </h2>
    </div>
  </div>
  <h5 class="mb-3">{{ @$institute->institute_name }}</h5>
  <ul class="nav nav-tabs np-student-tab-container" id="myTabs" role="tablist">
    <li class="nav-item np-student-tab" role="presentation">
      <a class="nav-link active np-student-tab-link" id="tab1-tab" data-bs-toggle="tab" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true">
        <img src="{{ asset('frontend/noipunno/images/icons/student-tab1.svg') }}" alt=""> শিক্ষার্থীর তথ্য পরিবর্তন
      </a>
    </li>
    <!-- <li class="nav-item np-student-tab" role="presentation">
        <a class="nav-link np-student-tab-link" id="tab2-tab" data-bs-toggle="tab" href="#tab2" role="tab" aria-controls="tab2" aria-selected="false">
          <img src="{{ asset('frontend/noipunno/images/icons/student-tab1.svg') }}"> একাধিক শিক্ষার্থী যুক্ত করুন
        </a>
      </li> -->
  </ul>

  <div class="tab-content" id="myTabsContent">
    <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
      <!-- Content for Tab 1 -->

      <div class="row">
        <div class="col-md-12 col-sm-12">
          <!-- student add -->
          <section class="np-teacher-add-form" id="edit-form">
            <div class="np-input-form-bg">
              <div class="container">
                <form method="POST" action="{{ route('student.update', $student->caid) }}" enctype="multipart/form-data">
                  @method('PUT')
                  @csrf
                  <div class="row">
                    <div class="col-md-4 col-sm-12">
                      <div>
                        <label for="branch1" class="form-label">ব্রাঞ্চ</label>
                        <div class="input-group">
                          <select class="form-select np-teacher-input" aria-label="Default select example" id="branch1" name="branch">
                            <option value="">Select Branch</option>
                            @foreach ($branchs as $branch)
                            <option value="{{ @$branch->uid }}" @if ($student->branch == $branch->uid) selected @endif>{{ @$branch->branch_name }}</option>
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
                            <option value="{{ @$shift->uid }}" @if ($student->shift == $shift->uid) selected @endif>{{ @$shift->shift_name }}</option>
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
                            <option value="{{ @$version->uid }}" @if ($student->version == $version->uid) selected @endif>{{ @$version->version_name }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-4 col-sm-12 mt-3">
                      <div>
                        <label for="class1" class="form-label">ক্লাস <span class="error">*</span></label>
                        <div class="input-group">
                          <select class="form-select np-teacher-input" aria-label="Default select example" id="class1" name="class">
                            <option value="">Select Class</option>
                            @foreach ($classList as $class)
                            <option value="{{ $class['class_id'] }}" @if ($student->class == $class['class_id']) selected @endif>{{ $class['name_en'] }}</option>
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
                            <option value="{{ @$section->uid }}" @if ($student->section == $section->uid) selected @endif>{{ @$section->section_name }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-4 col-sm-12 mt-3">
                      <div>
                        <label for="registration_year1" class="form-label">সন <span class="error">*</span></label>
                        <div class="input-group">
                        <input type="number" class="form-control np-teacher-input" id="registration_year1" name="registration_year" value="{{$student->registration_year}}">
                          <!-- <select class="form-select np-teacher-input" aria-label="Default select example" id="registration_year1" name="registration_year">
                            <option value="">নির্বাচন করুন</option>
                            <option value="2022" @if($student->registration_year == 2022) selected @endif>2022</option>
                            <option value="2023" @if($student->registration_year == 2023) selected @endif>2023</option>
                            <option value="2024" @if($student->registration_year == 2024) selected @endif>2024</option>
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
                        <label for="loginId" class="form-label">রোল নাম্বার  <span class="error">*</span></label>
                        <input type="number" class="form-control np-teacher-input" id="loginId" name="roll" value="{{old('roll', $student->roll)}}">
                      </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                      <div class="mb-3">
                        <label for="studentName" class="form-label">শিক্ষার্থীর নাম (বাংলা) </label>
                        <input type="text" class="form-control np-teacher-input" id="studentName" name="student_name_bn" value="{{$student->student_name_bn}}">
                        @if ($errors->has('student_name_bn'))
                        <small class="help-block form-text text-danger">{{ $errors->first('student_name_bn') }}</small>
                        @endif
                      </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                      <div class="mb-3">
                        <label for="studentNametEnglish" class="form-label">শিক্ষার্থীর নাম (English) <span class="error">*</span></label>
                        <input type="text" class="form-control np-teacher-input" id="studentNametEnglish" name="student_name_en" value="{{$student->student_name_en}}">
                        @if ($errors->has('student_name_en'))
                        <small class="help-block form-text text-danger">{{ $errors->first('student_name_en') }}</small>
                        @endif
                      </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                      <div class="mb-3">
                        <label for="BRID" class="form-label">জন্ম নিবন্ধন নং</label>
                        <input type="text" class="form-control np-teacher-input" id="BRID" name="brid" placeholder="20XXXXXXXXXXXXXXX" value="{{$student->brid}}">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-3 col-sm-12">
                      <label for="birthday" class="form-label">জন্ম তারিখ</label>
                      <input type="date" class="form-control np-teacher-input" id="birthday" name="date_of_birth" max="<?php echo date('Y-m-d'); ?>" value="{{$student->date_of_birth}}">
                    </div>
                    <div class="col-md-3 col-sm-12">
                      <div class="mb-3">
                        <label for="gender" class="form-label">লিঙ্গ</label>
                        <div class="input-group">
                          <select class="form-select np-teacher-input" aria-label="Default select example" id="gender" name="gender">
                            <option value=""> নির্বাচন করুন</option>
                            <option value="Male" {{ @$student->gender == 'Male' ? 'selected' : ''}}>ছাত্র</option>
                            <option value="Female" {{ @$student->gender == 'Female' ? 'selected' : ''}}> ছাত্রী</option>
                            <option value="Other" {{ @$student->gender == 'Other' ? 'selected' : ''}}> অন্যান্য</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                      <div class="mb-3">
                        <label for="dhormo" class="form-label">ধর্ম </label>
                        {{-- <input type="text" class="form-control np-teacher-input" id="dhormo" name="religion" value="{{$student->religion}}"> --}}
                        <select class="form-select np-teacher-input" aria-label="Default select example" id="dhormo" name="religion">
                          <option value=""> ধর্ম নির্বাচন করুন</option>
                          <option value="Islam" {{ @$student->religion == 'Islam' ? 'selected' : ''}}>ইসলাম</option>
                          <option value="Hinduism" {{ @$student->religion == 'Hinduism' ? 'selected' : ''}}> হিন্দু</option>
                          <option value=" Christianity" {{ @$student->religion == 'Christianity' ? 'selected' : ''}}> খ্রিষ্টান</option>
                          <option value="Buddhism" {{ @$student->religion == 'Buddhism' ? 'selected' : ''}}> বৌদ্ধ</option>
                          <option value="Other" {{ @$student->religion == 'Other' ? 'selected' : ''}}> Other</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                      <div class="mb-3">
                        <label for="phone" class="form-label">মোবাইল নাম্বার </label>
                        <input type="number" class="form-control np-teacher-input num" maxlength="11" id="phone" name="student_mobile_no" value="{{$student->student_mobile_no}}" placeholder="01xxxxxxxxx">
                      </div>
                    </div>

                  </div>
                  <div class="row">
                    <div class="col-md-3 col-sm-12">
                      <div class="mb-3">
                        <label for="motherName" class="form-label">মাতার নাম (বাংলা)</label>
                        <input type="text" class="form-control np-teacher-input" id="motherName" name="mother_name_bn" value="{{$student->mother_name_bn}}">
                      </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                      <div class="mb-3">
                        <label for="motherNameEnglish" class="form-label">মাতার নাম (English)</label>
                        <input type="text" class="form-control np-teacher-input" id="motherNameEnglish" name="mother_name_en" value="{{$student->mother_name_en}}">
                      </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                      <div class="mb-3">
                        <label for="fatherName" class="form-label">পিতার নাম (বাংলা)  <span class="error">*</span></label>
                        <input type="text" class="form-control np-teacher-input" id="fatherName" name="father_name_bn" value="{{$student->father_name_bn}}">
                      </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                      <div class="mb-3">
                        <label for="fatherNameEnglish" class="form-label">পিতার নাম (English)</label>
                        <input type="text" class="form-control np-teacher-input" id="fatherNameEnglish" name="father_name_en" value="{{$student->father_name_en}}">
                      </div>
                    </div>

                  </div>

                  <div class="row">
                    <div class="col-md-3 col-sm-12">
                      <div class="mb-3">
                        <label for="farhersPhoneNumber" class="form-label">পিতার মোবাইল নাম্বার </label>
                        <input type="number" class="form-control np-teacher-input num" maxlength="11" id="farhersPhoneNumber" name="father_mobile_no" value="{{$student->father_mobile_no}}" placeholder="01xxxxxxxxx">
                        @if ($errors->has('father_mobile_no'))
                        <small class="help-block form-text text-danger">{{ $errors->first('father_mobile_no') }}</small>
                        @endif
                      </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                      <div class="mb-3">
                        <label for="mothersPhoneNumber" class="form-label">মাতার মোবাইল নাম্বার</label>
                        <input type="number" class="form-control np-teacher-input num" maxlength="11" id="mothersPhoneNumber" name="mother_mobile_no" value="{{$student->mother_mobile_no}}" placeholder="01xxxxxxxxx">
                      </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                      <div class="mb-3">
                        <label for="guardian_name_bn" class="form-label">অভিভাবকের নাম <small>(যদি থাকে)</small></label>
                        <input type="text" class="form-control np-teacher-input" id="guardian_name_bn" name="guardian_name_bn" value="{{$student->guardian_name_bn}}">
                      </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                      <div class="mb-3">
                        <label for="guardian_mobile_no" class="form-label">অভিভাবকের মোবাইল নাম্বার </label>
                        <input type="number" class="form-control np-teacher-input num" maxlength="11" id="guardian_mobile_no" name="guardian_mobile_no" value="{{$student->guardian_mobile_no}}" placeholder="01xxxxxxxxx">
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
                  <div class="col-md-4"></div>
                    <div class="col-md-4 col-sm-12">
                    <a href="{{ route('student.index') }}" class="btn btn-primary np-btn-form-submit mt-3">বাতিল করুন <img src="{{ asset('frontend/noipunno/images/icons/arrow-right.svg') }}" alt="logo"></a>
                    </div>
                    <div class="col-md-4 col-sm-12">
                      <button type="submit" class="btn btn-primary np-btn-form-submit mt-3">তথ্য হালনাগাদ করুন <img src="{{ asset('frontend/noipunno/images/icons/arrow-right.svg') }}" alt="logo"></button>
                    </div>
                  </div>
                </form>
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
            <section class="np-teacher-add-form">
              <div class="np-input-form-bg">
                <!-- <form id="fileUploadForm" enctype="multipart/form-data"> -->
                <div class="row">
                  <div class="col-md-12">
                    <form id="file-upload-form" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                      <input id="file-upload" class="np-file-upload-hidden" type="file" name="fileUpload" />
                      <label for="file-upload" id="file-drag">
                        <img src="{{ asset('frontend/noipunno/images/icons/draganddrop.svg') }}" alt=""><br />
                        Drag and Drop or <span id="file-upload-btn">Browse</span>
                        <br /> <small>Supports: Excel, CSV</small>
                        <progress id="file-progress" value="0">
                          <span>0</span>%
                        </progress>
                        <output for="file-upload" id="messages"></output>
                      </label>
                    </form>
                  </div>
                </div>
                <div class="row np-file-upload-demo-file-btn-container">
                  <div class="col-md-2"></div>
                  <div class="col-md-3">
                    <p class="text-center">আপলোড করতে সমস্যা হচ্ছে ?</p>
                  </div>
                  <!-- <div class="col-md-5">
                      <a href="#" class="np-file-upload-demo-file-btn" download>একাধিক শিক্ষার্থী আপলোড করার নমুনা ডাউনলোড করুন</a>
                    </div> -->
                  <div class="col-md-2"></div>
                </div>
                <div class="row">
                  <div class="col-md-4"></div>
                  <div class="col-md-4 col-sm-12">
                    <div class="text-center ">
                      <button type="submit" class="btn btn-primary np-btn-form-submit mt-3">শিক্ষার্থীর তথ্য হালনাগাদ করুন <img src="{{ asset('frontend/noipunno/images/icons/arrow-right.svg') }}" alt="logo"></button>
                    </div>
                  </div>
                  <div class="col-md-4"></div>
                </div>
                <!-- </form> -->
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
  input[type="number"]:read-only {
    cursor: normal;
    background-color: rgb(240,240,240);
  }
</style>

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
        $('#class1').val('');
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
        $('#section1').empty();
        $('#registration_year1').empty();
        $.each(data, function(key, value) {
          $('#section1').append('<option value="' + value.uid + '">' + value.section_name + '</option>');
          $('#registration_year1').val(value.section_year)
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

  $(document).ready(function() {
    document.getElementById("edit-form").scrollIntoView({
      "behavior": "smooth"
    });
  });
</script>
@endsection