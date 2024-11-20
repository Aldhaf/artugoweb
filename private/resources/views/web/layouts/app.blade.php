<?php use App\Http\Controllers\HomeController; ?>
<?php use App\Http\Middleware\XSS; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>@yield('title') - {{ config('app.name') }}</title>
    <meta name="description" content="@yield('meta-description','ARTUGO')" />
    <meta name="author" content="">
    <meta name="facebook-domain-verification" content="90dsj4280gxoosis2vooej9io9pa65" />
    <link href="https://fonts.googleapis.com/css?family=Arimo:400,400i,700,700i&amp;subset=cyrillic,cyrillic-ext,greek,greek-ext,hebrew,latin-ext,vietnamese" rel="stylesheet">
    <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- <script src="{{ url('') }}/assets/backend/plugins/jQuery/jQuery-2.1.3.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.js"></script>
    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

    <link rel="shortcut icon" href="{{ asset('assets/img/icon-Artugo_Circle.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/datepicker/datepicker3.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/font-awesome/css/all.min.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/font-awesome/css/fontawesome.min.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/hamburgers/hamburgers.min.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/hamburgers/hamburgers.min.css') }}" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <!-- <link rel="stylesheet" href="{{ asset('assets/plugins/select2/select2.min.css') }}" type="text/css" /> -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/slick/slick.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/slick/slick-theme.css') }}" type="text/css" />

    <!--  js -->
    <!-- <script src="{{asset('assets/js/jquery.js') }}"></script> -->

    <script src="https://code.jquery.com/ui/1.11.2/jquery-ui.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('assets/plugins/jquery/jquery-3.5.1.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/plugins/font-awesome/js/all.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <!-- <script type="text/javascript" src="{{ asset('assets/plugins/select2/select2.min.js')}}"></script> -->
    <script type="text/javascript" src="{{ asset('assets/plugins/slick/slick.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/plugins/tinymce/tinymce.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
    {{-- <script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script> --}}
    <script defer src="https://friconix.com/cdn/friconix.js"> </script>

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?t=<?php echo time(); ?>" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}?t=<?php echo time(); ?>" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/responsive-button-slideshow.css') }}?t=<?php echo time(); ?>" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/responsive-feature-page.css') }}?t=<?php echo time(); ?>" type="text/css" />
    
    <!-- Script -->
    <script src="{{ url('') }}/assets/backend/js/script.js?v={{env('SCRIPTJS_VERSION')}}" type="text/javascript"></script>

    <style>
        .footer-social {
            padding: 5px 5px;
            width: 40px;
            text-align: center;
            background: rgba(255, 255, 255, 0);
            color: #000;
            border-radius: 4px;
            border: 2px solid #000;
            margin-right: 5px;
            display: inline-block;
            margin-bottom: 10px;
        }

        .footer-social:hover {
            background: rgba(255, 255, 255, 1);
            color: #000;
        }

        /* .sidebar-menu ul.sub-nav {
            display: none;
        } */

        .sidebar-menu ul.visible {
            display: block;
        }

        /* .mega-menu{
            display:flex;
            justify-content: space-between;
            position:absolute;
            left:0;
            min-width:100%;
            background: #efefef;
            opacity: 0;
            visibility: hidden;
        } */
        /* .mega-menu .col ul{
            margin:0;
            padding:5;
        } */
    </style>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <!-- <script async src="https://www.googletagmanager.com/gtag/js?id=UA-175227054-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-175227054-1');
    </script> -->
    
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <!-- <script async src="https://www.googletagmanager.com/gtag/js?id=G-PYK4LKWS6M"></script> -->

    @if(env('GTM_ID'))
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo env('GTM_ID'); ?>">
    window.dataLayer = window.dataLayer || [];
    function gtag(){
        dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', '<?php echo env('GTM_ID') ?>');
    </script>
    @endif

    <script>
    function categoryNavigation(el) {
        if (window.location.href.split("#")[0] !== el.href.split("#")[0]) {
            window.location.replace(el.href);
        } else {
            // if (window.location.href.split("#")[1] !== el.href.split("#")[1]) {
            //     showCategory(el.href.split("#")[1]);
            //     setTimeout(() => {
            //         document.location.href = el.href;
            //     }, 250);
            // }
        }
    }
    </script>

    <!-- // function showCategory (product_highlight_href) {
    //     $(".product-category").addClass("d-none");
    //     $("." + product_highlight_href).removeClass("d-none");
    //     // $("#" + product_highlight_href).focus();
    //     $(document).scrollTop();
    // }

    // function onLoadPage() {
    //     if (window.location.pathname.includes("/products/category/")) {
    //         showCategory(window.location.href.split("#")[1]);
    //     }
    // }
    // window.addEventListener("load", onLoadPage); -->

    </script>

    <!-- Facebook Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '2713137342259809');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=2713137342259809&ev=PageView&noscript=1" /></noscript>
    <!-- End Facebook Pixel Code -->


    <!-- <script>
		console.log("ADD widget.js SCRIPT");
		(function (d, t) {
			var BASE_URL = "https://omni.galan.id/widget";
			//var BASE_URL = "https://103.140.54.122/widget";
			var s = d.createElement(t);
			var b = d.body;
			s.src = BASE_URL + "/app.js";
			s.defer = true;
			s.async = true;
			b.appendChild(s);
			s.onload = function () {
				console.log("START CREATING WIDGET");
				createWidget({
					ic_base_url: BASE_URL,
					ic_widget_server: 'omni.galan.id',
					ic_widget_port: 23510,
					ic_asterisk_server: 'omni.galan.id',
					ic_asterisk_port: 8089
				});
			}
		})(document, "script");
	</script>
    <script src="https://omni.galan.id/widget/app.js" defer="" async=""></script>
    <script src="https://omni.galan.id/widget/SIPml-api.js" defer="" async=""></script>
    <script src="https://omni.galan.id/widget/phone.js" defer="" async=""></script>
    <style type="text/css">.button {z-index: 999999999999; border: none;border-radius: 30px;color: white;background-color: gray;padding: 15px;text-align: center;text-decoration: none;font-size: 16px;margin: 4px;cursor: pointer;position: fixed;bottom: 10px;left: 10px;}</style>
    <button id="btnCall" type="button" class="button" value="free" style="background-color: gray;"><img id="imgCall" src="https://omni.galan.id/widget/images/call.png"></button><audio id="audio_remote" autoplay=""></audio>
    <audio id="ringtone" src="https://omni.galan.id/widget/sounds/ringtone.wav" loop=""></audio><audio id="ringbacktone" src="https://omni.galan.id/widget/sounds/ringbacktone.wav" loop=""></audio><audio id="dtmfTone" src="https://omni.galan.id/widget/sounds/dtmf.wav"></audio> -->

