@extends('backend.layouts.backend-app')
@section('title', 'Installation Details')
@section('content')
<div>
	<style>
		.modal-dialog{
			overflow-y: initial !important
		}
		.modal-body{
			height: 80vh;
			overflow-y: auto;
		}
		.row {
			display: -webkit-box;
			display: -webkit-flex;
			display: -ms-flexbox;
			display: flex;
			flex-wrap: wrap;
		}
		.row > [class*='col-'] {
			display: flex;
			flex-direction: column;
		}
	</style>
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Installation No: {{ $service->service_no }}
			<small></small>
		</h1>
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- Small boxes (Stat box) -->
		<div class="row">

			<div class="col-sm-8">
				<div class="box box-solid">
					<div class="box-header">
			          <h3 class="box-title">Warranty & Installation Details</h3>
					  	@if($service->status != '0' && (Auth::user()->roles == '1' || Auth::user()->roles == '2'))
						<a href="{{ url('artmin/installation/update/uncomplete/' . $service->service_id) }}" class="btn btn-md btn-danger btn-uncomplete" style="float: right;"><i class="fa fa-unlock"></i>&nbsp;
						<?php echo $service->status == 1 ? 'Uncomplete' : 'Onprogress'; ?> Installation
						</a>
						@endif
			        </div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-5">
								<img src="{{ $product->product_image??'' }}" class="img-responsive">
							</div>
							<div class="col-sm-7 table-responsive">
								<?php if ($service->status == 1): ?>
									<div class="form-group">
										<button class="btn btn-sm btn-success"><i class="fa fa-check"></i> Installation Completed</button>
									</div>
								<?php endif; ?>
								<table class="table table-bordered">
									<tr>
										<td width="170px"><b>Product Name</b></td>
										<td>{{ $product->product_name??'' }}</td>
									</tr>
									<tr>
										<td width="170px"><b>Installation No</b></td>
										<td>{{ $service->service_no }}</td>
									</tr>
									<tr>
										<td width="170px"><b>Serial No</b></td>
										<td>{{ $warranty->serial_no }}</td>
									</tr>
								</table>
								<table class="table table-bordered">
									<?php
									$prefered_time = DB::table('ms_service_time')->where('id', $service->prefered_time)->first();
									if($service->visit_time != ''){
										$visit_time = DB::table('ms_service_time')->where('id', $service->visit_time)->first();
									}
									?>
									<tr>
										<td width="170px"><b>Confirmed Visit Time</b></td>
										<td>
											<?php if ($service->visit_date != '' && $service->visit_time != ''): ?>
												<b>{{ date('d M Y', strtotime($service->visit_date)) }} On {{ $visit_time->time }}</b> <i class="fa fa-check"></i>
											<?php else: ?>
												-
											<?php endif; ?>
										</td>
									</tr>
									<tr>
										<td width="170px"><b>Prefered Time</b></td>
										<td>{{ date('d M Y', strtotime($service->prefered_date)) }} On {{ $prefered_time->time }}</td>
									</tr>
									<tr>
										<td width="170px"><b>Status</b></td>
										<td>
											<?php if($service->status == 0) echo "On Progress"; else echo "Completed"; ?>
										</td>
									</tr>
									<tr>
										<td width="170px"><b>Assigned Service Center</b></td>
										<td>
											<?php
											$check_sc = DB::table('ms_service_center')->where('sc_id', $service->sc_id)->first();
											echo $check_sc ? $check_sc->sc_location : "";
											?>
										</td>
									</tr>
									<tr>
										<td width="170px"><b>Contact Name</b></td>
										<td>{{ $service->contact_name }}</td>
									</tr>
									<tr>
										<td width="170px"><b>Contact Phone</b></td>
										<td>{{ $service->contact_phone }}</td>
									</tr>
									<tr>
										<td width="170px"><b>Address</b></td>
										<td>{{ $service->service_address }}</td>
									</tr>
									<tr>
										<td width="170px"><b>City</b></td>
										<td>{{ $service->service_city }}</td>
										<!-- @if ($service->status != 0)
										<td>{{ $service->service_city }}</td>
										@else
										<td>
											<div style="display: flex; justify-content: space-between;">
												<span>{{ $service->service_city }}</span>
												<a style="width: 32px; height: 32px; padding-left: 8px; align-self: center;" href="javascript:void(0);" data-toggle="modal" data-target="#modal-change-service-city" class="btn btn-block btn-info"><i class="fa fa-edit"></i></a>
											</div>
										</td>
										@endif -->
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="box box-solid">
					<div class="box-header">
			          <h3 class="box-title">Logs</h3>
			        </div>
					<div class="box-body row">
						<div class="col-sm-12 table-responsive">
							<table class="table table-bordered data-table" data-order="[[ 0, &quot;desc&quot; ]]">
								<thead>
									<tr>
										<th>Time</th>
										<th>Status</th>
										<th width="250px">Info</th>
										<th width="150px">Notes</th>
										<th width="30"><center>Aksi</center></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($progress as $prog): ?>
										<tr>
											<td data-order="{{ date('Y-m-d H:i:s', strtotime($prog->created_at)) }}">{{ date("d-m-Y H:i", strtotime($prog->created_at)) }}</td>
											<td>

												<label class="label <?php if($prog->status == 13) echo "label-success"; else echo "label-primary"; ?>">
													<?php
													$update_status = DB::table('ms_service_status')->where("id", $prog->status)->first();
													if(isset($update_status->service_status)){
														$prog->service_status = $update_status->service_status;
														$prog->service_city_id = $service->service_city_id;
														$prog->sc_id = $service->sc_id;
														$prog->technician = $service->visit_technician;
														echo $update_status->service_status;
													}
													?>
												</label>

											</td>
											<td>
												<?= $prog->info ?>
												<?php
												if($prog->pic != ''){
													echo "<br><b>PIC: </b>" . $prog->pic;
												}
												?>
											</td>
											<td><?= $prog->notes ?></td>
											<td>
												<center>
												<div style="display: flex; gap: 4px; justify-content: center;">
													@if ($service->status == 0)
													<button class="btn btn-primary btn-xs btn-edit" data='<?php echo str_replace("'", "", json_encode($prog)) ?>' data-toggle="tooltip" title="Revisi Data"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
													@endif
													<?php
														// $attachments = DB::table('reg_service_progress_attachment')
														// ->select("*")
														// ->addSelect(DB::raw("CONCAT('" . url("/") . "/', path_file) AS url" ))
														// ->where("progress_id", $prog->id)->get();
													?>
													<button class="btn btn-warning btn-xs btn-attachments" progress-id="<?php echo $prog->id; ?>" data='<?php //echo str_replace("'", "", json_encode($attachments)) ?>' data-toggle="tooltip" title="Upload Images"><i class="fa fa-picture-o" aria-hidden="true"></i></button>
												</div>
												</center>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<?php if ($service->status == 0): ?>

			<div class="col-sm-4">
				<div class="box box-solid">
					<div class="box-header">
			          <h3 class="box-title">Quick Action</h3>
			        </div>
					<div class="box-body">
						<div class="row">
							<div class="col-lg-12 form-group">
								<?php if ($service->visit_date == ''): ?>
									<a href="javascript:void(0);" data-toggle="modal" data-target="#modal-schedule" class="btn btn-block btn-primary"><i class="fa fa-calendar"></i> Set Schedule</a>
								<?php else: ?>
									<a href="javascript:void(0);" data-toggle="modal" data-target="#modal-schedule" class="btn btn-block btn-primary"><i class="fa fa-refresh"></i> Reschedule</a>
								<?php endif; ?>
							</div>
							<?php if ($service->visit_date != ''): ?>
								<div class="col-sm-12 form-group">
									<a href="{{ url('artmin/installation/update/processing/' . $service->service_id) }}" class="btn btn-block btn-info" onclick="return confirm('Are you sure?');"><i class="fa fa-tasks"></i> Process Installation</a>
								</div>
								<div class="col-sm-12 form-group">
									<a href="{{ url('artmin/installation/update/complete/' . $service->service_id) }}" class="btn btn-lg btn-block btn-success"><i class="fa fa-check"></i> Complete Installation</a>
								</div>
								<div class="col-sm-12 form-group">
									<a href="{{ url('artmin/installation/update/cancel/' . $service->service_id) }}" class="btn btn-lg btn-block btn-danger"><i class="fa fa-check"></i> Cancel Installation</a>
								</div>
							<?php endif; ?>
						</div>

					</div>
				</div>
			</div>
			<div class="col-sm-4 hide">
				<div class="box box-solid">
					<div class="box-header">
			          <h3 class="box-title">Update Service Status</h3>
			        </div>
					<div class="box-body">
						<form class="" action="" method="post">
							{{ csrf_field() }}
							<div class="form-group">
								<?php
								$status = DB::table('ms_service_status')->where('type', $service->service_type)->get();
								?>
								<label>Status</label>
								<select class="select2 form-control" name="status">
									<option value="">Select Status</option>
									<?php foreach ($status as $stat): ?>
										<option value="{{ $stat->id }}">{{ $stat->service_status }}</option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="form-group">
								<label>Notes</label>
								<textarea name="notes" class="form-control"></textarea>
							</div>
							<div class="form-group">
								<label>PIC</label>
								<input type="text" class="form-control" name="pic">
							</div>
							<div class="form-group">
								<button class="btn btn-primary btn-block">
									Update Service Status
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<?php endif; ?>

		</div><!-- /.row -->
	</section><!-- /.content -->
