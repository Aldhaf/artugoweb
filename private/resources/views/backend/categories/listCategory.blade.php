@extends('backend.layouts.backend-app')
@section('title', 'Categories')
@section('content')
<div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Categories
      <small>Parent Data</small>
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">

      <div class="col-sm-12">
        <div class="box box-solid">
          <div class="box-body">
            <div class="col-sm-12 table-container">
              <table class="table table-bordered data-table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Parent</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Installation Service</th>
                    <th>Status</th>
                    <th>Brochure</th>
                    <th>Banner</th>
                    <th>Product Highlight</th>
                    <th>Feature Highlight</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($categories as $key => $val)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td><?php
                        if ($val->parent_id != '0' && !empty($val->parent_id)) {
                          echo DB::table('ms_categories')->where('category_id', $val->parent_id)->first()->name;
                        } else {
                          echo '-';
                        }
                        ?></td>
                    <td>{{ $val->name }}</td>
                    <td>{{ $val->slug }}</td>
                    <td>
                      <center>
                        <button data-id="{{ $val->category_id }}" class="btn {{$val->need_installation == '1' ? 'btn-primary' : ''}} btn-installation" data-installation="{{$val->need_installation}}">{{$val->need_installation == '1' ? 'Available' : 'Not Available'}}</button>
                      </center>
                    </td>
                    <td>
                      <center>
                        <button data-id="{{ $val->category_id }}" class="btn {{$val->active == '1' ? 'btn-primary' : ''}} btn-status" data-status="{{$val->active}}">{{$val->active == '1' ? 'Active' : 'Not Active'}}</button>
                      </center>
                    </td>
                    <td>
                      <center>
                        <?php
                        if (!empty($val->brochure)) {
                        ?>
                          <button class="btn btn-primary btn-upload-brochure" data-id="{{$val->category_id}}" brochure="{{$val->brochure ?? ''}}">Update Brochure</button>
                        <?php
                        } else {
                        ?>
                          <button class="btn btn-upload-brochure" data-id="{{$val->category_id}}">Upload</button>
                        <?php
                        }
                        ?>
                      </center>
                    </td>
                    <td>
                      <center>
                        <button class="btn btn-primary btn-config-banner" data-id="{{ $val->category_id }}">Configuration</button>
                      </center>
                    </td>
                    <td>
                      <center>
                        <button class="btn btn-primary btn-config" data-id="{{ $val->category_id }}" data-slug="{{ $val->product_highlight_href }}" image="{{ $val->product_highlight_image }}" image_mobile="{{ $val->product_highlight_image_mobile }}" title-text="{{ $val->product_highlight_text_title }}" subtitle-text="{{ $val->product_highlight_text_subtitle }}" title-color="{{ $val->product_highlight_text_color }}" title-align="{{ $val->product_highlight_text_align }}">Configuration</button>
                      </center>
                    </td>
                    <td>
                      <center>
                        <button class="btn btn-primary btn-config-feature" data-id="{{ $val->category_id }}">Configuration</button>
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

