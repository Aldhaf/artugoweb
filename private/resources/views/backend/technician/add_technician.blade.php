@extends('backend.layouts.backend-app')
@section('title', 'Add New Technician')
@section('content')
<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Technician
      <small>New</small>
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">

      <div class="col-md-6">
        <div class="box box-solid">
          <!-- <div class="box-header">
			          <h3 class="box-title">Products</h3>
			        </div> -->
          <div class="box-body">
            @include('backend.layouts.alert')
            <form class="" action="{{ url('artmin/technician/add-technician-process') }}" method="post" enctype="multipart/form-data">
              {{ csrf_field() }}
              <div class="form-group">
                <label>Service Center</label>
                <select class="form-control select2" name="sc_id">
                  <option value="">Select Service Center</option>
                  <?php foreach ($servicecenter as $sc) : ?>
                    <option value="<?= $sc->sc_id ?>" <?php if (old('sc_id') == $sc->sc_id) echo "selected"; ?>><?= $sc->sc_location; ?></option>
                  <?php endforeach; ?>
                </select>
                @if ($errors->has('sc_id'))
                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> Select Service Center wajib diisi!</label>
                @endif
              </div>
              <div class="form-group">
                <label>Technician Type</label>
                <select class="form-control select2" name="technician_type_id">
                  <option value="">Select Technician Type</option>
                  <?php foreach ($technician_type as $tt) : ?>
                    <option value="<?= $tt->id ?>" <?php if (old('technician_type_id') == $tt->id) echo "selected"; ?>><?= $tt->code . ' | ' . $tt->description; ?></option>
                  <?php endforeach; ?>
                </select>
                @if ($errors->has('technician_type_id'))
                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> Technician Type wajib diisi!</label>
                @endif
              </div>
              <div class="form-group">
                <label>Technician</label>
                <input type="text" class="form-control" name="technician" value="{{ old('technician') }}" placeholder="Technician">
                @if ($errors->has('technician'))
                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('technician') }}</label>
                @endif
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" class="form-control" name="phone" value="{{ old('phone') }}" placeholder="Phone Number">
                    @if ($errors->has('phone'))
                    <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('phone') }}</label>
                    @endif
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Email</label>
                    <input type="text" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email">
                    @if ($errors->has('email'))
                    <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('email') }}</label>
                    @endif
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Join Date</label>
                    <input type="text" class="form-control datepicker" name="join_date" id="join_date" value="{{ old('join_date', date("d-m-Y")) }}" placeholder="dd-mm-yyyy">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Odoo User ID</label>
                    <input type="text" class="form-control" name="odoo_user_id" value="{{ old('odoo_user_id') }}" placeholder="Odoo User ID">
                    @if ($errors->has('odoo_user_id'))
                    <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('odoo_user_id') }}</label>
                    @endif
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Cover Area</label>
                    <input type="text" class="form-control" name="cover_area" value="{{ old('cover_area') }}" placeholder="Cover Area">
                    @if ($errors->has('cover_area'))
                    <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('cover_area') }}</label>
                    @endif
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-group">
                    <?php
                      $status = old('status', 'off');
                      if (is_string($status)) {
                        $status = ($status == 'on' ? 1 : 0);
                      } else {
                        $status = intval($status);
                      }
                    ?>
                    <label>Active</label>
                    <input type="checkbox" id="status_chk" name="status" <?php echo $status == 1 ? "checked" : ""; ?> />
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-group">
                    <button class="btn btn-primary"><i class="fa fa-plus"></i> Add New Technician</button>
                  </div>
                </div>

              </div>

            </form>
          </div>
        </div>
      </div>
    </div><!-- /.row -->
  </section><!-- /.content -->
</div>

<script type="text/javascript">
  $('#customer_type').on('change', function() {
    var type = $('#customer_type').val();

    $('#form-exist-customer').addClass('hide');
    $('#form-new-customer').addClass('hide');
    if (type == 1) {
      $('#form-new-customer').removeClass('hide');
    } else if (type == 2) {
      $('#form-exist-customer').removeClass('hide');
    }

  });

  $('#status_chk').on('change', function() {
    $('#status').val(this.value === "on" ? 1 : 0);
  });
</script>

@endsection