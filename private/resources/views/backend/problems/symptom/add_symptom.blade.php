@extends('backend.layouts.backend-app')
@section('title', 'Add New Symptom Problem')
@section('content')
<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Symptom Problem
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
            <form class="" action="{{ url('artmin/problem-symptom/add-problem-symptom-process') }}" method="post" enctype="multipart/form-data">
              {{ csrf_field() }}
              <div class="form-group">
                <label>Select Category</label>
                <select class="form-control select2" name="problem_initial_id">
                  <option value="">Select Category</option>
                  <?php foreach ($problems_initial as $pi) : ?>
                    <option value="<?= $pi->problem_initial_id ?>" <?php if (old('problem_initial_id') == $pi->problem_initial_id) echo "selected"; ?>><?= $pi->initial; ?></option>
                  <?php endforeach; ?>
                </select>
                @if ($errors->has('problem_initial_id'))
                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('problem_initial_id') }}</label>
                @endif
              </div>
              <div class="form-group">
                <label>Select Type</label>
                <select class="form-control select2" name="type">
                  <option value="1">Service Request</option>
                  <option value="0">Installation Request</option>
                </select>
                @if ($errors->has('type'))
                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('type') }}</label>
                @endif
              </div>
              <div class="form-group">
                <label>Symptom</label>
                <input type="text" class="form-control" name="symptom" value="{{ old('symptom') }}" placeholder="Symptom">
                @if ($errors->has('symptom'))
                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('symptom') }}</label>
                @endif
              </div>

              <div class="form-group">
                <button class="btn btn-primary"><i class="fa fa-plus"></i> Add New Symptom Problem</button>
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
</script>

@endsection