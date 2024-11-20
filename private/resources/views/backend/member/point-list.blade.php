@extends('backend.layouts.backend-app')
@section('title', 'Member')
@section('content')
<link rel="stylesheet" type="text/css" href="{{url('assets/backend/plugins/daterangepicker/daterangepicker.css?v=3.1')}}" />
<style>
  .gap-2 {
    gap: 8px;
  }
</style>
<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Member
      <small>Point</small>
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12">
        <button class="btn btn-primary btn-toggle-filter">Filter/Search Member</button>
        <button style="margin-right: 10px;" class="btn btn-primary pull-right btn-export" rpt-type="excel"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Data Excel</button>
      </div>
    </div>
    <br>
    <div class="section-filter">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-solid">
            <div class="box-body">
              <div class="col-md-12">
                <form action="" method="GET" class="fdata_filter">
                  <div class="row">
                    <div class="col-md-4">
                      <label for="">Fullname</label>
                      <input type="text" placeholder="Fullname" class="form-control" name="name" value="{{ (isset($_GET['name']) ? $_GET['name'] : null) }}">
                    </div>
                    <div class="col-md-4">
                      <label for="">Phone Number</label>
                      <input type="number" placeholder="Phone Number" class="form-control" name="phone" value="{{ (isset($_GET['phone']) ? $_GET['phone'] : null) }}">
                    </div>
                    <div class="col-md-4">
                      <label for="">Email</label>
                      <input type="email" placeholder="Email" class="form-control" name="email" value="{{ (isset($_GET['email']) ? $_GET['email'] : null) }}">
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-md-12">
                      <button class="btn btn-primary btn-submit-filter">Submit Filter</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <br/>
    <div class="row">
      <div class="col-sm-12">
        <div class="box box-solid">
          <div class="box-body">
            <div class="col-sm-12 table-responsive">
              <table class="table data-table table-sm table-bordered table-hover member-table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Warranty</th>
                    <th>Name</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>City</th>
                    <th>Registered At</th>
                    <th>Point</th>
                    <th>Expired At</th>
                    <th><center>Status Point</center></th>
                    <th>Action</th>
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

<div class="modal mverification" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Verification Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" class="fdata">
          {{ csrf_field() }}
          <input type="hidden" name="warranty_id">
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-4">
                  Name
                </div>
                <div class="col-md-6">
                  : <span class="member_name">Loading Data...</span>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  Gender
                </div>
                <div class="col-md-6">
                  : <span class="gender first-capitalize">Loading Data...</span>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  Birth Date
                </div>
                <div class="col-md-6">
                  : <span class="birth_date">Loading Data...</span>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  Phone Number
                </div>
                <div class="col-md-6">
                  : <span class="member_phone_number">Loading Data...</span>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  KTP
                </div>
                <div class="col-md-6">
                  : <span class="ktp">Loading Data...</span>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  Email
                </div>
                <div class="col-md-6">
                  : <span class="member_email">Loading Data...</span>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  Address
                </div>
                <div class="col-md-6 d-flex flex-row">
                  <div>:&nbsp;</div><span class="address">Loading Data...</span>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  City
                </div>
                <div class="col-md-6">
                  : <span class="city">Loading Data...</span>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-md-4">
                  Warranty Number
                </div>
                <div class="col-md-6">
                  : <span class="warranty_no">Loading Data...</span>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  Serial Number
                </div>
                <div class="col-md-6">
                  : <span class="serial_no">Loading Data...</span>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  Product
                </div>
                <div class="col-md-6">
                  : <span class="product_name">Loading Data...</span>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  Purchase Date
                </div>
                <div class="col-md-6">
                  : <span class="purchase_date">Loading Data...</span>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  Product Review
                </div>
                <div class="col-md-6 d-flex flex-row">
                  <div>:&nbsp;</div><span class="testimony">Loading Data...</span>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn-verify btn-point-approve" member_point_id=""><i class="fa fa-paper-plane"></i> Approve</button>
        <button type="button" class="btn btn-danger btn-revisi btn-point-reject" member_point_id=""><i class="fa fa-close"></i> Reject</button>
      </div>
    </div>
  </div>
</div>

