@extends('backend.layouts.backend-app')
@section('title', 'Slide Show')
@section('content')

<link rel="stylesheet" type="text/css" href="{{url('assets/backend/plugins/daterangepicker/daterangepicker.css?v=3.1')}}" />
<script type="text/javascript" src="{{ url('assets/plugins/datepicker/bootstrap-datepicker.js') }}"></script>

<div>
	<section class="content-header">
		<h1>
            Slide Show
			<small>List</small>
		</h1>
	</section>
	<section class="content">
		<div class="row mb-4">
			<div class="col-sm-12">
				<button class="btn btn-success btn-create-edit pull-right">Add &nbsp;&nbsp;<i class="fa fa-plus"></i></button>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-solid">
					<div class="box-body">

						<div class="col-sm-12 table-responsive">
							<table class="table data-table table-sm table-bordered table-hover slideshow-table">
								<thead>
									<tr>
										<th><label>#</label></th>
										<th><label style="width: 100px;">Title</label></th>
										<th><label style="width: 100px;">Sub Title</label></th>
										<th><label style="width: 160px;">Content</label></th>
										<th><label style="width: 100px;">Desktop</label></th>
										<th><label style="width: 100px;">Mobile</label></th>
										<th><label style="width: 100px;">Click Link</label></th>
										<th><label style="width: 80px;">Button Text</label></th>
										<th><label style="width: 60px;">Display</label></th>
										<th><label style="width: 50px;">Ordering</label></th>
										<th><center><label style="width: 80px;"></label></center></th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<div class="modal m-slideshow" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Slide Show Image</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="fdata" enctype="multipart/form-data" action="{{ url('artmin/slide-show') }}" method="POST">
          {{ csrf_field() }}
          <input type="hidden" name="id">
		  <input type="hidden" name="type">
		  <input type="hidden" name="image_old">
		  <input type="hidden" name="image_mobile_old">
          <div class="row">
            <div class="col-md-6">
              <label>Image Web</label>
              <img id="imgPreview" style="width:100%">
              <br>
              <input type="file" id="imgInp" class="form-control" name="image">
            </div>
            <div class="col-md-6">
              <label>Image Mobile</label>
              <img id="imgPreview_mobile" style="width:100%">
              <br>
              <input type="file" id="imgInp_mobile" class="form-control" name="image_mobile">
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12">
              <label for="title">Title Text</label>
              <input type="text" class="form-control" placeholder="Title Text" name="title">
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12">
              <label for="sub_title">Sub-Title Text</label>
              <input type="text" class="form-control" placeholder="Sub-Title Text" name="sub_title">
            </div>
          </div>
		  <br>
          <div class="row">
            <div class="col-md-12">
              <label for="url">Clik Link</label>
              <input type="text" class="form-control" placeholder="URL" name="url">
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-6">
              <label for="btn_text">Button Text</label>
              <input type="text" class="form-control" placeholder="Button Text" name="btn_text">
            </div>
            <div class="col-md-6">
              <label for="ordering">Ordering</label>
			  <input type="number" class="form-control" placeholder="Ordering" name="ordering">
            </div>
          </div>
		  <br>
          <div class="row">
            <div class="col-md-12">
              <label for="content">Content</label>
              <textarea type="text" class="form-control" placeholder="Content" name="content" rows="4" allow-ascii="true"></textarea>
            </div>
          </div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">Save&nbsp;&nbsp;<i class="fa fa-save"></i></button>
			</div>
        </form>
      </div>
    </div>
  </div>
</div>


<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>

@if ($errors->has('image') || $errors->has('image_mobile'))
<script type="text/javascript">
	swal({
		title: "Perhatian",
		text: "Gagal menyimpan data karena image tidak boleh kosong!",
		icon: "error",
		dangerMode: true,
	});
</script>
@endif

