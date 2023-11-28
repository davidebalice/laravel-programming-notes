@extends('admin.dashboard')
@section('admin')

<div class="page-content">
	<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
		<div class="breadcrumb-title pe-3">Role premission</div>
		<div class="ps-3">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0 p-0">
					<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
					</li>
					<li class="breadcrumb-item active" aria-current="page">Role premission</li>
				</ol>
			</nav>
		</div>
		<div class="ms-auto">
			
		</div>
	</div>
				 
	<hr/>
				
	<div class="card">
		<div class="card-body">
			<div class="table-responsive">
				<table id="tableView" class="table table-striped table-bordered" style="width:100%">
				<thead>
				<tr>
					<th>Roles</th> 
					<th>Permission  </th>
					<th>Action</th> 
				</tr>
				</thead>
				<tbody>
				@foreach($roles as $key => $item)		
					<tr>
						<td>{{ $item->name }}</td> 
						<td> 
							@php
								$i=0;
							@endphp
							@foreach($item->permissions as $perm)
							<span class="badge rounded-pill bg-danger">{{ $perm->name }}</span>
							@php
								$i++;
								if($i==10){
									echo"<br />";
									$i=0;
								}
							@endphp
							@endforeach
						</td> 
						<td>
							<a href="{{ route('admin.edit.roles',$item->id) }}" class="btn-sm btn-info btnCms">
								<i class="fa fa-pencil"></i>
							</a>
							<a href="{{ route('admin.delete.roles',$item->id) }}" class="btn-sm btn-danger btnCms2" id="delete{{$item->id}}" >
								<i class="fa fa-trash"></i>
							</a>
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