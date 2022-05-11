@extends('layouts.frontend.app')
@section('content')
<main>

    <!-- sign up area start -->
    <section class="signup__area po-rel-z1 pt-100 pb-145">
       <div class="sign__shape">
          <img class="man-1" src="{{ asset('frontend/img/icon/sign/man-1.png') }}" alt="">
          <img class="man-2" src="{{ asset('frontend/img/icon/sign/man-2.png') }}" alt="">
          <img class="circle" src="{{ asset('frontend/img/icon/sign/circle.png') }}" alt="">
          <img class="zigzag" src="{{ asset('frontend/img/icon/sign/zigzag.png') }}" alt="">
          <img class="dot" src="{{ asset('frontend/img/icon/sign/dot.png') }}" alt="">
          <img class="bg" src="{{ asset('frontend/img/icon/sign/sign-up.png') }}" alt="">
       </div>
       <div class="container">
          <div class="row">
             <div class="col-xxl-8 offset-xxl-2 col-xl-8 offset-xl-2">
                <div class="page__title-wrapper text-center mb-55">
                   <h2 class="page__title-2">Masuk Aplikasi</h2>
                   <p>Silahkan login ke Aplikasi dengan Email dan Password Anda</p>
                </div>
             </div>
          </div>
          <div class="row">
             <div class="col-xxl-6 offset-xxl-3 col-xl-6 offset-xl-3 col-lg-8 offset-lg-2">
                <div class="sign__wrapper white-bg">
                   <div class="sign__header mb-35">
                      <div class="sign__in text-center">
                         <a href="#" class="sign__social text-start mb-15"><i class="fab fa-facebook-f"></i>Masuk dengan Facebook</a>
                         <p> <span>........</span> Atau, <a href="sign-in.html">Masuk </a> Dengan email<span> ........</span> </p>
                      </div>
                   </div>
                   <div class="sign__form">
                      <form action="#">
                         <div class="sign__input-wrapper mb-25">
                            <h5>Email</h5>
                            <div class="sign__input">
                               <input type="text" placeholder="email">
                               <i class="fal fa-envelope"></i>
                            </div>
                         </div>
                         <div class="sign__input-wrapper mb-10">
                            <h5>Password</h5>
                            <div class="sign__input">
                               <input type="text" placeholder="Password">
                               <i class="fal fa-lock"></i>
                            </div>
                         </div>
                         {{-- <div class="sign__action d-sm-flex justify-content-between mb-30">
                            <div class="sign__agree d-flex align-items-center">
                               <input class="m-check-input" type="checkbox" id="m-agree">
                               <label class="m-check-label" for="m-agree">Keep me signed in
                                  </label>
                            </div>
                            <div class="sign__forgot">
                               <a href="#">Forgot your password?</a>
                            </div>
                         </div> --}}
                         <button class="w-btn w-btn-11 w-100 mt-3"> <span></span> Masuk</button>
                         <div class="sign__new text-center mt-20">
                            <p>Baru tau Bidme ? <a href="{{ route('signup') }}">Daftar</a></p>
                         </div>
                      </form>
                   </div>
                </div>
             </div>
          </div>
       </div>
    </section>
    <!-- sign up area end -->
 </main>
@endsection