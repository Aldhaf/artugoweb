@extends('backend.layouts.backend-app')
@section('title', 'Cashback')
@section('content')

<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Cashback
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
              <a href="{{ url('artmin/promotion/cashback/settings') }}" class="btn btn-primary"><i class="fa fa-gear"></i> Settings</a>
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
                    <th>Nominal</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($cashback as $key => $val)
                  <?php
                  $nominal_cashback = 0;
                  if (!empty($val->foto_sertifikat_vaksin)) {
                    $nominal_cashback = $val->nominal + $val->nominal_extra;
                  } else {
                    $nominal_cashback = $val->nominal;
                  }
                  ?>
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ date('d M Y',strtotime($val->created_at)) }}</td>
                    <td>{{ $val->warranty_no }}</td>
                    <td>{{ $val->ktp }}</td>
                    <td>{{ $val->no_rekening }}</td>
                    <td>{{ $val->nama_bank }}</td>
                    <td>{{ $val->kota_tempat_rekening_dibuka }}</td>
                    <td>{{ $val->atas_nama_rekening }}</td>
                    <td>Rp. {{ number_format($nominal_cashback) }}</td>
                    <td>
                      <center>
                        <?php
                        if ($val->verified == '1') {
                        ?>
                          Cashback Disetujui
                          <!-- <i data-toggle="tooltip" title="Verified" style="color:green" class="fa fa-check-circle"></i> -->
                        <?php
                        } elseif ($val->verified == '2') {
                        ?>
                          Cashback Ditransfer
                          <!-- <i data-toggle="tooltip" title="Rejected" style="color:red" class="fa fa-times-circle"></i> -->
                        <?php
                        } else {
                        ?>
                          Cashback Diproses
                          <!-- <i data-toggle="tooltip" title="Pending" style="color:#294E5D" class="fa fa-minus-circle"></i> -->
                        <?php
                        }
                        ?>
                      </center>
                    </td>
                    <td>

                      <button style="margin:3px" class="btn btn-primary btn-xs btn-check-verification-data" cashback_id="{{ $val->cashback_id }}" type="detail" data-toggle="tooltip" title="Detail Data"><i class="fa fa-search"></i></button>
                      <?php
                      if ($val->verified == '0') {
                      ?>
                        <button style="margin:3px" class="btn btn-primary btn-xs btn-check-verification-data" cashback_id="{{ $val->cashback_id }}" type="verification" data-toggle="tooltip" title="Verification Data Check"><i class="fa fa-check-circle"></i></button>
                      <?php
                      } elseif ($val->verified == '1') {
                      ?>
                        <button style="margin:3px" class="btn btn-primary btn-xs btn-verifikasi-finance" cashback_id="{{ $val->cashback_id }}" type="verification" data-toggle="tooltip" title="Verifikasi Transfer oleh Finance"><i class="fa fa-money"></i></button>
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
                    Submit Cashback Date
                  </div>
                  <div class="col-md-6">
                    : <span class="submit_cashback_date">Loading Data...</span>
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
                <div class="row">
                  <div class="col-md-12">
                    <center>
                      <img src="" class="foto_sertifikat_vaksin" style="width:100%" alt="">
                      <br> Foto Sertifikat Vaksin
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
        <button type="button" class="btn btn-primary btn-verify" cashback_id="">Verify</button>
        <button type="button" class="btn btn-primary btn-revisi" cashback_id="">Revisi</button>
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
        <form class="ftransfer" action="{{ url('artmin/promotion/cashback/transfer') }}" method="POST" enctype="multipart/form-data">
          {{ csrf_field() }}
          <input type="hidden" name="cashback_id">
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


<div class="modal mrevisi" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Revisi Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ url('artmin/promotion/cashback/revisi') }}" enctype="multipart/form-data" method="POST" class="form_revisi">
          {{ csrf_field() }}
          <input type="hidden" name="cashback_id">
          <div class="row">
            <div class="col-md-12">
              <label for="">KTP</label>
              <input type="text" name="ktp" placeholder="KTP" class="form-control form-dark" required="true">
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12">
              <label for="">Nama Bank</label>
              <input type="text" name="nama_bank" placeholder="Nama Bank" class="form-control form-dark" required="true">
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12">
              <label for="">No Rekening</label>
              <input type="text" name="no_rekening" placeholder="No Rekening" class="form-control form-dark" required="true">
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-6">
              <label for="">Atas Nama Rekening</label>
              <input type="text" name="atas_nama_rekening" placeholder="Atas Nama Rekening" class="form-control form-dark" required="true">
            </div>
            <div class="col-md-6">
              <label for="">Kota Tempat Rekening Dibuka</label>
              <input type="text" name="kota_tempat_rekening_dibuka" placeholder="Kota Tempat Rekening Dibuka" class="form-control form-dark" required="true">
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-6">
              <label for="">Foto KTP</label>
              <img src="" class="foto_ktp" id="foto_ktp" style="width:100%" alt="">
              <input type="file" name="foto_ktp" class="form-control form-dark" required="true" id="fktp">
            </div>

            <div class="col-md-6">
              <label for="">Foto Barang</label>
              <img src="" class="foto_barang" id="foto_barang" style="width:100%" alt="">
              <input type="file" name="foto_barang" class="form-control form-dark" required="true" id="fbarang">
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12">
              <label for="">Foto Sertifikat Vaksin (Opsional)</label>
              <img src="" class="foto_sertifikat_vaksin" id="foto_sertifikat_vaksin" style="width:100%" alt="">
              <input type="file" name="foto_sertifikat_vaksin" class="form-control form-dark" id="fsertifikat">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-save-revisi">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>


