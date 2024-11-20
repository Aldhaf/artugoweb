<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Mail;
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\DB;

class EmailHelper
{

    public static function wrap_body_html ($content)
    {
        return '
            <!DOCTYPE html>
            <html lang="en" dir="ltr">
                <head>
                    <meta charset="utf-8">
                    <style media="screen">
                        *{
                            box-sizing: border-box;
                        }
                        body{
                            font-family: "Helvetica";
                            font-size: 14px;
                            line-height: 1.6;
                        }
                    </style>
                </head>
                <body>
                    <div style="background: #f3f3f3; width: 100%; height: 100%; margin: 0px; padding: 50px 10px;">
                        <div style="width: 100%; max-width: 600px; background: #fff; margin: 0px auto; color: #000; box-shadow: 0px 0px 10px 2px rgba(0,0,0,0.05);">
                            <div style="background: #000; text-align: left; width: 100%; padding: 20px 35px;">
                                <a href="' . env('APP_URL') . '/"><img src="' . env('APP_URL') . '/assets/img/artugo-logo-white.png" style="height: 60px;"></a>
                            </div>
                            <div style="background: #fff; padding: 50px;">
                                ' . $content . '
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
                                    <b>Website:</b> <a href="' . env('APP_URL') . '/">www.artugo.co.id</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </body>
            </html>
            ';
    }

    public static function warranty_registration($data)
    {

        $warranty = $data['warranty'];
        $warranty_list = $data['warranty_list'];
        $product = $data['product'];

        $content = '
            Dear ' . $data["name"] . ', <br>
            <p>
                Selamat datang di ARTUGO Family.<br>
                Berikut adalah informasi garansi produk Anda :
            </p>
            <div style="background: #f1f1f1; color: #000; padding: 20px; margin-bottom: 20px;">
                <div style="width: 50%; float:left; min-width: 180px;">
                    <div style="margin-bottom: 10px;">
                        <b>Product</b><br>
                        ' . $product->product_name . '
                    </div>
                    <div style="margin-bottom: 10px;">
                        <b>Serial No.</b><br>
                        ' . $warranty->serial_no . '
                    </div>
                    <div style="margin-bottom: 10px;">
                        <b>Registered Phone</b><br>
                        ' . $warranty->reg_phone . '
                    </div>
                </div>
                <div style="width: 50%; float:left; min-width: 180px;">
                    <div style="margin-bottom: 10px;">
                        <b>Warranty No.</b><br>
                        ' . $warranty->warranty_no . '
                    </div>
                    <div style="margin-bottom: 10px;">
                        <b>Purchase Date</b><br>
                        ' . date("d M Y", strtotime($warranty->purchase_date)) . '
                    </div>
                    <div style="margin-bottom: 10px;">
                        <b>Registered From</b><br>
                        ' . date("d M Y", strtotime($warranty->created_at)) . '
                    </div>
                </div>
                <div style="clear: both;"></div>
            </div>
        ';
        $content .= '
            <div style="background: #f1f1f1; color: #000;">
                <div style="font-size: 16px; padding: 20px 20px 0px 20px; font-weight: bold; text-align: center;">Warranty Information</div>
        ';

        foreach ($warranty_list as $war) {
            $year = "Year";
            if ($war->warranty_period > 1) {
                $year = "Years";
            } else if ($war->warranty_period <= 0) {
                $year = "";
            }
            $content .= '
                <div style="width: 50%; float: left; padding: 20px; min-width: 180px;">
                    <b style="font-size: 18px;">' . ($year == "" ? "Lifetime" : ($war->warranty_period . ' ' . $year)) . '</b> <br>
                    ' . $war->warranty_type .
                    ($year == "" ? "" : '<br>Expired on: ' . date("d M Y", strtotime($war->warranty_end))) . '
                </div>
            ';
        }

        $content .= '<div style="clear: both;"></div></div>';

        $content .= '
            <div stle="clear:both;"></div>
            <div style="margin: 20px 0px;">
                Untuk informasi lebih lanjut mengenai produk dan garansi anda, silahkan hubungi:
            </div>
            <div style="background: #f1f1f1; color: #000; padding: 20px;">
                <div style="width: 50%; float: left; min-width: 180px;">
                    <b>Email</b><br>
                    care@artugo.co.id
                </div>
                <div style="width: 50%; float: left; min-width: 180px;">
                    <b>Whatsapp</b><br>
                    <a href="https://wa.me/6287784401818">+62 877 8440 1818</a>
                </div>
                <div style="clear: both;"></div>
            </div>
        ';
        $data['content'] = $content;
        $data['subject'] = "Digital Warranty Registration - #" . $warranty->warranty_no;

        return Mail::send([], [], function ($message) use($data) {
            $message->to($data['to'])
            ->subject($data['subject'])
            ->setBody(EmailHelper::wrap_body_html($data['content']), 'text/html');
        });

        // return EmailHelper::send_email($data);
    }



