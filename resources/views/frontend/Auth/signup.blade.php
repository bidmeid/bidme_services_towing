@extends('layouts.frontend.app')
@section('content')
<main>

    <!-- sign up area start -->
    <section class="signup__area po-rel-z1 pt-100 pb-145">
       <div class="sign__shape">
          <img class="lazyload man-1" data-src="{{ asset('frontend/img/icon/sign/man-3.png') }}" alt="">
          <img class="lazyload man-2 man-22" data-src="{{ asset('frontend/img/icon/sign/man-2.png') }}" alt="">
          <img class="lazyload circle" data-src="{{ asset('frontend/img/icon/sign/circle.png') }}" alt="">
          <img class="lazyload zigzag" data-src="{{ asset('frontend/img/icon/sign/zigzag.png') }}" alt="">
          <img class="lazyload dot" data-src="{{ asset('frontend/img/icon/sign/dot.png') }}" alt="">
          <img class="lazyload bg" data-src="{{ asset('frontend/img/icon/sign/sign-up.png') }}" alt="">
          <img class="lazyload flower" data-src="{{ asset('frontend/img/icon/sign/flower.png') }}" alt="">
       </div>
       <div class="container">
          <div class="row">
             <div class="col-xxl-8 offset-xxl-2 col-xl-8 offset-xl-2">
                <div class="page__title-wrapper text-center mb-55">
                   <h2 class="page__title-2">Buat akun gratis</h2>
                   <p>Buat akun Bidme dengan beberapa langkah dibawah ini.</p>
                </div>
             </div>
          </div>
          <div class="row">
             <div class="col-xxl-6 offset-xxl-3 col-xl-6 offset-xl-3 col-lg-8 offset-lg-2">
                <div class="sign__wrapper white-bg">
                   <div class="sign__header mb-35">
                      <div class="sign__in text-center">
                         <a href="#" class="sign__social text-start mb-15"><i class="fab fa-facebook-f"></i>Daftar dengan Facebook</a>
                         <p> <span>........</span> Atau, daftar dengan email<span> ........</span> </p>
                      </div>
                   </div>
                   <div class="sign__form">
                      <form id="FormSignup">
                         <div class="sign__input-wrapper mb-25">
                            <h5>Nama Lengkap</h5>
                            <div class="sign__input">
                               <input type="text" name="name" placeholder="Nama Lengkap" autocomplete="off">
                               <i class="fal fa-user"></i>
                            </div>
                         </div>
                         <div class="sign__input-wrapper mb-25">
                            <h5>Email</h5>
                            <div class="sign__input">
                               <input type="text" name="email" placeholder="Email" autocomplete="off">
                               <i class="fal fa-envelope"></i>
                            </div>
                         </div>
                         <div class="sign__input-wrapper mb-25">
                            <h5>No Telpon</h5>
                            <div class="sign__input">
                               <input type="text" name="no_telp" placeholder="No Telpon" autocomplete="off">
                               <i class="fal fa-user"></i>
                            </div>
                         </div>
                         <div class="sign__input-wrapper mb-25">
                            <h5>Password</h5>
                            <div class="sign__input">
                               <input type="text" name="password" placeholder="Password" >
                               <i class="fal fa-lock"></i>
                            </div>
                         </div>
                         <div class="sign__input-wrapper mb-10">
                            <h5>Konfirmasi Password</h5>
                            <div class="sign__input">
                               <input type="text"name="password_confirmation" placeholder="Konfirmasi Password">
                               <i class="fal fa-lock"></i>
                            </div>
                         </div>
                         <div class="sign__action d-flex justify-content-between mb-30">
                            <div class="sign__agree d-flex align-items-center">
                               <input class="m-check-input" type="checkbox" id="m-agree">
                               <label class="m-check-label" for="m-agree">Saya setuju dengan <a href="#">syarat & ketentuan</a>
                                  </label>
                            </div>
                         </div>
                         <button type="submit" class="w-btn w-btn-11 w-100"> <span></span> Daftar</button>
                         <div class="sign__new text-center mt-20">
                            <p>Sudah punya akun ? <a href="{{ route('login') }}"> Masuk</a></p>
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

 <script src="{{ asset('frontend/js/vendor/jquery-3.5.1.min.js') }}"></script>
 <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
 <script>
    $("#FormSignup").submit(function(event){
       event.preventDefault();
       var form = $(this)[0];
       var data = new FormData(form);

       $.ajax({
            data: data,
            url: '{{ route('api.signup') }}',
            method: 'POST',
            processData: false,
            contentType: false,
            cache: false,

            complete: function(response){                
               if(response.status == 201){
                  swal("Selamat", "Akun Anda berhasil dibuat", "success")
                  .then(() => {
                     window.location.replace('{{ route('login') }}');
                  });	 
                     }else{
                        swal("Gagal", "Akun Anda gagal dibuat", "warning");
                     }
                  },
               dataType:'json'
       });

    });
 </script>
@endsection