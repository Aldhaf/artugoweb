@extends('backend.layouts.backend-app')
@section('title', 'Gallery')
@section('content')
<div>
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Gallery
			<small> List</small>
		</h1>
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- Small boxes (Stat box) -->
		<div class="row">
			<div class="col-sm-12">
				@include('backend.layouts.alert')
			</div>
            <div class="col-sm-12">
                <div class="box box-solid">
					<!-- <div class="box-header">
			          <h3 class="box-title">Products</h3>
			        </div> -->
					<div class="box-body">
						<div class="form-group">
							<a href="{{ url('artmin/gallery/add') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Gallery</a>
						</div>
                        <div class="row">
							<div class="col-sm-12 table-container">
								<table class="table table-bordered data-table">
									<thead>
										<tr>
											<th>#</th>
											<th>Image</th>
											<th>Title</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php $i = 1; ?>
										<?php foreach ($gallery as $row): ?>
											<tr>
												<td><?= $i++ ?></td>
                                                <td><img src="{{ $row->image }}" height="50px"></td>
                                                <td>{{ $row->title }}</td>
												<td>
                                                    <a href="{{ url('artmin/gallery/edit/' . $row->id) }}" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></a>
													<a href="{{ url('artmin/gallery/delete/' . $row->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure want to remove this data?')"><i class="fa fa-trash"></i></a>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
                            </div>
						</div>
					</div>
				</div>
            </div>
		</div><!-- /.row -->
	</section><!-- /.content -->
</div><!-- /.row (main row) -->


@endsection