    public static function tradein_confirmation($data)
    {

        $warranty = $data['warranty'];
        $tradein = $data['tradein'];
        $tradein_periode = $data['tradein_periode'];

        $content = '
            Dear Tim Finance, <br>
            <p>
            Validasi kelengkapan dokumen Trade In telah selesai atas data pelanggan :
            </p>
            <div style="background: #f1f1f1; color: #000; padding: 20px; margin-bottom: 20px;">
                <div style="width: 50%; float:left; min-width: 180px;">
                    <div style="margin-bottom: 10px;">
                        <b>Nama Pelanggan</b><br>
                        ' . $warranty->reg_name . '
                    </div>
                    <div style="margin-bottom: 10px;">
                        <b>Model Produk</b><br>
                        ' . $warranty->product_name . '
                    </div>
                    <div style="margin-bottom: 10px;">
                        <b>Nomor Rekening</b><br>
                        ' . $tradein->no_rekening . '
                    </div>
                    </div>
                    <div style="width: 50%; float:left; min-width: 180px;">
                    <div style="margin-bottom: 10px;">
                        <b>Nomor KTP</b><br>
                        ' . $tradein->ktp . '
                    </div>
                    <div style="margin-bottom: 10px;">
                        <b>Nama Bank</b><br>
                        ' . $tradein->nama_bank . '
                    </div>
                    <div style="margin-bottom: 10px;">
                        <b>Nama Pemilik Rekening</b><br>
                        ' . $tradein->atas_nama_rekening . '
                    </div>
                    <div style="margin-bottom: 10px;">
                        <b>Kota Tempat Rekening Dibuka</b><br>
                        ' . $tradein->kota_tempat_rekening_dibuka . '
                    </div>
                </div>
                <div style="clear: both;"></div>
            </div>
            <p>
                Selanjutnya mohon diproses pembayaran Trade-In sebesar nominal : Rp ' . number_format($tradein_periode->nominal) . '
            </p>
        ';

        $content .= '<div style="clear: both;"></div>';

        $data['content'] = $content;
        $data['subject'] = "Permintaan Transfer Program Trade-In " . $warranty->reg_name . " (" . $warranty->warranty_no . ")";

        return Mail::send([], [], function ($message) use($data) {
            $message->to($data['to'])
            ->subject($data['subject'])
            ->setBody(EmailHelper::wrap_body_html($data['content']), 'text/html');
        });

        // return EmailHelper::send_email($data);
    }


    public static function cashback_confirmation($data)
    {

        $warranty = $data['warranty'];
        $cashback = $data['cashback'];
        $cashback_periode = $data['cashback_periode'];

        $vaksin = false;
        if ($cashback->foto_sertifikat_vaksin) {
            $vaksin = true;
        }

        $content = '
            Dear Tim Finance, <br>
            <p>
            Validasi kelengkapan dokumen Cashback telah selesai atas data pelanggan :
            </p>
            <div style="background: #f1f1f1; color: #000; padding: 20px; margin-bottom: 20px;">
                <div style="width: 50%; float:left; min-width: 180px;">
                    <div style="margin-bottom: 10px;">
                        <b>Nama Pelanggan</b><br>
                        ' . $warranty->reg_name . '
                    </div>
                    <div style="margin-bottom: 10px;">
                        <b>Model Produk</b><br>
                        ' . $warranty->product_name . '
                    </div>
                    <div style="margin-bottom: 10px;">
                        <b>Nomor Rekening</b><br>
                        ' . $cashback->no_rekening . '
                    </div>
                    </div>
                    <div style="width: 50%; float:left; min-width: 180px;">
                    <div style="margin-bottom: 10px;">
                        <b>Nomor KTP</b><br>
                        ' . $cashback->ktp . '
                    </div>
                    <div style="margin-bottom: 10px;">
                        <b>Nama Bank</b><br>
                        ' . $cashback->nama_bank . '
                    </div>
                    <div style="margin-bottom: 10px;">
                        <b>Nama Pemilik Rekening</b><br>
                        ' . $cashback->atas_nama_rekening . '
                    </div>
                    <div style="margin-bottom: 10px;">
                        <b>Kota Tempat Rekening Dibuka</b><br>
                        ' . $cashback->kota_tempat_rekening_dibuka . '
                    </div>
                </div>
                <div style="clear: both;"></div>
            </div>
            <p>
                Selanjutnya mohon diproses pembayaran Cashback sebesar nominal : Rp ' . number_format($cashback_periode->nominal)  . ($vaksin ? ' - Extra Sertifikat Vaksin : Rp ' . number_format($cashback_periode->nominal_extra) : null) . '
            </p>
        ';

        $content .= '<div style="clear: both;"></div>';

        $data['content'] = $content;
        $data['subject'] = "Permintaan Transfer Program Cashback " . $warranty->reg_name . " (" . $warranty->warranty_no . ")";

        return Mail::send([], [], function ($message) use($data) {
            $message->to($data['to'])
            ->subject($data['subject'])
            ->setBody(EmailHelper::wrap_body_html($data['content']), 'text/html');
        });

        // return EmailHelper::send_email($data);
    }



