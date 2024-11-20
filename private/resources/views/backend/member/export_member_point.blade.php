<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Artugo Store Sales</title>
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
    }

    .zui-table tbody td {
      border: solid 1px #404040;
      color: #333;
      padding: 10px;
      text-shadow: 1px 1px 1px #fff;
    }

    .center {
      margin-left: auto;
      margin-right: auto;
    }
  </style>
</head>

<body>
  <table class="zui-table center">
    <thead>
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Address</th>
        <th>City</th>
        <th>Product</th>
        <th>Warranty Number</th>
        <th>Serial Number</th>
        <th>Purchase Date</th>
        <th>Expired At</th>
        <th>Status</th>
        <th>Total Point</th>
        <th>Point Terpakai</th>
        <th>Sisa Point</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $status_map = [
          "waiting" => "Request",
          "approved" => "Approved",
          "rejected" => "Rejected"
        ];
        $i = 1;
      ?>
      <?php foreach ($member_point as $row) : ?>
        <tr>
          <td><?= $i++ ?></td>
          <td>{{ $row->name }}</td>
          <td>{{ $row->email }}</td>
          <td>{{ $row->phone }}</td>
          <td>{{ $row->address }}</td>
          <td>{{ $row->city }}</td>
          <td>{{ $row->product_name_odoo }}</td>
          <td>{{ $row->warranty_no }}</td>
          <td>{{ $row->serial_no }}</td>
          <td>{{ $row->purchase_date }}</td>
          <td>{{ $row->expired_at }}</td>
          <td>{{ $status_map[$row->status] }}</td>
          <td>{{ $row->value }}</td>
          <td>{{ $row->used }}</td>
          <td>{{ $row->balance }}</td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>

</html>