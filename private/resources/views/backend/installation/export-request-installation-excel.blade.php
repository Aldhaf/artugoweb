<table class="zui-table center">
  <thead>
    <tr>
      <th>Service No</th>
      <th>Date</th>
      <th>User ID</th>
      <th>Member Name</th>
      <th>Member Phone Number</th>
      <th>Member Address</th>
      <th>Product Name</th>
      <th>Serial Number</th>
      <th>Service Center Location</th>
      <th>Nama Teknisi</th>
      <th>City</th>
      <th>Purchase Date</th>
      <th>Service Status</th>
      <th>Info</th>
      <th>Notes</th>
      <th>Visit Date</th>
    </tr>
  </thead>
  <tbody>
    <?php $i = 1; ?>
    <?php foreach ($service as $srv) : ?>
      <tr>
        <td>{{ $srv->service_no }}</td>
        <td>{{ date('Y-m-d',strtotime($srv->created_at)) }}</td>
        <td>{{ $srv->userName }}</td>
        <td>{{ $srv->reg_name }}</td>
        <td>{{ $srv->reg_phone }}</td>
        <td>{{ $srv->reg_address }}</td>
        <td>{{ $srv->product_name??'' }}</td>
        <td>{{ $srv->serial_no }}</td>
        <td>{{ $srv->sc_location }}</td>
        <td>{{ $srv->name }}</td>
        <td>{{ $srv->city_name }}</td>
        <td>{{ $srv->purchase_date }}</td>
        <td>{{ $srv->service_status }}</td>
        <td>{{ $srv->info }}</td>
        <td>{{ $srv->notes }}</td>
        <td>{{ $srv->visit_date }}</td>
        <td>{{ ($srv->service_type == '1' ? 'Service' : 'Installation') }}</td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>