    public static function special_voucher($data)
    {
        $warranty_no = $data['warranty_no'];
        $serial_number = $data['serial_number'];
        $cashback = $data['cashback'];

        $content = '
            <center>
            Pelanggan yang terhormat,
            <b>SELAMAT!</b> Anda beruntung menjadi Pemenang Promo "Nomor Seri Unik"<br>
            Nomor Seri Produk Anda : <b style="color:red">' . $serial_number . '</b><br>
            Anda berhak atas Voucher Cash Back sebesar : <br>
            <h3 style="color:red">Rp. ' . number_format($cashback) . '</h3>
            Terima kasih
            <br><br>
            Cara redeem Voucher : <br>
            1. Belanja produk ARTUGO di Toko terdekat <br>
            2. Registrasi Digital Warranty untuk produk baru <br>
            3. Masukkan kode Voucher Cash Back <br>
            4. Dana Cash Back ditransfer ke Rekening Pelanggan
            <br><br>
            <i style="color:#3C80AF">Program promo berhadiah Voucher Belanja "Nomor Seri Unik" diselenggarakan oleh : <br> PT. Kreasi Ardua Indonesia (ARTUGO)</i>
            </center>
        ';

        $content .= '<div style="clear: both;"></div>';

        $data['content'] = $content;
        $data['subject'] = "Special Voucher ARTUGO - #" . $warranty_no;

        return Mail::send([], [], function ($message) use($data) {
            $message->to($data['to'])
            ->subject($data['subject'])
            ->setBody(EmailHelper::wrap_body_html($data['content']), 'text/html');
        });
        
        // return EmailHelper::send_email($data);
    }


    public static function special_voucher_confirmation_finance($data)
    {

        $special_voucher = $data['special_voucher'];
        $warranty = $data['warranty'];
        $unique_number = $data['unique_number'];

        $content = '
            Dear Tim Finance, <br>
            <p>
            Validasi kelengkapan dokumen Special Voucher telah selesai atas data pelanggan :
            </p>
            <div style="background: #f1f1f1; color: #000; padding: 20px; margin-bottom: 20px;">
                <div style="width: 50%; float:left; min-width: 180px;">
                    <div style="margin-bottom: 10px;">
                        <b>Nama Pelanggan</b><br>
                        ' . $warranty->reg_name . '
                    </div>
                    <div style="margin-bottom: 10px;">
                        <b>Model Produk</b><br>
                        ' . $warranty->product_name . '
                    </div>
                    <div style="margin-bottom: 10px;">
                        <b>Nomor Rekening</b><br>
                        ' . $special_voucher->no_rekening . '
                    </div>
                    </div>
                    <div style="width: 50%; float:left; min-width: 180px;">
                    <div style="margin-bottom: 10px;">
                        <b>Nomor KTP</b><br>
                        ' . $special_voucher->ktp . '
                    </div>
                    <div style="margin-bottom: 10px;">
                        <b>Nama Bank</b><br>
                        ' . $special_voucher->nama_bank . '
                    </div>
                    <div style="margin-bottom: 10px;">
                        <b>Nama Pemilik Rekening</b><br>
                        ' . $special_voucher->atas_nama_rekening . '
                    </div>
                    <div style="margin-bottom: 10px;">
                        <b>Kota Tempat Rekening Dibuka</b><br>
                        ' . $special_voucher->kota_tempat_rekening_dibuka . '
                    </div>
                </div>
                <div style="clear: both;"></div>
            </div>
            <p>
                Selanjutnya mohon diproses pembayaran cashback sebesar nominal : Rp ' . number_format($unique_number->cashback) . '
            </p>
        ';

        $content .= '<div style="clear: both;"></div>';

        $data['content'] = $content;
        $data['subject'] = "Permintaan Transfer Program Special Voucher " . $warranty->reg_name . " (" . $warranty->warranty_no . ")";

        return Mail::send([], [], function ($message) use($data) {
            $message->to($data['to'])
            ->subject($data['subject'])
            ->setBody(EmailHelper::wrap_body_html($data['content']), 'text/html');
        });

        // return EmailHelper::send_email($data);
    }










