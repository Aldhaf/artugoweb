@extends('backend.layouts.backend-app')
@section('title', 'Trade In')
@section('content')

<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Trade In
      <small>Data</small>
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">

      <div class="col-sm-12">
        <div class="box box-solid">
          <!-- <div class="box-header">
			          <h3 class="box-title">Products</h3>
			        </div> -->
          <div class="box-body">
            <div class="form-group">
              <a href="{{ url('artmin/promotion/tradein/settings') }}" class="btn btn-primary"><i class="fa fa-gear"></i> Settings</a>
            </div>
            <div class="col-sm-12 table-container">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Warranty No.</th>
                    <th>KTP</th>
                    <th>Rekening</th>
                    <th>Bank</th>
                    <th>Kota Tempat Rekening Dibuka</th>
                    <th>Atas Nama Rekening</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($tradein as $key => $val)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ date('d M Y',strtotime($val->created_at)) }}</td>
                    <td>{{ $val->warranty_no }}</td>
                    <td>{{ $val->ktp }}</td>
                    <td>{{ $val->no_rekening }}</td>
                    <td>{{ $val->nama_bank }}</td>
                    <td>{{ $val->kota_tempat_rekening_dibuka }}</td>
                    <td>{{ $val->atas_nama_rekening }}</td>
                    <td>
                      <center>
                        <?php
                        if ($val->verified == '1') {
                        ?>
                          Trade-In Disetujui
                          <!-- <i data-toggle="tooltip" title="Verified" style="color:green" class="fa fa-check-circle"></i> -->
                        <?php
                        } elseif ($val->verified == '2') {
                        ?>
                          Trade-In Ditransfer
                          <!-- <i data-toggle="tooltip" title="Rejected" style="color:red" class="fa fa-times-circle"></i> -->
                        <?php
                        } else {
                        ?>
                          Trade-In Diproses
                          <!-- <i data-toggle="tooltip" title="Pending" style="color:#294E5D" class="fa fa-minus-circle"></i> -->
                        <?php
                        }
                        ?>
                      </center>
                    </td>
                    <td>

                      <button style="margin:3px" class="btn btn-primary btn-xs btn-check-verification-data" trade_id="{{ $val->trade_id }}" type="detail" data-toggle="tooltip" title="Detail Data"><i class="fa fa-search"></i></button>
                      <?php
                      if ($val->verified == '0') {
                      ?>
                        <button style="margin:3px" class="btn btn-primary btn-xs btn-check-verification-data" trade_id="{{ $val->trade_id }}" type="verification" data-toggle="tooltip" title="Verification Data Check"><i class="fa fa-check-circle"></i></button>
                      <?php
                      } elseif ($val->verified == '1') {
                      ?>
                        <button style="margin:3px" class="btn btn-primary btn-xs btn-verifikasi-finance" trade_id="{{ $val->trade_id }}" type="verification" data-toggle="tooltip" title="Verifikasi Transfer oleh Finance"><i class="fa fa-money"></i></button>
                      <?php
                      }
                      ?>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div><!-- /.row -->
  </section><!-- /.content -->
</div>


<div class="modal mverification" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Verification Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" class="fdata">
          {{ csrf_field() }}
          <form class="fdata">
            {{ csrf_field() }}
            <input type="hidden" name="warranty_id">
            <div class="row">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-4">
                    Member Name
                  </div>
                  <div class="col-md-6">
                    : <span class="member_name">Loading Data...</span>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    Member Email
                  </div>
                  <div class="col-md-6">
                    : <span class="member_email">Loading Data...</span>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    Member Phone Number
                  </div>
                  <div class="col-md-6">
                    : <span class="member_phone_number">Loading Data...</span>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-md-4">
                    Serial Number
                  </div>
                  <div class="col-md-6">
                    : <span class="serial_number">Loading Data...</span>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    Product
                  </div>
                  <div class="col-md-6">
                    : <span class="product_name">Loading Data...</span>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    Purchase Date
                  </div>
                  <div class="col-md-6">
                    : <span class="purchase_date">Loading Data...</span>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    Purchase Location
                  </div>
                  <div class="col-md-6">
                    : <span class="purchase_location">Loading Data...</span>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-md-12">
                    <center>
                      <img src="" class="purchase_invoice" style="width:100%" alt="">
                      <br> Purchase Invoice
                    </center>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-md-4">
                    Submit Trade In Date
                  </div>
                  <div class="col-md-6">
                    : <span class="submit_trade_in_date">Loading Data...</span>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    KTP
                  </div>
                  <div class="col-md-6">
                    : <span class="ktp">Loading Data...</span>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    Rekening
                  </div>
                  <div class="col-md-6">
                    : <span class="rekening">Loading Data...</span>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    Bank
                  </div>
                  <div class="col-md-6">
                    : <span class="bank">Loading Data...</span>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    Kota Tempat Rekening Dibuka
                  </div>
                  <div class="col-md-6">
                    : <span class="kota_tempat_rekening_dibuka">Loading Data...</span>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    Atas Nama Rekening
                  </div>
                  <div class="col-md-6">
                    : <span class="atas_nama_rekening">Loading Data...</span>
                  </div>
                </div>
                <hr>

                <div class="row">
                  <div class="col-md-12">
                    <center>
                      <img src="" class="foto_ktp" style="width:100%" alt="">
                      <br> Foto KTP
                    </center>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <center>
                      <img src="" class="foto_barang" style="width:100%" alt="">
                      <br> Foto Barang
                    </center>
                  </div>
                </div>

                <div class="buktitransfer">
                  <hr>
                  <div class="row">
                    <div class="col-md-12">
                      <center>
                        <img src="" class="transfer_finance" style="width:100%" alt="">
                        <br> Bukti Transfer Finance
                      </center>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </form>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-verify" trade_id="">Verify</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<div class="modal mfinance" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title-finance">Verifikasi Transfer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="ftransfer" action="{{ url('artmin/promotion/tradein/transfer') }}" method="POST" enctype="multipart/form-data">
          {{ csrf_field() }}
          <input type="hidden" name="trade_id">
          <div class="row">
            <div class="col-md-12">
              <label for="">Bukti Transfer</label>
              <center>
                <img id="blah" src="" style="width: 100%;" />
              </center>
              <br>
              <input type="file" id="imgInp" name="bukti_transfer" class="form-control">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-submit-transfer">Verify</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>


