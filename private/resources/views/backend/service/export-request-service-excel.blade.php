<table>
  <thead>
    <tr>
      <th>Service No</th>
      <th>Svc Type</th>
      <th>Request Date</th>
      <th>Prefered Visit</th>
      <th>Leadtime</th>
      <th>User ID</th>
      <th>Member Name</th>
      <th>Member Phone Number</th>
      <th>Member Address</th>
      <th>Product</th>
      <th>Serial No</th>
      <th>Service Center Location</th>
      <th>Nama Teknisi</th>
      <th>City</th>
      <th>Prov</th>
      <th>Branch</th>
      <th>Purchase Date</th>
      <th>Usaged</th>
      <th>Status Warranty</th>
      <th>Deskripsi Masalah</th>
      <th>Symptom</th>
      <th>Defect</th>
      <th>Action</th>
      <th>Remarks</th>
      <th>Close Date/Last Update</th>
      <th>Request</th>
      <th>Status</th>
      <th>Date Time</th>
    </tr>
  </thead>
  <tbody>
    <?php $i = 1; ?>
    <?php foreach ($service_request as $srv) : ?>
      <?php
      // DB::enableQueryLog();
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
        // ->orWhereRaw("(reg_service_progress.service_id = " . $srv->service_id . " AND IFNULL(reg_service_progress.symptom, 0) != 0 AND IFNULL(reg_service_progress.defect, 0) != 0 AND IFNULL(reg_service_progress.action, 0) != 0)")
        ->orderBy('reg_service_progress.created_at', 'desc')
        ->first();
      // dd(DB::getQueryLog());
      $interval = date_diff(date_create($srv->purchase_date), date_create(date('Y-m-d')));

      $status_warranty = '-';
      if ((int)$interval->format('%a') < 365) {
        $status_warranty = 'In Warranty';
      } else {
        $status_warranty = 'Out Warranty';
      }

      if (strtotime($srv->prefered_date) > strtotime(date('Y-m-d'))) {
        $lt = date_diff(date_create($srv->prefered_date), date_create(date('Y-m-d')));
        $leadtime = (int)$lt->format('%a');
      } else {
        $leadtime = 0;
      }

      ?>
      <tr>
        <td>{{ $srv->service_no }}</td>
        <td>
          <!-- svc_type -->
        </td>
        <td>{{ date('d-m-Y', strtotime($srv->created_at)) }}</td>
        <td>{{ date('d-m-Y', strtotime($srv->prefered_date)) }}</td>
        <td>{{ $leadtime }}</td>
        <td>{{ $srv->userName }}</td>
        <td>{{ $srv->contact_name }}</td>
        <td>{{ $srv->contact_phone }}</td>
        <td>{{ $srv->service_address }}</td>
        <td>{{ $srv->product_name_odoo }}</td>
        <td>{{ strtoupper($srv->serial_no) }}</td>
        <td>{{ $srv->sc_location }}</td>
        <td>{{ $srv->technicianName }}</td>
        <td>{{ $srv->city_name }}</td>
        <td>{{ $srv->province_name }}</td>
        <td>{{ $srv->branchName }}</td>
        <td>{{ $srv->purchase_date }}</td>
        <td>{{ $interval->format('%a') }}</td>
        <td>{{ $status_warranty }}</td>
        <td>{{ $srv->problem_notes }}</td>

        <td>{{ (!empty($latest_progress) ? $latest_progress->symptomName : null ) }}</td>
        <td>{{ (!empty($latest_progress) ? $latest_progress->defectName : null ) }}</td>
        <td>{{ (!empty($latest_progress) ? $latest_progress->actionName : null ) }}</td>

        <td>{{ (!empty($latest_progress) ? $latest_progress->notes : $srv->notes ) }}</td>
        <!-- <td>{{ (!empty($latest_progress->updated_at) ? date('d-m-Y', strtotime($latest_progress->updated_at)) : (!empty($srv->updated_at) ? date('d-m-Y', strtotime($srv->updated_at)) : null)) }}</td> -->
        <td>{{ (!empty($latest_progress->updated_at) ? date('d-m-Y', strtotime($latest_progress->updated_at)) : date('d-m-Y', strtotime($latest_progress->created_at))) }}</td>
        <td>{{ ($srv->service_type == '1' ? 'Service' : 'Installation') }}</td>
        <td>
          @if($srv->status == '0')
          On Progress
          @elseif($srv->status == '1')
          Completed
          @elseif($srv->status == '2')
          Cancel
          @endif
        </td>
        <td>{{ (!empty($latest_progress) ? $latest_progress->created_at : $srv->created_at ) }}</td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>