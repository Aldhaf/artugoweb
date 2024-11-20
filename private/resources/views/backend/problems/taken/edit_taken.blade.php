@extends('backend.layouts.backend-app')
@section('title', 'Edit Symptom')
@section('content')
<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Symptom
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
            <form class="" action="{{ url('artmin/problem-symptom/edit-problem-symptom-process') }}" method="post" enctype="multipart/form-data">
              {{ csrf_field() }}
              <input type="hidden" name="symptom_id" value="{{ ($problem_symptom->id ?? null) }}">
              <div class="form-group">
                <label>Pilih Kategori</label>
                <select class="form-control select2" name="problem_initial_id">
                  <option value="">Select Category</option>
                  <?php foreach ($problems_initial as $pi) : ?>
                    <option value="<?= $pi->problem_initial_id ?>" {{ ( !empty($problem_symptom->problem_initial_id) ? ($problem_symptom->problem_initial_id == $pi->problem_initial_id ? "selected='true'" : null) : null ) }}><?= $pi->initial; ?></option>
                  <?php endforeach; ?>
                </select>
                @if ($errors->has('problem_initial_id'))
                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('service_center') }}</label>
                @endif
              </div>
              <div class="form-group">
                <label>Symptom</label>
                <input type="text" class="form-control" name="symptom" value="{{ ($problem_symptom->symptom ?? null) }}" placeholder="Symptom">
                @if ($errors->has('symptom'))
                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('symptom') }}</label>
                @endif
              </div>


              <div class="form-group">
                <button class="btn btn-primary"><i class="fa fa-plus"></i> Edit Symptom</button>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div><!-- /.row -->
  </section><!-- /.content -->
</div>

<script type="text/javascript">

</script>

@endsection