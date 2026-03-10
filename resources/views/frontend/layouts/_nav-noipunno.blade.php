<div>
    <section class="noipunno-navbar-section np">
        <div class="container noipunno-navbar-container">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <a class="navbar-brand" href="/">
                        <img src="{{ asset('frontend/images/noipunno-new-logo.svg') }}" alt="">
                    </a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse navbar-end" id="navbarSupportedContent">
                        <ul class="navbar-nav d-flex justify-content-end ms-auto align-items-center">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle noipunno-dropdown" href="#" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="user-section">
                                        <img src="{{ asset('/frontend/images/user-profile.png') }}" alt="">
                                    </div>
                                </a>
                       
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownUser">
                                    <li>
                                        <div class="border-bottom topnav-dropdown-style" style="width: 200px;">
                                            <div class="d-flex align-items-center gap-2">
                                                <div><img src="{{ asset('/frontend/images/user-profile.png') }}" class="img-fluid icon-right-space"
                                                        alt="profile icon" /></div>
                                                <div>
                                                    {{-- <h6 class="profile-style">{{ $institute->institute_name }}</h6> --}}
                                                    <h6 class="profile-style">Head শিক্ষক</h6>
                                                    {{-- {{  $institute->eiin }} --}}
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <a href="my-profile/edit" style="text-decoration: none; padding: 5px;">
                                            <div class="topnav-dropdown-style dropdown-item profile-style">
                                                <img src="{{ asset('assets/icons/profile-icon.svg') }}" class="img-fluid icon-right-space"
                                                    alt="profile icon" />
                                                আমার প্রোফাইল
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" style="text-decoration: none; padding: 5px;">
                                            <div class="topnav-dropdown-style dropdown-item profile-style">
                                                <img src="{{ asset('assets/icons/setting-2.svg') }}" class="img-fluid icon-right-space"
                                                    alt="profile icon" />
                                                সেটিংস
                                            </div>
                                        </a>
                                    </li>
                                    <hr class="p-0 m-0" />
                                    <li>
                                        <a href="#" style="text-decoration: none; padding: 5px;">
                                            <div class="topnav-dropdown-style dropdown-item profile-style">
                                                <img src="{{ asset('assets/icons/help.svg') }}" class="img-fluid icon-right-space"
                                                    alt="profile icon" />
                                                সাহায্য
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" style="text-decoration: none; padding: 5px;">
                                            <div class="topnav-dropdown-style dropdown-item profile-style">
                                                <img src="{{ asset('assets/icons/info-circle.svg') }}" class="img-fluid icon-right-space"
                                                    alt="profile icon" />
                                                সাধারণ প্রশ্ন উত্তর
                                            </div>
                                        </a>
                                    </li>
                                    <hr class="p-0 m-0" />
                                    <li>
                                        <a href="#" style="text-decoration: none; padding: 5px;" class="d-lg-none">
                                            <div class="topnav-dropdown-style dropdown-item profile-style">
                                                <img src="{{ asset('assets/icons/search-normal.svg') }}"
                                                    class="img-fluid icon-right-space" alt="profile icon" />
                                                অনুসন্ধান করুন
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" style="text-decoration: none; padding: 5px;" class="d-lg-none">
                                            <div class="topnav-dropdown-style dropdown-item profile-style">
                                                <img src="{{ asset('assets/icons/star.svg') }}" class="img-fluid icon-right-space"
                                                    alt="profile icon" />
                                                প্রিয় বিষয়
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('logout') }}" style="text-decoration: none; padding: 5px;">
                                            <div class="topnav-dropdown-style dropdown-item profile-style">
                                                <img src="{{ asset('assets/icons/sign-out.svg') }}" class="img-fluid icon-right-space"
                                                    alt="profile icon" />
                                                সাইন আউট
                                            </div>
                                        </a>
                                    </li>
                      
                                    
                                </ul>   
                            </li>
                            
                        </ul>
                        
                    </div>
                </div>
            </nav>
        </div>
        <hr />

        {{-- sub header starts --}}
        <div class="container noipunno-navbar-container ">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">

                <div class="d-flex justify-content-between w-100">
                    {{-- Responsive Toggler buttton starts --}}
                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                        <img src="{{ asset('/frontend/images/home.svg') }}" alt="">
                    </button>
                    {{-- Responsive Toggler buttton ends --}
                    {{-- Responsive offcanvas starts --}}
                    <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
                        {{-- offcanvas header starts --}}
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title" id="offcanvasExampleLabel">
                                <a class="navbar-brand" href="/">
                                    <img src="{{ asset('frontend/images/noipunno-new-logo.svg') }}" alt="">
                                </a>
                            </h5>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>
                        {{-- offcanvas header ends --}}

                        {{-- Accordions starts --}}
                        <div class="offcanvas-body subheader-accordion">
                            {{-- prothom pata accordion starts --}}
                            <div class="accordion accordion-flush" id="prothomPata">
                                <div class="accordion-item">
                                    <h2 class="accordion-header " id="prothompata-headingOne">
                                        <button class="accordion-button collapsed d-flex justify-content-between align-items-center  w-100" type="button" data-bs-toggle="collapse" data-bs-target="#prothompata-collapseOne" aria-expanded="false" aria-controls="prothompata-collapseOne">
                                            <img src="{{ asset('/frontend/images/home.svg') }}" alt="">
                                            <span class="fs-6 px-2">প্রথম পাতা</span>

                                        </button>
                                    </h2>
                                    <div id="prothompata-collapseOne" class="accordion-collapse collapse" aria-labelledby="prothompata-headingOne" data-bs-parent="#prothomPata">
                                        <div class="accordion-body d-flex flex-column py-0 px-0 pages-buttons">
                                            <a href="#" class="d-block "><button class="w-100 btn btn-light px-5 text-start">প্রধান শিক্ষক</button>
                                            </a>
                                            <a href="#" class="d-block "><button class="w-100 btn btn-light px-5 text-start">বিষয়ভিত্তিক
                                                    শিক্ষা</button>
                                            </a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- prothom pata accordion ends --}}

                            {{-- report accordion starts --}}
                            <div class="accordion accordion-flush" id="report">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="report-headingOne">
                                        <button class="accordion-button collapsed d-flex justify-content-between align-items-center  w-100" type="button" data-bs-toggle="collapse" data-bs-target="#report-collapseOne" aria-expanded="false" aria-controls="report-collapseOne">
                                            <img src="{{ asset('/frontend/images/report.svg') }}" alt="">
                                            <span class="fs-6 px-2">রিপোর্ট</span>
                                        </button>
                                    </h2>
                                    <div id="report-collapseOne" class="accordion-collapse collapse" aria-labelledby="report-headingOne" data-bs-parent="#report">
                                        <div class="accordion-body d-flex flex-column py-0 px-0 pages-buttons">
                                            <a href="#" class="d-block"><button class="w-100 btn btn-light px-5 text-start">শিক্ষার্থীদের মূল্যায়ন</button></a>
                                            <a href="#" class="d-block"><button class="w-100 btn btn-light px-5 text-start">শিক্ষার্থীর ট্রান্সক্রিপ্ট</button></a>
                                            <a href="#" class="d-block"><button class="w-100 btn btn-light px-5 text-start">শ্রেণির প্রতিবেদন</button></a>
                                            <a href="#" class="d-block"><button class="w-100 btn btn-light px-5 text-start">শিক্ষার্থীর হাজিরা প্রতিবেদন</button></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- report accordion ends --}}

                            {{-- Shikkhok accordion (single menu) starts  --}}
                            <div class="accordion accordion-flush responsive-single-menu-button" id="shikkhok">
                                <div class="accordion-item">
                                    <h2 class="accordion-header pages-buttons" id="shikkhok-headingOne">
                                        <button class="accordion-button collapsed d-flex justify-content-between align-items-center  w-100 responsive-single-menu-button" type="button">
                                            <img src="{{ asset('/frontend/images/teacher.svg') }}" alt="">
                                            <span class="fs-6 px-2">শিক্ষক</span>
                                        </button>
                                    </h2>
                                </div>
                            </div>
                            {{-- Shikkhok accordion (single menu) ends  --}}

                            {{-- shikkharthi accordion starts --}}
                            <div class="accordion accordion-flush" id="shikkharthi">
                                <div class="accordion-item">
                                    <h2 class="accordion-header " id="shikkharthi-headingOne">
                                        <button class="accordion-button collapsed d-flex justify-content-between align-items-center  w-100" type="button" data-bs-toggle="collapse" data-bs-target="#shikkharthi-collapseOne" aria-expanded="false" aria-controls="shikkharthi-collapseOne">
                                            <img src="{{ asset('/frontend/images/student.svg') }}" alt="">
                                            <span class="fs-6 px-2">শিক্ষার্থী</span>

                                        </button>
                                    </h2>
                                    <div id="shikkharthi-collapseOne" class="accordion-collapse collapse" aria-labelledby="shikkharthi-headingOne" data-bs-parent="#shikkharthi">
                                        <div class="accordion-body d-flex flex-column py-0 px-0 pages-buttons">
                                            <a href="#" class="d-block "><button class="w-100 btn btn-light px-5 text-start">শিক্ষার্থীর
                                                    তালিকা</button>
                                            </a>
                                            <a href="#" class="d-block "><button class="w-100 btn btn-light px-5 text-start">শিক্ষার্থীর
                                                    হাজিরা</button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- shikkharthi accordion ends --}}

                            {{-- sreni accordion starts --}}
                            <div class="accordion accordion-flush" id="sreni">
                                <div class="accordion-item">
                                    <h2 class="accordion-header " id="sreni-headingOne">
                                        <button class="accordion-button collapsed d-flex justify-content-between align-items-center  w-100" type="button" data-bs-toggle="collapse" data-bs-target="#sreni-collapseOne" aria-expanded="false" aria-controls="sreni-collapseOne">
                                            <img src="{{ asset('/frontend/images/class.svg') }}" alt="">
                                            <span class="fs-6 px-2">শ্রেণি</span>

                                        </button>
                                    </h2>
                                    <div id="sreni-collapseOne" class="accordion-collapse collapse" aria-labelledby="sreni-headingOne" data-bs-parent="#sreni">
                                        <div class="accordion-body d-flex flex-column py-0 px-0 pages-buttons">
                                            <a href="#" class="d-block "><button class="w-100 btn btn-light px-5 text-start">ষষ্ঠ শ্রেণি</button>
                                            </a>
                                            <a href="#" class="d-block "><button class="w-100 btn btn-light px-5 text-start">সপ্তম শ্রেণি</button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- sreni accordion ends --}}

                            {{-- onurodh accordion (single menu) starts  --}}
                            <div class="accordion accordion-flush responsive-single-menu-button" id="shikkhok">
                                <div class="accordion-item">
                                    <h2 class="accordion-header pages-buttons" id="onurodh-headingOne">
                                        <button class="accordion-button collapsed d-flex justify-content-between align-items-center  w-100 responsive-single-menu-button" type="button">
                                            <img src="{{ asset('/frontend/images/request.svg') }}" alt="">
                                            <span class="fs-6 px-2">অনুরোধসমূহ</span>
                                        </button>
                                    </h2>
                                </div>
                            </div>
                            {{-- onurodh accordion (single menu) ends  --}}


                        </div>
                        {{-- Accordions ends --}}
                    </div>
                    {{-- Responsive offcanvas ends --}}


                    {{-- subheader left starts --}}
                    <div class="d-none d-lg-flex pages-buttons">
                        {{-- Sub Header nav items starts --}}
                        {{-- prothom pata starts  --}}
                        <div class="dropdown">
                            <button class="d-flex justify-content-between align-items-center btn btn-ligh" type="button" id="prothomPata" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ asset('/frontend/images/home.svg') }}" alt="">
                                <span class="fs-6 px-2">প্রথম পাতা</span>
                                <img src="{{ asset('/frontend/images/arrow-down.svg') }}" alt="">
                            </button>
                            <div class="dropdown-menu" aria-labelledby="prothomPata">
                                <div class="create-profile-dropdown-container">
                                    <a href="/" class="dropdown-item">
                                        <div class="d-flex t">
                                            <span>প্রধান শিক্ষক</span>
                                        </div>
                                    </a>
                                    {{-- <a class="dropdown-item">
                                        <div class="d-flex ">
                                            <span>বিষয়ভিত্তিক শিক্ষা</span>
                                        </div>
                                    </a> --}}
                                </div>
                            </div>
                        </div>
                        {{-- prothom pata ends  --}}

                        {{-- report starts --}}
                        {{-- <div class="dropdown">
                            <button class="d-flex justify-content-between align-items-center btn btn-ligh" type="button" id="prothomPata" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="fs-6 px-2">রিপোর্ট</span>
                                <img src="{{ asset('/frontend/images/arrow-down.svg') }}" alt="">
                            </button>
                            <div class="dropdown-menu" aria-labelledby="prothomPata">
                                <div class="create-profile-dropdown-container">
                                    <a class="dropdown-item">
                                        <div class="d-flex ">
                                            <span>শিক্ষার্থীদের মূল্যায়ন</span>
                                        </div>
                                    </a>
                                    <a href="{{ asset('/frontend/569087689_jibon_o_jiika_transcript.pdf') }}" class="dropdown-item">
                                        <div class="d-flex ">
                                            <span>শিক্ষার্থীর ট্রান্সক্রিপ্ট</span>
                                        </div>
                                    </a>
                                    <a class="dropdown-item">
                                        <div class="d-flex ">
                                            <span>শ্রেণির প্রতিবেদন</span>
                                        </div>
                                    </a>
                                    <a class="dropdown-item">
                                        <div class="d-flex ">
                                            <span>শিক্ষার্থীর হাজিরা প্রতিবেদন</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div> --}}
                        {{-- report ends --}}

                        {{-- shikkhok starts  --}}
                        <div class="dropdown">
                            <button class="d-flex justify-content-between align-items-center btn btn-ligh" type="button" id="prothomPata" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ asset('/frontend/images/teacher.svg') }}" alt="">
                                <span class="fs-6 px-2">শিক্ষক</span>
                                <img src="{{ asset('/frontend/images/arrow-down.svg') }}" alt="">
                            </button>

                            {{-- <div class="dropdown-menu" aria-labelledby="prothomPata">
                                <div class="create-profile-dropdown-container">
                                </div>
                            </div> --}}
                            
                            <div class="dropdown-menu" aria-labelledby="prothomPata">
                                <div class="create-profile-dropdown-container">
                                    <a href="{{ route('teacher.index') }}" class="dropdown-item">
                                        <div class="d-flex ">
                                            <span>শিক্ষকগণের তালিকা</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        {{-- shikkhok ends  --}}

                        {{-- shikkharthi starts --}}
                        {{-- <div class="dropdown">
                            <button class="d-flex justify-content-between align-items-center btn btn-ligh"
                                type="button" id="prothomPata" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ asset('/frontend/images/student.svg') }}" alt="">
                        <span class="fs-6 px-2">শিক্ষার্থী</span>
                        <img src="{{ asset('/frontend/images/arrow-down.svg') }}" alt="">
                        </button>
                        <div class="dropdown-menu" aria-labelledby="prothomPata">
                            <div class="create-profile-dropdown-container">
                                <a class="dropdown-item">
                                    <div class="d-flex ">
                                        <span>শিক্ষার্থীর তালিকা</span>
                                    </div>
                                </a>
                                <a class="dropdown-item">
                                    <div class="d-flex ">
                                        <span>শিক্ষার্থীর হাজিরা</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div> --}}
                    {{-- shikkharthi ends --}}

                    {{-- Sreni starts --}}
                    {{-- <div class="dropdown">
                            <button class="d-flex justify-content-between align-items-center btn btn-ligh"
                                type="button" id="prothomPata" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ asset('/frontend/images/class.svg') }}" alt="">
                    <span class="fs-6 px-2">শ্রেণি</span>
                    <img src="{{ asset('/frontend/images/arrow-down.svg') }}" alt="">
                    </button>
                    <div class="dropdown-menu" aria-labelledby="prothomPata">
                        <div class="create-profile-dropdown-container">
                            <a class="dropdown-item">
                                <div class="d-flex ">
                                    <span>ষষ্ঠ শ্রেণি</span>
                                </div>
                            </a>
                            <a class="dropdown-item">
                                <div class="d-flex ">
                                    <span>সপ্তম শ্রেণি</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div> --}}
                {{-- Sreni ends --}}

                {{-- onurodh starts --}}
                {{-- <div class="dropdown">
                            <button class="d-flex justify-content-between align-items-center btn btn-ligh"
                                type="button" id="prothomPata" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ asset('/frontend/images/request.svg') }}" alt="">
                <span class="fs-6 px-2">অনুরোধসমূহ</span>
                <img src="{{ asset('/frontend/images/arrow-down.svg') }}" alt="">
                </button>

                <div class="dropdown-menu" aria-labelledby="prothomPata">
                    <div class="create-profile-dropdown-container">
                    </div>
                </div>

        </div> --}}
        {{-- onurodh ends --}}
        {{-- Sub Header nav items ends --}}
