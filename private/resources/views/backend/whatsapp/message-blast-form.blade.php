@extends('backend.layouts.backend-app')
@section('title', 'Message Blast')
@section('content')
<style>
  .checkbox-col-header {
    width: 0px !important;
    margin: 0px !important;
    padding: 0px !important;
    text-align: center !important;
  }
</style>
<div>
  <!-- Content Header (Page header) -->
  <section class="content-header d-flex justify-content-between align-items-center">
    <h1>
      Message
      <small>Blast</small>
    </h1>
    <div>
      <a href="{{url('artmin/whatsapp/wa-msg-blast')}}" class="btn btn-sm btn-primary"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Kembali</a>
      @if ($queue->id && $queue->status !== 'waiting')
      <button id="btn-save" class="btn btn-sm btn-success"><i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
      @endif
    </div>
  </section>
  <!-- Main content -->
  <section class="content">

    <div class="row">
      <div class="col-sm-12">
        <form id="frm-template" class="fdata" action="{{ url('artmin/whatsapp/wa-msg-blast') }}" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <input class="hidden" value="{{$queue->id}}" name="id" />
          <div class="box box-solid">
            <div class="box-body">
              <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Message Template</label>
                  <?php if ($queue->status !== 'onprogress') { ?>
                    <select class="form-control select2" name="message_id" id="message_id">
                      <?php foreach($templates as $key => $val) { ?>
                      <option {{ ($val->id == old('message_id', $queue->message_id) ? 'selected="true"' : null) }} value="{{ $val->id }}">{{ $val->description }}</option>
                      <?php } ?>
                    </select>
                  <?php } else { ?>
                    <div class="form-control">{{ $templates[0]->description }}</div>
                  <?php }?>
                </div>
              </div>
              <div class="col-md-3">
                <label for="">Schedule Date</label>
                <?php if ($queue->status !== 'onprogress') { ?>
                  <input type="text" class="form-control datetimepicker" placeholder="Schedule Date" value="{{ date('d-m-Y H:i', strtotime($queue->schedule_date)) }}" name="schedule_date">
                <?php } else { ?>
                  <div class="form-control">{{ date('d-m-Y H:i', strtotime($queue->schedule_date)) }}</div>
                <?php }?>
              </div>
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
              <div class="col-md-3">
                <div class="form-group">
                  <label for="">Status</label>
                  <center class="border-primary" style="padding: 4px; border-radius: 6px; border: solid 1px lightgray;">{{$statusLabel[$queue->status]}}</center>
                </div>
              </div>
              </div>
              <div class="row">
                <div class="col-md-9"><div id="message_content" class="form-control textarea mb-5 mb-md-0" style="min-height: 8rem; height: 8rem; overflow-y: auto;">{{ $templates[0]->content }}</div></div>
                <div class="col-md-3 d-flex" style="gap: 6px; flex-wrap: wrap;">
                  @if ($queue->id && $queue->status !== 'onprogress' && $queue->status !== 'canceled')
                  <div><a id="btn-cancel" class="btn btn-sm btn-info"><i class="fa fa-undo"></i>&nbsp;&nbsp;Batalkan</a></div>
                  @endif
                  @if ($queue->id && $queue->status !== 'onprogress' && $queue->status !== 'waiting')
                  <div><a id="btn-process" class="btn btn-sm btn-warning"><i class="fa fa-paper-plane"></i>&nbsp;&nbsp;Proses</a></div>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    <section class="content-header px-0 d-flex justify-content-between align-items-center">
      <h4>
        Recipients
      </h4>
      @if($queue->status === 'draft' && $statusAction !== 'insert')
      <div>
        <button id="btn-add-recipients" class="btn btn-sm btn-warning"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Penerima</button>
      </div>
      @endif
    </section>

    <div class="row">
      <div class="col-sm-12">
        <div class="box box-solid">
          <div class="box-body">
            <div class="col-sm-12 table-container">
              <table id="tbl-recipients" class="table data-table table-sm table-bordered table-hover template-table" data-ordering="false">
                <thead>
                  <tr>
                    <th width="40">#</th>
                    <th width="140">Number</th>
                    <th>Name</th>
                    <th width="100"><center>Status</center></th>
                    <th class="hidden"><center>Action</center></th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div><!-- /.row -->

  </section><!-- /.content -->
</div>


<div id="modal-contacts" class="modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title modal-header-verification">Select Contacts</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
          <table id="tbl-contacts" class="table data-table table-sm table-bordered table-hover template-table" data-ordering="false">
            <thead>
              <tr>
                <th class="checkbox-col-header">
                  <input type="checkbox" id="check-select-all-contact" style="margin: 12px !important; margin-bottom: 10px;" />
                </th>
                <th>Name</th>
                <th>Code</th>
                <th>Phone</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $statusLabel = array(
                  'active' => 'Aktif',
                  'inactive' => 'Tidak Aktif'
                );
              ?>
            </tbody>
            <tfoot>
              <td colspan="4" style="text-align: end !important; padding: 12px !important;">
                <button id="btn-done-select-contact" class="btn btn-sm btn-success"><i class="fa fa-check"></i>&nbsp;&nbsp;Pilih</button>
              </td>
            </tfoot>
          </table>
      </div>
    </div>
  </div>
</div>

<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>

