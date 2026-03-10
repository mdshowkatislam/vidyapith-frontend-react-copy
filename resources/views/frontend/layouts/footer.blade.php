<footer class="footer">
    <div class="container">
        <div class="row px-0 footer-text-padding">
            <div class="col-sm-12 col-md-6">
                <p>সর্বস্বত্ব সংরক্ষিত © {{ en2bn(date('Y')) }} শিক্ষা মন্ত্রণালয়, গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</p>
            </div>
            <div class="col-sm-12 col-md-6 d-flex justify-content-lg-end">
                <ul class="d-flex align-items-center gap-3">
                    <li><a href="#" class="footer-list-items">কপিরাইট</a></li>
                    <li><a href="#" class="footer-list-items">গোপনীয়তা নীতি</a></li>
                    <li><a href="#" class="footer-list-items">জিজ্ঞাসা</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
<!-- footer end -->
<section class="chat_box">
    <div id="popup" class="popup ">
        <div class="popup-app-info-top ">
            <div class="popup-app-info-reserved">
                <h2 class="reserved-app-info p-0 m-0">সর্বস্বত্ব সংরক্ষিত {{ en2bn(date('Y')) }}</h2>
            </div>
            <div class="popup-card-body">
                <div class="d-flex popup-card-icons">
                    <div class=""><img src="{{ asset('assets//icons/bd-map.svg') }}" class="img-fluid" alt="" /></div>
                    <div class=""><img src="{{ asset('assets/icons/NCTB_logo-2.svg') }}" class="img-fluid" alt="" /></div>
                </div>
                <div class="popup-card-institutions">
                    <ul>
                        <li style="line-height: 16px;">পরিকল্পনা ও বাস্তবায়নে:</li>
                        <li style="line-height: 16px;">জাতীয় শিক্ষাক্রম ও পাঠ্যপুস্তক বোর্ড (এনসিটিবি),</li>
                        <li style="line-height: 16px;">শিক্ষা মন্ত্রণালয়,</li>
                        <li style="line-height: 16px;">গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</li>
                    </ul>
                </div>
            </div>
            <hr class="m-0 my-2 p-0"/>
            <div class="popup-app-info-bottom">
                <div class="d-flex popup-card-icons align-items-end">
                    <div class=""><img src="{{ asset('assets//icons/Aspire_to_Innovate_Seal 2.svg') }}" class="img-fluid"
                            alt="" /></div>
                    <div class=""><img src="{{ asset('assets/icons/unicef logo.svg') }}" class="img-fluid" alt="" /></div>
                </div>
                <div class="popup-card-institutions">
                    <ul>
                        <li style="line-height: 16px;">কারিগরি সহায়তায়:</li>
                        <li style="line-height: 16px;">এসপায়ার টু ইনোভেট (এটুআই), </li>
                        <li style="line-height: 16px;">আইসিটি বিভাগ এবং ইউনিসেফ </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="">
            <div class="popup-version-bottom">
                <div class="d-flex align-items-center popup-version">
                    <p class="popup-version-info text-light">ভার্সন {{ en2bn(config('app.latest_version')) }}
                        & সর্বশেষ প্রকাশ {{ en2bn(config('app.last_release_date')) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- <div id="overlay" class="overlay"></div> -->
    <div class="chat_btn">
        <img class="app-info-btn" onclick="togglePopup()" src="{{ asset('assets/icons/app-info.svg') }}" class="img-fluid"
            alt="app-info" />
    </div>
</section>
