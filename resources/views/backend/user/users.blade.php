@extends('admin.dashboard')
@section('admin')

<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Users </div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Users</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
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
                        <th>Image </th>
                        <th>Name </th>
                        <th>Email </th>
                        <th>Phone </th>
                        <th>Status </th> 
                        <th>Action</th> 
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $key => $item)		
                    <tr>
                        <td> <img src="{{ (!empty($item->photo)) ? url('upload/user/'.$item->photo):url('upload/no_image.jpg') }}" alt="Admin" class="rounded-circle p-1 userPhoto" width="64" height="64"></td>
                        <td> {{ $item->name }}  {{ $item->surname }} </td>
                        <td> {{ $item->email }}  </td>
                        <td> {{ $item->phone }}  </td>
                        <td>
                            @if($item->UserOnline())
                            <span class="badge badge-pill bg-success">Active Now </span>
                            @else
                                @if ($item->last_seen!=null)
                                    <span class="badge badge-pill bg-danger"> 
                                        {{ Carbon\Carbon::parse($item->last_seen)->diffForHumans() }} 
                                    </span>
                                @endif
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('edit.user',$item->id) }}" class="btn-sm btn-info btnCms" title="Edit"> 
                                <i class="fa fa-pencil"></i> 
                            </a>
                            <a href="{{ route('delete.user',$item->id) }}" class="btn-sm btn-danger btnCms2" id="delete{{$item->id}}" title="Delete" >
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