@extends('backend.layouts.backend-app')
@section('title', 'Service Details')
@section('content')
<div>
	<style>

		label {
			display: block;
		}
		input::placeholder {
			color: #dfdfdf;
		}
		input:focus::placeholder {
			color: transparent;
		}
		label {
			display: block;
		}
		input::placeholder {
			color: #dfdfdf;
		}
		input:focus::placeholder {
			color: transparent;
		}

		h1 {
			margin:1.5rem 0;
		}

		.ifta-label {
			margin-bottom:-30px;
			z-index:1;
			position:relative;
			padding:0px 10px 0 20px;
			pointer-events:none;
			color: #318CE7;
		}
		.ifta-field {
			border:0;
			border-radius:6px;
			padding:32px 20px 8px;
			margin:0 0 20px;
			width:100%;
			box-shadow:inset 0 0 0 1px #ccc;
		}
		.ifta-field:focus {
			-webkit-appearance:none;
			outline:none;
			box-shadow:inset 0 0 0 2px #007bff;
		}

		.group {
			width:100%:
		}
		.group:focus-within label {
			color:#007bff !important;
		}

		#detail_info_table {
			display: block;
		}

		#detail_info_ifta {
			display: none;
		}

		@media(max-width: 768px) {

			/* #detail_info_table {
				border: none;
			}

			#detail_info_table tr {
				border: none;
				border-bottom: 1px solid;
				width: 100% !important;
			}
				
			#detail_info_table tr td {
				border: none;
				display: table-row;
				width: 100% !important;
			}

			#detail_info_table tr td br {
				display: none;
			}

			#detail_info_table tr td .info-label {
				width: 100% !important;
				padding-top: 12px;
			}

			#detail_info_table tr td .info-value {
				width: 100% !important;
				padding-top: 6px;
				padding-bottom: 8px;
			} */

			#detail_info_ifta {
				display: block;
			}

			#detail_info_table {
				display: none;
			}

		}

		.modal-dialog{
			overflow-y: initial !important
		}
		.modal-body{
			height: 75vh;
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
		#mcreateso .modal-dialog {
			width: 70%;
		}

		/* Chrome, Safari, Edge, Opera */
		input::-webkit-outer-spin-button,
		input::-webkit-inner-spin-button {
		-webkit-appearance: none;
		margin: 0;
		}

		/* Firefox */
		input[type=number] {
		-moz-appearance: textfield;
		}

		.flex-gap-2 {
			gap: 8px;
		}
	</style>
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Service No: {{ $service->service_no }}
			<small></small>
		</h1>
		<div class="box-header">
			@if($service->status != '0' && (Auth::user()->roles == '1' || Auth::user()->roles == '2'))
			<a href="{{ url('artmin/service/update/uncomplete/' . $service->service_id) }}" class="btn btn-md btn-danger btn-uncomplete" style="float: right;"><i class="fa fa-unlock"></i>&nbsp;
			<?php echo $service->status == 1 ? 'Uncomplete' : 'Onprogress'; ?> Service
			</a>
			@endif
			<?php /*
			@if($service->status == 1 && !isset($service->odoo_so_id))
			<a href="javascript:void(0);" data-toggle="modal" data-target="#mcreateso" class="btn btn-md btn-primary mr-3" style="float: right;"><i class="fa fa-money"></i>&nbsp;
			Create SO
			</a>
			@endif */ ?>
		</div>
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- Small boxes (Stat box) -->
		<div class="row">

			<div class="col-sm-{{$service->status == 0 ? 8 : 12}}" <?php echo $service->status == 0 ? '' : 'style="padding-right: 0px;"'; ?>>
				<div class="box box-solid">
					<div class="box-header">
						<h3 class="box-title">Warranty & Service Details</h3>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-5">
								<img src="{{ $product->product_image??'' }}" class="img-responsive">
							</div>
							<div id="detail_info_table" class="col-sm-7 table-responsive">
								<table class="table table-bordered">
									<tr>
										<td widthxx="170px"><div class="info-label"><b>Product Name</b></div></td>
										<td><div class="info-value">{{ $product->product_name??'' }}</div></td>
									</tr>
									@if ($service->svc_type != null)
									<tr>
										<td widthxx="170px"><div class="info-label"><b>Service Type</b></div></td>
										<td><div class="info-value">{{ array('presale' => 'Presale', 'enduser' => 'End User')[$service->svc_type] }}</div></td>
									</tr>
									@endif
									<tr>
										<td widthxx="170px"><div class="info-label"><b>Problem Category</b></div></td>
										<td><div class="info-value">{{ $service->problem_category }}</div></td>
									</tr>
									<tr>
										<td widthxx="170px"><div class="info-label"><b>Deskripsi Masalah <br>( Remark )</b></div></td>
										<td><div class="info-value">{{ $service->problem_notes }}</div></td>
									</tr>
									<tr>
										<td widthxx="170px"><b>Service No</b></td>
										<td>{{ $service->service_no }}</td>
									</tr>
									<tr>
										<td widthxx="170px"><b>Serial No</b></td>
										<td>{{ $warranty->serial_no }}</td>
									</tr>
								</table>
								<table class="table table-bordered">
									<?php
									$prefered_time = DB::table('ms_service_time')->where('id', $service->prefered_time)->first();
									if ($service->visit_time != '') {
										$visit_time = DB::table('ms_service_time')->where('id', $service->visit_time)->first();
									}
									?>
									<tr>
										<td widthxx="170px"><b>Confirmed Visit Time</b></td>
										<td>
											<?php if ($service->visit_date != '' && $service->visit_time != '') : ?>
												<b>{{ date('d M Y', strtotime($service->visit_date)) }} On
													{{ $visit_time->time }}</b> <i class="fa fa-check"></i>
											<?php else : ?>
												-
											<?php endif; ?>
										</td>
									</tr>
									<tr>
										<td widthxx="170px"><b>Prefered Time</b></td>
										<td>{{ date('d M Y', strtotime($service->prefered_date)) }} On
											{{ $prefered_time->time }}
										</td>
									</tr>
									<tr>
										<td widthxx="170px"><b>Status</b></td>
										<td>
											@if($service->status == '0')
											On Progress
											@elseif($service->status == '1')
											Completed
											@elseif($service->status == '2')
											Cancel
											@endif
										</td>
									</tr>
									<tr>
										<td widthxx="170px"><b>Contact Name</b></td>
										<td>{{ $service->contact_name }}</td>
									</tr>
									<tr>
										<td widthxx="170px"><b>Contact Phone</b></td>
										<td>{{ $service->contact_phone }}</td>
									</tr>
									<tr>
										<td widthxx="170px"><b>Address</b></td>
										<td>{{ $service->service_address }}</td>
									</tr>
									<tr>
										<td widthxx="170px"><b>City</b></td>
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
									<tr>
										<td widthxx="170px"><b>Purchase Date</b></td>
										<td>{{ $service->purchase_date }}</td>
									</tr>
									<?php

									$interval = date_diff(date_create($service->purchase_date), date_create(date('Y-m-d')));

									$status_warranty = '-';
									if ((int)$interval->format('%a') < 365) {
										$status_warranty = 'In Warranty';
									} else {
										$status_warranty = 'Out Warranty';
									}

									?>
									<tr>
										<td widthxx="170px"><b>Warranty</b></td>
										<td>{{ $status_warranty }}</td>
									</tr>
									@if(isset($service->so_number))
									<tr>
										<td widthxx="170px"><b>SO Number</b></td>
										<td>{{ $service->so_number }}</td>
									</tr>
									@endif
								</table>
							</div>
							<div id="detail_info_ifta" class="col-sm-7">
								<div class="group">
									<label class="ifta-label">Product Name</label>
									<div class="ifta-field">{{ $product->product_name??'' }}</div>
								</div>
								@if ($service->svc_type != null)
								<div class="group">
									<label class="ifta-label">Service Type</label>
									<div class="ifta-field">{{ array('presale' => 'Presale', 'enduser' => 'End User')[$service->svc_type] }}</div>
								</div>
								@endif
								<div class="group">
									<label class="ifta-label">Contact Name</label>
									<div class="ifta-field">{{ $service->contact_name }}</div>
								</div>
								<div class="group">
									<label class="ifta-label">Contact Phone</label>
									<div class="ifta-field">{{ $service->contact_phone }}</div>
								</div>
								<div class="group">
									<label class="ifta-label">Address</label>
									<div class="ifta-field">{{ $service->service_address }}</div>
								</div>
								<div class="group">
									<label class="ifta-label">City</label>
									<div class="ifta-field">{{ $service->service_city }}</div>
								</div>
								<div class="group">
									<label class="ifta-label">Confirmed Visit Time</label>
									<div class="ifta-field">
										<?php if ($service->visit_date != '' && $service->visit_time != '') : ?>
											<b>{{ date('d M Y', strtotime($service->visit_date)) }} On
												{{ $visit_time->time }}</b> <i class="fa fa-check"></i>
										<?php else : ?>
											-
										<?php endif; ?>
									</div>
								</div>
								<div class="group">
									<label class="ifta-label">Prefered Time</label>
									<div class="ifta-field">{{ date('d M Y', strtotime($service->prefered_date)) }} On {{ $prefered_time->time }}</div>
								</div>
								<div class="group">
									<label class="ifta-label">Status</label>
									<div class="ifta-field">
										@if($service->status == '0')
										On Progress
										@elseif($service->status == '1')
										Completed
										@elseif($service->status == '2')
										Cancel
										@endif
									</div>
								</div>
								<div class="group">
									<label class="ifta-label">Address</label>
									<div class="ifta-field">{{ $service->service_address }}</div>
								</div>
								<div class="group">
									<label class="ifta-label">City</label>
									<div class="ifta-field">{{ $service->service_city }}</div>
								</div>
								<div class="group">
									<label class="ifta-label">Purchase Date</label>
									<div class="ifta-field">{{ $service->purchase_date }}</div>
								</div>
								<div class="group">
									<label class="ifta-label">Warranty</label>
									<div class="ifta-field">{{ $status_warranty }}</div>
								</div>
								@if(isset($service->so_number))
								<div class="group">
									<label class="ifta-label">SO Number</label>
									<div class="ifta-field">{{ $service->so_number }}</div>
								</div>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>

			<?php if ($service->status == 0) : ?>

				<div class="col-sm-4">
					<div class="row">
					<div class="box box-solid">
						<div class="box-header">
							<h3 class="box-title">Quick Action</h3>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-lg-12 form-group">
									<?php if ($service->visit_date == '') : ?>
										<a href="javascript:void(0);" data-toggle="modal" data-target="#modal-schedule" class="btn btn-block btn-primary"><i class="fa fa-calendar"></i> Set Schedule</a>
									<?php else : ?>
										<a href="javascript:void(0);" data-toggle="modal" data-target="#modal-schedule" class="btn btn-block btn-primary"><i class="fa fa-calendar"></i> Reschedule</a>
									<?php endif; ?>
								</div>
								<?php if ($service->visit_date != '') : ?>
									<!-- <div class="col-lg-6 form-group">
										<a href="javascript:void(0);" data-toggle="modal" data-target="#modal-schedule" class="btn btn-block btn-warning"><i class="fa fa-edit"></i> Pending</a>
									</div> -->
									<div class="col-sm-12 form-group">
										<a href="javascript:void(0);" data-toggle="modal" data-target="#modal-schedule" class="btn btn-block btn-info" service_status="Request Workshop Service Pickup"><i class="fa fa-edit"></i> Request Workshop Service Pickup</a>
									</div>
									@if ($service->visit_technician)
									<div class="col-sm-12 form-group">
										<a href="{{ url('artmin/service/update/processing/' . $service->service_id) }}" class="btn btn-block btn-info" onclick="return confirm('Are you sure?');"><i class="fa fa-tasks"></i> Process Service</a>
									</div>
									@endif
									<!-- @if ($allow_completed) -->
									<!-- <div class="col-sm-12 form-group">
										<a href="{{ url('artmin/service/update/complete/' . $service->service_id) }}" class="btn btn-lg btn-block btn-success btn-complete"><i class="fa fa-check"></i> Complete Service</a>
									</div> -->
									<!-- @endif -->
								<?php endif; ?>
								<div class="col-sm-12 form-group">
									<a href="{{ url('artmin/service/update/cancel/' . $service->service_id) }}" class="btn btn-block btn-danger"><i class="fa fa-check"></i> Cancel Service</a>
								</div>
							</div>

						</div>
					</div>
					</div>

					<!-- <div class="col-sm-4"> -->
					@if ($service->visit_technician)
					<div class="row">
						<div class="box box-solid">
							<div class="box-header">
								<h3 class="box-title">Update Service Status</h3>
							</div>
							<div class="box-body">
								<form class="form-update-service-status" action="" method="post">
									{{ csrf_field() }}
									<div class="form-group">
										<?php
										$statusNotIn = "'Service Visit Scheduled', 'Service Visit Rescheduled', 'Service Requested', 'Service On Progress', 'Request Workshop Service Pickup'"; //'Service Completed',
										// if (!$service->visit_technician) {
										// 	$statusNotIn = $statusNotIn . ", 'Service On Progress'";
										// }
										$status = DB::table('ms_service_status')->where('type', $service->service_type)->whereRaw("service_status NOT IN ($statusNotIn)")->get();
										?>
										<label>Status <span style="color:red">*</span></label>
										<select class="select2 form-control" id="status" name="status">
											<option value="">Select Status</option>
											<?php foreach ($status as $stat) : ?>
												<option value="{{ $stat->id }}" <?php if (old('status') == $stat->id) echo "selected"; ?>>{{ $stat->service_status }}</option>
											<?php endforeach; ?>
										</select>
									</div>
									<div class="form-group">
										<label>Symptoms <span class="reqflag_update_status hidden" style="color:red">*</span></label>
										<select class="select2 form-control" id="symptom" name="symptom">
											<option value="">Select Symptoms</option>
											<?php
											if (!empty($ms_problems_symptom)) {
											?>
												<?php foreach ($ms_problems_symptom as $nt) : ?>
													<option value="{{ $nt->id }}" <?php if (old('symptom') == $nt->id) echo "selected"; ?>>{{ $nt->symptom }}</option>
												<?php endforeach; ?>
											<?php
											}
											?>
										</select>
										@if ($errors->has('symptom'))
										<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> Symptoms tidak boleh kosong!</label>
										@endif
									</div>
									<div class="form-group">
										<label>Defect <span class="reqflag_update_status hidden" style="color:red">*</span></label>
										<select class="select2 form-control" id="defect" name="defect">
											<option value="">Select Defect</option>
											<?php
											if (!empty($ms_problems_defect)) {
											?>
												<?php foreach ($ms_problems_defect as $tk) : ?>
													<option value="{{ $tk->id }}" <?php if (old('defect') == $tk->id) echo "selected"; ?>>{{ $tk->defect }}</option>
												<?php endforeach; ?>
											<?php
											}
											?>
										</select>
										@if ($errors->has('defect'))
										<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> Defect tidak boleh kosong!</label>
										@endif
									</div>
									<div class="form-group">
										<label>Action <span class="reqflag_update_status hidden" style="color:red">*</span></label>
										<select class="select2 form-control" id="action" name="action">
											<option value="">Select Action</option>
											<?php
											if (!empty($ms_problems_action)) {
											?>
												<?php foreach ($ms_problems_action as $tk) : ?>
													<option value="{{ $tk->id }}" <?php if (old('action') == $tk->id) echo "selected"; ?>>{{ $tk->action }}</option>
												<?php endforeach; ?>
											<?php
											}
											?>
										</select>
										@if ($errors->has('action'))
										<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> Action tidak boleh kosong!</label>
										@endif
									</div>
									<div class="form-group">
										<label>Remarks <span class="reqflag_update_status hidden" style="color:red">*</span></label>
										<textarea id="notes" name="notes" placeholder="Deskripsi Masalah" class="form-control">{{old('notes')}}</textarea>
										@if ($errors->has('notes'))
										<label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> Remarks tidak boleh kosong!</label>
										@endif
									</div>
									<div class="form-group">
										<button class="btn btn-primary btn-update-service btn-block">
											Update Service Status
										</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					@endif

				</div>

			<?php endif; ?>

		</div><!-- /.row -->

		<div class="row">
			<div class="col-sm-12" style="padding-right: 0px;">
				<div class="box box-solid">
					<div class="box-header">
						<h3 class="box-title">Logs</h3>
					</div>
					<div class="box-body row">
						<div class="col-sm-12 table-responsive">
							<table class="table data-table-np table-sm table-bordered table-hover" data-order="[[ 0, &quot;desc&quot; ]]">
								<thead>
									<tr>
										<th>Time</th>
										<th>Status</th>
										<th>Info</th>
										<th>Symptoms</th>
										<th>Defect</th>
										<th>Action</th>
										<th>Remarks</th>
										<th width="80">Last Update</th>
										<th width="30"><center>Aksi</center></th>
									</tr>
								</thead>
								<tbody>
									<?php
									if (!empty($progress)) {
									?>
										<?php foreach ($progress as $prog) : ?>
											<tr>
												<td data-order="{{ date('Y-m-d H:i:s', strtotime($prog->created_at)) }}">
													{{ date("d-m-Y H:i", strtotime($prog->created_at)) }}
												</td>
												<td>

													<label class="label <?php if ($prog->status == 13) echo "label-success";
																							else echo "label-primary"; ?>">
														<?php
														$update_status = DB::table('ms_service_status')->where("id", $prog->status)->first();
														if (isset($update_status->service_status)) {
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
													if ($prog->pic != '') {
														echo "<br><b>PIC: </b>" . $prog->pic;
													}
													?>
												</td>
												<td>{{ $prog->symptomName }}</td>
												<td>{{ $prog->defectName }}</td>
												<td>{{ $prog->actionName }}</td>
												<td>{{ $prog->notes }}</td>
												<td>{{ $prog->updated_at ?? $prog->created_at }}</td>
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
									<?php
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

	</section><!-- /.content -->
</div>

<!-- Modal Set Schedule -->
<div class="modal fade" id="modal-schedule" role="dialog" aria-labelledby="Schedule" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content" style="">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-schedule-title"><b>Set Service Schedule</b></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">
						<ion-icon name="close-outline"></ion-icon>
					</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="{{ url('artmin/service/set-schedule/' . $service->service_id) }}" method="post" onsubmit="return check_set_schedule();">
					{{ csrf_field() }}
					<input type="hidden" id="progress_status" name="progress_status" value="3">
					<?php
					$visit_time = DB::table('ms_service_time')->get();
					$technician = DB::table('ms_technician')->where('status', 1)->where('sc_id', $service->sc_id)->get();
					$date = '';
					$time = 1;
					if ($service->visit_time != '') {
						$vi_time = $service->visit_time;
					} else {
						$vi_time = $service->prefered_time;
					}

					if ($service->visit_date != '') {
						$vi_date = date('d-m-Y', strtotime($service->visit_date));
					} else {
						$vi_date = date('d-m-Y', strtotime($service->prefered_date));
					}
					?>
					<div class="form-group">
						<label>Visit Date <span style="color:red">*</span></label>
						<input type="text" class="form-control datepicker" xxxxdata-date-start-date="{{ date('d/m/Y', strtotime($vi_date)) }}" data-date-end-date="{{ date('d/m/Y', strtotime('+5 days', strtotime($vi_date))) }}" name="visit_date" id="visit_date" value="{{ date('d-m-Y', strtotime($vi_date)) }}" placeholder="dd-mm-yyyy">
					</div>
					<div class="form-group">
						<label>Visit Time <span style="color:red">*</span></label>
						<select class="form-control" name="visit_time" id="visit_time">
							<?php
							if (!empty($visit_time)) {
							?>
								<?php foreach ($visit_time as $time) : ?>
									<option value="<?= $time->id ?>" <?php if (old('time', $vi_time) == $time->id) echo "selected"; ?>><?= $time->time ?>
									</option>
								<?php endforeach; ?>
							<?php
							}
							?>
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

					<?php /*
					<div class="form-group hidden">
						<?php
							$notes = []; //DB::table('ms_problems_defect')->where('problem_initial_id', $warranty->problem_initial_id)->get();
						?>
						<label>Notes/Defect (Optional)</label>
						<select class="select2 form-control" name="notes">
							<option value="">Select Notes</option>
							<?php foreach ($notes as $nt) : ?>
								<option value="{{ $nt->id }}">{{ $nt->defect }}</option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group hidden">
						<?php
							$taken = []; //DB::table('ms_problems_taken')->where('problem_initial_id', $warranty->problem_initial_id)->get();
						?>
						<label>Action/Taken (Optional)</label>
						<select class="select2 form-control" name="action">
							<option value="">Select Taken</option>
							<?php foreach ($taken as $tk) : ?>
								<option value="{{ $tk->id }}">{{ $tk->taken }}</option>
							<?php endforeach; ?>
						</select>
					</div>
					*/ ?>
					<div class="form-group">
						<label>Symptoms (Optional)</label>
						<select class="select2 form-control" name="symptom">
							<option value="">Select Symptoms</option>
							<?php foreach ($ms_problems_symptom as $nt) : ?>
								<option value="{{ $nt->id }}">{{ $nt->symptom }}</option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group">
						<label>Defect (Optional)</label>
						<select class="select2 form-control" name="defect">
							<option value="">Select Defect</option>
							<?php foreach ($ms_problems_defect as $tk) : ?>
								<option value="{{ $tk->id }}">{{ $tk->defect }}</option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group">
						<label>Action (Optional)</label>
						<select class="select2 form-control" name="action">
							<option value="">Select Action</option>
							<?php foreach ($ms_problems_action as $tk) : ?>
								<option value="{{ $tk->id }}">{{ $tk->action }}</option>
							<?php endforeach; ?>
						</select>
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
				<form class="frevisi" action="{{ url('artmin/service/request-details/progress/revisi') }}" method="post">
					{{ csrf_field() }}
					<input type="hidden" name="reg_service_progress_id">
					<!-- <div class="form-group hidden"> -->
						<?php
							// $statusNotIn = "'Service Visit Scheduled', 'Service Visit Rescheduled', 'Service Requested', 'Service Completed', 'Service On Progress'";
							$status = []; //DB::table('ms_service_status')->where('type', $service->service_type)->whereRaw("service_status NOT IN ($statusNotIn)")->get();
						?>
						<!-- <label>Revisi Status</label>
						<select class="select2 form-control" name="revisi_status" id="revisi_status">
							<option value="">Select Status</option> -->
							<?php foreach ($status as $stat) : ?>
								<!-- <option value="{{ $stat->id }}">{{ $stat->service_status }}</option> -->
							<?php endforeach; ?>
						<!-- </select>
					</div> -->

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

					<!-- <div class="form-group">
						<label>Revisi Info</label>
						<textarea name="revisi_info" class="form-control" placeholder="Revisi Info"></textarea>
					</div> -->
					<div class="form-group">
						<label>Symptoms</label>
						<select class="select2 form-control" name="revisi_symptom">
							<option value="">Select Symptoms</option>
							<?php foreach ($ms_problems_symptom as $nt) : ?>
								<option value="{{ $nt->id }}">{{ $nt->symptom }}</option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group">
						<label>Defect</label>
						<select class="select2 form-control" name="revisi_defect">
							<option value="">Select Defect</option>
							<?php foreach ($ms_problems_defect as $tk) : ?>
								<option value="{{ $tk->id }}">{{ $tk->defect }}</option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group">
						<label>Action</label>
						<select class="select2 form-control" name="revisi_action">
							<option value="">Select Action</option>
							<?php foreach ($ms_problems_action as $tk) : ?>
								<option value="{{ $tk->id }}">{{ $tk->action }}</option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group">
						<label>Remarks</label>
						<textarea name="revisi_notes" placeholder="Deskripsi Masalah" class="form-control"></textarea>
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

<div id="mcreateso" class="modal mcreateso" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Create SO</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="form-create-so" action="{{ url('artmin/service/create-so/' . $service->service_id) }}" method="post">
				{{ csrf_field() }}
				<div class="mb-4 d-flex">
					<div class="col-md-10 mx-0 px-0 d-flex flex-gap-2">
						<div class="form-group">
							<label>Price List</label>
							<div style="width: 200px;">
								<select class="form-control select2" name="pricelist_id" id="pricelist_id">
									<option value="">-- Pilih Price List --</option>
									@foreach($odoo_pricelist as $key => $val)
									<option value="{{ $val->odoo_pricelist_id }}">{{ $val->description }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group hidden">
							<label>Teknisi</label>
							<div style="width: 350px;">
								<select class="form-control select2" name="odoo_user_id" id="odoo_user_id">
									<option value="">-- Pilih Teknisi --</option>
								</select>
							</div>
						</div>
					</div>
					<div class="form-group col-md-2 mx-0 px-0 text-right">
						<label class="mt-1">&nbsp;</label>
						<button type="button" id="btn-add-item" class="btn btn-sm btn-warning"><i class="fa fa-plus">&nbsp;&nbsp;Add Item</i></button>
					</div>
				</div>
				<table class="table table-bordered">
					<thead>
						<th width="40%">Product</th>
						<th width="25%" class="hidden"><center>Price</center></th>
						<th width="10%"><center>QTY</center></th>
						<th width="25%" class="hidden"><center>Total</center></th>
					</thead>
					<tbody id="order_products_container"></tbody>
					<tfoot class="hidden">
						<tr>
							<td colspan="3"><center><strong>TOTAL</strong></center></td>
							<td><input id="total-product-price" value="0" readonly class="form-control text-right"></td>
						</tr>
					</tfoot>
				</table>
				</form>
			</div>
			<div class="modal-header border-top">
				<div class="text-right">
					<button id="btn-submit-createso" class="btn btn-success"><i class="fa fa-send">&nbsp;&nbsp;Submit</i></button>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal Change Service City -->
<!-- <div class="modal fade" id="modal-change-service-city" role="dialog" aria-labelledby="Service City" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><b>Change Service City</b></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">
						<ion-icon name="close-outline"></ion-icon>
					</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="{{ url('artmin/service/request-details/update-city/' . $service->service_id) }}" method="post" onsubmit="return check_set_change_service_city();">
					{{ csrf_field() }}
					<input type="hidden" name="service_city_id_prev" id="service_city_id_prev" value="{{ $service->service_city_id }}">
					<input type="hidden" name="service_city" id="service_city" value="{{ $service->service_city }}">
					<div class="form-group">
						<label>City <span style="color:red">*</span></label>
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
		$('[name="revisi_symptom"]').val(data.symptom).trigger('change');
		$('[name="revisi_defect"]').val(data.defect).trigger('change');
		$('[name="revisi_action"]').val(data.action).trigger('change');
		$('[name="revisi_info"]').val(data.info);
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

	$('[name="status"]').on('change', function (e) {
		var status = $('[name="status"]').select2('data');
		if ((status || []).length === 0) {
			return;
		} else if (!status[0].id) {
			return;
		}
		if (["1", "20"].includes(status[0].id)) {
			$('.reqflag_update_status').removeClass("hidden");
		} else {
			$('.reqflag_update_status').addClass("hidden");
		}
	});

	$(document).on('click', '.btn-update-service', function(e) {
		e.preventDefault();
		let submit = true;

		if ($('#status').val() == '') {
			$('#status').select2('open');
			submit = false;
			return false;
		}

		if (!$('.reqflag_update_status').hasClass("hidden")) {

			if ($('#symptom').val() == '') {
				$('#symptom').select2('open');
				submit = false;
				return false;
			}


			if ($('#defect').val() == '') {
				$('#defect').select2('open');
				submit = false;
				return false;
			}


			if ($('#action').val() == '') {
				$('#action').select2('open');
				submit = false;
				return false;
			}

			if ($('#notes').val() == '') {
				$('#notes').focus();
				submit = false;
				return false;
			}
		}

		if (submit) {
			$('.form-update-service-status').submit();
		}


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

	$(document).on('click', '.btn-complete', function(e) {
		e.preventDefault();
		swal({
			title: "Konfirmasi",
			text: "Apakah Anda yakin merubah status service menjadi Complete?", // tanpa mengisi Symtomps, Defect, Action dan Remarks?\n\nJika Ya Klik OK.\n\nGunakan Form UPDATE SERVICE STATUS untuk merubah status dengan mengisi Symtomps, Defect, Action dan Remarks.",
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
			text: "Apakah Anda yakin merubah status service menjadi Uncomplete?", // tanpa mengisi Symtomps, Defect, Action dan Remarks?\n\nJika Ya Klik OK.\n\nGunakan Form UPDATE SERVICE STATUS untuk merubah status dengan mengisi Symtomps, Defect, Action dan Remarks.",
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

	$(document).on('click', '#btn-submit-createso', function(e) {
		e.preventDefault();
		let submit = true;

		if ($('#pricelist_id').val() == '') {
			$('#pricelist_id').select2('open');
			submit = false;
			return false;
		}

		if (submit) {
			$('#form-create-so').submit();
		}

	});

	function check_set_schedule() {
		var visit_date = $('#visit_date').val();
		var visit_time = $('#visit_time').val();
		var technician = $('#technician').val();

		if (technician == '') {
			alert('Please select Technician first.');
			return false;
		} else {
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

	$('.btn-info').on('click', function (e) {
		var service_status = $(this).attr('service_status') || "Set Service Schedule";
		if (service_status === 'Request Workshop Service Pickup') {
			$('#modal-schedule-title').html(service_status);
			$('#progress_status').val("7");
		}
	})
	
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
				url: `{{ url("/artmin/service/progress-attachment/") }}/${attachId}`,
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

	function calculateTotalItem () {
		const rows = $("#order_products_container").find("tr");
		
		let totalQty = 0;
		let totalPrice = 0;
		for (let i = 0; i < rows.length; i++) {
			const price = Number($(rows[i]).find(`#productprice-${i}`).val());
			const qty = Number($(rows[i]).find(`#productqty-${i}`).val());
			const totalRow = (price * qty);
			totalQty += qty;
			totalPrice += totalRow;
			$(`#totalproductprice-${i}`).val(totalRow);
		}
		$("#total-product-price").val(totalPrice);
		// $("#total-product-qty").val(totalQty);
		console.log("Recalculated...");
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
			<form id="form-attach-progress-${id}" action="{{ url('artmin/service/progress-attachment/${progress_id}') }}" method="post" enctype="multipart/form-data" class="fattachments" style="flex: 1;" attachment-id="${id}" ${isnew ? "isnew" : ""}>
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

		function renderRowProductsContainer (product, isnew=false) {
			const { id, default_code, price, qty } = product;
			return `<tr productid-id="${id}">
			<td>
			<select class="form-control select2" name="default_code[]" id="productid-${id}" value=${default_code || ""}></select>
			</td>
			<td class="hidden">
			<input id="productprice-${id}" name="product_price[]" value="${price || 0}" readonly class="form-control text-right productprice"></input>
			</td>
			<td><input type="number" id="productqty-${id}" value="${qty || 1}" min="1" name="product_uom_qty[]" class="form-control text-center"></input></td>
			<td class="hidden">
			<input id="totalproductprice-${id}" value="0" readonly class="form-control text-right totalproductprice"></input>
			</td>
			</tr>`;
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

		$('#btn-add-item').on('click', function (e) {
			let last = $("#order_products_container").children().last();
			let id = 0;
			if (last && last.length > 0) {
				id = Number(last.attr("productid-id"))+1;
			}
			$("#order_products_container").append(renderRowProductsContainer({ id: id, default_code: "", price: 0, qty: 1 }, true));
			setTimeout(() => {
				initSelectSeachProduct($(`#productid-${id}`));
				$(`#productqty-${id}`).on("keyup", function(e) {
					if ((e.keyCode >= 48 && e.keyCode <= 57) || [38, 40].includes(e.keyCode)) {
						calculateTotalItem();
					}
				});
			}, 500);
		});

		function initSelectSeachProduct (el, selected) {
			if (!$(el).hasClass("select2")) {
				$(el).addClass("select2");
			}
			$(el).select2({
				placeholder: "Search Product...",
				ajax: {
					url: '{{ url("/products/all_json_for_so?display=0")}}',
					dataType: "json",
					delay: 250,
					processResults: function(data) {
						return {
							results: $.map(data, function(item) {
								return {
									id: item.default_code,
									text: `${item.default_code} | ${item.product_name_odoo}` ,
									price: item.price
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
						return "Ketik Product Code, Product Name...";
					}
				},
				minimumInputLength: 3,
				cache: true,
				// templateResult: formatResult,
				// templateSelection: formatSelection,
			});

			$(el).on('change', function(e) {
				// $(this).closest("tr").find(".productprice").val($(this).select2('data')[0].price);
				$.ajax({
					type: 'GET',
					url: `{{ url("/products/by_default_code")}}/${e.target.value}`,
				}).then(function (data) {
					if (data){
						$(e.target).closest("tr").find(".productprice").val(data.price);
						calculateTotalItem();
					}
				});
			});

			if (selected) {
				$.ajax({
					type: 'GET',
					url: `{{ url("/products/all_json_for_so?display=0")}}&q=${selected}`,
				}).then(function (data) {
					if (data.length > 0) {
						const match = data.find((o) => o.default_code === selected);
						if (match) {

							el.trigger({
								type: 'select2:select',
								params: {
									data: data.map((item) => ({
										id: match.default_code,
										text: `${match.default_code} | ${match.product_name_odoo}` ,
										price: match.price
									}))
								}
							});
							el.append(new Option(`${match.default_code} | ${match.product_name_odoo}`, match.default_code, true, true)).trigger("change");

						}
					}
				});
			}

		}

	});

</script>

@endsection