<script>

  var contacts_selected = {};

  function postData(data, callBack) {
    let url = '{{ url("artmin/whatsapp/wa-msg-blast") }}';
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

  $(document).on('click', '#btn-save', function() {
    $("#frm-template").submit();
  });

  $(document).on('click', '#btn-add-recipients', function() {
    $("#modal-contacts").modal("show");
  });

  $(document).on('click', '#btn-done-select-contact', function() {

    swal({
      title: "Konfirmasi",
      text: "Tambahkan penerima pesan?",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((confirm) => {
      if (confirm) {
        let url = '{{ url("artmin/whatsapp/wa-msg-blast-recipient") }}';
        $.ajax({
          url: url,
          method: "POST",
          data: {
            "id": $("[name='id']").val(),
            "_token": $('[name="_token"]').val(),
            "contacts_selected": JSON.stringify(Object.keys(contacts_selected))
          },
          success: function(res) {
            if (res.success) {
              swal('Berhasil', res.message, 'success').then((confirm) => location.reload());
            } else {
              swal('Gagal', res.message, 'error');
            }
          }
        });

      }
    });
  });

  $(document).on('click', "#btn-process", function(e) {

    let data = new FormData();
    data.append("_token", $('[name="_token"]').val());
    data.append("id", $('[name="id"]').val());
    data.append("status", "waiting");

    swal({
        title: "Konfirmasi",
        text: "Siap untuk di proses?",
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

  $(document).on('click', "#btn-cancel", function(e) {

    let data = new FormData();
    data.append("_token", $('[name="_token"]').val());
    data.append("id", $('[name="id"]').val());
    data.append("status", "canceled");

    swal({
      title: "Konfirmasi",
      text: "Yakin akan dibatalkan?",
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

  function onChangeCheckRow (e) {
    const contactId = $($(e.target)).attr("contact-id");
    if($(e.target)[0].checked) {
      contacts_selected[contactId] = true;
    } else {
      delete contacts_selected[contactId];
    }
    console.log(contacts_selected);
  }

  function onChangeAllCheck (e) {
    const selectAll = $(e.target)[0].checked;
    const tbl = $("#tbl-contacts tbody tr");
    for (let i = 0; i < tbl.length; i++) {
      $(tbl[i]).find("td input")[0].checked = selectAll;

      const contactId = $($(tbl[i]).find("td input")[0]).attr("contact-id");
      if (selectAll) {
        contacts_selected[contactId] = true;
      } else {
        delete contacts_selected[contactId];
      }
    }

    console.log(contacts_selected);
  }

  function initSelectSeachTemplate () {
    if (!$("#message_id").hasClass("select2")) {
        $("#message_id").addClass("select2");
    }
    $("#message_id").select2({
        placeholder: "Search Template...",
        ajax: {
            url: '{{ url("artmin/whatsapp/wa-msg-template-json")}}',
            dataType: "json",
            delay: 250,
            processResults: function(data) {
                return {
                    results: $.map(data, function(item) {
                        return {
                            id: item.id,
                            text: item.description,
                            content: item.content,
                        }
                    })
                };
            },
        },
        escapeMarkup: function(m) {
            return m;
        },
        language: {
            searching: function() {
                return "Ketik Deskripsi atau Kontent untuk mencari...";
            }
        },
        minimumInputLength: 3,
        cache: true,
        // templateResult: formatResult,
        // templateSelection: formatSelection,
    });

    $("#message_id").on("change", function(e) {
      $("#message_content").html($(this).select2("data")[0].content);
    });
  }

  function initRecipientsTable() {
    $('#tbl-recipients').DataTable().clear().destroy();
    $('#tbl-recipients').DataTable({
      paging: true,
      searching: true,
      processing: true,
      serverSide: true,

      retrieve: true,
      responsive: false,
      destroy: true,

      ajax: '{!! route('artmin.whatsapp.wamsgblast.recipients', $queue->id) !!}', // memanggil route yang menampilkan data json
      columns: [
        {
          data: "id",
          name: "id",
          searchable: false,
          render: function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
          }
        },
        {
          data: "number",
          name: "number"
        },
        {
          data: "name",
          name: "name",
        },
        {
          data: "status",
          name: "status",
          searchable: false,
          render : function (data, type, row) {
            let textClass = "";
            if(data === "waiting"){
              data = "Menunggu";
              textClass = "warning";
            } else if(data === "sent") {
              data = "Terkirim";
              textClass = "success";
            } else if(data === "cancel") {
              data = "Batal";
              textClass = "primary";
            } else if(data === "failed") {
              data = "Gagal";
              textClass = "danger";
            } else {
              data = "";
            }
            return `<center class="bg-${textClass}" style="padding: 4px; border-radius: 6px; border: solid 1px lightgray;">${data}</center>`;
          }
        }
      ],
      order: [[1, "desc" ]]
    });
  }

  function initContactTable() {
    $('#tbl-contacts').DataTable().clear().destroy();
    $('#tbl-contacts').DataTable({
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
          searchable: false,
          className: "checkbox-col-header",
          render: function (data, type, row, meta) {
            const checkId = `check-contact-${data}`;
            setTimeout(() => {
              $(`#${checkId}`).off("change");
              $(`#${checkId}`).on("change", onChangeCheckRow);
            }, 500);
            return `<input type="checkbox" id="${checkId}" contact-id="${data}" ${contacts_selected[data] ? "checked" : ""} />`;
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
        }
      ],
      order: [[1, "desc" ]]
    });
  }

  $(document).ready(function(e) {

    $("#modal-contacts").on("hide.bs.modal", function () {
      contacts_selected = {};
      $("#check-select-all-contact")[0].checked = false;
      initContactTable();
    });

    $("#check-select-all-contact").on("change", onChangeAllCheck);

    setTimeout(() => initSelectSeachTemplate(), 1000);

    setTimeout(() => initRecipientsTable(), 1000);

    setTimeout(() => initContactTable(), 1000);

  });

</script>


@endsection