<div class="modal mpointadj" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="mpointadj_title">Point Adjustment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="f_adjpoint" method="POST" action="{{ url('/artmin/member/point/adjustment') }}" enctype="multipart/form-data">
          {{ csrf_field() }}
          <input type="hidden" name="member_id" id="adjpoint_member_id">
          <input type="hidden" name="adj_type" id="adjpoint_type">
          <div class="row mb-3 adjpoint_warranty_div">
            <div class="col-md-12">
              <label for="">For Warranty</label>
              <select name="warranty_id" id="adjpoint_warranty_id" class="form-control select2"></select>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-12">
              <label for="">Transaction Date</label>
              <input type="text" class="form-control datepicker" name="trx_date" id="adjpoint_trx_date" style="cursor: pointer;" placeholder="DD-MM-YYYY">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-12">
              <label for="">Value</label>
              <input name="value" id="adjpoint_value" type="number" class="form-control" placeholder="Point Adjustment Value">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-12">
              <label for="">Description</label>
              <input name="description" id="adjpoint_description" type="text" class="form-control" placeholder="Point Adjustment Description">
            </div>
          </div>
          <div class="row mb-3" id="section_ref_number">
            <div class="col-md-12">
              <label for="">Reference Number</label>
              <input name="ref_number" id="adjpoint_ref_number" type="text" class="form-control" placeholder="Reference Number Ex: SO Number">
            </div>
          </div>
          <div class="row mb-3 hidden" id="section_exp_date">
            <div class="col-md-12">
              <label for="">Expired At <span class="text-danger">( Kosongkan Jika Tanpa Batas Waktu )</span></label>
              <input type="text" class="form-control datepicker" name="expired_at" id="adjpoint_expired_at" style="cursor: pointer;" placeholder="DD-MM-YYYY">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-adjpoint-submit" id="">Submit</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal m-export" tabindex="-1" role="dialog">
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
						<label for="">Period</label>
						<input type="text" class="form-control dp_range" placeholder="Period" name="period">
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

  var initFilter = false;

  if(initFilter){
    $('.section-filter').show();
  }else{
    $('.section-filter').hide();
  }

  $(document).on('click', '.btn-toggle-filter', function(e) {
    e.preventDefault();
    initFil();
  });

  function initFil() {
    if (initFilter) {
      initFilter = false;
      $('.section-filter').hide();
    } else {
      initFilter = true;
      $('.section-filter').show();
    }
  }

  // $(document).on('click', '.btn-point-claim', function(e) {
  //   e.preventDefault();
  //   let id = $(this).attr('id');
  //   $('[name="member_id"]').val(id);
  //   $("#mpointadj_title").html("Claim Point");
  //   $('.mpointadj').modal('show');
  // });

  function clearAdjForm () {
    $("#adjpoint_warranty_id").select2().empty();
		$("#adjpoint_warranty_id").val(null).trigger("change");
    $("#adjpoint_type").val("");
    $("#adjpoint_trx_date").val("");
    $("#adjpoint_value").val("");
    $("#adjpoint_description").val("");
    $("#adjpoint_ref_number").val("");
    $("#adjpoint_expired_at").val("");
  }

	$('.dp_range').daterangepicker({
		locale: {
			format: 'YYYY-MM-DD'
		}
	});

  $(document).on("click", ".btn-point-adjin", function(e) {
    e.preventDefault();

    clearAdjForm();

    let member_id = $(this).attr("member_id");
    let warranty_id = $(this).attr("warranty_id");
    $("#adjpoint_member_id").val(member_id);
    // initSelectWarranty(warranty_id);
    $(".adjpoint_warranty_div").addClass("hidden");
    $("#adjpoint_type").val("in");
    // $("#section_exp_date").removeClass("hidden");
    $("#section_ref_number").addClass("hidden");
    $("#mpointadj_title").html("Add Points");
    $(".mpointadj").modal("show");
  });

  $(document).on("click", ".btn-point-adjout", function(e) {
    e.preventDefault();

    clearAdjForm();

    let member_id = $(this).attr("member_id");
    let warranty_id = $(this).attr("warranty_id");
    $("#adjpoint_member_id").val(member_id);
    $(".adjpoint_warranty_div").removeClass("hidden");
    initSelectWarranty(warranty_id);
    $("#adjpoint_type").val("out");
    // $("#section_exp_date").addClass("hidden");
    $("#section_ref_number").removeClass("hidden");
    $("#mpointadj_title").html("Reduction Points");
    $(".mpointadj").modal("show");
  });

  function approveRequestPoint(pointId) {
		let url = `{{ url("artmin/member/point/approve") }}/${pointId}`;
		let data = {
			"_token": "{{ csrf_token() }}"
		};

		$.post(url, data, function(data) {
			if (!data.success && data.message) {
				var message = data.message;
				if (data.messages && Array.isArray(data.messages)) {
					message += "\n\n" + data.messages.join("\n");
				} else if (data.messages && Object.keys(data.messages).length > 0) {
					message += "\n\n" + Object.keys(data.messages).map((key) => data.messages[key].join("\n")).join("\n");
				}
				swal('Attention!', message, 'error');
			} else {
				swal('Success', 'Berhasil di Approve!', 'success').then((confirm) => location.reload());
			}
		});
	}

  function rejectRequestPoint(pointId) {
		let url = `{{ url("artmin/member/point/reject") }}/${pointId}`;
		let data = {
			"_token": "{{ csrf_token() }}"
		};

		$.post(url, data, function(data) {
			if (!data.success && data.message) {
				var message = data.message;
				if (data.messages && Array.isArray(data.messages)) {
					message += "\n\n" + data.messages.join("\n");
				} else if (data.messages && Object.keys(data.messages).length > 0) {
					message += "\n\n" + Object.keys(data.messages).map((key) => data.messages[key].join("\n")).join("\n");
				}
				swal('Attention!', message, 'error');
			} else {
				swal('Success', 'Berhasil di Reject!', 'success').then((confirm) => location.reload());
			}
		});
	}

  $(document).on('click', '.btn-check-verification-data', function(e) {
    e.preventDefault();

    let type = $(this).attr('type');

    if (type == 'detail') {
      $('.btn-verify').hide();
      $('.mverification .modal-title').text('Detail Data');
    } else {
      $('.btn-verify').show();
      $('.mverification .modal-title').text('Verification Data');
    }

    let member_point_id = $(this).attr('member_point_id');
    $('.btn-verify').attr('member_point_id', member_point_id);
    $('.btn-revisi').attr('member_point_id', member_point_id);

    let jsonData = JSON.parse($($(this).closest("div")).attr('json-data'));

    $('.member_name').text(jsonData.name);
    $('.gender').text(jsonData.gender);
    $('.birth_date').text(jsonData.birth_date);
    $('.member_email').text(jsonData.email);
    $('.member_phone_number').text(jsonData.phone);
    $('.serial_no').text(jsonData.serial_no);
    $('.warranty_no').text(jsonData.warranty_no);
    $('.product_name').text(jsonData.product_name_odoo);
    $('.purchase_date').text(jsonData.purchase_date);
    $('.ktp').text(jsonData.ktp);
    $('.address').text(jsonData.address);
    $('.city').text(jsonData.city);
    $('.testimony').text(jsonData.star ? `Rating (${jsonData.star}), ` : "" + (jsonData.review || ""));

    $('.mverification').modal('show');
  });

  $(document).on("click", ".btn-point-approve", function(e) {
    e.preventDefault();

    let id = $(this).attr("member_point_id");
    swal({
      title: "Konfirmasi",
      text: "Approve Request Claim Point?",
      icon: "warning",
      buttons: true,
      dangerMode: true
    }).then((confirm) => {
      if (confirm) {
        approveRequestPoint(id);
      }
    });

  });

  $(document).on("click", ".btn-point-reject", function(e) {
    e.preventDefault();

    let id = $(this).attr("member_point_id");
    swal({
      title: "Konfirmasi",
      text: "Reject Request Claim Point?",
      icon: "warning",
      buttons: true,
      dangerMode: true
    }).then((confirm) => {
      if (confirm) {
        rejectRequestPoint(id);
      }
    });

  });

  $(document).on('submit', '.f_adjpoint', function(e) {
    e.preventDefault();

    swal({
        title: "Konfirmasi",
        text: "Data Poin akan disimpan!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((confirm) => {
        if (confirm) {
          let url = e.target.action;
          let data = $(this).serializeArray();
          $.post(url, data, function(retData) {
            if (retData.success) {
              swal('Berhasil', 'Account Telah Digabungkan', 'success').then((confirm) => location.reload());
            } else if (!retData.success && retData.message) {
              swal('Gagal', retData.message, 'error');
            }
          });
        }
      });

  });

  $(document).on('click', '.btn-adjpoint-submit', function(e) {
    e.preventDefault();
    $('.f_adjpoint').submit();
  });

  function makeid(length) {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
  }

  var MapLabelStatusPoint = {
    waiting: "Request",
    approved: "Approved",
    rejected: "Rejected"
  };

  var MapColorStatusPoint = {
    waiting: "btn-warning",
    approved: "btn-success",
    rejected: "btn-danger"
  };

  $(document).on('click', '.btn-export', function(e) {
		e.preventDefault();
		const rptType = $(this).attr("rpt-type");
		$(".m-export").find(".modal-title").html("Export " + (rptType === "pdf" ? "PDF" : "Excel"));
		$(".m-export").attr("rpt-type", rptType);
		$(".m-export").modal('show');
	});

	$(document).ready( function() {
		$('.data-table').DataTable().clear().destroy();
		$('.member-table').DataTable({
			paging: true,
			searching: true,
			processing: true,
			serverSide: true,

			retrieve: true,
			responsive: false,
			destroy: true,

			ajax: '{!! route('artmin.memberpoint.list') !!}',
			columns: [
				{
					data: "id",
					name: "id",
					render: function (data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				},
        {
					data: 'warranty_no',
					name: 'warranty_no',
					searchable: false
				},
				{
					data: 'name',
					name: 'name',
					searchable: true
				},
        {
					data: 'phone',
					name: 'phone',
					searchable: true
				},
				{
					data: 'email',
					name: 'email',
					searchable: true
				},
				{
					data: 'city',
					name: 'city',
					searchable: true
				},
				{
					data: 'created_at',
					name: 'created_at',
					render : function (data, type, row) {
						return moment(data).format('DD-MM-YYYY');
					}
				},
        {
					data: 'claimed_point',
					name: 'claimed_point',
          searchable: false,
					render : function (data, type, row) {
						return `<div class="prod-info font-weight-bold warranty-prod-item bg-success text-white rounded-3 px-2" style="width: fit-content;">${data||0}</div>`;
					}
				},
				{
					data: 'expired_at',
					name: 'expired_at',
					searchable: false,
					render : function (data, type, row) {
						return moment(data).format('DD-MM-YYYY');
					}
				},
				{
					data: 'status',
					name: 'status',
					searchable: false,
					render : function (data, type, row) {
            return `<div class="btn btn-xs ${MapColorStatusPoint[data]}">${MapLabelStatusPoint[data]}</div>`;
					}
				},
				{
					data: null,
					searchable: false,
					render : function (data, type, row) {
            let btn = "";
            if (row.status === "approved") {
              btn = `<a style="width: fit-content;" data-toggle="tooltip" title="Detail Points" class="btn btn-primary btn-xs btn-point-detail" href="{{url('/artmin/member/point')}}/${row.member_id}"><i class="fa fa-list"></i></a>
                <button style="width: fit-content;" data-toggle="tooltip" title="Add Points" class="btn btn-success btn-xs btn-point-adjin" warranty_id="${row.warranty_id}" member_id="${row.member_id}"><i class="fa fa-plus-square"></i></button>
                <button style="width: fit-content;" data-toggle="tooltip" title="Reduction Point" class="btn btn-danger btn-xs btn-point-adjout" warranty_id="${row.warranty_id}" member_id="${row.member_id}"><i class="fa fa-minus-square"></i></button>`;
            } else if (row.status === "waiting") {
              btn = `
              <button style="margin:3px" class="btn btn-primary btn-xs btn-check-verification-data" member_point_id="${row.id}" type="verification" data-toggle="tooltip" title="Verification Data Check"><i class="fa fa-check-circle"></i></button>
              <button style="width: fit-content;" class="btn btn-success btn-xs btn-point-approve" member_point_id="${row.id}" data-toggle="tooltip" title="Approve"><i class="fa fa-paper-plane"></i></button>
              <button style="width: fit-content;" class="btn btn-danger btn-xs btn-point-reject" member_point_id="${row.id}" data-toggle="tooltip" title="Reject"><i class="fa fa-close"></i></button>`;
            }

            return `<div class="d-flex gap-2 flex-column align-items-center" json-data='${JSON.stringify(row)}'>${btn}</div>`;
					}
				}
			],
			order: [[0, 'desc' ]]
		});

    // setTimeout(() => {
		// 	initSelectWarranty();
		// }, 1000);

	});

  $(document).on('click', '.btn-exec-export', function(e) {
		e.preventDefault();


		let tanggal = $('[name="period"]').val();
		let tglFrom = tanggal.split(' ')[0].split('/').join('-');
		let tglTo = tanggal.split(' ')[2].split('/').join('-');

		const rptType = $(".m-export").attr("rpt-type");
		let url = `{{ url('artmin/member/point/export') }}` + '/' + tglFrom + '/' + tglTo;

		window.open(url, '_blank');

	});

  function initSelectWarranty (warranty_id) {
        if (!$("#adjpoint_warranty_id").hasClass("select2")) {
            $("#adjpoint_warranty_id").addClass("select2");
        }

        $("#adjpoint_warranty_id").select2({
            placeholder: "Search Warranty Number...",
            ajax: {
                url: `{{ url("artmin/warranty-json") }}?member_id=${$("#adjpoint_member_id").val()}` + (warranty_id ? `&warranty_id=${warranty_id}` : ""),
                dataType: "json",
                delay: 250,
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.warranty_id,
                                text: `${item.warranty_no} | ${item.product_name}` // | ${item.base_point}
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
            minimumInputLength: 0,
            cache: true,
        });

        // if (warranty_id) {
        //   $("#adjpoint_warranty_id").select2("val", warranty_id);
        // }
    }

</script>

@endsection