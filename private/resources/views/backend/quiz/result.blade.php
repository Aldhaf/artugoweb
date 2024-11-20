@extends('backend.layouts.backend-app')
@section('title', 'Quiz Question')
@section('content')

<link rel="stylesheet" type="text/css" href="{{url('assets/backend/plugins/daterangepicker/daterangepicker.css?v=3.1')}}" />
<script type="text/javascript" src="{{ url('assets/plugins/datepicker/bootstrap-datepicker.js') }}"></script>

<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      {{ $quiz->name }}
      <small>Participants</small>
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
								<div class="col-md-12 pull-right">
									<button style="margin-right: 10px;" class="btn btn-primary pull-right btn-export-excel"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Excel</button>
								</div>
							</div>
						</div>
            <div class="col-sm-12 table-container">
              <table class="table table-bordered data-table-ns">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Waktu Mulai</th>
                    <th>Durasi Pengerjaan</th>
                    <th>APC</th>
                    <th>Jawaban Benar</th>
                    <th>Jawaban Salah</th>
                    <th>Score</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($quiz_answer as $key => $val)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ date('d M Y - H:i:s',strtotime($val->created_at)) }}</td>
                    <td>{{ (!empty($val->duration) ? number_format((float)$val->duration / 60, 2, '.', '') .' menit ('.$val->duration .' detik'.')'  : null) }}</td>
                    <td>{{ $val->apcName }}</td>
                    <td>{{ $val->count_true }}</td>
                    <td>{{ $val->count_false }}</td>
                    <td>{{ $val->score }}</td>
                    <td class="{{ $val->deleted_at != null ? 'text-danger' : 'text-success' }}">{{ $val->deleted_at != null ? 'Batal' : 'Selesai' }}</td>
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

<div class="modal m-export-excel" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Export Excel</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<label for="">Tanggal</label>
						<input type="text" class="form-control dp_range" placeholder="Tanggal" name="period">
					</div>
				</div>
        <div class="row mt-2">
          <div class="col-md-12">
            <div class="form-group">
              <label>Status</label>
              <select class="select2 form-control" name="status">
                <option value="all">All</option>
                <option value="retry">Retry</option>
                <option value="completed">Completed</option>
              </select>
            </div>
          </div>
        </div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-exec-export" type="excel">Export</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script src="{{url('assets/backend/plugins/daterangepicker/moment.min.js')}}"></script>
<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{url('assets/backend/plugins/daterangepicker/daterangepicker.min.js?v=3.1')}}"></script>

<script>
  $('.dp_range').daterangepicker({
		autoUpdateInput: false,
		allowEmpty: true,
		locale: {
			format: 'YYYY-MM-DD'
		}
	});
  $('.dp_range').on('apply.daterangepicker', function(ev, picker) {
		$(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
	});
	$('.dp_range').on('cancel.daterangepicker', function(ev, picker) {
		$(this).val('');
	});

  $(document).on('click', '.btn-export-excel', function(e) {
		e.preventDefault();
		$('.m-export-excel').modal('show');
	});
  $(document).on('click', '.btn-exec-export', function(e) {
		e.preventDefault();
		let tanggal = $('[name="period"]').val();
    if (tanggal === "") {
      swal({
        title: "Informasi",
        text: "Pilih Tanggal!",
        icon: "info",
        buttons: false
      });
      return;
    }
		let tglFrom = tanggal.split(' ')[0].split('/').join('-');
		let tglTo = tanggal.split(' ')[2].split('/').join('-');

		let url = "{{ url('artmin/quiz/result_export/') }}" + '/' + tglFrom + '/' + tglTo + '/' + {{$quiz->id}} + '/' + $("select[name=status]").val();
		window.open(url, '_blank');
	});
</script>

@endsection