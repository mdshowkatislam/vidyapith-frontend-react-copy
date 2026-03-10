@php
    $user = auth()->user();
    // $eiinId = $user->eiin;
    // $pi_reviews_count = \App\Models\PiReview::with(['teacher'])
    //     ->whereHas('teacher', function ($query) use ($eiinId) {
    //         $query->where('eiin', $eiinId);
    //     })
    //     ->where('is_approved', 0)
    //     ->where('session', date('Y'))
    //     ->count();
    // $bi_reviews_count = \App\Models\BiReview::with(['teacher'])
    //     ->whereHas('teacher', function ($query) use ($eiinId) {
    //         $query->where('eiin', $eiinId);
    //     })
    //     ->where('is_approved', 0)
    //     ->where('session', date('Y'))
    //     ->count();

    // $subject_reviews_count = \App\Models\PiBiReview::with(['teacher'])
    //     ->whereHas('teacher', function ($query) use ($eiinId) {
    //         $query->where('eiin', $eiinId);
    //     })
    //     ->where('is_approved', 0)
    //     ->where('session', date('Y'))
    //     ->count();
    // $review_count = $pi_reviews_count + $bi_reviews_count + $subject_reviews_count;
    $review_count = 0;
@endphp

<div class="topnav border-bottom-color">
    <div class="container">
        <div class="row">
            <div class="d-flex justify-content-between align-items-center py-2">
                <div><a href="/"><img src="{{ asset('assets/images/noipunno-new-logo.svg') }}" class="img-fluid"
                            alt="main logo" /></a></div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-none d-lg-block" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <a href="#"><img src="{{ asset('assets/icons/search-normal.svg') }}"
                                class="img-fluid mx-2" alt="search icon" /></a>
                        <!-- Modal -->
                        {{-- <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        ...
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary">Save changes</button>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                    <div class="d-none d-lg-block"> <a href="#"><img src="{{ asset('assets/icons/star.svg') }}"
                                class="img-fluid mx-2" alt="=favourite logo" /></a>
                    </div>
                    <div class="d-none d-lg-block" onclick="myFunction()">
                        <a href="#">
                            <img src="{{ asset('assets/icons/dark-light-mode.svg') }}" class="img-fluid mx-2 tick-icons"
                                alt="main logo" />
                        </a>
                    </div>
                    <div class=" position-relative">
                        <a href="#">
                            <img src="{{ asset('assets/icons/notification.svg') }}" class="img-fluid" alt="main logo" />
                            <span
                                class="position-absolute top-0 start-50 translate-middle d-flex mt-1  mx-2 justify-content-center align-items-center badge notification-badge rounded-pill bg-danger">
                                {{$review_count}}
                            </span>
                        </a>
                    </div>
                    <div class="btn-group position-relative">
                        <a class="navbar-menu-item d-flex align-items-center ms-2" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            @if (@$user->institute->logo || @$user->institute->logo != 0)
                                <img src="{{ Storage::url(@$user->institute->logo) }}"
                                    class="img-fluid topnav-profile-icon-style" alt="main logo">
                            @else
                                <img src="{{ asset('assets/icons/teacher.svg') }}"
                                    class="img-fluid topnav-profile-icon-style" alt="profile icon" />
                            @endif
                            {{-- <img src="{{ asset('assets/icons/teacher.svg') }}"
                                class="img-fluid topnav-profile-icon-style" alt="moon icon" /> --}}
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end management-dropdown">
                            <li>
                                <div class="border-bottom-color topnav-dropdown-style">
                                    <div class="d-flex align-items-center gap-2">
                                        {{-- <div>
                                            @if ($user->institute->logo)
                                            <img src="{{ Storage::url(@$user->institute->logo) }}" class="img-fluid icon-right-space"
                                                alt="main logo">
                                            @else
                                            <img src="{{ asset('assets/icons/teacher.svg') }}"
                                                class="img-fluid icon-right-space" alt="profile icon" />
                                            @endif

                                        </div> --}}
                                        <div>
                                            <h6 class="profile-style">{{ $user->name }}</h6>
                                            @if (!empty(@$user->eiin))
                                                <h6 class="profile-style"> EIIN: {{ $user->eiin }}</h6>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li>

                            {{-- <li>
                                <a href="#">
                                    <div class="topnav-dropdown-style dropdown-item profile-style">
                                        <img src="{{ asset('assets/icons/profile-icon.svg') }}"
                                            class="img-fluid icon-right-space" alt="profile icon" />
                                        আমার প্রোফাইল
                                    </div>
                                </a>
                            </li> --}}

                            @if (!empty(@$user->eiin))
                                <li>
                                    <a href="/institutes/{{ @$user->eiin }}/edit">
                                        <div class="topnav-dropdown-style dropdown-item profile-style">
                                            <img src="{{ asset('assets/icons/profile-icon.svg') }}"
                                                class="img-fluid icon-right-space" alt="profile icon" />
                                            প্রতিষ্ঠানের তথ্য
                                        </div>
                                    </a>
                                </li>
                            @endif

                            <li>
                                <a href="#">
                                    <div class="topnav-dropdown-style dropdown-item profile-style">
                                        <img src="{{ asset('assets/icons/setting-2.svg') }}"
                                            class="img-fluid icon-right-space" alt="profile icon" />
                                        সেটিংস
                                    </div>
                                </a>
                            </li>
                            <hr class="p-0 m-0" />
                            <li>
                                <a href="#">
                                    <div class="topnav-dropdown-style dropdown-item profile-style">
                                        <img src="{{ asset('assets/icons/help.svg') }}"
                                            class="img-fluid icon-right-space" alt="profile icon" />
                                        সাহায্য
                                    </div>
                                </a>
                            </li>
                            <li>
                                {{-- <a href="{{route('otp_view')}}"> --}}
                                <a href="{{route('change_new_pin')}}">
                                    <div class="topnav-dropdown-style dropdown-item profile-style">
                                        <img style="width: 20%" src="{{ asset('assets/icons/lock.svg') }}"
                                            class="img-fluid icon-right-space" alt="profile icon" />
                                            পিন পরিবর্তন
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="topnav-dropdown-style dropdown-item profile-style">
                                        <img src="{{ asset('assets/icons/info-circle.svg') }}"
                                            class="img-fluid icon-right-space" alt="profile icon" />
                                        সাধারণ প্রশ্ন উত্তর
                                    </div>
                                </a>
                            </li>
                            <hr class="p-0 m-0" />
                            <li>
                                <a href="#" class="d-lg-none">
                                    <div class="topnav-dropdown-style dropdown-item profile-style">
                                        <img src="{{ asset('assets/icons/search-normal.svg') }}"
                                            class="img-fluid icon-right-space" alt="profile icon" />
                                        অনুসন্ধান করুন
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="d-lg-none">
                                    <div class="topnav-dropdown-style dropdown-item profile-style">
                                        <img src="{{ asset('assets/icons/star.svg') }}"
                                            class="img-fluid icon-right-space" alt="profile icon" />
                                        প্রিয় বিষয়
                                    </div>
                                </a>
                            </li>
                            <hr class="d-lg-none p-0 m-0" />
                            <li>
                                <a href="#" class="d-lg-none">
                                    <div class="topnav-dropdown-style dropdown-item profile-style"
                                        onclick="myFunction()">
                                        <img src="{{ asset('assets/icons/dark-light-mode.svg') }}"
                                            class="img-fluid tick-icons" alt="main logo" />থিম নির্বাচন করুন
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('logout') }}">
                                    <div class="topnav-dropdown-style dropdown-item profile-style">
                                        <img src="{{ asset('assets/icons/sign-out.svg') }}"
                                            class="img-fluid icon-right-space" alt="profile icon" />
                                        সাইন আউট
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
