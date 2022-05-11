<!doctype html>
<html class="no-js" lang="zxx">
   
<head>
      <meta charset="utf-8">
      <meta http-equiv="x-ua-compatible" content="ie=edge">
      <title>Bidme Indonesia</title>
      <meta name="description" content="">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- Place favicon.ico in the root directory -->
      <link rel="shortcut icon" type="image/x-icon" href="{{ asset('frontend/img/favicon.png') }}">
      <!-- CSS here -->
      <link rel="stylesheet" href="{{ asset('frontend/css/preloader.css') }}">
      <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">
      <link rel="stylesheet" href="{{ asset('frontend/css/meanmenu.css') }}">
      <link rel="stylesheet" href="{{ asset('frontend/css/animate.min.css') }}">
      <link rel="stylesheet" href="{{ asset('frontend/css/owl.carousel.min.css') }}">
      <link rel="stylesheet" href="{{ asset('frontend/css/backToTop.css') }}">
      <link rel="stylesheet" href="{{ asset('frontend/css/jquery.fancybox.min.css') }}">
      <link rel="stylesheet" href="{{ asset('frontend/css/fontAwesome5Pro.css') }}">
      <link rel="stylesheet" href="{{ asset('frontend/css/elegantFont.css') }}">
      <link rel="stylesheet" href="{{ asset('frontend/css/default.css') }}">
      <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
   </head>
   <body>
      <!--[if lte IE 9]>
      <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
      <![endif]-->
      
      <!-- Add your site or application content here -->  

      <!-- pre loader area start -->
      <div id="loading">
         <div id="loading-center">
            <div id="loading-center-absolute">
               <div class="object" id="object_one"></div>
               <div class="object" id="object_two" style="left:20px;"></div>
               <div class="object" id="object_three" style="left:40px;"></div>
               <div class="object" id="object_four" style="left:60px;"></div>
               <div class="object" id="object_five" style="left:80px;"></div>
            </div>
         </div>  
      </div>
      <!-- pre loader area end -->

      <!-- back to top start -->
      <div class="progress-wrap">
         <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
         </svg>
      </div>
      <!-- back to top end -->

      <!-- header area start -->
      @include('partials.frontend.header')
      <!-- header area end -->

      <!-- sidebar area start -->
      <div class="sidebar__area">
         <div class="sidebar__wrapper">
            <div class="sidebar__close">
               <button class="sidebar__close-btn" id="sidebar__close-btn">
               <span><i class="fal fa-times"></i></span>
               <span>close</span>
               </button>
            </div>
            <div class="sidebar__content">
               <div class="logo mb-40">
                  <a href="index.html">
                  <img src="{{ asset('frontend/img/logo/logo.png') }}" alt="logo">
                  </a>
               </div>
               <div class="mobile-menu mobile-menu-3"></div>
               <div class="sidebar__info mt-350">
                  <a href="#" class="w-btn w-btn-blue-2 w-btn-4 d-block mb-15 mt-15">login</a>
                  <a href="#" class="w-btn w-btn-blue d-block">sign up</a>
               </div>
            </div>
         </div>
      </div>
      <!-- sidebar area end -->      
      <div class="body-overlay"></div>
      <!-- sidebar area end -->

      {{-- <main> --}}
         @yield('content')

      <!-- footer area start -->
      @include('partials.frontend.footer')
      <!-- footer area end -->

      <!-- JS here -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.2.2/lazysizes.min.js" async=""></script>
      <script src="{{ asset('frontend/js/vendor/jquery-3.5.1.min.js') }}"></script>
      <script src="{{ asset('frontend/js/vendor/waypoints.min.js') }}"></script>
      <script src="{{ asset('frontend/js/bootstrap.bundle.min.js') }}"></script>
      <script src="{{ asset('frontend/js/jquery.meanmenu.js') }}"></script>
      <script src="{{ asset('frontend/js/owl.carousel.min.js') }}"></script>
      <script src="{{ asset('frontend/js/jquery.fancybox.min.js') }}"></script>
      <script src="{{ asset('frontend/js/isotope.pkgd.min.js') }}"></script>
      <script src="{{ asset('frontend/js/parallax.min.js') }}"></script>
      <script src="{{ asset('frontend/js/backToTop.js') }}"></script>
      <script src="{{ asset('frontend/js/jquery.counterup.min.js') }}"></script>
      <script src="{{ asset('frontend/js/ajax-form.js') }}"></script>
      <script src="{{ asset('frontend/js/wow.min.js') }}"></script>
      <script src="{{ asset('frontend/js/imagesloaded.pkgd.min.js') }}"></script>
      <script src="{{ asset('frontend/js/main.js') }}"></script>
   </body>

</html>

