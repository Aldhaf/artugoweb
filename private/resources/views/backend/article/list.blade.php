@extends('backend.layouts.backend-app')
@section('title', Request::segment(2) == 'article' ? 'Article' : 'Product Knowledge')
@section('content')
<div>
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?php echo Request::segment(2) == 'article' ? 'Article' : 'Product Knowledge'; ?>
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
							<a href="{{ url('artmin/' . Request::segment(2) . '/new-post') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Post</a>
						</div>
                        <div class="row">
							<div class="col-sm-12 table-container">
								<table class="table table-bordered data-table">
									<thead>
										<tr>
											<th>#</th>
											<th>Image</th>
											<th>Title</th>
											<th>Status</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php $i = 1; ?>
										<?php foreach ($article as $row): ?>
											<tr>
												<td><?= $i++ ?></td>
                                                <td><img src="{{ $row->image }}" height="50px"></td>
                                                <td>{{ $row->title }}</td>
												<td>
													<?php if($row->status == 0) echo "Draft"; else echo "Published"; ?>
												</td>
												<td>
                                                    <a href="{{ url('artmin/' . Request::segment(2) . '/edit/' . $row->id) }}" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></a>
													<a href="{{ url('artmin/' . Request::segment(2) . '/delete/' . $row->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure want to remove this data?')"><i class="fa fa-trash"></i></a>
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
