@extends('backend.layouts.backend-app')
@section('title', 'New Post')
@section('content')
<div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Article
            <small> New Post</small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-sm-7">
                @include('backend.layouts.alert')
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="col-sm-6">
                    <div class="box box-solid">
                        <div class="box-body">


                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}">
                                @if ($errors->has('title'))
                                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('title') }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Slug</label>
                                <input type="text" class="form-control" name="slug" id="url" value="{{ old('slug') }}">
                                @if ($errors->has('slug'))
                                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('slug') }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Image</label><br />
                                <a href="<?= url('fmanager/dialog.php?field_id=image&akey=R4z5OEYf1h29rzVepedx') ?>" class="iframe-btn btn bg-black flat"><i class="fa fa-search"></i> Browse Image</a><br />
                                <input type="hidden" name="image" id="image" value="<?= old('image') ?>"/>
                                @if ($errors->has('image'))
                                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('image') }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <img id="preview_image" class="preview" src="<?= old('image') ?>"/>
                            </div>

                        </div>
                    </div>
                </div><!-- /.row -->
                <div class="col-md-6">

                    <div class="box box-solid">
                        <div class="box-body">
                            <div class="form-group">
                                <label>Gallery Images <a href="javascript:void(0)" onclick="add_images()" class="btn btn-sm btn-success"><i class="fa fa-plus"></i></a></label>
                            </div>
                            <input type="hidden" id="image_count" name="image_count" value="1">
                            <div id="gallery-image-container">
                                <div class="gallery-images" id="gallery-image-1">
                                    <a href="<?= url('fmanager/dialog.php?field_id=image-1&akey=R4z5OEYf1h29rzVepedx') ?>" class="iframe-btn btn btn-xs bg-black"><i class="fa fa-search"></i> Browse</a><br />
                                    <input type="hidden" name="gallery_image[]" id="image-1"/>
                                    <div class="form-group">
                                        <img id="preview_image-1" class="preview"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <button class="btn btn-primary"><i class="fa fa-plus"></i> Add New Gallery</button>
                    </div>
                </div>
            </form>
    </section><!-- /.content -->
</div><!-- /.row (main row) -->


<script src="https://cdn.ckeditor.com/ckeditor5/21.0.0/classic/ckeditor.js"></script>
<script>
    $(document).ready(function() {

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#preview_images').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }

        $('#image').change(function(e) {
            e.preventDefault();
            readURL(this);
        });

        ClassicEditor
            .create(document.querySelector('#editorr'))
            .then(editor => {
                console.log(editor);
            })
            .catch(error => {
                console.error(error);
            });

    });

    function add_images(){
        var count = $('#image_count').val();
        count = parseInt(count) + 1;
        $('#image_count').val(count);

        $('#gallery-image-container').append('<div class="gallery-images" id="gallery-image-'+count+'"> <a href="<?= url("fmanager/dialog.php?field_id=image-'+count+'&akey=R4z5OEYf1h29rzVepedx") ?>" class="iframe-btn btn btn-xs bg-black"><i class="fa fa-search"></i> Browse</a><br /> <a href="javascript:void(0)" class="remove-gallery" onclick="remove_images('+count+')"><i class="fa fa-times"></i></a> <input type="hidden" name="gallery_image[]" id="image-'+count+'"/> <div class="form-group"> <img id="preview_image-'+count+'" class="preview"/> </div> </div>');
    }

    function remove_images(id){
        $('#gallery-image-'+id).remove();
    }
</script>
@endsection
