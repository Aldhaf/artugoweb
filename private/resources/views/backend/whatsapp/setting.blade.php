@extends('backend.layouts.backend-app')
@section('title', 'Message Blast')
@section('content')
<div>
  <section class="content-header d-flex justify-content-between align-items-center">
    <h1>
      Message
      <small>Setting</small>
    </h1>
    <div>
      <button id="btn-save" class="btn btn-sm btn-success"><i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
    </div>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-lg-6">
        <form id="frm-setting" class="fdata" action="{{ url('artmin/whatsapp/wa-setting') }}" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <input class="hidden" value="{{$setting ? $setting->id : 0}}" name="id" />
          <div class="box box-solid">
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>WA Client</label>
                    <select class="form-control select2" name="wa_client_id" id="wa_client_id">
                      <?php foreach($wa_clients as $key => $val) { ?>
                      <option {{ ($val->id == old('wa_client_id', ($setting ? $setting->wa_client_id : 0)) ? 'selected="true"' : null) }} value="{{ $val->id }}">{{ $val->client_id }}</option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <div style="display: flex; gap: 8px; align-items: end;">
                      <label>Send Interval</label>
                      <label class="text-danger">(Seconds)</label>
                    </div>
                    <!-- <div style="display: flex; gap: 8px; align-items: end;"> -->
                    <input type="number" class="form-control" id="send_interval" name="send_interval" value="{{old('send_interval', ($setting ? $setting->send_interval : 30))}}" />
                      <!-- <label>seconds </label>
                    </div> -->
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Send Limit </label>
                    <input type="number" class="form-control" id="send_limit" name="send_limit" value="{{old('send_limit', ($setting ? $setting->send_limit : 500))}}" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
</div>

<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>

<script>

  var contacts_selected = {};

  function postData(data, callBack) {
    let url = '{{ url("artmin/whatsapp/wa-setting") }}';
    $.ajax({
      type: "POST",
      enctype: 'multipart/form-data',
      url: url,
      data: data,
      processData: false,
      contentType: false,
      cache: false,
      timeout: 600000,
    })
    .success((r) => swal('Success', 'Status has been changed.', 'success').then((confirm) => {
      if (callBack) callBack();
    }))
    .error((e) => swal('Error', 'Failed to update status!.', 'error'));
  }

  $(document).ready(function(e) {

    $(document).on('click', '#btn-save', function(e) {
      e.preventDefault();
      $('#frm-setting').submit();
    })

    $(document).on('submit', '#frm-setting', function(e) {
      e.preventDefault();

      var data = new FormData(this);
      swal({
        title: "Konfirmasi",
        text: "Yakin simpan data?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((confirm) => {
        if (confirm) {
          postData(data, () => location.reload());
        }
      });
    });

  });

</script>

@endsection