@extends('backend.layouts.backend-app')
@section('title', 'Whatsapp Dashboard')
@section('content')
<style>
    .nav {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        padding-left: 0;
        margin-bottom: 0;
        list-style: none;
    }
    .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
        color: #fff;
        background-color: #007bff;
    }
    .nav-justify-content-center {
        justify-content: center;
    }
    .nav-fill .nav-item {
        -webkit-box-flex: 1;
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
        text-align: center;
    }
    .nav-pills .nav-link {
        border-radius: 0.25rem;
    }
    .nav-link {
        display: block;
        padding: 0.5rem 1rem;
    }



    .block-header {
        margin-bottom: 15px;
    }
    .block-header h2 {
        margin: 0 !important;
        color: #666 !important;
        font-weight: normal;
        font-size: 16px;
    }
    .info-box {
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        height: 80px;
        display: flex;
        cursor: default;
        background-color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 30px;
    }
    .info-box .content {
        display: inline-block;
        padding: 7px 10px;
    }
    .info-box .icon {
        display: inline-block;
        text-align: center;
        background-color: rgba(0, 0, 0, 0.12);
        width: 80px;
    }
    .info-box .icon i {
        color: #fff;
        font-size: 50px;
        line-height: 80px;
    }
    .info-box .content .text {
        font-size: 16px;
        margin-top: 11px;
        color: #555;
    }
    .bg-pink {
        background-color: #E91E63 !important;
        color: #fff;
    }
    .bg-cyan {
        background-color: #00BCD4 !important;
        color: #fff;
    }
    .bg-light-green {
        background-color: #8BC34A !important;
        color: #fff;
    }
    .bg-orange {
        background-color: #ff851b !important;
    }
    .number {
        font-size: 2vw;
        font-weight: bold;
    }
    .bg-pink .content .text, .bg-pink .content .number {
        color: #fff !important;
    }
    .bg-cyan .content .text, .bg-cyan .content .number {
        color: #fff !important;
    }
    .bg-light-green .content .text, .bg-light-green .content .number {
        color: #fff !important;
    }
    .bg-orange .content .text, .bg-orange .content .number {
        color: #fff !important;
    }

    .info-box.hover-zoom-effect .icon {
        overflow: hidden;
    }
    .info-box.hover-zoom-effect .icon i {
        -moz-transition: all 0.3s ease;
        -o-transition: all 0.3s ease;
        -webkit-transition: all 0.3s ease;
        transition: all 0.3s ease;
    }

    .info-box.hover-zoom-effect:hover .icon i {
        opacity: 0.4;
        -moz-transform: rotate(-32deg) scale(1.4);
        -ms-transform: rotate(-32deg) scale(1.4);
        -o-transform: rotate(-32deg) scale(1.4);
        -webkit-transform: rotate(-32deg) scale(1.4);
        transform: rotate(-32deg) scale(1.4);
    }

    .info-box.hover-expand-effect:after {
        background-color: rgba(0, 0, 0, 0.05);
        content: ".";
        position: absolute;
        left: 80px;
        top: 0;
        width: 0;
        height: 100%;
        color: transparent;
        -moz-transition: all 0.95s;
        -o-transition: all 0.95s;
        -webkit-transition: all 0.95s;
        transition: all 0.95s;
    }

    .info-box.hover-expand-effect:hover:after {
        width: 100%;
    }
</style>
<div>
    <section class="content">
        <div class="container-fluid">

            <div class="block-header">
                <h2>Message Dashboard</h2>
            </div>

            <nav class="nav nav-pills nav-justified mb-4 nav-justify-content-center">
                @foreach($periode as $key_per => $val_per)
                <a data-key="{{$key_per}}" class="nav-item nav-link {{$key_per == 'today' ? 'active' : ''}} pointer pills-period" >{{$val_per}}</a>
                @endforeach
            </nav>

            <div class="row clearfix">
                @foreach($status as $key_sts => $val_sts)
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box {{$colors[$key_sts]}} hover-expand-effect">
                        <div class="icon">
                            <i class="fa fa-tasks"></i>
                        </div>
                        <div class="content">
                            <div class="text">{{$val_sts}}</div>
                            <div id="count_value_{{$key_sts}}" class="number">{{$messages[$key_sts]}}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>

<script>

  var period_selected = "today";

  function loadTotalMessage () {
    $.ajax({
        url: "{{url('artmin/whatsapp/message_count')}}" + `/${period_selected}`,
        method: "GET",
        data: {
        "_token": $('[name="_token"]').val(),
        },
        success: function(res) {
            if (res.success) {
                for (const row of res.data) {
                    $(`#count_value_${row.status}`).text(row.total);
                }
            }
        }
    });
  }

  $(document).ready(function(e) {

    $(".pills-period").on("click", (e) => {
        period_selected = $(e.target).attr("data-key");
        $(".pills-period").removeClass("active");
        $(e.target).addClass("active");

        $(".number").text("0");

        loadTotalMessage();
    });

    loadTotalMessage();

  });

</script>

@endsection