<script>
  $(document).on('click', '.btn-revisi', function(e) {
    e.preventDefault();

    $('.mverification').modal('hide');
    $('.mrevisi').modal('show');
  });


  $('.mrevisi').on('hidden.bs.modal', function() {
    $('.mverification').modal('show');
  });



  $(document).ready(function() {
    function readURL(input, loc) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
          $('#' + loc).attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]); // convert to base64 string
      }
    }

    $("#imgInp").change(function() {
      readURL(this, 'blah');
    });
    $("#fktp").change(function() {
      readURL(this, 'foto_ktp');
    });
    $("#fbarang").change(function() {
      readURL(this, 'foto_barang');
    });
    $("#fsertifikat").change(function() {
      readURL(this, 'foto_sertifikat_vaksin');
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

    let cashback_id = $(this).attr('cashback_id');

    $('[name="cashback_id"]').val(cashback_id);

    $('.mfinance').modal('show');
  });

  $(document).on('click', '.btn-check-verification-data', function(e) {
    e.preventDefault();

    let type = $(this).attr('type');

    if (type == 'detail') {
      $('.btn-verify').hide();
      $('.mverification .modal-title').text('Detail Data');
    } else {
      $('.btn-verify').show();
      $('.mverification .modal-title').text('Verification Data');

    }

    $('.member_name').text('Loading Data...');
    $('.member_email').text('Loading Data...');
    $('.member_phone_number').text('Loading Data...');

    $('.serial_number').text('Loading Data...');
    $('.product_name').text('Loading Data...');
    $('.purchase_date').text('Loading Data...');
    $('.purchase_location').text('Loading Data...');

    $('.purchase_invoice').attr('src', null);

    $('.submit_cashback_date').text('Loading Data...');
    $('.ktp').text('Loading Data...');
    $('.rekening').text('Loading Data...');
    $('.bank').text('Loading Data...');
    $('.atas_nama_rekening').text('Loading Data...');


    $('.foto_ktp').attr('src', null);

    $('.foto_barang').attr('src', null);

    $('.foto_sertifikat_vaksin').attr('src', null);


    $('.transfer_finance').attr('src', null);

    $('.buktitransfer').hide();


    let cashback_id = $(this).attr('cashback_id');
    let url = '{{ url("artmin/promotion/cashback/get_data_verification") }}' + '/' + cashback_id;

    $('.btn-verify').attr('cashback_id', cashback_id);

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

      $('.submit_cashback_date').text(data.cashback.created_at);
      $('.ktp').text(data.cashback.ktp);
      $('.rekening').text(data.cashback.no_rekening);
      $('.bank').text(data.cashback.nama_bank);
      $('.kota_tempat_rekening_dibuka').text(data.cashback.kota_tempat_rekening_dibuka);
      $('.atas_nama_rekening').text(data.cashback.atas_nama_rekening);


      $('.foto_ktp').attr('src', data.cashback.foto_ktp);

      $('.foto_barang').attr('src', data.cashback.foto_barang);

      $('.foto_sertifikat_vaksin').attr('src', data.cashback.foto_sertifikat_vaksin);


      $('[name="cashback_id"]').val(data.cashback.cashback_id);
      $('[name="ktp"]').val(data.cashback.ktp);
      $('[name="no_rekening"]').val(data.cashback.no_rekening);
      $('[name="nama_bank"]').val(data.cashback.nama_bank);
      $('[name="kota_tempat_rekening_dibuka"]').val(data.cashback.kota_tempat_rekening_dibuka);
      $('[name="atas_nama_rekening"]').val(data.cashback.atas_nama_rekening);




      if (data.cashback.transfer_finance) {
        $('.buktitransfer').show();
        $('.transfer_finance').attr('src', data.cashback.transfer_finance);
      } else {
        $('.buktitransfer').hide();
      }

    });


    $('.mverification').modal('show');
  });

  $(document).on('click', '.btn-save-revisi', function(e) {
    e.preventDefault();

    swal({
        title: "Confirmation",
        text: "Are you sure to edit this data?",
        icon: "info",
        buttons: true
      })
      .then((willDelete) => {
        if (willDelete) {
          $('.form_revisi').submit();
        }
      });
  });

  $(document).on('click', '.btn-verify', function(e) {
    e.preventDefault();

    swal({
        title: "Confirmation",
        text: "Are you sure to verify this Cashback",
        icon: "info",
        buttons: true
      })
      .then((willDelete) => {
        if (willDelete) {
          let url = '{{ url("artmin/promotion/cashback/verified") }}';
          let data = {
            "_token": "{{ csrf_token() }}",
            "cashback_id": $(this).attr('cashback_id')
          }

          console.log(data);
          $.post(url, data, function(e) {
            swal('Success', 'Cashback Has Been Verified', 'success').then((confirm) => location.reload());
          });
        }
      });
  });
</script>

@endsection