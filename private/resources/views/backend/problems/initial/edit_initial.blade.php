@extends('backend.layouts.backend-app')
@section('title', 'Edit Initial Problem')
@section('content')
<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Initial Problem
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
            <form class="" action="{{ url('artmin/problem-initial/edit-problem-initial-process') }}" method="post" enctype="multipart/form-data">
              {{ csrf_field() }}
              <input type="hidden" name="initial_id" value="{{ ($problems_initial->problem_initial_id ?? null) }}">
              <div class="form-group">
                <label>Initial</label>
                <input type="text" class="form-control" name="initial" value="{{ ($problems_initial->initial ?? null) }}" placeholder="Initial">
                @if ($errors->has('initial'))
                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('initial') }}</label>
                @endif
              </div>

              <div class="form-group">
                <button class="btn btn-primary"><i class="fa fa-plus"></i> Edit Initial</button>
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