</div>
{{-- subheader left ends --}}


{{-- Create Profile starts --}}
<div>
    <div class="dropdown">
        <button class="np-btn-form-submit border-0 rounded-1 d-flex justify-content-between align-items-center rounded-1 dropdown-toggle" type="button" id="createMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="{{ asset('/frontend/images/add.svg') }}" alt="">
            <span class="px-3">ব্যবস্থাপনা</span>
            <img src="{{ asset('/frontend/images/arrow-down-white.svg') }}" alt="">
        </button>

        <div class="dropdown-menu" aria-labelledby="createMenuButton">
            <div class="create-profile-dropdown-container">
                <a class="dropdown-item" href="{{ route('noipunno.dashboard.branch.add') }}">
                    <div class="d-flex align-items-center">
                        <img class="d-block pe-2" src="{{ asset('/frontend/images/student.svg') }}" alt="">
                        <span>ব্রাঞ্চ ব্যবস্থাপনা</span>
                    </div>
                </a>

                <a class="dropdown-item" href="{{ route('noipunno.dashboard.shift.add') }}">
                    <div class="d-flex align-items-center">
                        <img class="d-block pe-2" src="{{ asset('/frontend/images/student.svg') }}" alt="">
                        <span>শিফট ব্যবস্থাপনা</span>
                    </div>
                </a>

                <a class="dropdown-item" href="{{ route('noipunno.dashboard.version.add') }}">
                    <div class="d-flex align-items-center">
                        <img class="d-block pe-2" src="{{ asset('/frontend/images/student.svg') }}" alt="">
                        <span>ভার্সন ব্যবস্থাপনা</span>
                    </div>
                </a>

                <a class="dropdown-item" href="{{ route('noipunno.dashboard.section.add') }}">
                    <div class="d-flex align-items-center">
                        <img class="d-block pe-2" src="{{ asset('/frontend/images/student.svg') }}" alt="">
                        <span>সেকশন ব্যবস্থাপনা</span>
                    </div>
                </a>
                <a class="dropdown-item" href="{{ route('teacher.index') }}">
                    <div class="d-flex align-items-center">
                        <img class="d-block pe-2" src="{{ asset('/frontend/images/teacher.svg') }}" alt="">
                        <span>শিক্ষক ব্যবস্থাপনা</span>
                    </div>
                </a>
                <a class="dropdown-item" href="{{ route('student.index') }}">
                    <div class="d-flex align-items-center">
                        <img class="d-block pe-2" src="{{ asset('/frontend/images/student.svg') }}" alt="">
                        <span>শিক্ষার্থী ব্যবস্থাপনা</span>
                    </div>
                </a>
                <a class="dropdown-item" href="{{ route('noipunno.dashboard.classroom.add') }}">
                    <div class="d-flex align-items-center">
                        <img class="d-block pe-2" src="{{ asset('/frontend/images/student.svg') }}" alt="">
                        <span>বিষয় শিক্ষক নির্বাচন</span>
                    </div>
                </a>
        </div>
    </div>
</div>
</div>
{{-- Create Profile ends --}}

</div>


</nav>
</div>
{{-- sub header ends --}}

</section>
<section class="empty-section"></section>
</div>
