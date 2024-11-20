@extends('backend.layouts.backend-app')
@section('title', ($statusAction == 'insert' ? 'Add' : 'Edit') . ' Question')
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      {{ ($statusAction == 'insert' ? 'Add' : 'Edit') }} Question
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
            <form class="fdata">
              {{ csrf_field() }}
              <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-3">
                    <label for="">Product Code</label>
                    <input type="text" class="form-control" placeholder="Product Code" name="product_code">
                  </div>
                  <div class="col-md-9">
                    <label for="">Question</label>
                    <input type="text" name="quiz_question" id="" class="form-control" placeholder="Tuliskan Pertanyaan...">
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-md-12">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>Option</th>
                          <th>Answare</th>
                          <th>Correct</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>
                            <input type="hidden" name="option[]" value="A">
                            <center>A</center>
                          </td>
                          <td><input type="text" class="form-control" name="option_answer[]" placeholder="Tuliskan Opsi Jawaban..."></td>
                          <td>
                            <center>
                              <button data-option="a" class="btn_correct btn_correct_a btn btn-primary">Correct</button>
                              <input type="hidden" value="1" name="iscorrect[]" class="form-control answ_correct correct_a">
                            </center>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <input type="hidden" name="option[]" value="B">
                            <center>B</center>
                          </td>
                          <td><input type="text" class="form-control" name="option_answer[]" placeholder="Tuliskan Opsi Jawaban..."></td>
                          <td>
                            <center>
                              <button data-option="b" class="btn_correct btn_correct_b btn">Incorrect</button>
                              <input type="hidden" value="0" name="iscorrect[]" class="form-control answ_correct correct_b">
                            </center>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <input type="hidden" name="option[]" value="C">
                            <center>C</center>
                          </td>
                          <td><input type="text" class="form-control" name="option_answer[]" placeholder="Tuliskan Opsi Jawaban..."></td>
                          <td>
                            <center>
                              <button data-option="c" class="btn_correct btn_correct_c btn">Incorrect</button>
                              <input type="hidden" value="0" name="iscorrect[]" class="form-control answ_correct correct_c">
                            </center>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <input type="hidden" name="option[]" value="D">
                            <center>D</center>
                          </td>
                          <td><input type="text" class="form-control" name="option_answer[]" placeholder="Tuliskan Opsi Jawaban..."></td>
                          <td>
                            <center>
                              <button data-option="d" class="btn_correct btn_correct_d btn">Incorrect</button>
                              <input type="hidden" value="0" name="iscorrect[]" class="form-control answ_correct correct_d">
                            </center>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
        <center>
          <a href="{{ url('artmin/quiz') }}">
            <button class="btn btn-default">Back</button>
          </a>
          <button class="btn btn-primary btn-save">Save</button>
          <!-- <button class="btn btn-primary">Save & Insert Next</button> -->
        </center>
      </div>

    </div><!-- /.row -->
  </section><!-- /.content -->
</div>


<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>


<script>
  $('.select2').select2();

  $(document).on('click', '.btn_correct', function(e) {
    e.preventDefault();

    let idx_option = $(this).attr('data-option');

    $('.btn_correct').removeClass('btn-primary');
    $('.btn_correct').text('Incorrect');

    $('.btn_correct_' + idx_option).addClass('btn-primary');
    $('.btn_correct_' + idx_option).text('Correct');

    $('.answ_correct').val('0');
    $('.correct_' + idx_option).val('1');
  });

  $(document).on('click', '.btn-save', function(e) {
    e.preventDefault();

    $('.fdata').submit();
  });

  $(document).on('submit', '.fdata', function(e) {
    e.preventDefault();

    swal({
        title: "Save Data",
        text: "Are you sure to submit this data?",
        icon: "info",
        buttons: true
      })
      .then((willDelete) => {
        if (willDelete) {
          let url = '{{ url("artmin/quiz/question/add") }}';
          let data = $(this).serializeArray();

          $.post(url, data, function(r) {
            swal('Success', 'Data Has Been Saved', 'success').then((confirm) => location.reload());
          });
        }
      });
  });
</script>

@endsection