@extends('backend.layouts.backend-app')
@section('title', 'Quiz Question')
@section('content')

<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Quiz Question
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
              <div class="row">
                <div class="col-md-6">
                  <a href="{{ url('artmin/quiz/question/add/' . $quiz->id) }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Question</a>
                </div>
              </div>
            </div>
            <div class="col-sm-12 table-container">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Question</th>
                    <th>Answer</th>
                    <!-- <th>Action</th> -->
                  </tr>
                </thead>
                <tbody>
                  @foreach($quiz_question as $key => $val)
                  <?php
                  $data_choice = DB::table('quiz_choice')->where('quiz_question_id', $val->id)->get();
                  ?>
                  <tr>
                    <th>{{ $key + 1 }}</th>
                    <th>{{ $val->soal }}</th>
                    <th>{{ $val->option }}</th>
                    <td>
                      @foreach($data_choice as $dc)
                  <tr>
                    <td></td>
                    <td colspan="2">{{ $dc->option }}. {{ $dc->answer }}</td>
                  </tr>
                  @endforeach
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


<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>




@endsection