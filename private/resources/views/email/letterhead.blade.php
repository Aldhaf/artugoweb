<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <style media="screen">
            *{
                box-sizing: border-box;
            }
        </style>
    </head>
    <body style="font-family: 'Helvetica'; font-size: 14px; line-height: 1.6;">
        <div style="background: #f3f3f3; width: 100%; height: 100%; margin: 0px; padding: 50px 10px;">
            <div style="width: 100%; max-width: {{ $maxwidth ?? '600px'}}; background: #fff; margin: 0px auto; color: #000; box-shadow: 0px 0px 10px 2px rgba(0,0,0,0.05);">
                <div style="background: #000; text-align: left; width: 100%; padding: 20px 35px;">
                    <a href="{{ url('') }}"><img src="{{ url('assets/img/artugo-logo-white.png') }}" style="height: 60px;"></a>
                </div>
                <div style="background: #fff; padding: 50px;">
                    @yield('content')
                </div>
                <div style="background: #fff; color: #000; padding: 0px 50px;">
                    <div style="border-top: 1px solid #ddd; padding: 30px 0px 50px 0px; font-size: 12px">
                        <div style="font-size: 10px; margin-bottom: 20px;">
                            Email ini adalah email otomatis yang dikirim melalui system, mohon tidak membalas email ini. Jika butuh bantuan atau informasi lebih lanjut, silahkan hubungi kami di care@artugo.co.id.
                        </div>
                        <b>ARTUGO</b><br>
                        PT KREASI ARDUO INDONESIA<br>
                        Jl. Gatot Subroto Km. 7,8 no. 88 Blok 3-5, Jatake, <br>
                        Kec. Jatiuwung Tangerang - Banten 15136, Indonesia<br>
                        <b>Phone:</b> +62 877 8440 1818<br>
                        <b>E-mail:</b> care@artugo.co.id<br>
                        <b>Website:</b> <a href="<?php echo env('APP_URL');?>/">www.artugo.co.id</a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>