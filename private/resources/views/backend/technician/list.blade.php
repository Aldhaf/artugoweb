@extends('backend.layouts.backend-app')
@section('title', 'Technician')
@section('content')
<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Technician
      <small>Data</small>
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">

      <div class="col-sm-12">
        <div class="box box-solid">
          <!-- <div class="box-header">
			          <h3 class="box-title">Products</h3>
			        </div> -->
          <div class="box-body">
            <div class="form-group">
              <a href="{{ url('artmin/technician/add-technician') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Technician</a>
            </div>
            <div class="col-sm-12 table-container">
              <table class="table table-bordered data-table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th width="120">Service Center</th>
                    <th>Technician</th>
                    <th width="65">Joined At</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Cover Area</th>
                    <th><center>Status</center></th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($technician as $key => $val)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $val->sc_location }}</td>
                    <td>
                      <div>
                        {{ $val->name }}
                        @if($val->technician_type)
                        </br><i>{{$val->technician_type}}</i>
                        @endif
                      </div>
                    </td>
                    <td>{{ $val->join_date ? date('d-m-Y', strtotime($val->join_date)) : '' }}</td>
                    <td>{{ $val->phone }}</td>
                    <td>{{ $val->email }}</td>
                    <td>{{ $val->cover_area }}</td>
                    <td>
                      <center>
                        <button data-id="{{ $val->id }}" class="btn {{$val->status == '1' ? 'btn-primary' : ''}} btn-status" data-status="{{$val->status}}">{{$val->status == '1' ? 'Active' : 'Not Active'}}</button>
                      </center>
                    </td>
                    <td>
                      <a title="Edit" href="{{ url('artmin/technician/edit-technician/'.$val->technician_id) }}" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></a>
                      <button title="Delete" class="btn btn-danger btn-xs btn-delete" technician_id="{{ $val->technician_id }}" technician_name="{{ $val->name }}"><i class="fa fa-trash"></i></button>
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

    let technician_id = $(this).attr('technician_id');
    let technician_name = $(this).attr('technician_name');

    let r = confirm('Are you sure to delete data Technician ' + technician_name);

    if (r) {
      let data = {
        "_token": "{{ csrf_token() }}",
        "technician_id": technician_id
      };
      
      let url = '{{ url("artmin/technician/delete-technician") }}';

      $.post(url,data,function(data){
        location.reload();
        // console.log(data);
      });

    }

  });

  $(document).on('click','.btn-status',function(e){
    e.preventDefault();

    const curStatus = $(this).attr("data-status");
    const techID = $(this).attr("data-id");
    const url = '{{ url("artmin/technician/change-status") }}/' + techID;

    swal({
        title: "Confirmation",
        text: `Are you sure to change status to ${curStatus === "1" ? "Not Active" : "Active"}?`,
        icon: "info",
        buttons: true
      })
      .then((ok) => {
        if (ok) {
          const data = {
            "_token": "{{ csrf_token() }}",
            category_id: techID,
            active: curStatus === "1" ? "0" : "1"
          };

          $.post(url, data)
          .success((r) => swal('Success', 'Status has been changed.', 'success').then((confirm) => location.reload()))
          .error((e) => swal('Error', 'Failed to update status!.', 'error'));
        }
      });
  });
</script>


@endsection