@extends('backend.layouts.backend-app')
@section('title', 'FAQ Answer')
@section('content')

<style>
ol {
  padding-left: 16px;
}

ol li::marker {
	font-weight: 600;
}

tr td {
  vertical-align: top !important;
}
.answer-item {
	display: flex;
	width: 100%;
	justify-content: space-between;
	background-color: buttonface;
	border-radius: 6px;
}
.answer-item-cell {
	display: flex;
	width: 100%;
	background-color: buttonface;
	border-radius: 6px;
}
.span-ellipsis {
	overflow: hidden;
	white-space: nowrap;
	text-overflow: ellipsis;
	padding-right: 6px;
}
</style>

<div>
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Question Answer
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
                                    <button class="btn btn-primary btn-add"><i class="fa fa-plus"></i> Add Question Answer</button>
                                </div>
                                <div class="col-md-6"></div>
                            </div>
                        </div>
						<div class="col-sm-12 table-responsive">
							<table class="table data-table table-sm table-bordered table-hover question-answer-table">
								<thead>
									<tr>
										<th width="22"><label>#</label></th>
										<th><label>Category</label></th>
										<th><label>Type</label></th>
										<th><label>Question</label></th>
										<th><label>Answer</label></th>
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

<div class="modal fade m-export" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-lg modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Question Answer</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <form class="fdata" method="post" action="{{url('artmin/faq/question-answer')}}">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="0" />
					<div class="row">
						<div class="col-md-6">
							<div class="col-md-12">
								<div class="form-group">
									<label>Category <span style="color:red">*</span></label>
									<select class="form-control select2" name="category_id" id="category_id">
										<option value="">Select Category</option>
									</select>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Model <span style="color:red">*</span></label>
									<select class="form-control select2" name="subcategory_id" id="subcategory_id">
										<option value="">Select Model</option>
									</select>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Question <span style="color:red">*</span></label>
									<textarea class="form-control" placeholder="Question" name="question"></textarea>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Keywords</label>
									<textarea class="form-control" placeholder="Input Keyword for this Question..." name="keywords"></textarea>
								</div>
							</div>
						</div>
						<div class="col-md-6">

							<div class="col-md-12">
								<div class="form-group">
									<label>Answer</label>
									<select class="form-control" id="select_answer_id">
									</select>
								</div>
							</div>
							<div class="col-md-12">
								<button title="Add" class="btn btn-success btn-xs btn-add-answer pull-right"><i class="fa fa-plus"></i> Add</button>
							</div>

							<div class="col-md-12 mt-4" style="height: 35vh; overflow: auto;">
								<div style="width: 100%; display: flex; flex-direction: column;" id="answer-container">
								</div>
							</div>
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

	var question_id;
	var collapsed = {};
	var loadedAnswer = {};

	function appendAnswerListItem (answer, id) {
		$("#answer-container").append(`<div class="p-3 mb-2 answer-item">
		<input type="hidden" name="faq_answer_id[]" value="${id}" id="${id}" />
		<input type="hidden" name="answer_id[]" value="${answer.id}" id="answer-${answer.id}" />
<span class="span-ellipsis" title="${answer.text}">${answer.text}</span>
<a title="Delete" class="btn btn-danger btn-xs btn-delete-answer pull-right"><i class="fa fa-minus"></i></a>
</div>`);
	}

	function bindSelectCategories (elTargetId, parent_id = 0, callback) {
		$(elTargetId).select2().empty();
		$(elTargetId).val(null).trigger("change");

		let query = `?parent_id=${parent_id}`;

		var apiUrl = `{{url("artmin/product/categories-json")}}${query}`;
		$.get(apiUrl, function(data) {
			$(elTargetId).select2({
				data: [{ id: "", text: parent_id === 0 ? "Select Category" : "Select Model" }, ...data.map((o) => ({ id: o.category_id, text: `${o.name}` }))]
			});
			if (callback) {
				callback();
			}
		});
	}

	function loadFaqAnswer (faq_id, callback) {
		var apiUrl = `{{url("artmin/faq/faq-answer-json")}}?faq_id=${faq_id}`;
		$.get(apiUrl, function(data) {
			if (callback) {
				callback(data);
			}
		});
	}

	function initSelectAnswer () {
        if (!$("#select_answer_id").hasClass("select2")) {
            $("#select_answer_id").addClass("select2");
        }
        $("#select_answer_id").select2({
            placeholder: "Search Answer...",
            ajax: {
                url: '{{ url("artmin/faq/answer-json") }}',
                dataType: "json",
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
								id: item.id,
                                text: item.description
                            }
                        })
                    };
                },
            },
            escapeMarkup: function(m) {
                return m;
            },
            language: {
                searching: function() {
                    return "Ketik untuk mencari...";
                }
            },
            minimumInputLength: 2,
            cache: true,
        });
    }

	$(document).on('click', '.btn-add-answer', function(e) {
        e.preventDefault();
        var data = $(".fdata [id='select_answer_id']").select2("data");
		if (data && data.length > 0) {
			if ($("#answer-" + data[0].id).length > 0) {
				swal('Perhatian', 'Answer sudah ditambahkan sebelumnya, pilih yang lain!', 'info');
			} else {
				appendAnswerListItem(data[0], 0);
			}
		}
    });

	$(document).on('click', '.btn-delete-answer', function(e) {

		e.preventDefault();

		const faqAnswerId = $(this).closest("div").find("input[name='faq_answer_id[]']").val();
		const answerContent = $(this).closest("div");

		if (faqAnswerId === "0") {
			answerContent.remove();
		} else {
			swal({
				title: "Konfirmasi",
				text: "Yakin akan menghapus data?",
				icon: "warning",
				buttons: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					let url = `{{url('artmin/faq/delete-faq-answer')}}/${faqAnswerId}`;
					$.post(url, { "_token": "{{ csrf_token() }}" }, function(e) {
						answerContent.remove();
						swal('Berhasil', 'Data telah dihapus.', 'success');
					});
				}
			});
		}
    });

    $(document).on('click', '.btn-add', function(e) {
        e.preventDefault();
        $(".fdata [name='id']").val(0);
		$(".fdata [name='category_id']").val("").trigger("change");
		$(".fdata [name='subcategory_id']").val("").trigger("change");;
        $(".fdata [name='question']").val("");
		$(".fdata [name='keywords']").val("");
		$("#answer-container").html("");
        $('.modal').modal('show');
    });

    $(document).on('click', '.btn-submit', function(e) {
        e.preventDefault();

		const messages = [];
		if ($(".fdata [name='category_id']").val() === "") {
			messages.push("• Category tidak boleh kosong!");
		}
		if ($(".fdata [name='subcategory_id']").val() === "") {
			messages.push("• Model tidak boleh kosong!");
		}
		if ($(".fdata [name='question']").val() === "") {
			messages.push("• Question tidak boleh kosong!");
		}
		
        if (messages.length > 0) {
            swal('Perhatian', messages.join("\n"), 'error');
        } else {
            $('.fdata').submit();
        }
    });

	$(document).on('click', '.btn-delete', function(e) {
		e.preventDefault();

        question_id = $(this).attr('data-id');

        swal({
			title: "Konfirmasi",
			text: "Yakin akan menghapus data?",
			icon: "warning",
			buttons: true,
		})
		.then((willDelete) => {
			if (willDelete) {
				let url = `{{url('artmin/faq/delete-question-answer')}}/${question_id}`;
				$.post(url, { "_token": "{{ csrf_token() }}" }, function(e) {
					swal('Berhasil', 'Data telah dihapus.', 'success').then((confirm) => location.reload());
				});
			}
		});

    });

    $(document).on('click', '.btn-edit', function(e) {
		e.preventDefault();
		question_id = $(this).attr('data-id');
        
        const data = $('.question-answer-table').DataTable().data()[$(this).attr('data-idx')];
        if (data) {

			bindSelectCategories("#subcategory_id", data.category_id, () => {
				$(".fdata [name='subcategory_id']").val(data.subcategory_id).trigger("change");;
			});

			loadFaqAnswer(data.id, (data) => {
				for (const answer of data) {
					appendAnswerListItem({ id: answer.answer_id, text: answer.description }, answer.id);
				}
			});

			$(".fdata [name='id']").val(data.id);
			$(".fdata [name='category_id']").val(data.category_id).trigger("change");
            $(".fdata [name='question']").val(data.question);
			$(".fdata [name='keywords']").val(data.keywords);
            $('.modal').modal('show');
        }

	});

	$(document).on("click", ".btn-collapse-answer", function(e) {
		e.preventDefault();
		const id = $(this).closest("div").attr("data-id");
		collapsed[id] = !collapsed[id];
	});

	$(document).ready( function() {

		$("#category_id").on("change", function(e) {
			var data = $(this).select2("data");
			if (data && data.length > 0) {
				bindSelectCategories("#subcategory_id", data[0].id);
			}
		});

		bindSelectCategories("#category_id");

		setTimeout(() => {
			initSelectAnswer();
		}, 1000);

		$('.data-table').DataTable().clear().destroy();
		$('.question-answer-table').DataTable({
			paging: true,
			searching: true,
			processing: true,
			serverSide: true,

			retrieve: true,
			responsive: false,
			destroy: true,

			ajax: '{!! route('artmin.faq-question-answer.list') !!}', // memanggil route yang menampilkan data json
			columns: [
				{
					data: "id",
					name: "id",
					searchable: false,
					render: function (data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				},
				{
					data: 'category_name',
					name: 'category_name',
					searchable: false
				},
				{
					data: 'subcategory_name',
					name: 'subcategory_name',
					searchable: false
				},
				{
					data: 'question',
					name: 'question',
					searchable: true
				},
				{
					data: 'keywords',
					name: 'keywords',
					searchable: true,
					render: function (data, type, row, meta) {
						if (!collapsed[row.id]) {
							collapsed[row.id] = false;
						}
						if (!loadedAnswer[row.id]) {
							setTimeout(() => {
								if ($(`#faq-answer-${row.id}`).children().length === 0) {
									loadFaqAnswer(row.id, (data) => {
										var html = "";
										for (const answer of data) {
											html += `<div class="pl-2 mb-2 answer-item-cell" style="width: 300px;"><span class="span-ellipsis p-0" title="${answer.description}">${answer.description}</span></div>`;
										}
										$(`#faq-answer-${row.id}`).html(html);
									});
								}
							}, 500);
							loadedAnswer[row.id] = true;
						}

						return `<div id="faq-answer-${row.id}"></div>`;
					}
				},
				{
					data: null,
					searchable: false,
					render : function (data, type, row, meta) {
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