</div>

<!-- Modal Set Schedule -->
<div class="modal fade" id="modal-schedule" tabindex="-1" role="dialog" aria-labelledby="Schedule" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><b>Set Installation Schedule</b></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"><ion-icon name="close-outline"></ion-icon></span>
				</button>
			</div>
			<div class="modal-body">
				<form action="{{ url('artmin/installation/set-schedule/' . $service->service_id) }}" method="post" onsubmit="return check_set_schedule();">
					{{ csrf_field() }}
					<?php
					$visit_time = DB::table('ms_service_time')->get();
					$technician = DB::table('ms_technician')->where('status', 1)->where('sc_id', $service->sc_id)->get();
					$date = '';
					$time = 1;
					if($service->visit_time != ''){
						$vi_time = $service->visit_time;
					}
					else{
						$vi_time = $service->prefered_time;
					}

					if($service->visit_date != ''){
						$vi_date = date('d-m-Y', strtotime($service->visit_date));
					}
					else{
						$vi_date = date('d-m-Y', strtotime($service->prefered_date));
					}
					?>
					<div class="form-group">
						<label>Visit Date *</label>
						<input type="text" class="form-control datepicker" name="visit_date" id="visit_date" data-date-end-date="{{ date('d/m/Y', strtotime('+5 days', strtotime($vi_date))) }}" value="{{ date('d-m-Y', strtotime($vi_date)) }}" placeholder="dd-mm-yyyy">
					</div>
					<div class="form-group">
						<label>Visit Time *</label>
						<select class="form-control" name="visit_time" id="visit_time">
							<?php foreach ($visit_time as $time): ?>
								<option value="<?= $time->id ?>" <?php if(old('time', $vi_time) == $time->id) echo "selected"; ?>><?= $time->time ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group">
						<label>City <span style="color:red">*</span></label>
						<select class="form-control select2" name="service_city_id" id="service_city_id">
							<option value="">Select City</option>
							@foreach($city as $key => $val)
							<option {{ ($val->city_id == $service->service_city_id ? 'selected="true"' : null) }} value="{{ $val->city_id }}">{{ $val->city_name }}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group">
						<label>Service Center <span style="color:red">*</span></label>
						<select class="form-control select2" id="sc_id" name="sc_id">
							<!-- <option value="">Select Service Center</option> -->
							<?php foreach ($service_center as $key) : ?>
								<option value="<?= $key->sc_id ?>" <?php if (old('sc_id', $service->sc_id) == $key->sc_id) echo "selected"; ?>>
									<?= $key->sc_location . ' | ' . $key->regional_name ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="form-group">
						<label>Technician <span style="color:red">*</span></label>
						<select class="form-control select2" id="technician" name="technician">
							<!-- <option value="">Select Technician</option> -->
							<?php foreach ($technician as $tech) : ?>
								<option value="<?= $tech->id ?>" <?php if (old('technician', $service->visit_technician) == $tech->id) echo "selected"; ?>>
									<?= $tech->technician_id . ' - ' . $tech->name ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="form-group">
						<label>Notes (Optional)</label>
						<textarea class="form-control" name="notes"></textarea>
					</div>
					<div class="form-group">
						<button class="btn btn-primary">Set Visit Schedule</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Modal Service Center -->
