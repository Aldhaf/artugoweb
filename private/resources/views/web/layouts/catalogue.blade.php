<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>ARTUGO | e-Catalogue Book</title>
    <link href="https://www.cssscript.com/wp-includes/css/sticky.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/4.3.1/flatly/bootstrap.min.css">
    <script defer src="https://stacksnippets.net/scripts/snippet-javascript-console.min.js?v=1"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.js"></script>
    <!-- <script defer src="https://friconix.com/cdn/friconix.js"></script> -->
    <link rel="stylesheet" href="assets/backend/plugins/icofont/icofont.min.css" type="text/css">
    <style>

        body {
            overflow: hidden;
            /* background-size: cover !important;
            background: url("uploads/slides/ARTUGO-WEBSITE-HOME-BANGKITBARENG-2.jpg") no-repeat; */
        }

        .wrapper {
            position: relative;
            /* overflow: scroll; */
            /* position: fixed;
            top: 70px; */
            width: 100%;
            /* height: calc(100% - 140px); */
            height: 100%;
            margin: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            align-content: center;
            flex-wrap: wrap;
        }

        /* .container-catalogue {
            position: relative;
            margin: auto;
            width: 940px;
            left: calc(-50% + 470px);
            -webkit-transition: all .7s ease-in-out;
            -moz-transition: all .7s ease-in-out;
            -ms-transition: all .7s ease-in-out;
            -o-transition: all .7s ease-in-out;
            transition: all .7s ease-in-out;
        }

        .container-catalogue:has(.page-cover-top[style*="display: none;"]) {
            left: 0;
        }

        .container-catalogue:has(.page-cover-bottom[style*="display: block;"]) {
            left: calc(-50% + 940px);
        } */

        .rotate-180 {
            transform: rotate(180deg);
        }

        .page {
            /* padding: 20px; */
            background-color: #fdfaf7;
            color: #785e3a;
            border: solid 1px #c2b5a3;
            overflow: hidden;
        }

        /* .page.--right > .page-content {
            box-shadow: inset 7px 0 30px -7px rgb(0 0 0 / 40%);
        } */

        .middle-fold {
            position: fixed;
            height: 100%;
            width: 100%;
        }

        .page.--left > .page-content .middle-fold {
            right: 0px;
            box-shadow: inset -7px 0 30px -7px rgb(0 0 0 / 40%);
        }

        .page.--right > .page-content .middle-fold {
            left: 0px;
            box-shadow: inset 7px 0 30px -7px rgb(0 0 0 / 40%);
        }

        .page .page-content {
            /* width: 470px;
            height: 470px; */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: stretch;
            box-shadow: 0px 0 30px 0px rgba(36, 10, 3, 0.5), -2px 0 5px 2px rgba(0, 0, 0, 0.4);
        }

        .page .page-content .page-header {
            height: 30px;
            font-size: 100%;
            text-transform: uppercase;
            text-align: center;
        }

        /* .page .page-content .page-image {
            width: 470px;
            height: 470px;
            background-size: contain;
            background-position: center center;
            background-repeat: no-repeat;
        } */

        /* .page .page-content .page-text {
            height: 100%;
            flex-grow: 1;
            font-size: 80%;
            text-align: justify;
            margin-top: 10px;
            padding-top: 10px;
            box-sizing: border-box;
            border-top: solid 1px #f4e8d7;
        }

        .page .page-content .page-footer {
            height: 30px;
            border-top: solid 1px #f4e8d7;
            font-size: 80%;
            color: #998466;
        }

        .page.--left {
            border-right: 0;
            box-shadow: inset -7px 0 30px -7px rgba(0, 0, 0, 0.4);
        }

        .page.--right {
            border-left: 0;
            box-shadow: inset 7px 0 30px -7px rgba(0, 0, 0, 0.4);
        }

        .page.--right .page-footer {
            text-align: right;
        }

        .page.hard {
            background-color: #f2e8d9;
            border: solid 1px #998466;
        }

        .page.page-cover h2 {
            text-align: center;
            padding-top: 50%;
            font-size: 210%;
        }
        */

        .page.page-cover {
            /* color: #785e3a; */
            background-color: transparent;
            /* border: solid 1px #998466; */
            border: none;
        }

        .page.page-cover.page-cover-top {
            box-shadow: inset 0px 0 30px 0px rgba(36, 10, 3, 0.5), -2px 0 5px 2px rgba(0, 0, 0, 0.4);
        }

        .page.page-cover.page-cover-bottom {
            box-shadow: inset 0px 0 30px 0px rgba(36, 10, 3, 0.5), 10px 0 8px 0px rgba(0, 0, 0, 0.4);
        }

        .bottom-bar {
            background: #30353a;
            position: fixed;
            bottom: 0px;
            width: 100%;
            color: white;
            height: 70px;
            flex: 1;
        }

        .page-navigator {
            background: rgba(18, 18, 17);
            display: flex;
            align-items: center;
            flex-direction: row;
            justify-content: center;
            gap: 12px;
            padding: 8px 0px 8px 0px;
            color: white;
            height: 100%;
            flex: 1;
        }

        .navigation {
            background: rgba(18, 18, 17);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 70px;
            z-index: 99999999;
            -webkit-backdrop-filter: saturate(180%) blur(20px);
            backdrop-filter: saturate(180%) blur(20px);

            /* display: flex; */
            /* flex: 1; */
        }

        .nav-content {
            flex: 1;
            margin: auto;
            position: relative;
            flex-wrap: wrap;
            align-items: center;
            align-content: center;
            justify-content: space-between;
        }

        .btn-find {
            padding: 0px;
            margin: 0px;
            border-radius: 30px;
            height: 30px;
            width: 30px;
            align-self: center;
        }

        input:focus {
            border: none;
            outline: none;
        }

        .logo-container {
            /* position: absolute;
            display: inline-block;
            top: 0px;
            left: 50px; */
            padding-left: 50px;
        }

        .logo {
            display: inline-block;
            padding: 12px 10px;
        }

        .logo img {
            height: 55px;
        }

        .zoom {
            zoom: 150%;
        }

        .input-find-container {
            /* width: 400px; */
            width: 100%;
            background: white;
            border-radius: 40px;
            display: flex;
            align-content: center;
            padding-right: 3px;
            /* margin-right: 16px; */
            /* padding-top: 12px;
            padding-bottom: 12px; */
        }

        .find-result-container-header {
            width: 100%;
            display: flex;
            flex-direction: row;
            justify-content: center;
            justify-items: center;
            align-items: center;
            gap: 12px;
        }

        .top-text-find {
            flex: 1;
            margin: auto;
            text-align: center;
            border: none;
            padding-right: 8px;
            /*
            background: white;
            width: 530px;
            border-radius: 40px;
            */
        }

        .top-text-find:focus {
            border: none;
            outline: none;
        }

        .flip-book {
            display: none;
            transition: transform .7s;
            -webkit-transition: transform .7s;
            -moz-transition: transform .7s;
            -ms-transition: transform .7s;
            -o-transition: transform .7s;
        }

        .zoom-container {
            position: fixed;
            right: 18px;
            height: calc(100% - 140px);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            justify-items: center;
            align-items: center;
            gap: 4px;
            z-index: 99999;
            padding-bottom: 24px;
        }

        .find-result-container {
            padding: 12px;
            position: fixed;
            background: #000000;
            width: 300px;
            right: 0px;
            top: 0px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            justify-items: center;
            align-items: center;
            gap: 4px;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 9999999999;

            transition: all .7s;
            -webkit-transition: all .7s;
            -moz-transition: all .7s;
            -ms-transition: all .7s;
            -o-transition: all .7s;
        }

        .find-results {
            /* position: relative; */
            width: 100%;
            flex: 1;
            height: calc(100% - 70px);
        }

        .find-result-pin {
            height: 48px;
            width: 32px;
            background: #000000;
            color: #FFFFFF;
            position: fixed;
            top: calc(50% - (22px /2 ));
            bottom: calc(50% - (22px /2 ));
            right: calc(300px - 5px);
            padding-left: 8px;
            border-top-left-radius: 24px;
            border-bottom-left-radius: 24px;
            display: flex;
            align-items: center;
            cursor: pointer;
            font-size: 22px;
        }

        .btn-close-find-result {
            padding: 0px;
            margin: 0px;
            border-radius: 30px;
            height: 30px;
            width: 36px;
            align-self: center;
            align-items: center;
            background: red;
        }

        .btn-zoom {
            height: 35px;
            width: 35px;
            border-radius: 18px;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        input[type=range][orient=vertical] {
            writing-mode: bt-lr; /* IE */
            -webkit-appearance: slider-vertical; /* Chromium */
            width: 8px;
            height: 175px;
            padding: 0 5px;
        }

        .btn-toggle-find {
            margin-right: 22px;
        }

        .btn-toggle-find-mobile {
            display: none;
        }

        @media screen and (max-width: 1100px) {}

        @media screen and (max-width: 800px) {}

        @media screen and (max-width: 499px) {
            .wrapper {
                margin: auto;
                overflow: hidden;
            }
            .container-catalogue {
                /* margin-top: 50%; */
                width: 100%;
            }
            /* .btn-first {
                display: none;
            }
            .btn-end {
                display: none;
            } */
            .input-find-container {
                /* width: calc(100% - 32px); */
                /* position: fixed; */
                /* margin-left: 16px; */
            }
            .nav-content {
                justify-content: center;
            }
            .logo-container {
                padding-left: 0px;
            }
            .btn-toggle-find-mobile {
                display: block;
            }
            .find-result-container {
                width: 100%;
            }
            .btn-full-screen {
                /* display: none; */
            }
        }
    </style>
</head>

<body>

    <div class="wrapper">
        @yield('content')
    </div>

    <script src="assets/plugins/page-flip/page-flip.browser.js"></script>
    <script>

        var flipsound = new Audio("assets/media/flipsound.mp3");
        var pageFlip;
        var height = window.innerHeight - 180;
        var zoomIn = false;
        var maxZoom = 16;
        var defaultZoom = 5;

        var setZoom = (zoom,el) => {
            transformOrigin = [0,0];
            // el = el || instance.getContainer();
            var p = ["webkit", "moz", "ms", "o"],
                s = "scale(" + zoom + ")",
                oString = (transformOrigin[0] * 100) + "% " + (transformOrigin[1] * 100) + "%";

            for (var i = 0; i < p.length; i++) {
                el.style[p[i] + "Transform"] = s;
                el.style[p[i] + "TransformOrigin"] = oString;
            }

            el.style["transform"] = s;
            el.style["transformOrigin"] = oString;
        }

        //setZoom(5,document.getElementsByClassName('container')[0]);

        var showVal = (a) => {
            var zoomScale = Number(a) / defaultZoom;
            setZoom(zoomScale, document.querySelector(".flip-book"));
        }

        var isMobile = false;

        var onWindowResize = () => {
            if (window.innerHeight > window.innerWidth || window.innerWidth <= 499) {
                isMobile = true;
                // maxZoom = 15;
            }

            document.querySelector("#input-zoom").max = maxZoom;
            document.querySelector("#input-zoom").value = defaultZoom;

            height = ( isMobile ? window.innerWidth : window.innerHeight - 180);
            if (!isMobile) {
                document.querySelector(".page-content").style["height"] = height + "px";
                document.querySelector(".page-content").style["width"] = height + "px";
                document.querySelector(".page-image").style["height"] = height + "px";
                document.querySelector(".page-image").style["width"] = height + "px";
            }
            
            document.querySelector(".wrapper").style["top"] = "70px";
            document.querySelector(".wrapper").style["width"] = window.innerWidth + "px";
            document.querySelector(".wrapper").style["height"] = window.innerHeight - 140 + "px";

            const coverTopShow = document.querySelector(".page-cover-top").style["display"] !== "none";
            // document.querySelector(".container-catalogue").style["width"] = (height * 2) + "px";
            // document.querySelector(".container-catalogue").style["left"] = coverTopShow ? `calc(-50% + ${height}px)` : "0px";
            // document.querySelector(".container-catalogue").style["padding-top"] = ((window.innerHeight - height - 140) / 2 ) + "px";

            let styleCustomDynamic = document.querySelector("#custom_style_dynamic");
            if (styleCustomDynamic) {
                styleCustomDynamic.remove();
            }

            const style = document.createElement("style");
            style.id = "custom_style_dynamic";

            if (isMobile) {
                style.innerHTML = `
                .page .page-content {
                    width: 100%;
                    height: 100%;
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;
                    align-items: stretch;
                }
                .page .page-content .page-image {
                    height: 100%;
                    background-size: contain;
                    background-position: center center;
                    background-repeat: no-repeat;
                }
                `;
            } else {
                style.innerHTML = `
                .container-catalogue {
                    overflow: hidden;
                    scroll-behavior: smooth;
                    /*position: relative;*/
                    position: absolute;
                    margin: auto;
                    padding-top: ${((window.innerHeight - height - 140) / 2 )}px;
                    width: ${height * 2 + 4}px;
                    height: ${height * 2 + 4}px;
                    
                    /*height: ${window.innerHeight - 140}px;*/
                    top: 0px; /*70px;*/

                    ${isMobile ? "left: -" + (height/2) + "px;" : ""}

                    ${!isMobile ? `left: ${window.innerWidth/2 - (height * 1.5)}px;` : ""}
                    
                    -webkit-transition: all .7s ease-in-out;
                    -moz-transition: all .7s ease-in-out;
                    -ms-transition: all .7s ease-in-out;
                    -o-transition: all .7s ease-in-out;
                    transition: all .7s ease-in-out;
                }

                .container-catalogue:has(.page-cover-top[style*="display: none;"]) {
                    left: ${window.innerWidth / 2 - height}px;
                }

                .container-catalogue:has(.page-cover-bottom[style*="display: block;"]) {
                    left: ${window.innerWidth / 2- (height / 2)}px;
                    
                    /*width: ${height}px;*/
                }

                .page .page-content .page-image {
                    width: ${height}px;
                    height: ${height}px;
                    background-size: contain;
                    background-position: center center;
                    background-repeat: no-repeat;
                }
                `;
            }
            document.head.appendChild(style);
        }

        var isFullscreen = () => {
            if (document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement) {
                return true;
            } else {
                return false;
            }
        }

        document.addEventListener('DOMContentLoaded', function () {

            const minWidth = 316;

            pageFlip = new St.PageFlip(
                document.getElementById("demoBookExample"),
                {

                    width: isMobile ? minWidth * 2 : height, // base page width
                    height: isMobile ? minWidth * 2 : height, // base page height

                    // width: window.innerWidth*2,
                    // height: window.innerWidth*2,

                    size: isMobile ? "fixed" : "stretch",

                    minWidth: minWidth,
                    maxWidth: 1000,
                    minHeight: minWidth,
                    maxHeight: 1000,

                    useMouseEvents: !isMobile,
                    // swipeDistance: isMobile ? 9999999 : 40,

                    // usePortrait: true,
                    left: 0,
                    top: 0,
                    flippingTime: 700,
                    showCover: true,
                    maxShadowOpacity: 0.5, // Half shadow intensity
                    disableFlipByClick: true,
                    mobileScrollSupport: true, // disable content scrolling on mobile devices,
                }
            );

            // load pages
            pageFlip.loadFromHTML(document.querySelectorAll(".page"));

            document.querySelector(".page-total").innerText = pageFlip.getPageCount();
            document.querySelector(".page-orientation").innerText = pageFlip.getOrientation();

            document.querySelector(".btn-first").addEventListener("click", () => {
                pageFlip.flip(1);
            });

            document.querySelector(".btn-prev").addEventListener("click", () => {
                // if (!isMobile) {
                pageFlip.flipPrev(); // Turn to the previous page (with animation)
                // } else {
                //     const prevPage = Number(document.querySelector(".page-current").innerText)-1;
                //     if (prevPage > 0) {
                //         pageFlip.flip({ pageNum: prevPage, corner: 'top' });
                //     }
                // }
            });

            document.querySelector(".btn-next").addEventListener("click", () => {
                pageFlip.flipNext(); // Turn to the next page (with animation)
            });

            document.querySelector(".btn-end").addEventListener("click", () => {
                pageFlip.flip(pageFlip.getPageCount() - 2);
            });

            document.querySelector(".btn-full-screen").addEventListener("click", () => {
                if (!isFullscreen()) {
                    $(".btn-full-screen").addClass("rotate-180");
                    if (document.documentElement.requestFullscreen) {
                        document.documentElement.requestFullscreen();
                    } else if (document.documentElement.webkitRequestFullscreen) { /* Safari */
                        document.documentElement.webkitRequestFullscreen();
                    } else if (document.documentElement.msRequestFullscreen) { /* IE11 */
                        document.documentElement.msRequestFullscreen();
                    }
                } else {
                    $(".btn-full-screen").removeClass("rotate-180");
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    } else if (document.webkitExitFullscreen) { /* Safari */
                        document.webkitExitFullscreen();
                    } else if (document.msExitFullscreen) { /* IE11 */
                        document.msExitFullscreen();
                    }
                }
            });

            document.querySelector("#btn-find-catalogue").addEventListener("click", () => {
                const keyword = document.querySelector(".top-text-find").value;
                let url = '{{ url("catalogue/find") }}' + '?keyword=' + keyword;
                $.get(url, (r) => {
                    console.log("XXXX", r);
                });
            });

            document.querySelector(".btn-toggle-find-mobile").addEventListener("click", () => {
                const el = document.querySelector(".input-find-container");
                console.log(el);
            });

            document.querySelector(".btn-zoom-in").addEventListener("click", () => {
                let zoom = Number(document.querySelector("#input-zoom").value) + 1;
                zoomIn = true;
                if (zoom > maxZoom) return;
                document.querySelector("#input-zoom").value = zoom ;

                // if (document.querySelector(".container-catalogue").style.overflow === "") {
                    const scrollSize = 0; //30;
                    if (!isMobile) {
                        document.querySelector(".container-catalogue").style.left = "0px";
                    }
                    
                    if (pageFlip.getCurrentPageIndex() > 0) {
                        document.querySelector(".container-catalogue").style.paddingLeft = `calc(${document.querySelector(".page-content").style.width} / 2)`;
                        document.querySelector(".container-catalogue").style.paddingRight = `calc(${document.querySelector(".page-content").style.width} / 2)`;
                    } else {
                        if (isMobile) {
                            // document.querySelector(".container-catalogue").style.paddingLeft = "60px";
                            // document.querySelector(".container-catalogue").style.paddingRight = "60px";
                        } else {
                            document.querySelector(".container-catalogue").style.paddingLeft = "0px";
                        }
                    }

                    // if (pageFlip.getCurrentPageIndex() === 0) {
                    //     document.querySelector(".container-catalogue").style.paddingRight =  `-${document.querySelector(".page-content").clientWidth}px`;
                    // }

                    document.querySelector(".container-catalogue").style.width = `${window.innerWidth + scrollSize}px`;
                    document.querySelector(".container-catalogue").style.height = `calc(${window.innerHeight + scrollSize}px - ${document.querySelector(".bottom-bar").clientHeight + document.querySelector(".navigation").clientHeight}px)`;
                    
                    setTimeout(() => {
                        document.querySelector(".container-catalogue").style.overflow = "scroll";
                        // document.querySelector(".container-catalogue").style.scrollBehavior = "smooth";
                        if (pageFlip.getCurrentPageIndex() === 0) {
                            document.querySelector(".container-catalogue").scrollTo(Number(document.querySelector(".page-content").style.width.replace("px", "")) / 3, 0);
                        }
                        //else {
                        //     document.querySelector(".container-catalogue").scrollTo(Number(document.querySelector(".page-content").style.width.replace("px", "")) / 2, 0);
                        // }
                    }, 800);

                    // const scrollSize = document.querySelector(".container-catalogue").offsetWidth - document.querySelector(".container-catalogue").clientWidth;
                // }

                showVal(zoom);
            });

            document.querySelector(".btn-zoom-out").addEventListener("click", () => {
                const zoom = Number(document.querySelector("#input-zoom").value) - 1;
                if (zoom === 0) return;
                document.querySelector("#input-zoom").value = zoom ;

                if (zoom === defaultZoom) {
                    zoomIn = false;
                    document.querySelector(".container-catalogue").attributeStyleMap.delete("overflow");
                    document.querySelector(".container-catalogue").attributeStyleMap.delete("left");
                    document.querySelector(".container-catalogue").attributeStyleMap.delete("width");
                    document.querySelector(".container-catalogue").attributeStyleMap.delete("padding");
                    document.querySelector(".container-catalogue").attributeStyleMap.delete("height");
                }

                showVal(zoom);
            });

            pageFlip.on("turning", (e) => {
                if ($(this).turn('data').mouseAction) {
                    return event.preventDefault();
                }
            });

            // triggered by page turning
            pageFlip.on("flip", (e) => {
                if(e.data <= 1) {
                    // document.querySelector(".container-catalogue").style["width"] = e.data === 1 ? "940px" : "470px";
                    // if (e.data === 1) {
                    //     document.querySelector(".stf__wrapper").style["padding-bottom"] = "50%";
                    // }
                    // setTimeout(() => {
                    //     // window.dispatchEvent(new Event("resize"));
                    //     // pageFlip.update();
                    // }, 500);
                }
                document.querySelector(".page-current").innerText = e.data + 1;
                if (zoomIn && e.data > 0) { // && (document.querySelector(".container-catalogue").style.paddingLeft || "0px") === "0px"
                    // Di tambah padding left (width page / 2 + (1.5 * 30) yg mana 30 itu adalah width untuk hide scrollbar) ketika halaman dibuka covernya
                    document.querySelector(".container-catalogue").style.paddingLeft = `${(Number(document.querySelector(".page-content").style.width.replace("px", "")) / 2) + 45}px`;
                }
                flipsound.play();
            });

            // triggered when the state of the book changes
            pageFlip.on("changeState", (e) => {
                document.querySelector(".page-state").innerText = e.data;
            });

            // triggered when page orientation changes
            pageFlip.on("changeOrientation", (e) => {
                document.querySelector(".page-orientation").innerText = e.data;
            });

            onWindowResize();

            window.addEventListener("resize", onWindowResize);

            // document.querySelectorAll(".page.stf__item.--soft").forEach((el) => el.addEventListener("drag", (e) => console.log("ZZZ", e)));
            // document.addEventListener("drag", (e) => console.log("ZZZ", e));
        });
    </script>
</body>

</html>