<script>

	imgInp.onchange = evt => {
		const [file] = imgInp.files
		if (file) {
			imgPreview.src = URL.createObjectURL(file)
		}
	}

	imgInp_mobile.onchange = evt => {
		const [file] = imgInp_mobile.files
		if (file) {
			imgPreview_mobile.src = URL.createObjectURL(file)
		}
	}

	$(document).on('click', '.btn-create-edit', function(e) {
		e.preventDefault();

		const dataIndex = $(this).attr("data-index");

		let data = { type: "1", title: "", sub_title: "", image: "", image_mobile: "", url: "", btn_text: "", ordering: "", content: "" };
		if (dataIndex >= 0) {
			data= $('.data-table').DataTable().data()[dataIndex];
		}

		$("input[name='id']").val(data.id);
		$("input[name='type']").val(data.type);
		$("input[name='title']").val(data.title);
		$("input[name='sub_title']").val(data.sub_title);

		$("input[name='image_old']").val(data.image);
		$("#imgPreview").attr("src",data.image);
		$("input[name='image_mobile_old']").val(data.image_mobile);
		$("#imgPreview_mobile").attr("src",data.image_mobile);

		$("input[name='url']").val(data.url);
		$("input[name='btn_text']").val(data.btn_text);
		$("input[name='ordering']").val(data.ordering);

		$("textarea[name='content']").val(data.content || "");
		// $("textarea[name='content']").html(data.content || "");

		$('.m-slideshow').modal('show');
	});

	$(document).on('click', '.btn-display', function(e) {
		e.preventDefault();
		let id = $(this).attr('data-id');
		let display = $(this).attr('data-display');

		swal({
				title: "Konfirmasi",
				text: display === "0" ? "Slide show akan di tampilkan di halaman utama." : "Slide show akan di sembunyikan dari halaman utama.",
				icon: "warning",
				buttons: true,
				dangerMode: true,
			})
			.then((confirm) => {
				if (confirm) {
					let url = '{{ url("artmin/slide-show/show-hide") }}';
					let data = {
						"_token": "{{ csrf_token() }}",
						"id": id,
						"display": display === "0" ? "1" : "0"
					};
					$.post(url, data, function(r) {
						swal('Success', 'Data berhasil diubah.', 'success').then((confirm) => location.reload());
					});
				}
			});
	});

	$(document).ready( function() {
		$('.data-table').DataTable().clear().destroy();
		$('.slideshow-table').DataTable({
			paging: true,
			searching: true,
			processing: true,
			serverSide: true,

			retrieve: true,
			responsive: false,
			destroy: true,

			ajax: '{!! route('artmin.slideshow.list') !!}', // memanggil route yang menampilkan data json
			columns: [
				{
					data: "id",
					name: "id",
					render: function (data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				},
				{
					data: 'title',
					name: 'title',
					searchable: true
				},
				{
					data: 'sub_title',
					name: 'sub_title',
                    searchable: true
					// render : function (data, type, row) {
					// 	return moment(data).format('DD-MM-YYYY');
					// }
				},
				{
					data: 'content',
					name: 'content',
                    searchable: true,
				},
				{
					data: 'image',
					name: 'image',
					searchable: false,
					render : function (data, type, row) {
                        return `<img src="${row.image}" style="max-width: unset !important; width: 100px !important;" />`;
					}
				},
				{
					data: 'image_mobile',
					name: 'image_mobile',
					searchable: false,
					render : function (data, type, row) {
                        return `<img src="${row.image_mobile}" style="max-width: unset !important; width: 80px !important; height: 100px !important;" />`;
					}
				},
				{
					data: 'url',
					name: 'url',
					searchable: false,
					render : function (data, type, row) {
                        return `<div style="width: 120px;"><a href="${row.url}" target="_blank"  rel="noreferrer noopener" style="display: block; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;" >${row.url}</a></div>`;
					}
				},
				{
					data: 'btn_text',
					name: 'btn_text',
                    searchable: false
				},
				{
					data: 'display',
					name: 'display',
                    searchable: false,
                    render : function (data, type, row) {
                        return `<button data-id="${row.id}" class="btn ${row.display == 1 ? 'btn-primary' : 'btn-default'} btn-display" data-display="${row.display}">${row.display == 1 ? 'Hide' : 'Display'}</button>`;
					}
				},
				{
					data: 'ordering',
					name: 'ordering',
					searchable: false,
				},
				{
					data: 'ordering',
					name: 'ordering',
					render : function (data, type, row, meta) {
                        return `<center><button data-id="${row.id}" data-index="${meta.row}" class="btn btn-success btn-create-edit">Edit &nbsp;&nbsp;<i class="fa fa-edit"></i></button></center>`;
					}
				}
			],
			order: [[9, 'asc' ]],
			drawCallback: function (settings) { 
			},
		});
	});
</script>

@endsection