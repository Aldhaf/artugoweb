@extends('backend.layouts.backend-app')
@section('title', 'Message Blast')
@section('content')
<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Message
      <small>Blast</small>
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-sm-12">
        <div class="box box-solid">
          <div class="box-body">

            <div class="form-group">
              <div class="row">
                <div class="col-md-6">
                  <a href="{{url('artmin/whatsapp/wa-msg-blast/new')}}" class="btn btn-primary btn-add"><i class="fa fa-plus"></i> New Message Blast</a>
                </div>
                <div class="col-md-6 pull-right">
                </div>
              </div>
            </div>

            <!-- <div class="col-sm-12 table-container">
              <table class="table table-bordered data-table"> -->
            <div class="col-sm-12 table-responsive">
              <table class="table data-table table-sm table-bordered table-hover template-table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Template</th>
                    <th>Content</th>
                    <th>Schedule Date</th>
                    <th><center>Status</center></th>
                    <th><center>Action</center></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $statusLabel = array(
                      'draft' => 'Draft',
                      'waiting' => 'Waiting',
                      'onprogress' => 'On Progress',
                      'completed' => 'Completed',
                      'canceled' => 'Canceled',
                      'uncompleted' => 'Not Complete',
                    );
                  ?>
                  @foreach($queue as $key => $val)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $val->description }}</td>
                    <td>{{ $val->schedule_date }}</td>
                    <td><center>{{ $statusLabel[$val->status] }}</center></td>
                    <td>
                      <center>
                        <a href="{{ url('artmin/whatsapp/wa-msg-blast/'.$val->id) }}">
                          <button class="btn btn-sm btn-primary">Detail</button>
                        </a>
                      </center>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div><!-- /.row -->
  </section><!-- /.content -->
</div>

<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>

<script>
  $(document).on('click', '.btn-delete', function(e) {

    e.preventDefault();

    let id = $(this).attr('id');
    let description = $(this).attr('description');
    let r = confirm('Are you sure to delete blast message\n(' + description + ').');
    if (r) {
      let data = { "_token": "{{ csrf_token() }}", "id": id };
      let url = '{{ url("artmin/whatsapp/wa-msg-blast-delete") }}';
      $.post(url, data)
      .success((r) => {
        if(r.success) {
          swal("Berhasil", r.message, "success").then((confirm) => location.reload());
        } else {
          swal("Gagal", r.message, "error");
        }
      }).error((e) => swal('Gagal', e.responseJSON.message, 'error'));
    }
  });

  $(document).ready(function() {
    $('.data-table').DataTable().clear().destroy();
    $('.template-table').DataTable({
      paging: true,
      searching: true,
      processing: true,
      serverSide: true,

      retrieve: true,
      responsive: false,
      destroy: true,

      ajax: '{!! route('artmin.whatsapp.wamsgblast') !!}', // memanggil route yang menampilkan data json
      columns: [
        {
          data: "id",
          name: "id",
          render: function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
          }
        },
        {
          data: "description",
          name: "wa_messages.description",
        },
        {
          data: "content",
          name: "wa_messages.content",
          render : function (data, type) {
            return (data || "").substr(0, 100) + "...";
          }
        },
        {
          data: "schedule_date",
          name: "schedule_date",
          render : function (data, type, row) {
            return moment(data || row.created_at).format("DD-MM-YYYY HH:mm:ss");
          }
        },
        {
          data: "status",
          name: "status",
          searchable: false,
          render : function (data, type, row) {
            if (data === "draft") {
              return "Draft";
            } else if(data === "waiting"){
              return "Waiting";
            } else if(data === "onprogress") {
              return "On Progress";
            } else if(data === "completed") {
              return "Completed";
            } else if(data === "canceled") {
              return "Canceled";
            } else if(data === "uncompleted") {
              return "Not Complete";
            }
          }
        },
        {
          data: null,
          searchable: false,
          render : function (data, type, row) {
            const delBtn = `<button title="Delete" class="btn btn-danger btn-xs btn-delete" id="${row.id}" description="${row.description}"><i class="fa fa-trash"></i></button>`;
            return `
              <center>
              <a href="{{ url('artmin/whatsapp/wa-msg-blast/${row.id}') }}" class="btn btn-primary btn-xs"><i class="fa fa-search"></i></a>
              ${delBtn}
              </center>
            `;
          }
        }
      ],
      order: [[1, "desc" ]],
      drawCallback: function (settings) { 
        // console.log("XXX------Current-Page", $(this).DataTable().page());
        // console.log("XXX------Current-Search", $(this).DataTable().search());
        // console.log("XXX------Current-Order", $(this).DataTable().order());
      },
    });
  });

</script>


@endsection