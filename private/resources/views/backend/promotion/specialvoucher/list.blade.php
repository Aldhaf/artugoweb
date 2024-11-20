@extends('backend.layouts.backend-app')
@section('title', 'Special Voucher')
@section('content')

<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Special Voucher
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
              <a href="{{ url('artmin/promotion/specialvoucher/settings') }}">
                <button class="btn btn-primary"><i class="fa fa-gear"></i> Settings</button>
              </a>
            </div>
            <div class="col-sm-12 table-container">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Warranty No.</th>
                    <th>Serial Number</th>
                    <th>Member Name</th>
                    <th>Reg Name</th>
                    <th>Reg Phone Number</th>
                    <th>Reg Email</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  function check_voucher($warranty_id)
                  {
                    $check = DB::table('reg_warranty')->where('special_voucher_from', $warranty_id)->first();
                    if (!empty($check)) {
                      $ret = [
                        'status' => true,
                        'warranty_no' => $check->warranty_no
                      ];
                    } else {
                      $ret = [
                        'status' => false,
                      ];
                    }
                    return $ret;
                  }
                  ?>
                  @foreach($special_voucher as $key => $val)
                  <?php $used = check_voucher($val->warranty_id); ?>
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ date('d M Y',strtotime($val->created_at)) }}</td>
                    <td>{{ $val->warranty_no }}</td>
                    <td>{{ $val->serial_no }}</td>
                    <td>{{ $val->member_name }}</td>
                    <td>{{ $val->reg_name }}</td>
                    <td>{{ $val->reg_phone }}</td>
                    <td>{{ $val->reg_email }}</td>
                    <td>
                      <?php
                      if ($val->verified == '1') {
                        if ($used['status']) {
                          echo 'voucher has been used<br><b style="cursor:pointer">' . $used['warranty_no'] . '</b>';
                        } else {
                          echo 'voucher has not been used';
                        }
                      } else {
                        echo 'Waiting Verification Warranty';
                      }
                      ?>
                    </td>
                    <td>
                      <button style="margin:3px" class="btn btn-primary btn-xs btn-check-verification-data" data='<?php echo json_encode($val) ?>' type="verification" data-toggle="tooltip" title="Detail"><i class="fa fa-search"></i></button>
                      <!-- <button style="margin:3px" class="btn btn-primary btn-xs btn-check-verification-data" trade="{{ $val->id }}" type="detail" data-toggle="tooltip" title="Detail Data"><i class="fa fa-search"></i></button> -->
                      <?php
                      if ($val->verified == '1' && $used['status'] && empty($val->bukti_transfer)) {
                      ?>
                        <button style="margin:3px" class="btn btn-primary btn-xs btn-verifikasi-finance" special_voucher_id="{{ $val->id }}" data='<?php echo json_encode($val) ?>' type="verification" data-toggle="tooltip" title="Verifikasi Transfer Cashback oleh Finance"><i class="fa fa-money"></i></button>
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




<div class="modal mdata" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title modal-header-verification">Detail</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
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
                  : <span class="member_name"></span>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  Member Email
                </div>
                <div class="col-md-6">
                  : <span class="member_email"></span>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  Member Phone Number
                </div>
                <div class="col-md-6">
                  : <span class="member_phone_number"></span>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-md-4">
                  Serial Number
                </div>
                <div class="col-md-6">
                  : <span class="serial_number"></span>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  Product
                </div>
                <div class="col-md-6">
                  : <span class="product_name"></span>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  Purchase Date
                </div>
                <div class="col-md-6">
                  : <span class="purchase_date"></span>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  Purchase Location
                </div>
                <div class="col-md-6">
                  : <span class="purchase_location"></span>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-md-4">
                  KTP
                </div>
                <div class="col-md-6">
                  : <span class="ktp"></span>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  Nama Bank
                </div>
                <div class="col-md-6">
                  : <span class="nama_bank"></span>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  No Rekening
                </div>
                <div class="col-md-6">
                  : <span class="no_rekening"></span>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  Kota Tempat Rekening Dibuka
                </div>
                <div class="col-md-6">
                  : <span class="kota_tempat_rekening_dibuka"></span>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  Atas Nama Rekening
                </div>
                <div class="col-md-6">
                  : <span class="atas_nama_rekening"></span>
                </div>
              </div>
              <hr>

              <div class="row">
                <div class="col-md-6">
                  <center>
                    <img src="" class="foto_ktp" style="width:100%" alt="">
                    <br> Foto KTP
                  </center>
                </div>
                <div class="col-md-6">
                  <center>
                    <img src="" class="foto_barang" style="width:100%" alt="">
                    <br> Foto Barang
                  </center>
                </div>
              </div>


              <div class="row">
                <div class="col-md-12">
                  <center>
                    <img src="" class="purchase_invoice" style="width:100%" alt="">
                    <br> Purchase Invoice
                  </center>
                </div>
              </div>




              <div class="buktitransfer">
                <hr>
                <div class="row">
                  <div class="col-md-12">
                    <center>
                      <img src="" class="transfer_finance" style="width:100%" alt="">
                      <br> Bukti Transfer Cashback
                    </center>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<div class="modal mfinance" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title-finance">Verifikasi Transfer Cashback</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="ftransfer" action="{{ url('artmin/promotion/specialvoucher/transfer_cashback') }}" method="POST" enctype="multipart/form-data">
          {{ csrf_field() }}
          <input type="hidden" name="special_voucher_id">
          <div class="row">
            <div class="col-md-4">
              KTP
            </div>
            <div class="col-md-6">
              : <span class="ktp"></span>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              Nama Bank
            </div>
            <div class="col-md-6">
              : <span class="nama_bank"></span>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              No Rekening
            </div>
            <div class="col-md-6">
              : <span class="no_rekening"></span>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              Kota Tempat Rekening Dibuka
            </div>
            <div class="col-md-6">
              : <span class="kota_tempat_rekening_dibuka"></span>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              Atas Nama Rekening
            </div>
            <div class="col-md-6">
              : <span class="atas_nama_rekening"></span>
            </div>
          </div>
          <hr>

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

  $(document).on('click', '.btn-verifikasi-finance', function(e) {
    e.preventDefault();

    let special_voucher_id = $(this).attr('special_voucher_id');

    $('[name="special_voucher_id"]').val(special_voucher_id);

    let data = JSON.parse($(this).attr('data'));
    $('.ktp').text(data.ktp);
    $('.nama_bank').text(data.nama_bank);
    $('.no_rekening').text(data.no_rekening);
    $('.kota_tempat_rekening_dibuka').text(data.kota_tempat_rekening_dibuka);
    $('.atas_nama_rekening').text(data.atas_nama_rekening);

    $('.mfinance').modal('show');
  });


  function clear_val() {
    $('[name="warranty_id"]').val('');
    $('.member_name').text('');
    $('.member_email').text('');
    $('.member_phone_number').text('');
    $('.purchase_date').text('');
    $('.purchase_location').text('');
    $('.serial_number').text('');
    $('.product_name').text('');

    $('.ktp').text('');
    $('.nama_bank').text('');
    $('.no_rekening').text('');
    $('.kota_tempat_rekening_dibuka').text('');
    $('.atas_nama_rekening').text('');

    $('.purchase_invoice').attr('src', '');
  }

  $(document).on('click', '.btn-check-verification-data', function(e) {
    e.preventDefault();
    let data = JSON.parse($(this).attr('data'));
    let type = $(this).attr('type');

    if (type == 'verification') {
      $('.btn-accept-data').show();
      $('.btn-denied-data').show();
      $('.modal-header-verification').text('Detail');
    } else {
      $('.btn-accept-data').hide();
      $('.btn-denied-data').hide();
      $('.modal-header-verification').text('Revisi Data');
    }


    $('.btn-revisi-data').attr('data', $(this).attr('data'));

    clear_val();

    console.log(data);

    $('[name="warranty_id"]').val(data.warranty_id);
    $('.member_name').text(data.reg_name);
    $('.member_email').text(data.reg_email);
    $('.member_phone_number').text(data.reg_phone);
    $('.purchase_date').text(data.purchase_date);
    $('.purchase_location').text(data.purchase_loc);
    $('.serial_number').text(data.serial_no);
    $('.product_name').text(data.product_name);
    $('.purchase_invoice').attr('src', data.files);

    $('.ktp').text(data.ktp);
    $('.nama_bank').text(data.nama_bank);
    $('.no_rekening').text(data.no_rekening);
    $('.kota_tempat_rekening_dibuka').text(data.kota_tempat_rekening_dibuka);
    $('.atas_nama_rekening').text(data.atas_nama_rekening);


    $('.foto_ktp').attr('src', data.foto_ktp);

    $('.foto_barang').attr('src', data.foto_barang);


    console.log(data);

    if (data.bukti_transfer) {
      $('.buktitransfer').show();
      $('.transfer_finance').attr('src', data.bukti_transfer);
    } else {
      $('.buktitransfer').hide();
    }


    $('.mdata').modal('show');

  });
</script>

@endsection