<script>
  $(document).ready(function() {
    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
          $('#blah').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]); // convert to base64 string
      }
    }

    $("#imgInp").change(function() {
      readURL(this);
    });
  });

  $(document).on('click', '.btn-submit-transfer', function(e) {
    e.preventDefault();

    swal({
        title: "Confirmation",
        text: "Are you sure to submit this data?",
        icon: "info",
        buttons: true
      })
      .then((willDelete) => {
        if (willDelete) {
          $('.ftransfer').submit();
        }
      });
  });

  $(document).on('click', '.btn-verifikasi-finance', function(e) {
    e.preventDefault();

    let trade_id = $(this).attr('trade_id');

    $('[name="trade_id"]').val(trade_id);

    $('.mfinance').modal('show');
  });

  $(document).on('click', '.btn-check-verification-data', function(e) {
    e.preventDefault();

    let type = $(this).attr('type');

    if (type == 'detail') {
      $('.btn-verify').hide();
      $('.modal-title').text('Detail Data');
    } else {
      $('.btn-verify').show();
      $('.modal-title').text('Verification Data');

    }

    $('.member_name').text('Loading Data...');
    $('.member_email').text('Loading Data...');
    $('.member_phone_number').text('Loading Data...');

    $('.serial_number').text('Loading Data...');
    $('.product_name').text('Loading Data...');
    $('.purchase_date').text('Loading Data...');
    $('.purchase_location').text('Loading Data...');

    $('.purchase_invoice').attr('src', null);

    $('.submit_trade_in_date').text('Loading Data...');
    $('.ktp').text('Loading Data...');
    $('.rekening').text('Loading Data...');
    $('.bank').text('Loading Data...');
    $('.atas_nama_rekening').text('Loading Data...');


    $('.foto_ktp').attr('src', null);

    $('.foto_barang').attr('src', null);

    $('.transfer_finance').attr('src', null);

    $('.buktitransfer').hide();
    
    

    let trade_id = $(this).attr('trade_id');
    let url = '{{ url("artmin/promotion/tradein/get_data_verification") }}' + '/' + trade_id;

    $('.btn-verify').attr('trade_id', trade_id);

    $.get(url, function(retData) {
      let data = JSON.parse(retData);

      $('.member_name').text(data.member.name);
      $('.member_email').text(data.member.email);
      $('.member_phone_number').text(data.member.phone);

      $('.serial_number').text(data.reg_warranty.serial_no);
      $('.product_name').text(data.reg_warranty.product_name_odoo);
      $('.purchase_date').text(data.reg_warranty.purchase_date);
      $('.purchase_location').text(data.reg_warranty.purchase_loc);

      $('.purchase_invoice').attr('src', data.reg_warranty.files);

      $('.submit_trade_in_date').text(data.tradein.created_at);
      $('.ktp').text(data.tradein.ktp);
      $('.rekening').text(data.tradein.no_rekening);
      $('.bank').text(data.tradein.nama_bank);
      $('.kota_tempat_rekening_dibuka').text(data.tradein.kota_tempat_rekening_dibuka);
      $('.atas_nama_rekening').text(data.tradein.atas_nama_rekening);


      $('.foto_ktp').attr('src', data.tradein.foto_ktp);

      $('.foto_barang').attr('src', data.tradein.foto_barang);

      if(data.tradein.transfer_finance){
        $('.buktitransfer').show();
        $('.transfer_finance').attr('src', data.tradein.transfer_finance);
      }else{
        $('.buktitransfer').hide();
      }

    });


    $('.mverification').modal('show');
  });

  $(document).on('click', '.btn-verify', function(e) {
    e.preventDefault();

    swal({
        title: "Confirmation",
        text: "Are you sure to verify this trade in",
        icon: "info",
        buttons: true
      })
      .then((willDelete) => {
        if (willDelete) {
          let url = '{{ url("artmin/promotion/tradein/verified") }}';
          let data = {
            "_token": "{{ csrf_token() }}",
            "trade_id": $(this).attr('trade_id')
          }

          console.log(data);
          $.post(url, data, function(e) {
            swal('Success', 'Trade In Has Been Verified', 'success').then((confirm) => location.reload());
          });
        }
      });
  });
</script>

@endsection