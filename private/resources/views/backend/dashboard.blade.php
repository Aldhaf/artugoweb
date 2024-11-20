@extends('backend.layouts.backend-app')
@section('title', 'Dashboard')

<style>
	[data-toggle="collapse"] .fa:before {  
		content: "\f139";
	}

	[data-toggle="collapse"].collapsed .fa:before {
		content: "\f13a";
	}
/* ******************* */
    .info-box {
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        height: 80px;
        display: flex;
        cursor: default;
        background-color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 30px;
		border-radius: 12px !important;
    }
    .info-box .content {
        display: inline-block;
        padding: 7px 10px;
		min-height: auto;
		height: fit-content !important;
    }
    .info-box .icon {
        display: inline-block;
        text-align: center;
        background-color: rgba(0, 0, 0, 0.12);
        width: 80px;
		height: 100%
    }
    .info-box .icon i {
        color: #fff;
        font-size: 50px;
        line-height: 80px;
    }
    .info-box .content .text {
        font-size: 16px;
		font-weight: bold;
        margin-top: 11px;
        color: #555;
    }
    .bg-pink {
        background-color: #E91E63 !important;
        color: #fff;
    }
    .bg-cyan {
        background-color: #00BCD4 !important;
        color: #fff;
    }
    .bg-light-green {
        background-color: #8BC34A !important;
        color: #fff;
    }
    .bg-orange {
        background-color: #ff851b !important;
    }
    .number {
        font-size: 2vw;
        font-weight: bold;
		text-align: right;
    }
    .bg-pink .content .text, .bg-pink .content .number {
        color: #fff !important;
    }
    .bg-cyan .content .text, .bg-cyan .content .number {
        color: #fff !important;
    }
    .bg-light-green .content .text, .bg-light-green .content .number {
        color: #fff !important;
    }
    .bg-orange .content .text, .bg-orange .content .number {
        color: #fff !important;
    }

