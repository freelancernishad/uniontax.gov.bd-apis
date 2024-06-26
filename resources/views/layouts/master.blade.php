<x-ad />

</div>

<style>
    a.memberReBtn.blood:hover {
        background: red !important;
        color: white !important;
        border: 4px solid white !important;
    }
</style>

<div class="bottomheader" id="myHeader">
    <div class="cus_container">
        <nav class="nav" style="    justify-content: space-between;">
            <div class="mobilenav">
                <i onclick="mobileNav()" class="fas fa-bars"></i>
            </div>
            <ul class="main_nav list-unstyled mb-0" id="mobileNav">
                <li>
                    <a class="mainNanLink" href="{{ url('/') }}"><i class="fas fa-home"></i> &nbsp; Home</a>
                </li>
                <li><a class="mainNanLink" href="{{ url('about-us') }}">About Us</a></li>
                <li class="dropdown" onclick="submenuShow(1)">
                    <a class="mainNanLink" href="JavaScript:void(0)">Committee &nbsp; <i
                            class="fal fa-angle-down"></i></a>
                    <ul class="dropdownItem list-unstyled" id="1">
                        <li><a class="mainNanLink" href="{{ url('Committee') }}">Central Committee</a></li>
                        <li><a class="mainNanLink" href="{{ route('golobalCommitteelist') }}">Global Committee</a>
                        </li>
                        <li><a class="mainNanLink" href="{{ route('ExCommitteelist') }}">Ex. Committee</a></li>
                    </ul>
                </li>
                <li><a class="mainNanLink" href="{{ url('gallery') }}">Gallery</a></li>
                <li class="dropdown" onclick="submenuShow(2)">
                    <a class="mainNanLink" href="JavaScript:void(0)">Member &nbsp; <i
                            class="fal fa-angle-down"></i></a>
                    <ul class="dropdownItem list-unstyled" id="2">
                        <li><a class="mainNanLink" href="{{ url('member') }}">Member</a></li>
                        <li><a class="mainNanLink" href="{{ url('register-gide') }}">Registration Guideline</a>
                        </li>
                        <li><a class="mainNanLink" href="{{ url('register') }}">Registration</a></li>
                    </ul>
                </li>
                <li><a class="mainNanLink" href="{{ url('blogs') }}">News Room</a></li>
                <li><a class="mainNanLink" href="{{ url('contact') }}">Contact</a></li>



            </ul>


            {{-- <form action="" class="d-flex w-100 search_form">
                        <input type="text" id="search" class="w-90" name="search" />
                        <button type="submit" class="search_btn">
                            <i class="fal fa-search"></i>
                        </button>
                    </form> --}}


            <a href="{{ url('register') }}" class="memberReBtn">Apply For Membership</a>
            <a href="{{ url('blood') }}" class="memberReBtn blood"
                style="background: white;color: #d02804;border: 4px solid red;"><i class="fas fa-tint fa-fw"></i>click
                for Blood</a>

        </nav>
    </div>
</div>
</header>



@yield('content')

<x-footer />