<div class="modal fade" id="modal-service-center" tabindex="-1" role="dialog" aria-labelledby="Schedule" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><b>Change Service Center</b></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"><ion-icon name="close-outline"></ion-icon></span>
				</button>
			</div>
			<div class="modal-body">
				<form action="{{ url('artmin/installation/set-schedule/' . $service->service_id) }}" method="post" onsubmit="return check_set_schedule();">
					{{ csrf_field() }}
					<?php
					$visit_time = DB::table('ms_service_time')->get();
					$technician = DB::table('ms_technician')->where('status', 0)->where('sc_id', $service->sc_id)->get();
					$date = '';
					$time = 1;
					if($service->visit_time != ''){
						$vi_time = $service->visit_time;
					}
					else{
						$vi_time = $service->prefered_time;
					}

					if($service->visit_date != ''){
						$vi_date = date('d-m-Y', strtotime($service->visit_date));
					}
					else{
						$vi_date = date('d-m-Y', strtotime($service->prefered_date));
					}
					?>
					<div class="form-group">
						<label>Visit Date *</label>
						<input type="text" class="form-control datepicker" name="visit_date" id="visit_date" data-date-end-date="{{ date('d/m/Y', strtotime('+5 days', strtotime($vi_date))) }}" value="{{ date('d-m-Y', strtotime($vi_date)) }}" placeholder="dd-mm-yyyy">
					</div>
					<div class="form-group">
						<label>Visit Time *</label>
						<select class="form-control" name="visit_time" id="visit_time">
							<?php foreach ($visit_time as $time): ?>
								<option value="<?= $time->id ?>" <?php if(old('time', $vi_time) == $time->id) echo "selected"; ?>><?= $time->time ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group">
						<label>Technician *</label>
						<select class="form-control select2" id="technician" name="technician">
							<option value="">Select Technician</option>
							<?php foreach ($technician as $tech): ?>
								<option value="<?= $tech->id ?>" <?php if(old('technician', $service->visit_technician) == $tech->id) echo "selected"; ?>><?= $tech->technician_id . ' - ' . $tech->name ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group">
						<label>Notes (Optional)</label>
						<textarea class="form-control" name="notes"></textarea>
					</div>
					<div class="form-group">
						<button class="btn btn-primary">Set Visit Schedule</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal mrevisi" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Revisi Data</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="frevisi" action="{{ url('artmin/installation/request-details/progress/revisi') }}" method="post">
					{{ csrf_field() }}
					<input type="hidden" name="reg_service_progress_id">

					<div class="form-group input-grp-sch">
						<label>City <span style="color:red">*</span></label>
						<select class="form-control select2" name="revisi_service_city_id" id="revisi_service_city_id">
							<option value="">Select City</option>
							@foreach($city as $key => $val)
							<option {{ ($val->city_id == $service->service_city_id ? 'selected="true"' : null) }} value="{{ $val->city_id }}">{{ $val->city_name }}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group input-grp-sch">
						<label>Service Center <span style="color:red">*</span></label>
						<select class="form-control select2" id="revisi_sc_id" name="revisi_sc_id">
							<!-- <option value="">Select Service Center</option> -->
							<?php foreach ($service_center as $key) : ?>
								<option value="<?= $key->sc_id ?>" <?php if (old('sc_id', $service->sc_id) == $key->sc_id) echo "selected"; ?>>
									<?= $key->sc_location . ' | ' . $key->regional_name ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="form-group input-grp-sch">
						<label>Technician <span style="color:red">*</span></label>
						<select class="form-control select2" id="revisi_technician" name="revisi_technician">
							<!-- <option value="">Select Technician</option> -->
							<?php foreach ($technician as $tech) : ?>
								<option value="<?= $tech->id ?>" <?php if (old('technician', $service->visit_technician) == $tech->id) echo "selected"; ?>>
									<?= $tech->technician_id . ' - ' . $tech->name ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="form-group">
						<label>Notes (Optional)</label>
						<textarea name="revisi_notes" placeholder="Deskripsi Masalah" class="form-control"></textarea>
					</div>

					<div class="form-group">
					</div>
				</form>
			</div>
			<div class="modal-footer">

				<!-- <button class="btn btn-primary btn-block">
					Update Service Status
				</button> -->
				<button type="button" class="btn btn-primary btn-save-revisi">Save changes</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal mattachments" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Attachment Images</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<!-- <form class="frevisi" action="{{ url('artmin/service/request-details/progress/revisi') }}" method="post">
					{{ csrf_field() }}
					<input name="reg_service_progress_id" type="hidden" /> -->

					<div id="progress_attachments_container" style="display: flex; flex-direction: column; gap: 12px;">
					</div>

				<!-- </form> -->
			</div>
			<div class="modal-header border-top">
				<center><button type="button" id="btn-add-attachments" class="btn btn-warning"><i class="fa fa-plus">Add</i></button></center>
			</div>
		</div>
	</div>