</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.1.0/chart.js"></script>
@section('content')
<div id="dashboard">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Dashboard
			<small></small>
		</h1>
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- Small boxes (Stat box) -->

		<?php /*
		<div class="w-100 d-flex justify-content-between" style="gap: 8px;">
			<?php
				$dashboad_box = array(
					['service_type' => 'installation', 'period_type' => 'last7', 'color' => 'success', 'title' => 'Installation Last 7 Days', 'godetail' => ''],
					['service_type' => 'installation', 'period_type' => 'morethan7', 'color' => 'warning', 'title' => 'Installation More Than 7 Days', 'godetail' => ''],
					['service_type' => 'service', 'period_type' => 'last7', 'color' => 'info', 'title' => 'Service Last 7 Days', 'godetail' => ''],
					['service_type' => 'service', 'period_type' => 'morethan7', 'color' => 'danger', 'title' => 'Service More Than 7 Days', 'godetail' => ''],
				);
			?>
			@foreach ($dashboad_box as $box)
			<div class="box px-4 p-0xx pt-4xx" style="width: 33%; border-left: 0.35rem solid var(--color-{{$box['color']}});">
				<div class="box-title text-{{$box['color']}} label-{{$box['color']}} text-center pt-2" style="font-weight: bolder; font-size: 1.5rem; border-radius: 0.50rem; color: white;">
					<label>{{$box['title']}}</label>
				</div>
				<div class="box-body p-0 card-item">
					<div class="d-flex justify-content-between align-items-end border-bottom pointer card-sub-item">
						<label data-url="{{ '/artmin/dashboard/service-count?service_type=' . $box['service_type'] . '&period_type=' . $box['period_type'] . '&status_type=new' }}" class="pointer">New Request</label>
						<label data-value="0" id="{{$box['service_type']}}-new" class="text-warning pointer" style="font-size: 1.1rem; font-size: 3rem; padding: 0px; margin: 0px;">0</label>
					</div>
					<div class="d-flex justify-content-between align-items-end border-bottom pointer card-sub-item">
						<label data-url="{{ '/artmin/dashboard/service-count?service_type=' . $box['service_type'] . '&period_type=' . $box['period_type'] . '&status_type=schedule' }}" class="pointer">On Schedule</label>
						<label data-value="0" id="{{$box['service_type']}}-schedule" class="text-primary pointer" style="font-size: 1.1rem; font-size: 3rem; padding: 0px; margin: 0px;">0</label>
					</div>
					<div class="d-flex justify-content-between align-items-end border-bottom pointer card-sub-item">
						<label data-url="{{ '/artmin/dashboard/service-count?service_type=' . $box['service_type'] . '&period_type=' . $box['period_type'] . '&status_type=progress' }}" class="pointer">On Progress</label>
						<label data-value="0" id="{{$box['service_type']}}-progress" class="text-success pointer" style="font-size: 1.1rem; font-size: 3rem; padding: 0px; margin: 0px;">0</label>
					</div>
					@if ($box['period_type'] == 'last7')
					<div class="d-flex justify-content-between align-items-end border-bottom pointer card-sub-item">
						<label data-url="{{ '/artmin/dashboard/service-count?service_type=' . $box['service_type'] . '&period_type=' . $box['period_type'] . '&status_type=completed' }}" class="pointer">Completed</label>
						<label data-value="0" id="{{$box['service_type']}}-completed" class="text-success pointer" style="font-size: 1.1rem; font-size: 3rem; padding: 0px; margin: 0px;">0</label>
					</div>
					@endif
				</div>
			</div>
			@endforeach
		</div>
		*/ ?>

		@if(isset($point_summary))
		<div class="row">
			<div class="col-md-12">
				<div class="box box-solid">
					<div class="pointer d-flex bg-transparent btn-outline-primary align-items-start btn-toggle-collapsible" data-toggle="collapse" data-target="#pointSumDiv">
						<i class="fa mt-2" aria-hidden="true"></i>
						<h4 class="ml-2 mt-0 pt-0"><b>Point Summary</b></h4>
					</div>
					<div id="pointSumDiv" class="container-fluid mt-4">
						<div class="row clearfix">
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
								<a href="{{url('/artmin/member/point/list?status=waiting')}}" target="_blank">
									<div class="info-box bg-orange pointer">
										<div class="content w-100">
											<div class="text">Waiting Approval</div>
											<div class="number">{{$point_summary[0]->waiting}} Request</div>
										</div>
									</div>
								</a>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
								<a href="{{url('/artmin/member/point/list?status=rejected')}}" target="_blank">
									<div class="info-box bg-pink pointer">
										<div class="content w-100">
											<div class="text">Rejected Point</div>
											<div class="number">{{$point_summary[0]->rejected}} Request</div>
										</div>
									</div>
								</a>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
								<a href="{{url('/artmin/member/point/list?status=approved')}}" target="_blank">
									<div class="info-box bg-light-green pointer">
										<div class="content w-100">
											<div class="text">Approved Point</div>
											<div class="number">{{number_format($point_summary[0]->approved_point, 2)}}</div>
										</div>
									</div>
								</a>
							</div>
							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
								<div class="info-box bg-cyan">
									<div class="content w-100">
										<div class="text">Used Point</div>
										<div class="number">{{number_format($point_summary[0]->used_point, 2)}}</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		@endif

		@if(count($svc_data) > 0)
		<div class="row">
			<div class="col-md-12">
				<div class="box box-solid">
					<div class="pointer d-flex bg-transparent btn-outline-primary align-items-start btn-toggle-collapsible" data-toggle="collapse" data-target="#serviceSumDiv">
						<i class="fa mt-2" aria-hidden="true"></i>
						<h4 class="ml-2 mt-0 pt-0"><b>Service Summary</b></h4>
					</div>
					<div id="serviceSumDiv" class="table-responsive collapse in">
						<table class="table table-sm table-bordered table-hover ">
							<thead class="label-primary">
								<tr>
									<th class="text-center" rowspan="2"><label class="mr-4" style="width: 70px;">Item</label></th>
									<th class="text-center" colspan="2"><label class="mr-4" style="width: 40px;">HO</label></th>
									<th class="text-center" colspan="2"><label class="mr-4" style="width: 40px;">SBY</label></th>
									<th class="text-center" colspan="2"><label class="mr-4" style="width: 40px;">KDR</label></th>
									<th class="text-center" colspan="2"><label class="mr-4" style="width: 40px;">SMR</label></th>
									<th class="text-center" colspan="2"><label class="mr-4" style="width: 40px;">MKS</label></th>
									<th class="text-center" colspan="2"><label class="mr-4" style="width: 40px;">BDG</label></th>
									<th class="text-center" colspan="2"><label class="mr-4" style="width: 40px;">BJM</label></th>
									<th class="text-center" colspan="2"><label class="mr-4" style="width: 40px;">DPS</label></th>
									<th class="text-center" colspan="2"><label class="mr-4" style="width: 40px;">YGY</label></th>
									<th class="text-center" colspan="2"><label class="mr-4" style="width: 40px;">SLO</label></th>
									<th class="text-center" colspan="2"><label class="mr-4" style="width: 40px;">PLB</label></th>
									<th class="text-center" colspan="2"><label class="mr-4" style="width: 40px;">PWK</label></th>
								</tr>
								<tr>
								@foreach($svc_data[0] as $key => $value)
								<?php
								$width = 40;
								$class= "text-center";
								$labelH = "";
								if (strtolower($key) == 'item') {
									$width = 70;
									$class= "text-left";
									$labelH = "Item";
								} else if (str_contains(strtolower($key), 'more than')) {
									$width = 130;
								} else {
									$labelH = str_contains(strtolower($key), '_lte3') ? '<= 3d' : '> 3d';
								}
								?>
								@if(strtolower($key) != 'item')
								<th class="{{$class}}"><label class="mr-4" style="width: {{$width}}px;">{{$labelH}}</label></th>
								@endif
								@endforeach
								</tr>
							</thead>
							<tbody>
								@foreach ($svc_data as $index => $svc)
								<tr class="{{['bg-success','bg-danger'][$index]}}">
									@foreach($svc_data[0] as $key => $value)
									<td class="{{ in_array(strtolower($value), ['install', 'service']) ? 'text-left' : 'text-right' }} pr-2">
										<label>{{ $svc->$key }}</label>
									</td>
									@endforeach
								</tr>
								@endforeach
							</tbody>
							<tfoot class="bg-warning">
								<tr>
								<?php
								
								$sumArray = array();
								foreach ($svc_data[0] as $key=> $value) {
									if (strtolower($key) != 'item') {
										foreach ($svc_data as $idx => $row) {
											isset($sumArray[$key]) || $sumArray[$key] = 0;
											$sumArray[$key] += $row->$key;
										}
									}
								}

								foreach($svc_data[0] as $key => $value) {
									$label = strtolower($key) == 'item' ? 'TOTAL' : $sumArray[$key];
								?>
								<td class="{{ strtolower($label) == 'total' ? 'text-left' : 'text-right' }} pr-2">
									<label style="width: {{strlen($key)}}0;px">{{$label}}</label>
								</td>
								<?php } ?>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
		@endif

		<div class="row">
			<div class="col-md-12">
				<div class="box box-solid">
					<div class="d-flex justify-content-between">
						<div class="pointer d-flex bg-transparent btn-outline-primary align-items-start btn-toggle-collapsible" data-toggle="collapse" data-target="#serviceListDiv">
							<i class="fa mt-2" aria-hidden="true"></i>
							<h4 class="ml-2 mt-0 pt-0"><b>Service Data</b></h4>
						</div>
						<a href="{{ url('artmin/dashboard/export') }}">
							<button class="btn btn-primary">Export Excel</button>
						</a>
					</div>
					<div id="serviceListDiv" class="table-responsive mt-4 collapse in">
						<table class="table data-table-np table-sm table-bordered table-hover ">
							<thead>
								<tr>
									<th><label>Service No</label></th>
									<th><label style="width: 100px;">Request Date</label></th>
									<th><label>Leadtime</label></th>
									<th><label style="width: 120px;">Member Name</label></th>
									<!-- <th>Member Phone Number</th> -->
									<!-- <th>Member Address</th> -->
									<th><label style="width: 150px;">Product</label></th>
									<th><label>Serial No</label></th>
									<th><label style="width: 170px;">Service Center Location</label></th>
									<th><label style="width: 120px;">Nama Teknisi</label></th>
									<!-- <th>City</th>
								<th>Prov</th> -->
									<!-- <th>Branch</th> -->
									<th><label style="width: 110px;">Purchase Date</label></th>
									<th><label style="width: 170px;">Deskripsi Masalah</label></th>
									<!-- <th>Symptom</th>
									<th>Defect</th>
									<th>Action</th> -->
									<th><label style="width: 170px;">Remarks</label></th>
									<th><label style="width: 100px;">Last Update</label></th>
									<th><label>Request</label></th>
									<th><label style="width: 110px;">Status</label></th>
									<th><label>Action</label></th>
								</tr>
							</thead>
							<tbody>
								<?php $i = 1; ?>
								<?php foreach ($service_request as $srv) : ?>
									<?php
									$latest_progress = DB::table('reg_service_progress')
										->select(
											'reg_service_progress.*',
											'ms_problems_symptom.symptom as symptomName',
											'ms_problems_defect.defect as defectName',
											'ms_problems_action.action as actionName'
										)
										->leftJoin('ms_problems_symptom', 'ms_problems_symptom.id', 'reg_service_progress.symptom')
										->leftJoin('ms_problems_defect', 'ms_problems_defect.id', 'reg_service_progress.defect')
										->leftJoin('ms_problems_action', 'ms_problems_action.id', 'reg_service_progress.action')
										->where('reg_service_progress.service_id', $srv->service_id)
										->orderBy('reg_service_progress.created_at', 'desc')
										->first();

									$service_reques_date = date_create($srv->created_at);
									$date_now = date_create(date('Y-m-d'));
									$leadtime = date_diff($service_reques_date, $date_now)->format("%a");
									?>
									<tr>
										<td>{{ $srv->service_no }}</td>
										<td>{{ date('d-m-Y', strtotime($srv->created_at)) }}</td>
										<td>{{ $leadtime + 1 }}</td>
										<td>{{ $srv->contact_name }}</td>
										<!-- <td>{{ $srv->contact_phone }}</td> -->
										<!-- <td>{{ $srv->service_address }}</td> -->
										<td>{{ $srv->product_name_odoo }}</td>
										<td>{{ $srv->serial_no }}</td>
										<td>{{ $srv->sc_location }}</td>
										<td>{{ $srv->technicianName }}</td>
										<!-- <td>{{ $srv->city_name }}</td>
									<td>{{ $srv->province_name }}</td> -->
										<!-- <td>{{ $srv->branchName }}</td> -->
										<td>{{ $srv->purchase_date }}</td>
										<td>{{ $srv->problem_notes }}</td>
										<?php /*
										<td>{{ (!empty($latest_progress) ? $latest_progress->symptomName : null ) }}</td>
										<td>{{ (!empty($latest_progress) ? $latest_progress->defectName : null ) }}</td>
										<td>{{ (!empty($latest_progress) ? $latest_progress->actionName : null ) }}</td>
										*/ ?>
										<td>{{ (!empty($latest_progress) ? $latest_progress->notes : $srv->notes ) }}</td>
										<td>{{ (!empty($latest_progress->updated_at) ? date('d-m-Y', strtotime($latest_progress->updated_at)) : (!empty($srv->updated_at) ? date('d-m-Y', strtotime($srv->updated_at)) : null)) }}</td>
										<td>{{ ($srv->service_type == '1' ? 'Service' : 'Installation') }}</td>
										<td>
											<?php if ($srv->status == 0) echo "On Progress";
											else echo "Completed"; ?>
										</td>
										<td>
											<center>
												<a href="{{ ($srv->service_type == '1' ? url('artmin/service/request-details/'.$srv->service_id) : url('artmin/installation/request-details/'.$srv->service_id)) }}" class="btn btn-primary btn-xs"><i class="fa fa-search"></i></a>
											</center>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div><!-- /.row -->

		<div class="row">
			<div class="col-md-12">
				<div class="box box-solid">
					<div class="w-100 d-flex flex-col justify-content-between">
						<div class="pointer d-flex bg-transparent btn-outline-primary align-items-start btn-toggle-collapsible" data-toggle="collapse" data-target="#storeSalesChartDiv">
							<i class="fa mt-2" aria-hidden="true"></i>
							<h4 class="ml-2 mt-0 pt-0"><b>Store Sales</b></h4>
						</div>
						<div class="d-flex flex-col justify-content-end" style="gap: 6px;">
							<div class="form-group" style="width: 160px;">
								<div class="d-flex flex-col justify-content-between">
									<label>Regional</label>
									<label>All&nbsp;&nbsp;<input type='checkbox' class='pull-right' name="filter_store_sales_regional_all" checked /></label>
								</div>
								<select class="form-control" name="filter_store_sales_regional_id"></select>
							</div>
							<div class="form-group" id="container_store_select" style="width: 160px;">
								<div class="d-flex flex-col justify-content-between">
									<label>Store</label>
									<label>All&nbsp;&nbsp;<input type='checkbox' class='pull-right' name="filter_store_sales_store_all" checked /></label>
								</div>
								<select class="form-control" name="filter_store_sales_store_id"></select>
							</div>
							<div class="form-group" style="width: 75px;">
								<label for="">Year</label>
								<select class="form-control" name="sales_year">
									<option value="{{date('Y')}}" selected>{{date('Y')}}</option>
									<option value="{{date('Y')-1}}">{{date('Y')-1}}</option>
									<option value="{{date('Y')-2}}">{{date('Y')-2}}</option>
									<option value="{{date('Y')-3}}">{{date('Y')-3}}</option>
									<option value="{{date('Y')-4}}">{{date('Y')-4}}</option>
								</select>
							</div>
							<div class="form-group" style="width: 130px;">
								<label for="">Status</label>
								<select class="form-control" name="sales_status">
									<option value="" selected>All</option>
									<option value="0">Draft</option>
									<option value="1">Request Approve</option>
									<option value="2">Approved</option>
									<option value="3">Un Approve</option>
								</select>
							</div>
							<div class="form-group">
								<label for="" style="color: transparent;">.</label>
								<button class="btn btn-primary btn-submit-filter-report-sales" style="height: 34px;"><i class="fa fa-refresh"></i></button>
							</div>
						</div>
					</div>
					<div id="storeSalesChartDiv" class="w-100 pr-7 collapse in" style="height: 250px;">
						<canvas id="storeSalesChart" class="w-100"></canvas>
					</div>
				</div>
			</div>
		</div>

		@if($is_ho)
		<br>
		<div class="row">
			<div class="col-md-8">
				<div class="box box-solid">
					<h4><b>Registration Warranty Chart - {{ date('Y') }}</b></h4>
					<canvas id="income"></canvas>
				</div>
			</div>
			<div class="col-md-4">
				<div class="box box-solid">
					<h4><b>Top Product Registered - All Time</b></h4>
					<table class="table table-bordered">
						<thead>
							<tr>
								<td>Product</td>
								<td>Registered</td>
							</tr>
						</thead>
						<tbody>
							@foreach($top_product as $val)
							<tr>
								<td>{{ $val->product_name??'' }}</td>
								<td>{{ $val->sales }}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>

				<div class="box box-solid">
					<h4><b>Top Customer - All Time</b></h4>
					<table class="table table-bordered">
						<thead>
							<tr>
								<td>Customer</td>
								<td>Product</td>
							</tr>
						</thead>
						<tbody>
							@foreach($top_customer as $val)
							<tr>
								<td>{{ $val->customer_name }}</td>
								<td>{{ $val->sales }}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
		@else
		<canvas class="hidden" id="income"></canvas>
		@endif

	</section><!-- /.content -->
