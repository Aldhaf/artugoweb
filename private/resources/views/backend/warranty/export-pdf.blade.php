<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Artugo Warranty Digital</title>
  <style>
    * {
      font-family: Arial, Helvetica, sans-serif;
    }

    .zui-table {
      border: solid 1px #404040;
      border-collapse: collapse;
      border-spacing: 0;
      font: normal 13px Arial, sans-serif;
    }

    .zui-table thead th {
      background-color: #404040;
      border: solid 1px #404040;
      color: #fff;
      padding: 10px;
      text-align: left;
      text-shadow: 1px 1px 1px #fff;
      font-size: 8px;
      text-align: center;
    }

    .zui-table tbody td {
      border: solid 1px #404040;
      color: #333;
      padding: 10px;
      text-shadow: 1px 1px 1px #fff;
      font-size: 5px;
    }

    .center {
      margin-left: auto;
      margin-right: auto;
    }
  </style>
</head>

<body>
  <center>
    <h2>Artugo Digital Warranty Report Data</h2>
  </center>
  <table class="zui-table center">
    <thead>
      <tr>
        <th>#</th>
        <th>Warranty No.</th>
        <th>Product</th>
        <th>Status Install</th>
        <th>Serial No</th>
        <th>Reg Name</th>
        <th>Reg Phone</th>
        <th>Reg Address</th>
        <th>Reg City</th>
        <th>Reg Postal Code</th>
        <th>Reg Email</th>
        <th>Purchase Date</th>
        <th>Purchase Store Location</th>
        <th>Invoice</th>
        <th>Status Verified</th>
        <th>Created At</th>
      </tr>
    </thead>
    <tbody>
      <?php $i = 1; ?>
      <?php foreach ($warranty as $row) : ?>
        <tr>
          <td><?= $i++ ?></td>
          <td>{{ $row->warranty_no }}</td>
          <td>{{ $row->product_name??'' }}</td>
          <td>{{ $row->install_status }}</td>
          <td>{{ $row->serial_no }}</td>
          <td>{{ $row->reg_name }}</td>
          <td>{{ $row->reg_phone }}</td>
          <td>{{ $row->reg_address }}</td>
          <td>{{ $row->reg_city }}</td>
          <td>{{ $row->reg_post_code }}</td>
          <td>{{ $row->reg_email }}</td>
          <td>{{ date('d-m-Y', strtotime($row->purchase_date)) }}</td>
          <td>{{ $row->purchase_loc }}</td>
          <td>
            <img src="{{ $row->files }}" style="width:80px">
          </td>
          <td>
            <center>
              <?php
              if ($row->verified == '1') {
                echo 'Verified';
              } elseif ($row->verified == '2') {
                echo 'Rejected';
              } else {
                echo 'Pending';
              }
              ?>    
            </center>
          </td>
          <td>{{ date('d-m-Y', strtotime($row->created_at)) }}</td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>

</html>