</div>

<!-- Modal Change Installation City -->
<!-- <div class="modal fade" id="modal-change-service-city" role="dialog" aria-labelledby="Installation City" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><b>Change Installation City</b></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">
						<ion-icon name="close-outline"></ion-icon>
					</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="{{ url('artmin/installation/request-details/update-city/' . $service->service_id) }}" method="post" onsubmit="return check_set_change_service_city();">
					{{ csrf_field() }}
					<input type="hidden" name="service_city_id_prev" id="service_city_id_prev" value="{{ $service->service_city_id }}">
					<input type="hidden" name="service_city" id="service_city" value="{{ $service->service_city }}">
					<div class="form-group">
						<label>City *</label>
						<select class="form-control select2" name="service_city_idxx" id="service_city_idxx">
							<option value="">Select City</option>
							@foreach($city as $key => $val)
							<option {{ ($val->city_id == $service->service_city_id ? 'selected="true"' : null) }} value="{{ $val->city_id }}">{{ $val->city_name }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group text-right">
						<button class="btn btn-primary">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div> -->

<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>

<script type="text/javascript">

	function hideInputRevisiData (service_status) {
		if (service_status.toLowerCase().includes("scheduled")) {
			$(".input-grp-sch").removeClass("hidden");
		} else {
			$(".input-grp-sch").addClass("hidden");
		}
	}

	$(document).on('click', '.btn-edit', function(e) {
		e.preventDefault();
		let data = JSON.parse($(this).attr('data'));

		$('[name="reg_service_progress_id"]').val(data.id);
		// $('[name="revisi_status"]').val(data.status).trigger('change');
		$('[name="revisi_notes"]').val(data.notes);

		var status = data.service_status;
		if (status.toLowerCase().includes("scheduled")) {
			$('[name="revisi_service_city_id"]').val(data.service_city_id);
			$('[name="revisi_sc_id"]').val(data.sc_id);
			$('[name="revisi_technician"]').val(data.technician);
		}

		hideInputRevisiData(status);

		$('.mrevisi').modal('show');
	});

	$(document).on('submit', '.frevisi', function(e) {
		e.preventDefault();

		swal({
				title: "Konfirmasi",
				text: "Apakah Data Yang Anda Masukan Telah Sesuai?",
				icon: "info",
				buttons: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					let url = $(this).attr('action');
					let data = $(this).serializeArray();

					$.post(url, data, function(e) {
						console.log(e);
						swal('Berhasil', 'Data Progress Telah Direvisi', 'success').then((confirm) => location.reload());
					});
				}
			});

	});

	$(document).on('click', '.btn-save-revisi', function(e) {
		e.preventDefault();
		$('.frevisi').submit();
	});


	function check_set_schedule(){
		var visit_date = $('#visit_date').val();
		var visit_time = $('#visit_time').val();
		var technician = $('#technician').val();

		if(technician == ''){
			alert('Please select Technician first.');
			return false;
		}
		else {
			return true;
		}
	}

	function bindSelectTechnician (sc_id, is_revisi) {
		const elTargetId = is_revisi ? "#revisi_technician" : "#technician";
		$(elTargetId).select2().empty();
		$(elTargetId).val(null).trigger('change');
		var api_technician = `{{url("artmin/technician-json")}}?sc_id=${sc_id}`;
		$.get(api_technician, function(data) {
			$(elTargetId).select2({
				data: data.map((o) => ({ id: o.id, text: `${o.technician_id} - ${o.name}` }))
				// data: [{ id: "", text: "Select Technician" }].concat(data.map((o) => ({ id: o.id, text: `${o.technician_id} - ${o.name}` })))
			});
		});
	}

	function bindSelectServiceCenter (service_city_id, is_revisi) {
		const elTargetId = is_revisi ? "#revisi_sc_id" : "#sc_id";
		$(elTargetId).select2().empty();
		$(elTargetId).val(null).trigger('change');
		var api_service_center = `{{url("artmin/servicecenter-json")}}?service_city_id=${service_city_id}`;
		$.get(api_service_center, function(data) {
			$(elTargetId).select2({
				data: data.map((o) => ({ id: o.sc_id, text: `${o.sc_location} | ${o.regional_name}` }))
				// data: [{ id: "", text: "Select Service Center" }].concat(data.map((o) => ({ id: o.sc_id, text: `${o.sc_location} | ${o.regional_name}` })))
			});

			if (data.length > 0) {
				bindSelectTechnician(data[0].sc_id, is_revisi);
			}
		});
	}

	$('#service_city_id').on('change', function(e) {
		var service_city_data = $('#service_city_id').select2('data');
		var service_city_id = service_city_data[0].id;
		bindSelectServiceCenter(service_city_id);
	});

	$('#sc_id').on('change', function(e) {
		var sc = $('#sc_id').select2('data');
		if ((sc || []).length === 0) {
			return;
		} else if (!sc[0].id) {
			return;
		}

		var sc_id = sc[0].id;
		bindSelectTechnician(sc_id);
	});

	$('#revisi_service_city_id').on('change', function(e) {
		var service_city_data = $('#revisi_service_city_id').select2('data');
		var service_city_id = service_city_data[0].id;
		bindSelectServiceCenter(service_city_id, true);
	});

	$('#revisi_sc_id').on('change', function(e) {
		var sc = $('#revisi_sc_id').select2('data');
		if ((sc || []).length === 0) {
			return;
		} else if (!sc[0].id) {
			return;
		}

		var sc_id = sc[0].id;
		bindSelectTechnician(sc_id, true);
	});
	
	$(document).on('click', '.btn-complete', function(e) {
		e.preventDefault();
		swal({
			title: "Konfirmasi",
			text: "Apakah Anda yakin merubah status instalasi menjadi Complete?", // tanpa mengisi Symtomps, Defect, Action dan Remarks?\n\nJika Ya Klik OK.\n\nGunakan Form UPDATE SERVICE STATUS untuk merubah status dengan mengisi Symtomps, Defect, Action dan Remarks.",
			icon: "info",
			buttons: true,
		}).then((res) => {
			if (!res) return;

			let url = $(this).attr('href');
			$.get(url, function(e) {
				window.location.reload();
			});
		});
	});

	$(document).on('click', '.btn-uncomplete', function(e) {
		e.preventDefault();
		swal({
			title: "Konfirmasi",
			text: "Apakah Anda yakin merubah status instalasi menjadi Uncomplete?", // tanpa mengisi Symtomps, Defect, Action dan Remarks?\n\nJika Ya Klik OK.\n\nGunakan Form UPDATE SERVICE STATUS untuk merubah status dengan mengisi Symtomps, Defect, Action dan Remarks.",
			icon: "info",
			buttons: true,
		}).then((res) => {
			if (!res) return;

			let url = $(this).attr('href');
			$.get(url, function(e) {
				window.location.reload();
			});
		});
	});

	// $('#revisi_status').on('change', function(e) {
	// 	var status = $('#revisi_status').select2('data');
	// 	if (status.length === 0) {
	// 		return;
	// 	}

	// 	hideInputRevisiData(status[0].text);
	// });

	// function check_set_change_service_city() {
	// 	var service_city_id_prev = $('#service_city_id_prev').val();
	// 	var service_city_id = $('#service_city_id').val();
	// 	return service_city_id_prev !== service_city_id;
	// }

	function showAttachDescEdit(attachId) {
		$(`#attachment-desc-view-${attachId}`).addClass("hidden");
		$(`#attachment-desc-edit-${attachId}`).removeClass("hidden");
		$(`#description-attachments-input-${attachId}`).focus();
	}

	function showAttachDescView(attachId) {
		let isnew = $(`#form-attach-progress-${attachId}`).attr("isnew");
		if (isnew === undefined) {
			$(`#attachment-desc-view-${attachId}`).removeClass("hidden");
			$(`#attachment-desc-edit-${attachId}`).addClass("hidden");
		} else {
			$(`[attachment-id=${attachId}]`).remove();
		}
	}

	function onChangeAttachDescription(input, attachId) {
		$(`#description-attachments-${attachId}`).html(input.value);
	}

	function onChangeAttachmentImage(input) {
		if (input.files && input.files[0]) {
			let attachId = $(input).attr("attachment-id");
			var reader = new FileReader();
			reader.onload = function(e) {
				$('#view-attachments-'+attachId).attr("src", e.target.result);
				$('#view-attachments-'+attachId).removeClass("hidden");
			}
			reader.onloadend = function(e) {
				$('#attachment-desc-edit-'+attachId).removeClass("hidden");
				$(input).addClass("hidden");
				// $(".mattachments .modal-dialog .modal-content .modal-body").animate({
				// 	scrollTop: $("#description-attachments-input-"+attachId).offset().top
				// }, 2000, () =>
				$("#description-attachments-input-"+attachId).focus();
				// );
			}
			reader.readAsDataURL(input.files[0]);
		}
	}

	function removeRowAttachmentProgress (attachId) {
		let isnew = $(`#form-attach-progress-${attachId}`).attr("isnew");
		if (isnew === undefined) {
			$.ajax({
				type: "DELETE",
				url: `{{ url("/artmin/installation/progress-attachment/") }}/${attachId}`,
				data: { "_token": "{{ csrf_token() }}" },
				timeout: 600000,
				success: function (data) {
					swal(data.success ? 'Berhasil' : 'Gagal', `Attachment ${data.success ? 'berhasil' : 'gagal'} dihapus`, data.success ? 'success' : 'error');
					$(`[attachment-id=${attachId}]`).remove();
				},
				error: function (e) {
					swal('Gagal', 'Attachment gagal dihapus', 'error');
				}
			});
		} else {
			$(`[attachment-id=${attachId}]`).remove();
		}
	}

	$(document).ready(function(e) {

		$(document).on('submit', '.fattachments', function(e) {
			e.preventDefault();

			let url = $(this).attr('action');
			let attachmentId = $(this).attr('attachment-id');

			var data = new FormData(this);
			// If you want to add an extra field for the FormData
			// data.append("CustomField", "This is some extra data, testing");

			// disabled the submit button
			$("#btn-save-attach-progress-"+attachmentId).prop("disabled", true);

			$.ajax({
				type: "POST",
				enctype: 'multipart/form-data',
				url: url,
				data: data,
				processData: false,
				contentType: false,
				cache: false,
				timeout: 600000,
				success: function (data) {
					swal(data.success ? 'Berhasil' : 'Gagal', `Attachment ${data.success ? 'berhasil' : 'gagal'} diupload`, data.success ? 'success' : 'error');
					// console.log("SUCCESS : ", data);
					$("#btn-save-attach-progress-"+attachmentId).prop("disabled", false);
					$("#attachment-desc-edit-"+attachmentId).addClass("hidden");
					$("#attachment-desc-view-"+attachmentId).removeClass("hidden");
				},
				error: function (e) {
					swal('Gagal', 'Attachment gagal diupload', 'error');
					// console.log("ERROR : ", e);
					$("#btn-save-attach-progress-"+attachmentId).prop("disabled", false);
				}
			});
		});

		function renderRowAttachmentProgress (attach, isnew=false) {
			const { id, progress_id, url, description } = attach;
			return `
			<form id="form-attach-progress-${id}" action="{{ url('artmin/installation/progress-attachment/${progress_id}') }}" method="post" enctype="multipart/form-data" class="fattachments" style="flex: 1;" attachment-id="${id}" ${isnew ? "isnew" : ""}>
				{{ csrf_field() }}
				<img class="preview_image ${url === "" ? "hidden" : ""}" id="view-attachments-${id}" src="${url}" style="width:100%;border-top-left-radius: 6px;border-top-right-radius: 6px;" alt="" />
				<input type="file" id="attachments-${id}" value="" name="file_attachment" onchange="onChangeAttachmentImage(this)" class="form-control ${url !== "" ? "hidden" : ""} attachments" attachment-id=${id} accept="image/*" style="height: 43px; width: 100%;"></input>
				<div class="${url === "" ? "hidden" : ""}" id="attachment-desc-view-${id}" style="padding: 14px; border: 1px solid #dee2e6; border-bottom-left-radius: 6px; border-bottom-right-radius: 6px; display: flex; justify-content: space-between; flex-direction: row; gap: 4px; align-items: center;">
					<span id="description-attachments-${id}" onclick="showAttachDescEdit(${id})" style="font-size: 16px; ">${description}</span>
					<button type="button" class="btn btn-sm btn-danger" onclick="removeRowAttachmentProgress(${id})"><i class="fa fa-trash mr-0"></i></button>
				</div>
				<div class="${url !== "" ? "hidden" : ""}" id="attachment-desc-edit-${id}" style="padding: 14px; border: 1px solid #dee2e6; border-bottom-left-radius: 6px; border-bottom-right-radius: 6px; display: flex; justify-content: space-between; flex-direction: column; gap: 6px; align-items: end;">
					<input id="description-attachments-input-${id}" onchange="onChangeAttachDescription(this, ${id})" name="description" class="form-control" style="font-size: 16px; " value="${description}"></input>
					<div style="display: flex; justify-content: end; flex-direction: row; gap: 4px;">
						<button type="button" class="btn btn-sm btn-warning" onclick="showAttachDescView(${id})"><i class="fa fa-undo mr-0"></i></button>
						<button type="submit" id="btn-save-attach-progress-${id}" form="form-attach-progress-${id}" class="btn btn-sm btn-success"><i class="fa fa-save mr-0"></i></button>
					</div>
				</div>
			</form>`;
		}

		$(document).on('click', '.btn-attachments', function(e) {
			e.preventDefault();
			
			let progressId = $(this).attr('progress-id');
			// let data = JSON.parse($(this).attr('data'));
			var attachurl = `{{url("artmin/installation/progress-attachment/")}}/${progressId}`;
			$.get(attachurl, function(data) {
				$('[name="reg_service_progress_id"]').val(progressId);
				$("#progress_attachments_container").empty();
				let i = 0;
				for (const attach of data.data) {
					$("#progress_attachments_container").append(renderRowAttachmentProgress(attach));
					i++;
				}
				$('.mattachments').modal('show');
			});
		});

		$('#btn-add-attachments').on('click', function (e) {
			let last = $("#progress_attachments_container").children().last();
			let progressId = $('[name="reg_service_progress_id"]').val();
			let id = 0;
			if (last && last.length > 0) {
				id = Number(last.attr("attachment-id"))+1;
			}
			$("#progress_attachments_container").append(renderRowAttachmentProgress({ id: id, progress_id: progressId, url: "", description: "" }, true));

			setTimeout(() => {
				$(".mattachments .modal-dialog .modal-content .modal-body").animate({
					scrollTop: $("#attachments-"+id).offset().top
				}, 2000);
			}, 200);

		});

	});

</script>

@endsection
