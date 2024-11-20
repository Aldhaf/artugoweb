@extends('backend.layouts.backend-app')
@section('title', 'New Post')
@section('content')
<div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo Request::segment(2) == 'article' ? 'Article' : 'Product Knowledge'; ?>
            <small> New Post</small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-sm-12">
                @include('backend.layouts.alert')
            </div>
            <form action="" method="POST" enctype="multipart/form-data" class="w-100">
                {{ csrf_field() }}
                <div class="col-sm-12">
                    <div class="box box-solid">
                        <div class="box-body">

                            <div class="row">
                                <div class="col-md-9 form-group">
                                    <label>Title</label>
                                    <input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}">
                                    @if ($errors->has('title'))
                                    <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('title') }}</label>
                                    @endif
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Status</label>
                                    <select class="form-control" name="status">
                                        <option value="0" <?php if (old('status') == 0) echo "selected"; ?>>Draft</option>
                                        <option value="1" <?php if (old('status') == 1) echo "selected"; ?>>Publish</option>
                                    </select>
                                    @if ($errors->has('status'))
                                    <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('status') }}</label>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-9 form-group">
                                    <label>Slug</label>
                                    <input type="text" class="form-control" name="slug" id="url" value="{{ old('slug') }}">
                                    @if ($errors->has('slug'))
                                    <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('slug') }}</label>
                                    @endif
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Category</label>
                                    <select class="form-control select2" name="category">
                                        <option value="">Select Category</option>
                                        <?php foreach ($category as $cat) : ?>
                                            <option value="<?= $cat->id ?>" <?php if (old('category') == $cat->id) echo "selected"; ?>><?= $cat->title ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    @if ($errors->has('category'))
                                    <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('category') }}</label>
                                    @endif
                                </div>
                                <?php /*
                                <div class="col-md-3 form-group">
                                    <label>Reader</label>
                                    <select class="form-control" name="reader">
                                        <option value="customer" <?php if (old('status') == 'customer') echo "selected"; ?>>Customer</option>
                                        <option value="employee" <?php if (old('status') == 'employee') echo "selected"; ?>>Employee</option>
                                    </select>
                                    @if ($errors->has('reader'))
                                    <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('reader') }}</label>
                                    @endif
                                </div>
                                */ ?>
                            </div>

                            <div class="form-group">
                                <label>Meta Description</label>
                                <textarea class="form-control" name="meta_desc">{{ old('meta_desc') }}</textarea>
                                @if ($errors->has('meta_desc'))
                                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('meta_desc') }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Meta Tags (Separated with comma)</label>
                                <input type="text" class="form-control" name="meta_tags" value="{{ old('meta_tags') }}">
                                @if ($errors->has('meta_tags'))
                                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('meta_tags') }}</label>
                                @endif
                            </div>

                            <div class="form-group">
                                <label>Content</label>
                                <textarea type="text" class="form-control editor" name="content">{{ old('content') }}</textarea>
                                @if ($errors->has('content'))
                                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('content') }}</label>
                                @endif
                            </div>

                            <?php /*
                            <div class="form-group">
                                <label>Image Thumbnail</label><br />
                                <a href="<?= url('assets/fmanager/dialog.php?field_id=image&akey=R4z5OEYf1h29rzVepedx') ?>" class="iframe-btn btn bg-black flat"><i class="fa fa-search"></i> Browse Image</a><br />
                                <input type="hidden" name="image" id="image" value="<?= old('image') ?>"/>
                                <input type="file" name="image" id="image" value="<?= old('image') ?>" />
                                @if ($errors->has('image'))
                                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('image') }}</label>
                                @endif
                            </div> */ ?>

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
                            <!-- <div class="form-group">
                                <img id="preview_images" class="previews" style="width: 100%;" src="<?= old('image') ?>" />
                            </div> -->

                            @if (Request::segment(2) == 'product-knowledge')
                            <div class="form-group">
                                <label>PDF</label><br />
                                <a href="<?= url('fmanager/dialog.php?field_id=pdf&akey=R4z5OEYf1h29rzVepedx') ?>" class="iframe-btn btn bg-black flat"><i class="fa fa-search"></i> Browse Pdf</a><br />
                                <input type="hidden" name="pdf" id="pdf" value="<?= old('pdf') ?>"/>
                                @if ($errors->has('pdf'))
                                <label class="control-label input-error" for="inputError"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('pdf') }}</label>
                                @endif
                            </div>
                            <div class="form-group">
                                <embed id="preview_pdf" style="width:100%; height: <?php echo old('pdf') == '' ? '0px' : '1020px'; ?>; border: 1px solid rgb(226 232 240/var(--tw-border-opacity));" src="<?= old('pdf') ?>" type="application/pdf" />
                            </div>
                            @endif

                        </div>
                    </div>
                </div><!-- /.row -->

                <div class="col-md-12">
                    <div class="form-group">
                        <center>
                            <button class="btn btn-primary"><i class="fa fa-plus"></i> Add New Post</button>
                        </center>
                    </div>
                </div>
            </form>
    </section><!-- /.content -->
</div><!-- /.row (main row) -->


<script src="https://cdn.ckeditor.com/ckeditor5/21.0.0/classic/ckeditor.js"></script>
<script>
    $(document).ready(function() {

        setTimeout(() => $(".sidebar-toggle").click(), 500);

        function readURL(input, preview_el_id) {
            const srcUrl = ((input.files && input.files[0]) ? input.files[0] : input.value) || "";
            if (srcUrl !== "") {

                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $(`#${preview_el_id}`).attr('src', e.target.result);
                    }
                    reader.readAsDataURL(srcUrl); // convert to base64 string
                }

                if (preview_el_id === "preview_pdf") {
                    $(`#${preview_el_id}`).prop("style").height = "1020px";
                }

            }
        }

        $('#image').change(function(e) {
            e.preventDefault();
            readURL(this, "preview_images");
        });

        $('#pdf').change(function(e) {
            e.preventDefault();
            readURL(this, "preview_pdf");
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
</script>
@endsection
