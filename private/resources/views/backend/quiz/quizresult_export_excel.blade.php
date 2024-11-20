<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $quiz->name }}</title>
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
<table class="table data-table-np table-sm table-bordered table-hover ">
  <thead>
    <tr>
      <th rowspan="2" colspan="8">{{ $quiz->name }}</th>
    </tr>
    <tr></tr>
    <tr>
      <th>No.</th>
      <th>Waktu Mulai</th>
      <th>Durasi Pengerjaan</th>
      <th>APC</th>
      <th>Jawaban Benar</th>
      <th>Jawaban Salah</th>
      <th>Score</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    @foreach($quiz_answer as $key => $val)
    <tr>
      <td>{{ $key + 1 }}</td>
      <td>{{ date('d M Y - H:i:s',strtotime($val->created_at)) }}</td>
      <td>{{ (!empty($val->duration) ? number_format((float)$val->duration / 60, 2, '.', '') .' menit ('.$val->duration .' detik'.')'  : null) }}</td>
      <td>{{ $val->apcName }}</td>
      <td>{{ $val->count_true }}</td>
      <td>{{ $val->count_false }}</td>
      <td>{{ $val->score }}</td>
      <td class="{{ $val->deleted_at != null ? 'text-danger' : 'text-success' }}">{{ $val->deleted_at != null ? 'Batal' : 'Selesai' }}</td>
    </tr>
    @endforeach
  </tbody>
</table>
</body>