</head>

<?php

    // $category_parents = DB::table("ms_categories")
    // ->select(DB::raw("name, slug, IFNULL(icon, '') AS icon, CONCAT('products/category/', slug) AS href"))
    // ->where("active", 1)
    // ->where("parent_id", 0)
    // ->get();

    // function getSubCategory($slug, $flag) {

    //     $where = "";
    //     if ($flag == "footer_nav") {
    //         $where = " AND mc.footer_nav = 1 AND sub_mc.footer_nav = 1";
    //     } else if ($flag == "highlight_nav") {
    //         $where = " AND mc.highlight_nav = 1 AND sub_mc.highlight_nav = 1";
    //     } else if ($flag == "mega_menu") {
            
    //     }

    //     $cool_product = DB::table("ms_categories AS mc")
    //     ->select(DB::raw("sub_mc.name, IFNULL(sub_mc.icon, '') AS icon, CONCAT('products/category/{$slug}/', sub_mc.slug) AS href"))
    //     ->leftJoin("ms_categories AS sub_mc", "sub_mc.parent_id", "=", "mc.category_id")
    //     ->where("mc.slug", $slug)
    //     ->where("mc.active", 1)
    //     ->where("sub_mc.active", 1)
    //     ->whereRaw("IFNULL(sub_mc.name,'') <> '' " . $where)
    //     // ->where("mc.footer_nav", 1)
    //     // ->where("sub_mc.footer_nav", 1)
    //     ->orderBy("sub_mc.ordering")
    //     ->get();

    //     return $cool_product;
    // }