<div class="modal m-brochure" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Brochure</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="f-brochure" enctype="multipart/form-data" action="{{ url('artmin/product/categories/brochure') }}" method="POST">
          {{ csrf_field() }}
          <input type="hidden" name="categoryID">
          <div class="row">
            <div class="col-md-12">
              <embed
                  id="pdfPreview"
                  type="application/pdf"
                  frameBorder="0"
                  scrolling="auto"
                  height="300px"
                  width="100%">
              </embed>
              <br>
              <input type="file" class="form-control" id="file_brochure" name="brochure_file">
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-submit-brochure hidden">Save</button>
        <button type="button" class="btn btn-danger btn-remove-brochure hidden">Remove Brochure</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal mproducthighlight" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Configuration Product Highlight</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="fdata" enctype="multipart/form-data" action="{{ url('artmin/product/categories/configuration') }}" method="POST">
          {{ csrf_field() }}
          <input type="hidden" name="categoryID">
          <div class="row">
            <div class="col-md-6">
              <label for="">Banner Web</label>
              <img id="imgPreview" style="width:100%">
              <br>
              <input type="file" class="form-control" id="imgInp" name="product_highlight_image">
            </div>
            <div class="col-md-6">
              <label for="">Banner Mobile</label>
              <img id="imgPreview_mobile" style="width:100%">
              <br>
              <input type="file" class="form-control" id="imgInp_mobile" name="product_highlight_image_mobile">
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12">
              <label for="">Title Text</label>
              <input type="text" class="form-control" placeholder="Title Text" name="product_highlight_text_title">
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12">
              <label for="">Sub-Title Text</label>
              <input type="text" class="form-control" placeholder="Sub-Title Text" name="product_highlight_text_subtitle">
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-6">
              <label for="">Title Color</label>
              <select name="product_highlight_text_color" id="" class="form-control">
                <option value="#fff">White</option>
                <option value="#000">Black</option>
              </select>
            </div>
            <div class="col-md-6">
              <label for="">Title Align</label>
              <select name="product_highlight_text_align" class="form-control">
                <option value="left">Left</option>
                <option value="center">Center</option>
                <option value="right">Right</option>
              </select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-submit-config">Save Configuration</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal mbanner" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Configuration Banner</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="fdatabanner" enctype="multipart/form-data" method="POST" action="{{ url('artmin/product/categories/banner') }}">
          {{ csrf_field() }}
          <input type="hidden" name="categoryID_banner">
          <div class="row_banner">
          </div>
        </form>
        <hr>
        <div class="row">
          <div class="col-md-12">
            <button type="button" class="btn btn-primary btn-add-image-banner">Add Banner</button>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-submit-banner">Save Configuration</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal mfeaturehighlight" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Configuration Feature Highlight</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="fdatafeature" enctype="multipart/form-data" method="POST" action="{{ url('artmin/product/categories/feature') }}">
          {{ csrf_field() }}
          <input type="hidden" name="categoryID_feature">
          <div class="row_feature">
          </div>
        </form>
        <hr>
        <div class="row">
          <div class="col-md-12">
            <button type="button" class="btn btn-primary btn-add-image-feature">Add Image Feature</button>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-submit-feature">Save Configuration</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="{{url('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>

<script>
  var index_g_feature = 99;

  $("#imgInp").on("change", function (e) {
    const [file] = e.target.files;
    if (file) {
      $("#imgPreview").attr("src", URL.createObjectURL(file));
    }
  });

  $("#imgInp_mobile").on("change", function (e) {
    const [file] = e.target.files;
    if (file) {
      $("#imgPreview_mobile").attr("src", URL.createObjectURL(file));
    }
  });

  $("#file_brochure").on("change", function (e) {
    const [file] = e.target.files;
    if (file) {
      $("#pdfPreview").attr("src", URL.createObjectURL(file));
      $(".btn-submit-brochure").removeClass("hidden");
      if ($("#file_brochure").attr("data-val") !== "") {
        $(".btn-remove-brochure").removeClass("hidden");
      }
    } else {
      // $(".btn-submit-brochure").addClass("hidden");
      $(".btn-remove-brochure").addClass("hidden");
    }
  });

  $(document).on('click', '.btn-add-image-feature', function(e) {
    e.preventDefault();

    $('.row_feature').append('<div class="rowf row_' + index_g_feature + '">' +
      '<hr>' +
      '<input type="hidden" name="idx_feature[]" value="'+ index_g_feature +'">'+
      '<div class="row">' +
      '<div class="col-md-12">' +
      '<label for="">Img Icon Feature</label>' +
      '<img src="" alt="" class="img_feature_preview_' + index_g_feature + '" style="width: 100%">' +
      '<input type="file" name="img_feature[]" class="img_feature_file form-control" index="' + index_g_feature + '">' +
      '<br><button class="btn btn-danger btn-remove-rowf btn-remove-rowf-" index="'+ index_g_feature +'">Remove</button>'+
      '</div>' +
      '</div>' +
      '</div>');

    index_g_feature++;
  });

  $(document).on('click','.btn-submit-feature',function(e){
    e.preventDefault();
    swal({
        title: "Confirmation",
        text: "Are you sure to save this configuration?",
        icon: "info",
        buttons: true
      })
      .then((willDelete) => {
        if (willDelete) {
          $('.fdatafeature').submit()
        }
      });
  });

  $(document).on('change', '.img_feature_file', function(e) {
    e.preventDefault();
    const [file] = $(this).prop('files');
    const index = $(this).attr('index');
    if (file) {
      $('.img_feature_preview_' + index).attr('src', URL.createObjectURL(file));
    }
  });

  $(document).on('click', '.btn-config-feature', function(e) {
    e.preventDefault();
    let id = $(this).attr('data-id');
    let url = '{{ url("artmin/product/categories/feature") }}/' + id;
    $('[name="categoryID_feature"]').val(id);
    $('.rowf').remove();
    $.get(url, function(r) {
      if (r.length > 0) {
        for (let i = 0; i < r.length; i++) {
          $('.row_feature').append('<div class="rowf row_' + index_g_feature + '">' +
            '<input type="hidden" name="idx_feature[]" value="'+ index_g_feature +'">'+
            '<input type="hidden" name="current_feature[]" value="'+ r[i].img +'" index="' + index_g_feature + '">' +
            '<div class="row">' +
            '<div class="col-md-12">' +
            '<label for="">Img Icon Feature</label>' +
            '<img src="{{ url("/") }}/'+ r[i].img +'" alt="" class="img_feature_preview_' + index_g_feature + '" style="width: 100%">' +
            '<div style="display: flex; padding-top: 8px; padding-bottom: 20px;">'+
            '<input type="file" name="img_feature[]" class="img_feature_file form-control" index="' + index_g_feature + '">' +
            '<br><button style="margin-left: 8px;" class="btn btn-danger btn-remove-rowf btn-remove-rowf-"' + index_g_feature + '" index="'+ index_g_feature +'">Remove</button>'+
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>');
          index_g_feature++;
        }
      } else {
        $('.row_feature').append('<div class="rowf row_' + index_g_feature + '">' +
          '<div class="row">' +
          '<input type="hidden" name="idx_feature[]" value="'+ index_g_feature +'">'+
          '<div class="col-md-12">' +
          '<label for="">Img Icon Feature</label>' +
          '<img src="" alt="" class="img_feature_preview_' + index_g_feature + '" style="width: 100%">' +
          '<input type="file" name="img_feature[]" class="img_feature_file form-control" index="' + index_g_feature + '">' +
          '<br><button class="btn btn-danger btn-remove-rowf btn-remove-rowf-" index="'+ index_g_feature +'">Remove</button>'+
          '</div>' +
          '</div>' +
          '</div>');
        index_g_feature++;
      }
    });

    $('.mfeaturehighlight').modal('show');
  });

  $(document).on('click','.btn-remove-rowf',function(e){
    e.preventDefault();
    let idx = $(this).attr('index');
    $('.row_'+idx).remove(); 
  });

  $(document).on('click', '.btn-config', function(e) {
    e.preventDefault();

    let id = $(this).attr('data-id');
    let img = $(this).attr('image');
    let img_mobile = $(this).attr('image_mobile');
    let title_text = $(this).attr('title-text');
    let subtitle_text = $(this).attr('subtitle-text');
    let title_color = $(this).attr('title-color');
    let title_align = $(this).attr('title-align');

    if (img) {
      $('#imgPreview').attr('src', '{{ url("assets/uploads/product_highlight") }}/' + img);
    } else {
      $('#imgPreview').attr('src', null);
    }
    if (img_mobile) {
      $('#imgPreview_mobile').attr('src', '{{ url("assets/uploads/product_highlight") }}/' + img_mobile);
    } else {
      $('#imgPreview_mobile').attr('src', null);
    }

    $('[name="categoryID"]').val(id);
    $('[name="product_highlight_text_title"]').val(title_text);
    $('[name="product_highlight_text_subtitle"]').val(subtitle_text);
    $('[name="product_highlight_text_color"]').val(title_color);
    $('[name="product_highlight_text_align"]').val(title_align);

    $('.mproducthighlight').modal('show');
  });

  $(document).on('click', '.btn-upload-brochure', function(e) {
    e.preventDefault();

    $("#file_brochure").val("");
    $(".btn-remove-brochure").addClass("hidden");

    let id = $(this).attr('data-id');
    $('[name="categoryID"]').val(id);

    let brochure = $(this).attr('brochure') || "";
    $("#file_brochure").attr('data-val', brochure);

    if (brochure) {
      const hasHttp = ["http","https"].find((o) => brochure.includes(o));
      const fileUrl = hasHttp ? brochure : `{{ url("uploads/brochure") }}/${brochure}`;
      $("#pdfPreview").attr("src", fileUrl);
      $(".btn-remove-brochure").removeClass("hidden");
    } else {
      $("#pdfPreview").attr("src", null);
    }

    $(".btn-submit-brochure").addClass("hidden");
    $('.m-brochure').modal('show');
  });

  $(document).on('click', '.btn-submit-brochure', function(e) {
    e.preventDefault();

    swal({
        title: "Confirmation",
        text: "Are you sure to save this brochure?",
        icon: "info",
        buttons: true
      })
      .then((willDelete) => {
        if (willDelete) {
          $('.f-brochure').submit();
        }
      });
  });

  $(document).on('click', '.btn-remove-brochure', function(e) {
    e.preventDefault();

    swal({
        title: "Confirmation",
        text: "Are you sure to remove this brochure?",
        icon: "info",
        buttons: true
      })
      .then((willDelete) => {
        if (willDelete) {
          $("#file_brochure").val("");
          $('.f-brochure').submit();
        }
      });
  });

  $(document).on('click', '.btn-submit-config', function(e) {
    e.preventDefault();

    swal({
        title: "Confirmation",
        text: "Are you sure to save this configuration?",
        icon: "info",
        buttons: true
      })
      .then((willDelete) => {
        if (willDelete) {
          $('.fdata').submit();
        }
      });
  });

  var index_g_banner = 0;
  var renderItemBanner = (data) => {
    const { index_g_banner, image, title, description, url, ordering } = data;
    return `
    <div class="rowbanner row_${index_g_banner}">
      <hr>
      <input type="hidden" name="idx_banner[]" value="${index_g_banner}" />
      <input type="hidden" name="current_banner[]" value="${image}" index="${index_g_banner}" />
      <div class="row">
        <div class="col-md-12">
          <label for="">Banner Image</label>
          <img src="{{ url("/") }}/${image}" alt="" class="img_banner_preview_${index_g_banner}" style="width: 100%; margin-bottom: 12px;" />
          <br>
          <input type="file" name="img_banner[]" class="img_banner_file form-control" index="${index_g_banner}" />
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-md-9">
          <label for="">Banner Title</label>
          <input type="text" name="content_title[]" placeholder="Banner Title" class="form-control" value="${title || ""}" />
        </div>
        <div class="col-md-3">
          <label for="">Ordering</label>
          <input type="text" name="ordering[]" placeholder="Ordering" class="form-control" value="${ordering}" />
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-md-12">
          <label for="">Banner Description</label>
          <textarea name="content_description[]" cols="10" rows="5" placeholder="Banner Description" class="form-control">${description || ""}</textarea>
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-md-12">
          <label for="">Banner URL</label>
          <input type="text" name="content_url[]" cols="10" rows="5" placeholder="Banner URL" class="form-control" value="${url || ""}" />
        </div>
      </div>
      <br>
      <button class="btn btn-danger btn-remove-rowf btn-remove-rowf-" index="${index_g_banner}">Remove</button>
    </div>`;
  }

  $(document).on('click', '.btn-add-image-banner', function(e) {
    e.preventDefault();
    $('.row_banner').append(renderItemBanner({ index_g_banner, title: "", description: "", url: "", ordering: -1 }));
    index_g_banner++;
  });

  $(document).on('change', '.img_banner_file', function(e) {
    e.preventDefault();
    const [file] = $(this).prop('files');
    const index = $(this).attr('index');
    if (file) {
      $('.img_banner_preview_' + index).attr('src', URL.createObjectURL(file));
    }
  });

  $(document).on('click', '.btn-config-banner', function(e) {
    e.preventDefault();
    let id = $(this).attr('data-id');
    let url = '{{ url("artmin/product/categories/banner") }}/' + id;
    $('[name="categoryID_banner"]').val(id);
    $('.rowbanner').remove();

    $.get(url, function(r) {
      if (r.length > 0) {
        for (let i = 0; i < r.length; i++) {
          $('.row_banner').append(renderItemBanner({ index_g_banner, image: r[i].image, title: r[i].title, description: r[i].description, url: r[i].url, ordering: r[i].ordering }));
          index_g_banner++;
        }
      } else {
        $('.row_banner').append(renderItemBanner({ index_g_banner, image: "", title: "", description: "", url: "", ordering: 0 }));
        index_g_banner++;
      }
    });
    $('.mbanner').modal('show');
  });

  $(document).on('click','.btn-submit-banner',function(e){
    e.preventDefault();
    swal({
        title: "Confirmation",
        text: "Are you sure to save this configuration?",
        icon: "info",
        buttons: true
      })
      .then((willDelete) => {
        if (willDelete) {
          $('.fdatabanner').submit()
        }
      });
  });

  $(document).on('click','.btn-status',function(e){
    e.preventDefault();

    const curStatus = $(this).attr("data-status");
    const categoryID = $(this).attr("data-id");
    const url = '{{ url("artmin/product/categories/change-status") }}/' + categoryID;

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
            category_id: categoryID,
            active: curStatus === "1" ? "0" : "1"
          };

          $.post(url, data)
          .success((r) => swal('Success', 'Status has been changed.', 'success').then((confirm) => location.reload()))
          .error((e) => swal('Error', 'Failed to update status!.', 'error'));
        }
      });
  });

  $(document).on('click','.btn-installation',function(e){
    e.preventDefault();

    const curInstallation = $(this).attr("data-installation");
    const categoryID = $(this).attr("data-id");
    const url = '{{ url("artmin/product/categories/change-installation") }}/' + categoryID;

    swal({
        title: "Confirmation",
        text: `Are you sure to change installation to ${curInstallation === "1" ? "Not Available" : "Available"}?`,
        icon: "info",
        buttons: true
      })
      .then((ok) => {
        if (ok) {
          const data = {
            "_token": "{{ csrf_token() }}",
            category_id: categoryID,
            need_installation: curInstallation === "1" ? "0" : "1"
          };

          $.post(url, data)
          .success((r) => swal('Success', 'Installation has been changed.', 'success').then((confirm) => location.reload()))
          .error((e) => swal('Error', 'Failed to update installation!.', 'error'));
        }
      });
  });

</script>

@endsection