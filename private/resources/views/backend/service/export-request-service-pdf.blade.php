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
      text-align: center;
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
  <center>
    <h2>Artugo Digital Warranty Report Data</h2>
    Service Request
  </center>
  <table class="zui-table center">
    <thead>
      <tr>
        <th>#</th>
        <th>Service No.</th>
        <th>Request Date</th>
        <th>Prefered Visit</th>
        <th>Name</th>
        <th>Phone</th>
        <th>Product</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php $i = 1; ?>
      <?php foreach ($service_request as $srv) : ?>
        <tr>
          <td><?= $i++ ?></td>
          <td>{{ $srv->service_no }}</td>
          <td>{{ date('d-m-Y', strtotime($srv->created_at)) }}</td>
          <td>{{ date('d-m-Y', strtotime($srv->prefered_date)) }}</td>
          <td>{{ $srv->contact_name }}</td>
          <td>{{ $srv->contact_phone }}</td>
          <td>
            <?php $product = DB::table('ms_products')->where('product_id', $srv->product_id)->first();
            echo $product ? $product->product_name : ''; ?>
          </td>
          <td>
            @if($srv->status == '0')
            On Progress
            @elseif($srv->status == '1')
            Completed
            @elseif($srv->status == '2')
            Cancel
            @endif
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>

</html>