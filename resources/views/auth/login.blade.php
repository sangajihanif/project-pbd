 @extends('layouts.auth.login')

 @section('content')
     <div id="root" class="h-100">
         <!-- Background Start -->
         <div class="fixed-background"></div>
         <!-- Background End -->

         @if ($errors->any())
             @foreach ($errors->all() as $error)
                 <div class="alert alert-danger alert-dismissible fade show" role="alert">{{ $error }}<button
                         type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button></div>
             @endforeach
         @endif
         @if (session('error'))
             <div class="alert alert-danger alert-dismissible fade show" role="alert">
                 {{ session('error') }}
                 <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
             </div>
         @endif
         @if (session('success'))
             <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button
                     type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button></div>
         @endif
         @if (session('warning'))
             <div class="alert alert-warning alert-dismissible fade show" role="alert">{{ session('warning') }}<button
                     type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button></div>
         @endif

         <div class="container-fluid p-0 h-100 position-relative">
             <div class="row g-0 h-100">
                 <!-- Left Side Start -->
                 <div class="offset-0 col-12 d-none d-lg-flex offset-md-1 col-lg h-lg-100">
                     <div class="min-h-100 d-flex align-items-center">
                         <div class="w-100 w-lg-85 w-xxl-75">
                             <div>
                                 <div class="mb-4">
                                     <h2 class="text-white fw-bold">The Demon of Razgriz</h2>
                                 </div>
                                 <p class="text-white lh-lg mb-4 text-start" style="font-size: 0.95rem; opacity: 0.9;">
                                     When history witnesses a great change, Razgriz reveals itself,<br>
                                     first as a dark demon.<br>
                                     As a demon, it uses its power to rain death upon the land,<br>
                                     and then it dies.<br><br>
                                     However, after a period of slumber, Razgriz returns,<br>
                                     Its raven wings ablaze in majestic light.<br><br>
                                     <em>"From the depths of despair awaken the Razgriz,<br>
                                         The Demon from the North Sea."</em>
                                 </p>
                                 <div class="mb-4">
                                     <a class="btn btn-sm btn-outline-white" href="#">Ace Combat Lore</a>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
                 <!-- Left Side End -->

                 <!-- Right Side Start -->
                 <div class="col-12 col-lg-auto h-100 pb-4 px-4 pt-0 p-lg-0">
                     <div
                         class="sw-lg-70 min-h-100 bg-foreground d-flex justify-content-center align-items-center shadow-deep py-5 full-page-content-right-border">
                         <div class="sw-lg-50 px-5">
                             <div class="sh-11">
                                 <a href="index.html">

                                 </a>
                             </div>
                             <div class="mb-5">
                                 <h2 class="cta-1 mb-0 text-primary">Welcome,</h2>
                                 <h2 class="cta-1 text-primary">let's get started!</h2>
                             </div>
                             <div class="mb-5">
                                 <p class="h6">Please use your credentials to login.</p>
                                 <p class="h6">
                                     If you are not a member, please
                                     <a href="/register">register</a>
                                     .
                                 </p>
                             </div>
                             <div>
                                 <form action="{{ route('login.store') }}" method="POST" enctype="multipart/form-data"
                                     class="tooltip-end-bottom" novalidate>
                                     <div class="mb-3 filled form-group tooltip-end-top">
                                         <i data-acorn-icon="email"></i>
                                         <input class="form-control" placeholder="Email" name="email"
                                             value="{{ old('email') }}" />
                                     </div>
                                     <div class="mb-3 filled form-group tooltip-end-top">
                                         <i data-acorn-icon="lock-off"></i>
                                         <input class="form-control pe-7" name="password" type="password"
                                             placeholder="Password" />
                                         <a class="text-small position-absolute t-3 e-3"
                                             href="Pages.Authentication.ForgotPassword.html">Forgot?</a>
                                     </div>
                                     <button type="submit" class="btn btn-lg btn-primary">Login</button>
                                 </form>
                             </div>
                         </div>
                     </div>
                 </div>
                 <!-- Right Side End -->
             </div>
         </div>
     </div>

 @endsection
