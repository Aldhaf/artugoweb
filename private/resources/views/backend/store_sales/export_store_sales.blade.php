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
        <th>Date</th>
        <th>Sales No.</th>
        <th>Store</th>
        <th>Region</th>
        <th>Karyawan</th>
        <th>Nama Customer</th>
        <th>No Telp Customer</th>
        <th>Alamat Customer</th>
        <th>Code</th>
        <th>Product</th>
        <th>Category B</th>
        <th>Qty</th>
        <th>Harga</th>
      </tr>
    </thead>
    <tbody>
      <?php $i = 1; ?>
      <?php foreach ($store_sales as $row) : ?>

        <?php
        $det_sales = DB::table('ms_store_sales_product')->where('sales_id', $row->sales_id)->get();

        $rowspan = count($det_sales);
        ?>
        <tr>
          <td><?= $i++ ?></td>
          <td>{{ $row->sales_date }}</td>
          <td>'{{ $row->sales_no }}</td>
          <td>{{ $row->nama_toko }}</td>
          <td>{{ $row->regional_name }}</td>
          <th>{{ $row->karyawanName }}</th>
          <th>{{ $row->customer_nama }}</th>
          <th>{{ $row->customer_telp }}</th>
          <th>{{ $row->customer_alamat }}</th>
          <th>{{ $row->product_code }}</th>
          <th>{{ $row->product_name_odoo }}</th>
          <th>{{ ($row->flag_categ_b == '1' ? 'Ya' : 'Tidak') }}</th>
          <th>{{ $row->qty }}</th>
          <th>{{ $row->harga }}</th>
        </tr>
        <?php
        foreach ($det_sales as $det_key => $det_value) {
        ?>
        <?php
        }
        ?>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>

</html>