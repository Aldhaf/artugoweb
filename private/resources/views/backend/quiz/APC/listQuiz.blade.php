@extends('backend.layouts.backend-app')
@section('title', 'Quiz Question')
@section('content')

<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Module Quiz
      <small>Data</small>
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">

      @foreach($quiz as $val)
      <div class="col-md-6 col-12">
        <div class="box box-solid">
          <div class="box-header w--100">
            <h3 class="box-title">{{ $val->name }}</h3>
            @if (!empty($val->quiz_answer_id) && $val->quiz_answer_duration > 0)
              <strong class="text-success" style="position: absolute; right: 0px; top: -8px;">DONE</strong>
            @endif
          </div>
          <div class="box-body">
            <?php
            if (!empty($val->quiz_answer_id)) {
            ?>
              <b>Hasil :</b>
              <div class="row">
                <div class="col-md-4 col-6">
                  Jawaban Benar
                </div>
                <div class="col-md-6 col-6">
                  : {{ $val->quiz_answer_count_true }}
                </div>
              </div>
              <div class="row">
                <div class="col-md-4 col-6">
                  Jawaban Salah
                </div>
                <div class="col-md-6 col-6">
                  : {{ $val->quiz_answer_count_false }}
                </div>
              </div>
              <div class="row">
                <div class="col-md-4 col-6">
                  Score
                </div>
                <div class="col-md-6 col-6">
                  : {{ $val->quiz_answer_score }}
                </div>
              </div>
              @if($val->quiz_answer_duration > 0)
              <div class="row">
                <div class="col-md-4 col-6">
                  Waktu Pengerjaan
                </div>
                <div class="col-md-6 col-6">
                  : {{ floor($val->quiz_answer_duration/60) > 0 ? floor($val->quiz_answer_duration/60) . ' Menit' : '' }} {{ $val->quiz_answer_duration%60 }} Detik
                </div>
              </div>
              @endif
              <div class="row">
                <div class="col-md-4 col-6">
                  Retry Count
                </div>
                <div class="col-md-6 col-6">
                  : {{ DB::table('quiz_answer')->where('quiz_id', $val->id)->where('apc_id', Auth::user()->id)->whereNotNull('deleted_at')->count() }}
                </div>
              </div>
              <hr>
            <?php
            }
            ?>
            <b>Keterangan</b>
            <div class="row">
              <div class="col-md-4 col-6">
                Score Benar
              </div>
              <div class="col-md-6 col-6">
                : {{ $val->score_true }}
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 col-6">
                Score Salah
              </div>
              <div class="col-md-6 col-6">
                : {{ $val->score_false }}
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 col-6">
                Time Limit
              </div>
              <div class="col-md-6 col-6">
                : {{ $val->time_limit }} Menit
              </div>
            </div>
            <br>
            <center>
              @if (empty($val->quiz_answer_id))
                <a href="javascript:onStartQuiz({{$val->id}})">
                  <button class="btn btn-primary">Start Quiz</button>
                </a>
              @elseif ($val->answer_count < 1)
                <a href="javascript:onStartQuiz({{$val->id}})">
                  <button class="btn btn-primary">Retry Quiz</button>
                </a>
              @endif
            </center>
          </div>
        </div>
      </div>
      @endforeach

    </div><!-- /.row -->
  </section><!-- /.content -->
</div>


<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>

<script>
  var userId = "{{ Auth::user()->id }}";
  function onStartQuiz(quizId) {

    swal({
      title: "Mohon Perhatian",
      text: "Jika anda sudah siap untuk mengisi Quiz ini silahkan klik OK dan mulai mengisi Quiz lalu submit jawaban anda.",
      icon: "info",
      buttons: true
    }).then((confirmed) => {
      if (confirmed) {
        localStorage.removeItem(`apc_${userId}_quiz_${quizId}`);
        location.href = `{{url('artmin/quiz/question')}}/${quizId}`;
      }
    });

  }
</script>


@endsection