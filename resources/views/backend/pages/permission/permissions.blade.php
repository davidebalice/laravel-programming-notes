@extends('admin.dashboard')
@section('admin')

<div class="page-content">
	<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
		<div class="breadcrumb-title pe-3">Permissions</div>
		<div class="ps-3">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0 p-0">
					<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
					</li>
					<li class="breadcrumb-item active" aria-current="page">Permissions</li>
				</ol>
			</nav>
		</div>
		<div class="ms-auto">
			<div class="btn-group">
				<a href="{{ route('add.permission') }}" class="btn btn-primary">Add permission</a> 				 
			</div>
		</div>
	</div>
				 
	<hr/>

	<div class="card">
		<div class="card-body">
			<div class="table-responsive">
				<table id="tableView" class="table table-striped table-bordered" style="width:100%">
					<thead>
					<tr>
						<th>Permission name</th>
						<th>Group name</th>
						<th>Action</th> 
					</tr>
					</thead>
					<tbody>
					@foreach($permissions as $key => $item)		
						<tr>
							<td>{{ $item->name }}</td>
							<td>{{ $item->group_name }}</td>
							<td>
								<a href="{{ route('edit.permission',$item->id) }}" class="btn-sm btn-info btnCms"> <i class="fa fa-pencil"></i> </a>
								<a href="{{ route('delete.permission',$item->id) }}" class="btn-sm btn-danger btnCms2" id="delete{{$item->id}}" > <i class="fa fa-trash"></i></a>
							</td> 
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>		 
</div>

@endsection