</div><!-- /.row (main row) -->


<script>
	// bar chart data
	let januari = '{{$januari}}';
	let februari = '{{$februari}}';
	let maret = '{{$maret}}';
	let april = '{{$april}}';
	let mei = '{{$mei}}';
	let juni = '{{$juni}}';
	let juli = '{{$juli}}';
	let agustus = '{{$agustus}}';
	let september = '{{$september}}';
	let oktober = '{{$oktober}}';
	let november = '{{$november}}';
	let desember = '{{$desember}}';

	function onCardGoToDetail(el) {
		const child = $(el.target).find("[data-url]");
		$.get($(child).attr("data-url"), function(e) {
			// console.log("XXX", e);
		});
	}

	function onLoadCardData(dataUrl, callback) {
		$.get(dataUrl, function(res) {
			if (callback) {
				callback(res);
			}
		});
	}

	function CardLoad(card) {
		const cardChilds = $(card).children();
		for (let i = 0; i <= cardChilds.length; i++) {
			const cardSubItemLabel = $(cardChilds[i]).find("[data-url]");
			const cardSubItemValue = $(cardChilds[i]).find("[data-value]");
			const dataUrl = $(cardSubItemLabel).attr("data-url");
			if (dataUrl && cardSubItemValue) {
				onLoadCardData(dataUrl, (val) => $(cardSubItemValue).html(val));
				setInterval(() => onLoadCardData(dataUrl, (val) => $(cardSubItemValue).html(val)), 30000);
			}
		}
	}

	// $(".card-item").on("click", onCardGoToDetail);

	var barData = {
		labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
		datasets: [{
			fillColor: "#294E5D",
			strokeColor: "#23404D",
			data: [januari, februari, maret, april, mei, juni, juli, agustus, september, oktober, november, desember]
		}]
	}
	// get bar chart canvas
	var income = document.getElementById("income").getContext('2d');

	// draw bar chart
	new Chart(income).Bar(barData, {
		responsive: true,
		barValueSpacing: 2
	});

	function loadStoreSalesChart () {

		const year = $("[name='sales_year']").val();
		const status = $("[name='sales_status']").val();
		const regionalId = $("[name='filter_store_sales_regional_id']").val() || "";
		const storeId = $("[name='filter_store_sales_store_id']").val() || "";

		var chartData = [];

		$.get(`artmin/storesales/dashboard?year=${year}&status=${status}&regional_id=${regionalId}&store_id=${storeId}`, function(data) {
			if (data && Array.isArray(data)) {
				Array.from(Array(12)).forEach((o,i) => {
					const month = data.find((x) => Number(x.month) === (i+1));
					chartData.push(month ? month.total : 0);
				});

				var storeSalesChart = document.getElementById("storeSalesChart").getContext("2d");
				var storeSalesChartData = {
					labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
					datasets: [
						{
							fillColor: "#294E5D",
							strokeColor: "#23404D",
							data: chartData
						}
					]
				};
				new Chart(storeSalesChart).Bar(storeSalesChartData, {
					responsive: true,
					barValueSpacing: 16,
					responsive: true,
					maintainAspectRatio: false
				});

			}
		});
	}

	function initSelectSeachRegion () {

		if (!$("[name='filter_store_sales_regional_id']").hasClass("select2")) {
			$("[name='filter_store_sales_regional_id']").addClass("select2");
		}

		$("[name='filter_store_sales_regional_id']").select2({
			placeholder: "Search Regional Branch Store...",
			ajax: {
				url: '{{url("/artmin/storeregion-json")}}' + "?users_id={{Auth::user()->id}}",
				dataType: "json",
				delay: 250,
				processResults: function(data) {
					return {
						results: $.map(data, function(item) {
							return {
								text: item.regional_name,
								id: item.id
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
					return "Ketik nama kota...";
				}
			},
		cache: false,
			// minimumInputLength: 3,
			// templateResult: formatResult,
			// templateSelection: formatSelection,
		});
	}

	function initSelectSeachBranchStore (regional_id="") {

		// if (regional_id === "") {
		// 	$("#container_store_select").addClass("hidden");
		// 	$('#btn-select-store').addClass("hidden");
		// 	return;
		// }

		// $("#container_store_select").removeClass("hidden");

		if (!$("[name='filter_store_sales_store_id']").hasClass("select2")) {
			$("[name='filter_store_sales_store_id']").addClass("select2");
		}

		$("[name='filter_store_sales_store_id']").select2({
			placeholder: "Search Branch Store...",
			ajax: {
				url: '{{url("/artmin/storelocation-json")}}' + "?users_id={{Auth::user()->id}}" + (regional_id ? `&regional_id=${regional_id}` : ""),
				dataType: "json",
				delay: 250,
				processResults: function(data) {
					return {
						results: $.map(data, function(item) {
							return {
								text: item.nama_toko,
								id: item.store_id
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
					return "Ketik nama toko...";
				}
			},
		cache: false,
			// minimumInputLength: 3,
			// templateResult: formatResult,
			// templateSelection: formatSelection,
		});
	}
	

	$(document).ready(function() {

		setTimeout(() => {
			$(".btn-toggle-collapsible").trigger("click");
		}, 1000);

		loadStoreSalesChart();

		const cards = $(".card-item");
		for (let i = 0; i <= cards.length; i++) {
			CardLoad($(cards[i]));
		};

		$(".btn-submit-filter-report-sales").on("click", () => loadStoreSalesChart());

		$('[name="filter_store_sales_regional_id"]').on("change", function () {
			$("[name='filter_store_sales_store_id']").select2({ data: [] });
			$("[name='filter_store_sales_store_id']").val("")
			initSelectSeachBranchStore(this.selectedOptions[0].value);
			if (this.selectedOptions.length > 0 && this.selectedOptions[0].value) {
				$('[name="filter_store_sales_regional_all"]').prop('checked', false);
			}
		});

		$('[name="filter_store_sales_store_id"]').on("change", function () {
			if (this.selectedOptions.length > 0 && this.selectedOptions[0].value) {
				$('[name="filter_store_sales_store_all"]').prop('checked', false);
			}
		});
		
		$('[name="filter_store_sales_regional_all"]').on("change", function (e) {
			if ($('[name="filter_store_sales_regional_all"]').prop('checked')) {
				$("[name='filter_store_sales_store_id']").empty().trigger('change');
				$('[name="filter_store_sales_store_all"]').prop('checked', true);
				$("[name='filter_store_sales_regional_id']").empty().trigger('change');
			}
		});

		$('[name="filter_store_sales_store_all"]').on("change", function (e) {
			if ($('[name="filter_store_sales_store_all"]').prop('checked') === true) {
				$("[name='filter_store_sales_store_id']").empty().trigger('change');
			}
		});

		setTimeout(() => {
			initSelectSeachRegion();
			initSelectSeachBranchStore();
		}, 1000);

	});
</script>

@endsection