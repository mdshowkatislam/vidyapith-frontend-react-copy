<!DOCTYPE html>
<html lang="en" foxified="">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- TITLE -->
    <!-- bootstrap.min.css -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- font-awesome.min.css -->

    <!-- style.css -->
    <!-- <link href="./src/css/style.css" rel="stylesheet" /> -->
    <!-- fontfamily -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Oswald:wght@200;400;600&family=Poppins:wght@100;200;300;400;500;600;800&family=Roboto:wght@100;300;400;500;700;900&family=Work+Sans:wght@100;200;300;400;500;600;800&display=swap"
        rel="stylesheet">

    <title>Bidyapith</title>
    <style>
        @import url('https://fonts.maateen.me/solaiman-lipi/font.css');

        html,
        body {
            height: 100%;
        }

        .hero_bg_img {
            background-image: url("{{ asset('assets/images/mobile-error-bg.png') }}");
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        * {
            font-family: SolaimanLipi, sans-serif !important;
        }
    </style>
</head>
<body>
    <div class="contaienr-fluid h-100 d-flex align-items-center hero_bg_img">
        <div class="container">
            <div class="row">
                <div class="d-flex justify-content-center">
                    <div class="">
                        <div class="d-flex justify-content-center">
                            <img src={{ asset('assets/images/noipunno-new-logo.svg') }} style="width:50%">
                        </div>
                        <p class="ps-2 pt-5 text-center"
                            style="font-weight: 600; font-size: 24px; line-height: 36px;">
                            মোবাইলে শুধুমাত্র গুগল প্লেস্টোর এর বৈধ "বিদ্যাপীঠ অ্যাপ" এবং ডেস্কটপ বা ল্যাপটপ থেকে যেকোনো ব্রাউজারে বিদ্যাপীঠ প্লাটফর্ম ব্যবহার করতে পারবেন। মূল্যায়নের নিরাপত্তা নিশ্চিত করতে মোবাইল ব্রাউজার থেকে বিদ্যাপীঠ প্লাটফর্ম ব্যবহার সাময়িকভাবে বন্ধ রাখা হয়েছে।

                        </p>
                    </div>
                </div>
            </div>
            <div class="row fixed-bottom pb-2">
                <div class="d-flex flex-column flex-md-row justify-content-center justify-content-md-around">
                    <div class="d-flex gap-2 px-3 py-2 justify-content-center ">
                        <ul class="d-flex gap-2 align-items-end">
                            <div class="d-flex gap-2 flex-column flex-md-row">
                                <div class="d-flex gap-2">
                                    <p class="text-center" style="color: #000; font-size:16px;">সর্বস্বত্ব সংরক্ষিত © ২০২৪<br> শিক্ষা মন্ত্রণালয়,
                                        গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</p>
                                </div>
                            </div>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>