    # Send Email Function (Templated)
    #---------------------------------------------------------------------------

    // public static function send_email($data)
    // {
    //     $mail = new PHPMailer(true);

    //     try {
    //         // Server settings
    //         $mail->SMTPDebug = 0;
    //         $mail->isSMTP();
    //         $mail->Host = env('MAIL_HOST', 'smtp.gmail.com');
    //         $mail->SMTPAuth = true;
    //         $mail->Username = env('MAIL_USERNAME', 'no-reply@artugo.co.id');
    //         $mail->Password = env('MAIL_PASSWORD', 'gdwxtrxaaqbpqouf');
    //         $mail->SMTPSecure = env('MAIL_ENCRYPTION', 'tls');
    //         $mail->Port = env('MAIL_PORT', 587);

    //         //Recipients
    //         $mail->setFrom(env('MAIL_USERNAME', 'no-reply@artugo.co.id'), 'ARTUGO Indonesia');
    //         $mail->addAddress($data['to']);

    //         $body = '
    //         <!DOCTYPE html>
    //         <html lang="en" dir="ltr">
    //             <head>
    //                 <meta charset="utf-8">
    //                 <style media="screen">
    //                     *{
    //                         box-sizing: border-box;
    //                     }
    //                     body{
    //                         font-family: "Helvetica";
    //                         font-size: 14px;
    //                         line-height: 1.6;
    //                     }
    //                 </style>
    //             </head>
    //             <body>
    //                 <div style="background: #f3f3f3; width: 100%; height: 100%; margin: 0px; padding: 50px 10px;">
    //                     <div style="width: 100%; max-width: 600px; background: #fff; margin: 0px auto; color: #000; box-shadow: 0px 0px 10px 2px rgba(0,0,0,0.05);">
    //                         <div style="background: #000; text-align: left; width: 100%; padding: 20px 35px;">
    //                             <a href="https://www.artugo.co.id/"><img src="https://www.artugo.co.id/assets/img/artugo-logo-white.png" style="height: 60px;"></a>
    //                         </div>
    //                         <div style="background: #fff; padding: 50px;">
    //                             ' . $data['content'] . '
    //                         </div>
    //                         <div style="background: #fff; color: #000; padding: 0px 50px;">
    //                             <div style="border-top: 1px solid #ddd; padding: 30px 0px 50px 0px; font-size: 12px">
    //                                 <div style="font-size: 10px; margin-bottom: 20px;">
    //                                     Email ini adalah email otomatis yang dikirim melalui system, mohon tidak membalas email ini. Jika butuh bantuan atau informasi lebih lanjut, silahkan hubungi kami di care@artugo.co.id.
    //                                 </div>
    //                                 <b>ARTUGO</b><br>
    //                                 PT KREASI ARDUO INDONESIA<br>
    //                                 Jl. Gatot Subroto Km. 7,8 no. 88 Blok 3-5, Jatake, <br>
    //                                 Kec. Jatiuwung Tangerang - Banten 15136, Indonesia<br>
    //                                 <b>Phone:</b> +62 877 8440 1818<br>
    //                                 <b>E-mail:</b> care@artugo.co.id<br>
    //                                 <b>Website:</b> <a href="https://www.artugo.co.id/">www.artugo.co.id</a>
    //                             </div>
    //                         </div>
    //                     </div>
    //                 </div>
    //             </body>
    //         </html>
    //         ';

    //         //Content
    //         $mail->isHTML(true);
    //         $mail->Subject = $data['subject'];
    //         $mail->Body    = $body;
            
    //         // echo "sending";

    //         if( !$mail->send() ) {
    //             // return back()->with("failed", "Email not sent.")->withErrors($mail->ErrorInfo);
    //             print_r($mail->ErrorInfo);
    //         }

    //         // echo $body;
    //         // exit();
    //     } catch (Exception $e) {
    //         // echo "erorr";
    //         // dd($e);
    //         return false;
    //     }
    // }
}