?>

<body>

    <div class="navigation">
        <div class="nav-content">
            <div class="logo-container">
                <a href="{{ url('') }}" class="logo">
                    <img src="{{ url('assets/img/artugo-logo-white.png') }}">
                </a>
            </div>
            <div class="menu-container">
                <ul class="menu">
                    <li>
                        <a href="{{ url('') }}" class="menu-item <?php if (Request::segment(1) == "") echo "active"; ?>">
                            <div>Home</div>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="">
                            <div>Products</div>
                        </a>
                        <div class="mega-menu">
                            <?php $parent_index = 0; ?>
                            @foreach(HomeController::getCategoryParent() as $cat_parent)
                            @if($parent_index > 0)
                            <div class="col_mega_divider" style="background: gray;margin-top: 32px;margin-bottom: 32px;width: 2px;border-radius: 2px;">
                            </div>
                            @endif
                            <div class="col_mega">
                                <ul>
                                    <li>
                                        <center>
                                            <!-- <a href="{{ url($cat_parent->href) }}"> -->
                                            <a>
                                                <div style="width: 200px;height:170px;">
                                                    <img style="width: 80%;" src="{{ url($cat_parent->icon) }}" alt="">
                                                </div>
                                                <br>
                                                {{$cat_parent->name}} Product
                                            </a>
                                        </center>
                                        <div class="sub-menu">
                                            <div class="kolum">
                                                <center style="margin-top: 32px;border-top: solid 2px gray;">
                                                <ul>
                                                    <?php $sub_index = 0; ?>
                                                    @foreach(HomeController::getSubCategory($cat_parent->slug, "menu") as $val)
                                                    <li>
                                                        <center style="display:flex;">
                                                            @if($sub_index == 0)
                                                            <div class="col_mega_divider" style="width: 1px;margin-top: 0px; margin-bottom: 64px;border-radius: 1px;"></div>
                                                            @else
                                                            <div class="col_mega_divider" style="background: gray;width: 2px;margin-top: 0px; margin-bottom: 64px;border-radius: 1px;"></div>
                                                            @endif
                                                            <a href="{{ url($val->href) }}" style="height: 190px;">
                                                                <div style="width: 150px;height:150px;">
                                                                    <img style="width: 80%;" src="{{ url($val->icon) }}" alt="">
                                                                </div>
                                                                <br>
                                                                {{$val->name}}
                                                            </a>
                                                        </center>
                                                    </li>
                                                    <?php $sub_index++; ?>
                                                    @endforeach
                                                </ul>
                                                </center>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <?php $parent_index++; ?>
                            @endforeach
                        </div>
                    </li>
                    <!-- <li class="dropdown-menus">
                        <a href="#" class="menu-item <?php if (Request::segment(1) == "products") echo "active"; ?>">
                            <div>Products</div>
                        </a>
                        <a href="#" class="menu-item <?php if (Request::segment(1) == "products") echo "active"; ?>">
                            <div>Products</div>
                        </a>
                        <ul class="dropdown-menus-item">
                            <li>
                                <a href="{{ url('features') }}" class="menu-item" style="color:#fff">Features</a>
                            </li>
                        </ul>
                    </li> -->
                    <!-- <li class="dropdown-megamenu">
                        <a href="#" class="dropbtn">
                            <div>Products</div>
                        </a>
                        <ul class="dropdown-content-megamenu">
                            <li class="row">
                                <span class="column">
                                    <h3>Hot Product</h3>
                                    <a href="#">satu</a>
                                </span>
                                <span class="column">
                                    <h3>Cool Product</h3>
                                    <a href="#">satu</a>
                                </span>
                                <span class="column">
                                    <h3>Hot Product</h3>
                                    <a href="#">satu</a>
                                </span>
                                <span class="column">
                                    <h3>Cool Product</h3>
                                    <a href="#">satu</a>
                                </span>
                            </li>
                        </ul>
                    </li> -->
                    <li>
                        <a href="{{ url('warranty') }}" class="menu-item <?php if (Request::segment(1) == "warranty") echo "active"; ?>">
                            <div>Warranty</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('service') }}" class="menu-item <?php if (Request::segment(1) == "service") echo "active"; ?>">
                            <div>Service</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('article') }}" class="menu-item <?php if (Request::segment(1) == "article") echo "active"; ?>">
                            <div>Article</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('career') }}" class="menu-item <?php if (Request::segment(1) == "career") echo "active"; ?>">
                            <div>Career</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('about') }}" class="menu-item-last <?php if (Request::segment(1) == "about") echo "active"; ?>">
                            <div>About</div>
                        </a>
                    </li>
                    <li class="ml-0">
                        <?php if (Session::has('member_id')) : ?>
                            <a href="{{ url('member/dashboard') }}" class="menu-icon">
                                <div><i class="fa fa-user"></i></div>
                            </a>
                        <?php else : ?>
                            <a href="{{ url('member/login') }}" class="menu-icon" style="background-color: #fff;">
                                <div><i class="fa fa-user" style="color:#202123;font-size: 20px;"></i></div>
                            </a>
                        <?php endif; ?>
                    </li>
                    <li class="search-container">
                        <a href="javascript:void(0);" class="menu-icon" onclick="open_search()">
                            <div><i class="fa fa-search"></i></div>
                        </a>
                        <div class="search-box">
                            <form action="{{ url('search') }}" method="GET">
                                <input type="text" class="search-input" name="keywords" placeholder="Search..." value="{{ isset($keywords) ? $keywords : '' }}">
                                <button class="btn-search"><i class="fa fa-search"></i></button>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="mobile-menu">
                <?php if (Session::has('member_id')) : ?>
                    <a href="{{ url('member/dashboard') }}" class="mobile-menu-item">
                        <div><i class="fa fa-user"></i></div>
                    </a>
                <?php else : ?>
                    <a href="{{ url('member/login') }}" class="mobile-menu-item">
                        <div><i class="fa fa-user"></i></div>
                    </a>
                <?php endif; ?>
                <a href="javascript:void(0);" class="mobile-menu-item" onclick="open_search_mobile()">
                    <div><i class="fa fa-search"></i></div>
                </a>
                <span class="mobile-menu-item d-flex my-0 h-100">
                    <button class="hamburger hamburger--squeeze" type="button" id="pull">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </span>
            </div>
            <div class="search-box-mobile">
                <form action="{{ url('search') }}" method="GET">
                    <input type="text" class="search-input-mobile" name="keywords" placeholder="Search..." value="{{ isset($keywords) ? $keywords : '' }}">
                    <button class="btn-search-mobile"><i class="fa fa-search"></i></button>
                </form>
            </div>
        </div>
    </div>

    <div class="sidebar-overlay">
    </div>

    <div class="sidebar">
        <ul class="sidebar-menu">
            <li>
                <a href="{{ url('') }}" class="menu-item">
                    <div>Home</div>
                </a>
            </li>
            <li class="parent">
                <a href="#" class="menu-item product"> Produk </a>
                <ul id="product_menu" class="nav sub-nav bg-light" style="visibility: hidden; height: 0px; max-height: 0px; border-top: 1px solid #585858;">
                    @foreach(HomeController::getCategoryParent() as $cat_parent)
                    <li>
                        <!-- <a class="menu-item-parent d-flex flex-column align-items-center text-dark accordion-heading collapsed" data-toggle="collapse" data-target="#sub_{{$cat_parent->slug}}_container"> -->
                        <a id="menu-item-parent-{{$cat_parent->slug}}" class="menu-item-parent d-flex flex-column align-items-center text-dark" onclick="showHideSideProdCat('{{$cat_parent->slug}}')">
                            <div style="width: 80px; height: 70px;">
                                <img src="{{ url($cat_parent->icon) }}" alt="">
                            </div>
                            {{$cat_parent->name}} Product
                        </a>
                    </li>
                    @endforeach
                </ul>
            </li>
            <li>
                <a href="{{ url('warranty') }}" class="menu-item">
                    <div>Warranty</div>
                </a>
            </li>
            <li>
                <a href="{{ url('service') }}" class="menu-item">
                    <div>Service</div>
                </a>
            </li>
            <li>
                <a href="{{ url('article') }}" class="menu-item">
                    <div>Article</div>
                </a>
            </li>
            <!-- <li>
                <a href="{{ url('Gallery') }}" class="menu-item">
                    <div>Gallery</div>
                </a>
            </li> -->
            <!-- <li>
                <a href="{{ url('') }}" class="menu-item">
                    <div>Support</div>
                </a>
            </li> -->
            <li>
                <a href="{{ url('career') }}" class="menu-item <?php if (Request::segment(1) == "career") echo "active"; ?>">
                    <div>Career</div>
                </a>
            </li>
            <li>
                <a href="{{ url('about') }}" class="menu-item">
                    <div>About</div>
                </a>
            </li>
            <li>
                <a href="{{ url('member/dashboard') }}" class="menu-item">
                    <div>Member Page</div>
                </a>
            </li>
        </ul>
    </div>
    @foreach(HomeController::getCategoryParent() as $cat_parent)
    <div id="sidebar-menu-subcat-{{$cat_parent->slug}}" class="sidebar-menu-subcat d-nonex">
        @foreach(HomeController::getSubCategory($cat_parent->slug, "menu") as $val)
        <a href="{{ url($val->href) }}" class="menu-item-parent d-flex flex-column align-items-center text-dark">
            <img src="{{ url($val->icon) }}" alt="">
            {{$val->name}}
        </a>
        @endforeach
    </div>
    @endforeach
    <div class="content-container">
        @yield('content')
    </div>
    <div class="warranty-service-section" id="warranty-service-section" style="display: none;">
        <div class="content-inner">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-6 home-warranty-section">
                        <a href="{{ url('warranty/registration') }}">
                            <img src="{{ url('assets/img/icon-warranty.png')}}">
                            <h2>Register Product</h2>
                            <p>
                                Daftarkan produk anda untuk mengaktifkan garansi terbaik
                            </p>
                        </a>
                    </div>
                    <div class="col-sm-6 home-service-section">
                        <a href="{{ url('') }}">
                            <img src="{{ url('assets/img/icon-service.png')}}">
                            <h2>Service Tracking</h2>
                            <p>
                                Dapatkan informasi status servis produk anda secara real-time
                            </p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="support-section">
        <div class="col-sm-12" style="display: none;">
            <div class="row">
                <div class="col-md-6 support-image">
                    <img src="{{ url('assets/img/img-contact.png') }}" class="support-person">
                    <img src="{{ url('assets/img/accent-support.png')}}" class="support-image-accent">
                </div>
                <div class="col-md-6 support-content">
                    <div class="support-24">
                        <div class="support-icon">
                            <img src="{{ url('assets/img/icon-24h.png') }}">
                        </div>
                        <div class="support-header">
                            Service<br>
                            Response
                        </div>
                    </div>
                    <p style="margin-bottom: 50px;">
                        Butuh bantuan? Kami siap membantu anda. <br>
                        Silahkan hubungi kami ke <b>0811 1000 8010 (WA)</b> atau <b>care@artugo.co.id.</b>
                        Jaminan respon selama 24 jam.
                    </p>
                    <div class="support-image-mobile">
                        <img src="{{ url('assets/img/img-contact.png') }}" class="support-person">
                    </div>
                </div>
            </div>
        </div>
        <div class="footer" style="position: relative;">
            <img src="{{ url('assets/img/artugo-arrow-w.png')}}" class="footer-accent">
            <div class="container">
                <div class="row">
                    <div class="col-md-2 col-sm-6">
                        <div class="footer-item-header">
                            Cool Products
                        </div>
                        <?php
                        // $cool_product = DB::table('footer_content')->where('footerSubjectID','1')->where('status','1')->get();
                        ?>
                        @foreach(HomeController::getSubCategory("cooling", "footer_nav") as $val)
                        <a href="{{ url($val->href) }}" class="footer-link">{{ $val->name }}</a>
                        @endforeach
                        <br><br>
                        <div class="footer-item-header">
                            Hot Products
                        </div>
                        <?php
                        // $hot_product = DB::table('footer_content')->where('footerSubjectID','2')->where('status','1')->get();
                        ?>
                        @foreach(HomeController::getSubCategory("heater", "footer_nav") as $val)
                        <a href="{{ url($val->href) }}" class="footer-link">{{ $val->name }}</a>
                        @endforeach
                    </div>

                    <div class="col-md-2 col-sm-6 offset-md-1 footer-content">
                        <div class="footer-item-header">
                            Hygiene
                        </div>
                        <?php
                        // $hygiene_product = DB::table('footer_content')->where('footerSubjectID','3')->where('status','1')->get();
                        ?>
                        @foreach(HomeController::getSubCategory("hygiene", "footer_nav") as $val)
                        <a href="{{ url($val->href) }}" class="footer-link">{{ $val->name }}</a>
                        @endforeach
                        <br><br>
                        <?php /*
                        <div class="footer-item-header">
                            Accessories
                        </div>
                        <?php
                        // $accessories_product = DB::table('footer_content')->where('footerSubjectID','4')->where('status','1')->get();
                        ?>
                        @foreach(HomeController::getSubCategory("accessories", "footer_nav") as $val)
                        <a href="{{ url($val->href) }}" class="footer-link">{{ $val->name }}</a>
                        @endforeach
                        */ ?>
                    </div>

                    <div class="col-md-2 col-sm-6 footer-content">
                        <div class="footer-item-header">
                            Information
                        </div>
                        <?php
                        $information = DB::table('footer_content')->where('footerSubjectID','5')->where('status','1')->get();
                        ?>
                        @foreach($information as $val)
                        <a href="{{ url($val->href) }}" class="footer-link">{{ $val->name }}</a>
                        @endforeach
                        <br><br>
                        <div class="footer-item-header">
                            Dealers
                        </div>
                        <?php
                        $dealers = DB::table('footer_content')->where('footerSubjectID','6')->where('status','1')->get();
                        ?>
                        @foreach($dealers as $val)
                        <a href="{{ url($val->href) }}" class="footer-link">{{ $val->name }}</a>
                        @endforeach
                    </div>
                    <div class="col-md-2 col-sm-6 footer-content">
                        <div class="footer-item-header">
                            About Artugo
                        </div>
                        <?php
                        $about = DB::table('footer_content')->where('footerSubjectID','7')->where('status','1')->get();
                        ?>
                        @foreach($about as $val)
                        <a href="{{ url($val->href) }}" class="footer-link">{{ $val->name }}</a>
                        @endforeach
                    </div>
                    <div class="col-md-2 col-sm-6 footer-content">
                        <div class="footer-item-header">
                            Head Office
                        </div>
                        <div class="footer-content">
                            Jl. Gatot Subroto Km. 7,8 No. 88 Blok 3-5 RT 03 RW 05, Jatake, Jatiuwung Tangerang - Banten
                            15136, Indonesia
                        </div>
                        <div class="footer-content">
                            <!-- <b>P.</b> 1500-602 -->
                            <br>
                            <a href="mailto:care@artugo.co.id"><b>E.</b> care@artugo.co.id</a>
                        </div>
                        <div class="footer-content">
                            <a href="https://api.whatsapp.com/send?phone=6287784401818" target="_blank"><b>WA.</b> +62
                                877 8440 1818</a>
                        </div>
                        <div class="footer-content">
                            <a href="{{ url('contact') }}" class="btn btn-sm">Hubungi kami</a>
                        </div>
                        <div style="margin-top: 20px;">
                            <a href="https://www.instagram.com/artugoofficial/" target="_blank" class="footer-social"><i class="fab fa-instagram"></i></a>
                            <a href="https://www.youtube.com/channel/UCvHw_Utz5hxLDlAMBj2Aefg?view_as=subscriber" target="_blank" class="footer-social"><i class="fab fa-youtube"></i></a>
                            <a href="https://www.facebook.com/artugoofficial" target="_blank" class="footer-social"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://twitter.com/ArtugoOfficial" target="_blank" class="footer-social"><i class="fab fa-twitter"></i></a>
                            <a href="https://www.linkedin.com/company/artugoindonesia" target="_blank" class="footer-social"><i class="fab fa-linkedin"></i></a>
                            <a href="https://www.blibli.com/merchant/artugo-store/ARO-60078" target="_blank" class="footer-social">
                                <img src="{{ url('assets/icon_sosmed/Bli-Bli.png') }}">
                            </a>
                            <a href="https://www.lazada.co.id/shop/artugo-official-store" target="_blank" class="footer-social">
                                <img src="{{ url('assets/icon_sosmed/Lazada.png') }}">
                            </a>
                            <a href="https://shopee.co.id/artugo.os" target="_blank" class="footer-social">
                                <img src="{{ url('assets/icon_sosmed/Shopee.png') }}">
                            </a>
                            <a href="https://www.tokopedia.com/artugo" target="_blank" class="footer-social">
                                <img src="{{ url('assets/icon_sosmed/Tokopedia.png') }}">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-copyright">
            <div class="row">
                <div class="col-md-6">
                    <a href="{{ url('') }}" class="logo" style="/*height: 5px;*/ padding: 0px;">
                        <img src="{{ url('assets/img/artugo-logo-white.png') }}">
                    </a>
                </div>

                <div class="col-md-6 m-auto">
                    Copyright &copy; {{date('Y')}} PT Kreasi Arduo Indonesia. All rights reserved.
                </div>
            </div>
        </div>

        <button onclick="topFunction()" id="btn-back-top" title="Go to top"></button>

    </div>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-96393616-3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-96393616-3');
    </script>

    @if(env('GTM_ID_TEAMCREATIVE'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{env('GTM_ID_TEAMCREATIVE')}}"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag("js", new Date());

    gtag("config", "{{env('GTM_ID_TEAMCREATIVE')}}");
    </script>
    @endif

    <script>
        var pull = $('#pull');
        var menu = $('.sidebar');
        var overlay = $('.sidebar-overlay');
        pull.on('click', function() {
            if (pull.hasClass('is-active')) {
                overlay.addClass('d-none');
                overlay.removeClass('d-block');
                menu.removeClass('sidebar-active');
                pull.removeClass('is-active');
                $(".sidebar-menu-subcat.active").toggleClass("active");
            } else {
                overlay.removeClass('d-none');
                overlay.addClass('d-block');
                menu.addClass('sidebar-active');
                pull.addClass('is-active');
            }
            if ($('.search-box-mobile').hasClass('active')) {
                $('.search-box-mobile').removeClass('active');
            }
        });

        overlay.on('click', function() {
            // overlay.css('display', 'none');
            // menu.css('right', '-200px');
            // pull.removeClass('is-active');
            if ($(".sidebar.sidebar-active").length) {
                $(".hamburger-box").click();
            }
        });

        $('.select2').select2();
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            todayHighlight: true
        });

        $('.datepicker-today').datepicker({
            format: 'dd-mm-yyyy',
            endDate: '0d',
            todayHighlight: true
        })

        function open_search() {
            if ($('.search-box').hasClass('active')) {
                $('.search-box').removeClass('active');
            } else {
                $('.search-box').addClass('active');
                $('.search-input').focus();
            }
        }

        function open_search_mobile() {
            if ($(".sidebar.sidebar-active").length) {
                $(".hamburger-box").click();
            }
            if ($('.search-box-mobile').hasClass('active')) {
                $('.search-box-mobile').removeClass('active');
            } else {
                $('.search-box-mobile').addClass('active');
                $('.search-input-mobile').focus();
            }
        }

        function formatResult(result) {
            if (!result.id) return result.text;

            var markup = '<div class="clearfix">' +
                '<div class="row">' +
                '<div class="col-md-12">' +
                '<h6 style="color: aqua;">' + result.text + '</h6>' +
                '</div>' +
                '</div>' +
                '<p class="small">' + result.alamat_toko + '</p>' +
                '</div><hr>';

            return markup;
        }

        function formatSelection(result) {
            return result.text;
        }

        function showHideSideProdCat(slug) {
            const showed = $(`#sidebar-menu-subcat-${slug}`).hasClass("active");
            const showedOther = $(".sidebar-menu-subcat").hasClass("active");
            if (showedOther && !showed) {
                $(".menu-item-parent.active").toggleClass("active");
                $(`#menu-item-parent-${slug}`).toggleClass("active");
                $(".sidebar-menu-subcat.active").toggleClass("active");
                $(`#sidebar-menu-subcat-${slug}`).toggleClass("active");
            } else {
                $(`#sidebar-menu-subcat-${slug}`).toggleClass("active");
                $(`#menu-item-parent-${slug}`).toggleClass("active");
            }
        }


        $('.cari').select2({
            placeholder: 'Cari...',
            ajax: {
                url: '{{ url("cari")}}',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.nama_toko,
                                id: item.id,
                                latitude: item.latitude,
                                longitude: item.longitude,
                                alamat_toko: item.alamat_toko
                            }
                        })
                    };
                },
            },
            escapeMarkup: function(m) {
                return m;
            },
            templateResult: formatResult,
            templateSelection: formatSelection,
            language: {
                searching: function() {
                    return "Ketik Nama Toko, Alamat, Atau Kota...";
                }
            },
            cache: true
        });




        $('.cari').on('select2:select', function(e) {

            var data = e.params.data;

            var lat = data.latitude;
            var lng = data.longitude;
            var zoom = 10;
            $(this).find("option:selected").each(function() {
                var optionValue = $(this).attr("value");
                if (optionValue) {
                    // alert('sudah milih' + optionValue);
                }
            });

            // // add a marker
            // var marker = L.marker([lat, lng],{}).addTo(map);

            // // set the view
            // map.setView([lat, lng], zoom);
            var greenIcon = L.icon({
                iconUrl: 'assets/img/store-location.png',

                iconSize: [38, 45], // size of the icon
                shadowSize: [50, 64], // size of the shadow
                iconAnchor: [22, 94], // point of the icon which will correspond to marker's location
                shadowAnchor: [4, 62], // the same for the shadow
                popupAnchor: [-3, -76] // point from which the popup should open relative to the iconAnchor
            });

            var latLon = L.latLng(lat, lng);
            L.marker([lat, lng], {
                icon: greenIcon
            }).addTo(map).bindPopup('<b> ' + data.text + '</b><br>' + data.alamat_toko + '<br><a href="http://www.google.com/maps/place/' + lat + ',' + lng + '" target="_blank">Kunjungi Toko </a>');
            var bounds = latLon.toBounds(500); // 500 = metres
            map.panTo(latLon).fitBounds(bounds);

        });


        let mybutton = document.getElementById("btn-back-top");

        function setPositionSideMobileMenu () {
            if ($(".mobile-menu").css("display") !== "none" && $(".mobile-menu").position().top > 0) {
                $(".mobile-menu").css("top", 0);
            }
        }
        // When the user scrolls down 20px from the top of the document, show the button
        window.onscroll = function() {scrollFunction()};
        function scrollFunction() {
            setPositionSideMobileMenu();
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                mybutton.style.display = "block";
            } else {
                mybutton.style.display = "none";
            }
        }

        // When the user clicks on the button, scroll to the top of the document
        function topFunction() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }


        $(document).ready(function() {
            // $('.parent').click(function() {
            $('.menu-item.product').click(function() {
                $('.sub-nav').toggleClass('visible');
                $(".menu-item-parent.active").toggleClass("active");
                $(".sidebar-menu-subcat.active").toggleClass("active");
            });
            // $(".sidebar").height(document.body.clientHeight - 55);
            setPositionSideMobileMenu();
        });
    </script>


    @stack('js')
</body>

</html>