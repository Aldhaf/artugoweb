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
          <form class="fdata" method="POST" action="{{ url('artmin/quiz/question/submit') }}">
            {{ csrf_field() }}
            <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">
            <input type="hidden" name="duration">
            <div class="box-body">
              <div class="col-sm-12 ">
                <b>Time Remaining :</b> <span class="second_remaining">0</span>
                <br><br>
                @foreach($quiz_question as $key => $val)
                <?php
                  $data_choice = DB::table('quiz_choice')->where('quiz_question_id', $val->id)->get();
                  $data_answer_choice = DB::table('quiz_answer_detail')->where('quiz_answer_id', $quiz_answered_unfinished->id)->get();
                ?>
                <div class="row">
                  <div class="col-md-12">
                    <b>{{ $key + 1 . '. ' . $val->soal}}</b>
                    @foreach($data_choice as $dc_key => $dc)
                    <div class="form-check">
                      <input class="" type="radio" name="answer_{{$val->id}}" value="{{ $dc->id }}" id="flexRadioDefault{{ $key .'-'.$dc_key }}">
                      <label style="font-weight: 500;" for="flexRadioDefault{{ $key .'-'.$dc_key }}">
                        {{ $dc->answer }}
                      </label>
                    </div>
                    @endforeach
                  </div>
                </div>
                <br>
                @endforeach
              </div>
            </div>
          </form>
          <center>
            <button class="btn btn-primary btn-submit">Submit Answer</button>
          </center>
        </div>
      </div>

    </div><!-- /.row -->
  </section><!-- /.content -->
</div>


<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>

<script>

  var totalQuestion = {{count($quiz_question)}};

  var quizId = "{{ $quiz->id }}";
  var userId = "{{ Auth::user()->id }}";
  var quizAnswerId = "{{ $quiz_answered_unfinished ? $quiz_answered_unfinished->id : 0 }}";
  var quizSessionId = `apc_${userId}_quiz_${quizId}`;

  $(function(){
    init();
  });

  function init() {

    let quizState = localStorage.getItem(quizSessionId);

    if (quizState) {
      quizState = JSON.parse(quizState);
    }

    if (!quizState) {

      quizState = {
        quizId: "{{ $quiz->id }}",
        apcID: "{{ Auth::user()->id }}",
        remaining: Number("{{ $quiz->time_limit }}") * 60,
        duration: 0
      };

      localStorage.setItem(quizSessionId, JSON.stringify(quizState));

    }

    $('.second_remaining').text(quizState.remaining + " detik ");
    $('[name="duration"]').val(quizState.duration);

    countdown();

  }

  function countdown() {
    setInterval(function() {
      let quizState = localStorage.getItem(quizSessionId);
      if (!quizState) {
        return;
      }
      quizState = JSON.parse(quizState);
      quizState.remaining -= 1;
      quizState.duration += 1;
      localStorage.setItem(quizSessionId, JSON.stringify(quizState));
      $('.second_remaining').text(quizState.remaining + " detik ");
      $('[name="duration"]').val(quizState.duration);
      if (quizState.remaining < 1) {
        $('.fdata').submit();
      }
    }, 1000);
  }

  $(document).on('click', '.btn-submit', function(e) {
    e.preventDefault();

    if ($("input[name*='answer_']:checked").length < totalQuestion) {
      swal({
        title: "Warning",
        text: "Please complete the answer!",
        icon: "warning",
        buttons: false
      });
      return;
    }

    swal({
        title: "Submit Answer",
        text: "Are you sure to submit this answer?",
        icon: "info",
        buttons: true
      })
      .then((willDelete) => {
        if (willDelete) {
          $('.fdata').submit();
        }
      });
  });

</script>


@endsection