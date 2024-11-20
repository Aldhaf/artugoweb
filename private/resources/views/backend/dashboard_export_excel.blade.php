<table class="table data-table-np table-sm table-bordered table-hover ">
  <thead>
    <tr>
      <th>Service No</th>
      <th>Request Date</th>
      <th>Leadtime</th>
      <th>Member Name</th>
      <!-- <th>Member Phone Number</th> -->
      <!-- <th>Member Address</th> -->
      <th>Product</th>
      <th>Serial No</th>
      <th>Service Center Location</th>
      <th>Nama Teknisi</th>
      <!-- <th>City</th>
									<th>Prov</th> -->
      <!-- <th>Branch</th> -->
      <th>Purchase Date</th>
      <th>Symptom</th>
      <th>Defect</th>
      <th>Action</th>
      <th>Remarks</th>
      <th>Last Update</th>
      <th>Request</th>
      <th>Status</th>
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
        <td>{{ (!empty($latest_progress) ? $latest_progress->symptomName : null ) }}</td>
        <td>{{ (!empty($latest_progress) ? $latest_progress->defectName : null ) }}</td>
        <td>{{ (!empty($latest_progress) ? $latest_progress->actionName : null ) }}</td>
        <td>{{ (!empty($latest_progress) ? $latest_progress->notes : $srv->notes ) }}</td>
        <td>{{ (!empty($latest_progress->updated_at) ? date('d-m-Y', strtotime($latest_progress->updated_at)) : (!empty($srv->updated_at) ? date('d-m-Y', strtotime($srv->updated_at)) : null)) }}</td>
        <td>{{ ($srv->service_type == '1' ? 'Service' : 'Installation') }}</td>
        <td>
          <?php if ($srv->status == 0) echo "On Progress";
          else echo "Completed"; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>