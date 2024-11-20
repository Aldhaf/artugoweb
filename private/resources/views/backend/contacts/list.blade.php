@extends('backend.layouts.backend-app')
@section('title', 'Contacts')
@section('content')
<style>
  .contact-group-tagging {
    display: flex;
    gap: 6px;
    max-width: 200px;
    flex-wrap: wrap;
  }
  .contact-group-tagging small {
    padding-left: 4px;
    background: whitesmoke;
    border: 1px solid lightgray;
    border-radius: 8px;
    padding-right: 4px;
  }
</style>
<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Contacts
      <small>Data</small>
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
                  <a href="{{url('artmin/contacts/new')}}" class="btn btn-primary btn-add"><i class="fa fa-plus"></i> New Contact</a>
                </div>
                <div class="col-md-6 pull-right">
                </div>
              </div>
            </div>

            <div class="col-sm-12 table-responsive">
              <table class="table data-table table-sm table-bordered table-hover template-table">
                <thead>
                  <tr>
                    <th width="15">#</th>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Phone</th>
                    <th>Group</th>
                    <th width="50"><center>Status</center></th>
                    <th width="50"><center>Action</center></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $statusLabel = array(
                      'active' => 'Aktif',
                      'inactive' => 'Tidak Aktif'
                    );
                  ?>
                  @foreach($contacts as $key => $val)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $val->name }}</td>
                    <td>{{ $val->code }}</td>
                    <td>{{ $val->phone }}</td>
                    <td>{{ $val->group }}</td>
                    <td><center>{{ $statusLabel[$val->status] }}</center></td>
                    <td>
                      <center>
                        <a href="{{ url('artmin/contacts/'.$val->id) }}">
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
    let name = $(this).attr('name');
    let r = confirm('Are you sure to delete contact ' + name + '.');
    if (r) {
      let data = { "_token": "{{ csrf_token() }}", "id": id };
      let url = '{{ url("artmin/contacts-delete") }}';
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

      ajax: '{!! route('artmin.contacts') !!}', // memanggil route yang menampilkan data json
      columns: [
        {
          data: "id",
          name: "id",
          render: function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
          }
        },
        {
          data: "name",
          name: "name",
        },
        {
          data: "code",
          name: "code"
        },
        {
          data: "phone",
          name: "phone"
        },
        {
          data: "id",
          render: function (data, type, row, meta) {
            setTimeout(() => {
              for (const i of ["a","b","v"]) {
                $(`#div-group-${data}`).append(`<small>${i.repeat(5)}</small>`);
              }
            }, 1000);
            return `<div class="contact-group-tagging" id="div-group-${data}"></div>`;
          }
        },
        {
          data: "status",
          name: "status",
          searchable: false,
          render : function (data, type, row) {
            if(data === "active"){
              data = "Aktif";
            } else if(data === "inactive") {
              data = "Tidak Aktif";
            } else {
              data = "";
            }
            return `<center>${data}</center>`;
          }
        },
        {
          data: null,
          searchable: false,
          render : function (data, type, row) {
            const delBtn = `<button title="Delete" class="btn btn-danger btn-xs btn-delete" id="${row.id}" name="${row.name}"><i class="fa fa-trash"></i></button>`;
            return `
              <center>
              <a href="{{ url('artmin/contacts/${row.id}') }}" class="btn btn-primary btn-xs"><i class="fa fa-search"></i></a>
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