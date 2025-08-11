<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Login/Register | Edurock - Education LMS Template</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
    <!-- Place favicon.ico in the root directory -->

     <!-- CSS here -->
     <link rel="stylesheet" href="css/bootstrap.min.css">
     <link rel="stylesheet" href="css/animate.min.css">
     <link rel="stylesheet" href="css/aos.min.css">
     <link rel="stylesheet" href="css/magnific-popup.css">
     <link rel="stylesheet" href="css/icofont.min.css">
     <link rel="stylesheet" href="css/slick.css">
     <link rel="stylesheet" href="css/swiper-bundle.min.css">
     <link rel="stylesheet" href="css/style.css">
 
 
     <script>
         // On page load or when changing themes, best to add inline in `head` to avoid FOUC
         if (localStorage.getItem("theme-color") === "dark" || (!("theme-color" in localStorage) && window.matchMedia("(prefers-color-scheme: dark)").matches)) {
           document.documentElement.classList.add("is_dark");
         } 
         if (localStorage.getItem("theme-color") === "light") {
           document.documentElement.classList.remove("is_dark");
         } 
     </script>
 
 </head>
 
 
 <body class="body__wrapper">
     <!-- pre loader area start -->
     <div id="back__preloader">
         <div id="back__circle_loader"></div>
             <div class="back__loader_logo">
                 <img loading="lazy"  src="img/pre.png" alt="Preload">
             </div>
         </div>
     </div>
     <!-- pre loader area end -->
 
     <!-- Dark/Light area start -->
     <div class="mode_switcher my_switcher">
         <button id="light--to-dark-button" class="light align-items-center">
             <svg xmlns="http://www.w3.org/2000/svg" class="ionicon dark__mode" viewBox="0 0 512 512"><path d="M160 136c0-30.62 4.51-61.61 16-88C99.57 81.27 48 159.32 48 248c0 119.29 96.71 216 216 216 88.68 0 166.73-51.57 200-128-26.39 11.49-57.38 16-88 16-119.29 0-216-96.71-216-216z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/></svg>
 
             <svg xmlns="http://www.w3.org/2000/svg" class="ionicon light__mode" viewBox="0 0 512 512"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M256 48v48M256 416v48M403.08 108.92l-33.94 33.94M142.86 369.14l-33.94 33.94M464 256h-48M96 256H48M403.08 403.08l-33.94-33.94M142.86 142.86l-33.94-33.94"/><circle cx="256" cy="256" r="80" fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32"/></svg>
 
             <span class="light__mode">Light</span>
             <span class="dark__mode">Dark</span>
         </button>
     </div>
     <!-- Dark/Light area end -->

    <main class="main_wrapper overflow-hidden">


        <!-- login__section__start -->
        <div class="loginarea sp_top_100 sp_bottom_100">
            <div class="container">
                <div class="row">
                        <div class="tab-pane fade active show" id="projects__one" role="tabpanel" aria-labelledby="projects__one">
                            <div class="col-xl-8 col-md-8 offset-md-2">
                                <div class="loginarea__wraper">
                                    <div class="login__heading">
                                        <h5 class="login__title">Login</h5>
                                    </div>
                                    <form action="{{ route('admin.login.submit') }}" method="POST">
                                        @csrf
                                        <div class="login__form">
                                            <label class="form__label">Username or email</label>
                                            <input name="email" class="common__login__input" type="email" placeholder="Your username or email" required>
                                        </div>
                                        <div class="login__form">
                                            <label class="form__label">Password</label>
                                            <input name="password" class="common__login__input" type="password" placeholder="Password" required>
                                        </div>
                                        <div class="login__form d-flex justify-content-between flex-wrap gap-2">
                                            <div class="form__check">
                                                <input id="forgot" type="checkbox" name="remember">
                                                <label for="forgot"> Remember me</label>
                                            </div>
                                        </div>
                                        <div class="login__button">
                                            <button type="submit" class="default__button">Log In</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class=" login__shape__img educationarea__shape_image">
                    <img loading="lazy"  class="hero__shape hero__shape__1" src="img/education/hero_shape2.png" alt="Shape">
                    <img loading="lazy"  class="hero__shape hero__shape__2" src="img/education/hero_shape3.png" alt="Shape">
                    <img loading="lazy"  class="hero__shape hero__shape__3" src="img/education/hero_shape4.png" alt="Shape">
                    <img loading="lazy"  class="hero__shape hero__shape__4" src="img/education/hero_shape5.png" alt="Shape">
                </div>


            </div>
        </div>

        <!-- login__section__end -->

    </main>





    <!-- JS here -->
    <script src="js/vendor/modernizr-3.5.0.min.js"></script>
    <script src="js/vendor/jquery-3.6.0.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/isotope.pkgd.min.js"></script>
    <script src="js/slick.min.js"></script>
    <script src="js/jquery.meanmenu.min.js"></script>
    <script src="js/ajax-form.js"></script>
    <script src="js/wow.min.js"></script>
    <script src="js/jquery.scrollUp.min.js"></script>
    <script src="js/imagesloaded.pkgd.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/waypoints.min.js"></script>
    <script src="js/jquery.counterup.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/swiper-bundle.min.js"></script>
    <script src="js/main.js"></script>

    <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem("theme-color") === "dark" || (!("theme-color" in localStorage) && window.matchMedia("(prefers-color-scheme: dark)").matches)) {
          document.getElementById("light--to-dark-button")?.classList.add("dark--mode");
        } 
        if (localStorage.getItem("theme-color") === "light") {
          document.getElementById("light--to-dark-button")?.classList.remove("dark--mode");
        } 
      </script>


</body>

</html>