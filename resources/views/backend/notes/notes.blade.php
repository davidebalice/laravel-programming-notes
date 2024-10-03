@extends('admin.dashboard')
@section('admin')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="page-content">
	<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
		<div class="breadcrumb-title pe-3">Programming notes</div>
		<div class="ps-3">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0 p-0">
					<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
					</li>
					<li class="breadcrumb-item active" aria-current="page">Notes</li>
				</ol>
			</nav>
		</div>
		
	</div>
				 
	<hr/>
	<div class="ms-auto">
		<div class="btn-group">
			<a href="{{ route('add.note') }}" class="btn btn-primary addButton">+ Add note</a>
		</div>
	</div>
	<hr/>

	<div class="card">
		<div class="card-body">
			<div class="table-responsive">
				<table id="tableView" class="table table-bordered tableView" style="width:100%">
					<thead>
					<tr>
						<th style="width:150px">Category</th>
						<th style="width:70%">Title</th>
						<th>Action</th>
					</tr>
					</thead>
					<tbody>
						@foreach($notes as $key => $item)
							<tr>
								<td>
									<div class="categoryIconContainer">
										@foreach($item->category1 as $category)
											@if (file_exists($category->image))
												<img src="{{ asset($category->image) }}" class="categoryIcon">  
											@endif
										@endforeach
										@php
											/*
											@foreach($item->category2 as $category)
											@if (file_exists($category->image))
												<img src="{{ asset($category->image) }}" class="categoryIcon">  
											@endif
											@endforeach
											@foreach($item->category3 as $category)
												@if (file_exists($category->image))
													<img src="{{ asset($category->image) }}" class="categoryIcon">  
												@endif
											@endforeach
											*/
										@endphp
										
									</div>
								</td>
								<td>
									<p class="categoryTitle">{{ $item->name }}</p>
								</td>
								<td>
									<div class="buttonContainerNotes">
										<a href="{{ route('view.note',$item->id) }}">
											<i class="bx bx-edit viewNoteIcon"></i>
										</a>
										<a href="{{ route('delete.note',$item->id) }}" id="delete{{$item->id}}" >
											<i class="fa fa-trash deleteNoteIcon"></i>
										</a>
									</div>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
				{{ $notes->links() }}
			</div>
		</div>
	</div>
</div>

@endsection