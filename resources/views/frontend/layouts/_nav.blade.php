<style>
    .noipunno-dropdown {
        
    }

    .noipunno-dropdown::after {
        display: none;
    }

    .user-section {
        width: 32px;
        height: 32px;
    }

    .user-section img {
        width: 100%;
    }

    .noipunno-navbar-section {
        border-bottom: 1px solid #dbdade69;
        background: #FFF;
    }

    .noipunno-navbar-section .navbar {
        background-color: transparent !important;
    }
</style>

<section class="noipunno-navbar-section">
    <div class="container noipunno-navbar-container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <img src="{{ asset('frontend/images/noipunno-logo.svg') }}" alt="">
                </a>
                <a class="navbar-brand" href="#">Bidyapith</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
    
                <div class="collapse navbar-collapse navbar-end" id="navbarSupportedContent">
                    <ul class="navbar-nav d-flex justify-content-end ms-auto align-items-center">
                        <li class="nav-item">
                            <a class="nav-link" href="#"><img src="{{ asset('frontend/images/fav-start-icon.svg') }}" alt=""></a>
                        </li>
    
                        <li class="nav-item">
                            <a class="nav-link" href="#"><img src="{{ asset('frontend/images/mode-toggler.svg') }}" alt=""></a>
                        </li>
    
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle noipunno-dropdown" href="#" id="navbarDropdownUser" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ asset('frontend/images/notification.svg') }}" alt="">
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownUser">
                                <li><a class="dropdown-item" href="{{ route("logout") }}">Logout</a></li> 
                            </ul>
                        </li>

                       
    
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle noipunno-dropdown" href="#" id="navbarDropdownUser" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="user-section">
                                    <img src="{{ asset('/frontend/images/user-profile.png') }}" alt="">
                                </div>
                            </a>
                            
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownUser">
                                <li><a class="dropdown-item" href="{{ route("logout") }}">Logout</a></li> 
                            </ul>
                           
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</section>
