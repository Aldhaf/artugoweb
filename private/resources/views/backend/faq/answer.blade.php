@extends('backend.layouts.backend-app')
@section('title', 'Answer')
@section('content')

<div>
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Answer
			<small>List</small>
		</h1>
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- Small boxes (Stat box) -->
		<div class="row">

			<div class="col-sm-12">
				<div class="box box-solid">
					<div class="box-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <button class="btn btn-primary btn-add"><i class="fa fa-plus"></i> Add Answer</button>
                                </div>
                                <div class="col-md-6"></div>
                            </div>
                        </div>
						<div class="col-sm-12 table-responsive">
							<table class="table data-table table-sm table-bordered table-hover answer-table">
								<thead>
									<tr>
										<th width="22"><label>#</label></th>
										<th><label>Description</label></th>
										<th width="22"><center><label style="width: 80px;">Action</label></center></th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>

		</div><!-- /.row -->
	</section><!-- /.content -->
</div>

<div class="modal fade m-export" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Answer</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <form class="fdata" method="post" action="{{url('artmin/faq/answer')}}">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="0" />
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">Description <span style="color:red">*</span></label>
                            <textarea class="form-control" placeholder="Description" name="description"></textarea>
                        </div>
                    </div>
                </form>
			</div>
			<div class="modal-footer">
                <button type="button" class="btn btn-primary btn-submit">Submit</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>

<script>

    $(document).on('click', '.btn-add', function(e) {
        e.preventDefault();
        $(".fdata [name='id']").val(0);
        $(".fdata [name='description']").val("");
        $('.modal').modal('show');
    });

    $(document).on('click', '.btn-submit', function(e) {
        e.preventDefault();
        if ($(".fdata [name='description']").val() === "") {
            swal('Perhatian', 'Description tidak boleh kosong!', 'error');
        } else {
            $('.fdata').submit();
        }
    });

	var answer_id;

	$(document).on('click', '.btn-delete', function(e) {
		e.preventDefault();

        answer_id = $(this).attr('data-id');

        swal({
				title: "Konfirmasi",
				text: "Yakin akan menghapus data?",
				icon: "warning",
				buttons: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					let url = `{{url('artmin/faq/delete-answer')}}/${answer_id}`;
					$.post(url, { "_token": "{{ csrf_token() }}" }, function(e) {
						swal('Berhasil', 'Data telah dihapus.', 'success').then((confirm) => location.reload());
					});
				}
			});

    });

    $(document).on('click', '.btn-edit', function(e) {
		e.preventDefault();
		answer_id = $(this).attr('data-id');
        
        const data = $('.answer-table').DataTable().data()[$(this).attr('data-idx')];
        if (data) {
            $(".fdata [name='id']").val(data.id);
            $(".fdata [name='description']").val(data.description);
            $('.modal').modal('show');
        }

	});

	$(document).ready( function() {
		$('.data-table').DataTable().clear().destroy();
		$('.answer-table').DataTable({
			paging: true,
			searching: true,
			processing: true,
			serverSide: true,

			retrieve: true,
			responsive: false,
			destroy: true,

			ajax: '{!! route('artmin.faq-answer.list') !!}', // memanggil route yang menampilkan data json
			columns: [
				{
					data: "id",
					name: "id",
					render: function (data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				},
				{
					data: 'description',
					name: 'description',
					searchable: true
				},
				{
					data: null,
					searchable: false,
					render : function (data, type, row,meta) {
						return `
							<center>
                                <button title="Edit" class="btn btn-primary btn-xs btn-edit" data-idx="${meta.row}" data-id="${row.id}"><i class="fa fa-edit"></i></button>
                                <button title="Delete" class="btn btn-danger btn-xs btn-delete" data-id="${row.id}"><i class="fa fa-trash"></i></button>
							</center>
						`;
					}
				}
			],
			order: [[0, 'asc' ]],
			drawCallback: function (settings) { 
			},
		});

	});
</script>

@endsection