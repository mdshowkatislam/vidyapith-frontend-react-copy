<div class="main-nav border-bottom-color">
    <div class="container">
        <div class="row">
            <div class="d-flex justify-content-between">
                <div class="d-flex justify-content-between">
                    <div class="d-flex align-items-center">
                        <nav class="navbar navbar-expand-lg">
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                aria-expanded="false" aria-label="Toggle navigation">
                                <span><img src="{{ asset('assets/icons/menu.png') }}"
                                        class="img-fluid d-flex align-items-center" alt="" /></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                    <li class="nav-item dropdown nav-item-style pe-2">
                                        <a href="{{ route('home') }}"
                                            class="nav-link active navbar-menu-item d-flex align-items-center"><img
                                                src="{{ asset('assets/icons/home.svg') }}"
                                                class="img-fluid icon-right-space" alt="main logo" />
                                            প্রথম পাতা
                                            {{-- <img src="{{ asset('assets/icons/tik-ico.svg') }}" class="img-fluid icon-left-space tick-icons" alt="tik icon" /> --}}
                                        </a>
                                        {{-- <a class="nav-link active navbar-menu-item d-flex align-items-center"
                                            role="button" data-bs-toggle="dropdown" aria-expanded="false"><img
                                                src="{{ asset('assets/icons/home.svg') }}" class="img-fluid icon-right-space"
                                                alt="main logo"/>
                                            প্রথম পাতা
                                            <img src="{{ asset('assets/icons/tik-ico.svg') }}"
                                                class="img-fluid icon-left-space tick-icons" alt="tik icon" />
                                        </a> --}}
                                        {{-- <ul
                                            class="dropdown-menu border-0 dropdown-menu-item-style navbar-dropdown-bg-color">
                                            <li><a class="dropdown-item" href="#">
                                                    <div class="dropdown-list-item-style d-flex align-items-center">
                                                        <img src="{{ asset('assets/icons/nav-icos.svg') }}"
                                                            class="img-fluid dropdown-list-item-icon" alt="icon" />
                                                        <p class="dropdown-class-list">প্রধান শিক্ষক</p>
                                                    </div>
                                                </a>
                                            </li>
                                            <li><a class="dropdown-item" href="#">
                                                    <div class="dropdown-list-item-style d-flex align-items-center">
                                                        <img src="{{ asset('assets/icons/nav-icos.svg') }}"
                                                            class="img-fluid dropdown-list-item-icon" alt="icon" />
                                                        <p class="dropdown-class-list">বিষয়ভিত্তিক শিক্ষক</p>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul> --}}
                                    </li>
                                    {{-- <li class="nav-item dropdown nav-item-style pe-1">
                                        <a class="nav-link navbar-menu-item d-flex align-items-center" role="button"
                                            data-bs-toggle="dropdown" aria-expanded="false"><img
                                                src="{{ asset('assets/icons/report.svg') }}" class="img-fluid icon-right-space"
                                                alt="main logo" />
                                            রিপোর্ট<img src="{{ asset('assets/icons/tik-ico.svg') }}"
                                                class="img-fluid icon-left-space" alt="tik icon" />
                                        </a>
                                        <ul
                                            class="dropdown-menu border-0 dropdown-menu-item-style navbar-dropdown-bg-color">
                                            <li><a class="dropdown-item" href="#">
                                                    <div class="dropdown-list-item-style d-flex align-items-center">
                                                        <img src="{{ asset('assets/icons/nav-icos.svg') }}"
                                                            class="img-fluid dropdown-list-item-icon" alt="icon" />
                                                        <p class="dropdown-class-list">শিক্ষার্থীর ট্রান্সক্রিপ্ট
                                                        </p>
                                                    </div>
                                                </a>
                                            </li>
                                            <li><a class="dropdown-item" href="#">
                                                    <div class="dropdown-list-item-style d-flex align-items-center">
                                                        <img src="{{ asset('assets/icons/nav-icos.svg') }}"
                                                            class="img-fluid dropdown-list-item-icon" alt="icon" />
                                                        <p class="dropdown-class-list">শিক্ষার্থীদের রিপোর্ট কার্ড
                                                        </p>
                                                    </div>
                                                </a>
                                            </li>
                                            <li><a class="dropdown-item" href="#">
                                                    <div class="dropdown-list-item-style d-flex align-items-center">
                                                        <img src="{{ asset('assets/icons/nav-icos.svg') }}"
                                                            class="img-fluid dropdown-list-item-icon" alt="icon" />
                                                        <p class="dropdown-class-list">শ্রেণির প্রতিবেদন</p>
                                                    </div>
                                                </a>
                                            </li>
                                            <li><a class="dropdown-item" href="#">
                                                    <div class="dropdown-list-item-style d-flex align-items-center">
                                                        <img src="{{ asset('assets/icons/nav-icos.svg') }}"
                                                            class="img-fluid dropdown-list-item-icon" alt="icon" />
                                                        <p class="dropdown-class-list">শিক্ষার্থীর হাজিরা প্রতিবেদন
                                                        </p>
                                                    </div>
                                                </a>
                                            </li>
                                            <li><a class="dropdown-item" href="#">
                                                    <div class="dropdown-list-item-style d-flex align-items-center">
                                                        <img src="{{ asset('assets/icons/nav-icos.svg') }}"
                                                            class="img-fluid dropdown-list-item-icon" alt="icon" />
                                                        <p class="dropdown-class-list">Report
                                                        </p>
                                                    </div>
                                                </a>
                                            </li>
                                            <li><a class="dropdown-item" href="#">
                                                    <div class="dropdown-list-item-style d-flex align-items-center">
                                                        <img src="{{ asset('assets/icons/nav-icos.svg') }}"
                                                            class="img-fluid dropdown-list-item-icon" alt="icon" />
                                                        <p class="dropdown-class-list">Report card design
                                                        </p>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
 --}}
                                    <li class="nav-item dropdown nav-item-style pe-1">
                                        <a class="nav-link navbar-menu-item d-flex align-items-center" href="#"
                                            role="button" data-bs-toggle="dropdown" aria-expanded="false"><img
                                                src="{{ asset('assets/icons/nav-teacher-icon.svg') }}"
                                                class="img-fluid icon-right-space" alt="main logo" />
                                            শিক্ষক <img src="{{ asset('assets/icons/tik-ico.svg') }}"
                                                class="img-fluid icon-left-space" alt="tik icon" />
                                        </a>
                                        <ul class="dropdown-menu border-0 dropdown-menu-item-style">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('teacher.index') }}">
                                                    <div class="dropdown-list-item-style d-flex align-items-center">
                                                        <img src="{{ asset('assets/icons/nav-icos.svg') }}"
                                                            class="img-fluid dropdown-list-item-icon" alt="icon" />
                                                        <p class="dropdown-class-list">শিক্ষকগণের তালিকা</p>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li class="nav-item dropdown nav-item-style pe-1">
                                        <a class="nav-link navbar-menu-item d-flex align-items-center" href="#"
                                            role="button" data-bs-toggle="dropdown" aria-expanded="false"><img
                                                src="{{ asset('assets/icons/student-icon.svg') }}"
                                                class="img-fluid icon-right-space" alt="main logo" />
                                            শিক্ষার্থী <img src="{{ asset('assets/icons/tik-ico.svg') }}"
                                                class="img-fluid icon-left-space" alt="tik icon" />
                                        </a>
                                        <ul class="dropdown-menu border-0 dropdown-menu-item-style">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('student.index') }}">
                                                    <div class="dropdown-list-item-style d-flex align-items-center">
                                                        <img src="{{ asset('assets/icons/nav-icos.svg') }}"
                                                            class="img-fluid dropdown-list-item-icon" alt="icon" />
                                                        <p class="dropdown-class-list">নতুন শিক্ষার্থী রেজিস্ট্রেশন
                                                        </p>
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('student.promote') }}">
                                                    <div class="dropdown-list-item-style d-flex align-items-center">
                                                        <img src="{{ asset('assets/icons/nav-icos.svg') }}"
                                                            class="img-fluid dropdown-list-item-icon" alt="icon" />
                                                        <p class="dropdown-class-list">নতুন শ্রেণিতে যুক্ত করুন</p>
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('student.section_change') }}">
                                                    <div class="dropdown-list-item-style d-flex align-items-center">
                                                        <img src="{{ asset('assets/icons/nav-icos.svg') }}"
                                                            class="img-fluid dropdown-list-item-icon" alt="icon" />
                                                        <p class="dropdown-class-list">সেকশন পরিবর্তন করুন</p>
                                                    </div>
                                                </a>
                                            </li>
                                            {{-- <li>
                                                <a class="dropdown-item" href="{{ route('student.issue.transfer') }}">
                                                    <div class="dropdown-list-item-style d-flex align-items-center">
                                                        <img src="{{ asset('assets/icons/nav-icos.svg') }}"
                                                            class="img-fluid dropdown-list-item-icon" alt="icon" />
                                                        <p class="dropdown-class-list">ছাড়পত্র প্রদান করুন</p>
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('student.transfer.add') }}">
                                                    <div class="dropdown-list-item-style d-flex align-items-center">
                                                        <img src="{{ asset('assets/icons/nav-icos.svg') }}"
                                                            class="img-fluid dropdown-list-item-icon" alt="icon" />
                                                        <p class="dropdown-class-list">ছাড়পত্র প্রাপ্ত শিক্ষার্থী যুক্ত করুন</p>
                                                    </div>
                                                </a>
                                            </li> --}}
                                        </ul>
                                    </li>

                                    {{--
                                        <li class="nav-item dropdown nav-item-style pe-1">
                                        <a class="nav-link navbar-menu-item d-flex align-items-center" href="#"
                                            role="button" data-bs-toggle="dropdown" aria-expanded="false"><img
                                                src="{{ asset('assets/icons/class-icon.svg') }}"
                                                class="img-fluid icon-right-space" alt="main logo" />
                                            শ্রেণী <img src="{{ asset('assets/icons/tik-ico.svg') }}"
                                                class="img-fluid icon-left-space" alt="tik icon" />
                                        </a>
                                        <ul class="dropdown-menu border-0 dropdown-menu-item-style">
                                            <li><a class="dropdown-item" href="class6.html">
                                                    <div class="dropdown-list-item-style d-flex align-items-center">
                                                        <img src="{{ asset('assets/icons/nav-icos.svg') }}"
                                                            class="img-fluid dropdown-list-item-icon" alt="icon" />
                                                        <p class="dropdown-class-list">ষষ্ঠ শ্রেণী</p>
                                                    </div>
                                                </a>
                                            </li>
                                            <li><a class="dropdown-item" href="class7.html">
                                                    <div class="dropdown-list-item-style d-flex align-items-center">
                                                        <img src="{{  asset('assets/icons/nav-icos.svg') }}"
                                                            class="img-fluid dropdown-list-item-icon" alt="icon" />
                                                        <p class="dropdown-class-list">সপ্তম শ্রেণী</p>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="nav-item dropdown nav-item-style pe-1">
                                        <a class="nav-link navbar-menu-item d-flex align-items-center"
                                            href="#" role="button" data-bs-toggle="dropdown"
                                            aria-expanded="false"><img src="{{ asset('assets/icons/requests.svg') }}"
                                                class="img-fluid icon-right-space" alt="main logo" />
                                            অনুরোধগুলি
                                        </a>
                                    </li> --}}
                                    {{-- @if (date('Y-m-d H:i:s') > '2024-07-5   16:18:00') --}}
                                    {{-- <li class="nav-item dropdown nav-item-style pe-1">
                                        <a class="nav-link navbar-menu-item d-flex align-items-center" target="_blank" href="{{route('institute.paper')}}"
                                            role="button" ><img
                                                src="{{ asset('assets/icons/nav-teacher-icon.svg') }}"
                                                class="img-fluid icon-right-space" alt="main logo" />
                                                ষান্মাসিক সামষ্টিক প্রশ্নপত্র
                                        </a>
                                    </li> --}}
                                    <li class="nav-item dropdown nav-item-style pe-1">
                                        <a class="nav-link navbar-menu-item d-flex align-items-center"
                                            href="{{ route('student.board_registration.payment.list') }}"
                                            role="button"><img src="{{ asset('assets/icons/nav-teacher-icon.svg') }}"
                                                class="img-fluid icon-right-space" alt="main logo" />
                                            পেমেন্ট লিস্ট
                                        </a>
                                    </li>
                                    <li class="nav-item dropdown nav-item-style pe-1">
                                        <a class="nav-link navbar-menu-item d-flex align-items-center"
                                            href="{{ route('home') }}" role="button"><img
                                                src="{{ asset('assets/icons/nav-teacher-icon.svg') }}"
                                                class="img-fluid icon-right-space" alt="main logo" />
                                            বোর্ড নিবন্ধন
                                        </a>
                                    </li>

                                    <li class="nav-item dropdown nav-item-style pe-1">
                                        <a class="nav-link navbar-menu-item d-flex align-items-center" href="#"
                                            role="button" data-bs-toggle="dropdown" aria-expanded="false"><img
                                                src="{{ asset('assets/icons/student-icon.svg') }}"
                                                class="img-fluid icon-right-space" alt="main logo" />
                                                সংযুক্ত প্রতিষ্ঠান <img src="{{ asset('assets/icons/tik-ico.svg') }}"
                                                class="img-fluid icon-left-space" alt="tik icon" />
                                        </a>
                                        <ul class="dropdown-menu border-0 dropdown-menu-item-style">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('student.attached_institute') }}">
                                                    <div class="dropdown-list-item-style d-flex align-items-center">
                                                        <img src="{{ asset('assets/icons/nav-icos.svg') }}"
                                                            class="img-fluid dropdown-list-item-icon" alt="icon" />
                                                        <p class="dropdown-class-list">আবেদন
                                                        </p>
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('student.attached_institute_request.list') }}">
                                                    <div class="dropdown-list-item-style d-flex align-items-center">
                                                        <img src="{{ asset('assets/icons/nav-icos.svg') }}"
                                                            class="img-fluid dropdown-list-item-icon" alt="icon" />
                                                        <p class="dropdown-class-list">অনুমোদন</p>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    {{-- @endif --}}
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>

                @if (@auth()->user()->eiin_institute->board_uid || auth()->user()->user_type_id == 4)
                    <div class="d-lg-flex d-block align-items-lg-center mt-2 mt-lg-0">
                        <div class="btn-group position-relative">
                            <a class="nav-link navbar-menu-item nav-right-dorpdown  d-flex align-items-center"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false"><img
                                    src="{{ asset('assets/icons/add.svg') }}" class="img-fluid icon-right-space"
                                    alt="add icon" />
                                ব্যবস্থাপনা<img src="{{ asset('assets/icons/tik-ico-white.svg') }}"
                                    class="img-fluid icon-left-space" alt="dropdown icon" />
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end management-dropdown">
                                <li>
                                    <a href="{{ route('noipunno.dashboard.branch.add') }}">
                                        <div class="management-dropdown-style dropdown-item profile-style">
                                            <img src="{{ asset('assets/icons/branch-ico.svg') }}"
                                                class="img-fluid icon-right-space" alt="profile icon" />
                                            ব্রাঞ্চ ব্যবস্থাপনা
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('noipunno.dashboard.shift.add') }}">
                                        <div class="management-dropdown-style dropdown-item profile-style">
                                            <img src="{{ asset('assets/icons/branch-ico.svg') }}"
                                                class="img-fluid icon-right-space" alt="profile icon" />
                                            শিফট ব্যবস্থাপনা
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('noipunno.dashboard.version.add') }}">
                                        <div class="management-dropdown-style dropdown-item profile-style">
                                            <img src="{{ asset('assets/icons/branch-ico.svg') }}"
                                                class="img-fluid icon-right-space" alt="profile icon" />
                                            ভার্সন ব্যবস্থাপনা
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('noipunno.dashboard.section.add') }}">
                                        <div class="management-dropdown-style dropdown-item profile-style">
                                            <img src="{{ asset('assets/icons/branch-ico.svg') }}"
                                                class="img-fluid icon-right-space" alt="profile icon" />
                                            সেকশন ব্যবস্থাপনা
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('teacher.index') }}">
                                        <div class="management-dropdown-style dropdown-item profile-style">
                                            <img src="{{ asset('assets/icons/teacher-management.svg') }}"
                                                class="img-fluid icon-right-space" alt="profile icon" />
                                            শিক্ষক ব্যবস্থাপনা
                                        </div>
                                    </a>
                                </li>
                                <li><a href="{{ route('student.index') }}">
                                        <div class="management-dropdown-style dropdown-item profile-style">
                                            <img src="{{ asset('assets/icons/std-management.svg') }}"
                                                class="img-fluid icon-right-space" alt="profile icon" />
                                            শিক্ষার্থী ব্যবস্থাপনা
                                        </div>
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('noipunno.dashboard.classroom.add') }}">
                                        <div class="management-dropdown-style dropdown-item profile-style">
                                            <img src="{{ asset('assets/icons/std-management.svg') }}"
                                                class="img-fluid icon-right-space" alt="profile icon" />
                                            বিষয় শিক্ষক নির্বাচন
                                        </div>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
