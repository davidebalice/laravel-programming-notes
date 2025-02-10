@extends('admin.dashboard')
@section('admin')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="page-content">
	<div class="page-breadcrumb d-none d-sm-flex align-items-center">
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
				 
	<br />

	<div class="ms-auto mb-3">
		<div class="btn-group">
			<a href="{{ route('add.note') }}" class="btn btn-primary addButton buttonBase"><i class="fa fa-plus-circle"></i> Add note</a>
		</div>

		@if ($allCategories)
		<div class="btn-group categorySelectorWrapper">
			<div class="categorySelectorContainer">
				<div> Category:</div>
				<div style="background: white">
					<select name="category_id" id="category_id" class="form-control" required>
						<option value="" disabled>Select Category</option>
						@foreach($allCategories as $item)
							<option value="{{ $item->id }}"
							@if (isset($_GET['id']) && $_GET['id'] == $item->id)
								selected
							@endif
							>
							{{ $item->name }}</option>
						@endforeach
					</select>
				</div>
			</div>
		</div>
		@endif
		
		@if(isset($subcategories) && !$subcategories->isEmpty())
			<div class="btn-group">
				<div class="btn btn-primary addButton buttonBase" id="subcategoryButton"><i class="fa fa-caret-down"></i> Subcategories</div>
			</div>
		@endif
</div>
	

	@if(isset($subcategories) && !$subcategories->isEmpty())
		<ul id="subCategoryContainer">
			<ul>
				@foreach($subcategories as $subcategory)
					<li><a href="{{ route('notes', ['id' => $subcategory->category_id,'subcategory_id' => $subcategory->id]) }}">{{ $subcategory->name }}</a></li>
				@endforeach
			</ul>
		</ul>
	@endif

	<div class="card">
		<div class="card-body">
			<div class="table-responsive">
				<div style="float: right">{{ $notes->links() }}</div>
				<table id="tableView" class="table table-bordered tableView" style="width:100%">
					<thead>
					<tr>
						<th style="width:100px">Category</th>
						<th style="width:80%">Title</th>
						<th style="width:10%">Action</th>
					</tr>
					</thead>
					<tbody>
						@foreach($notes as $key => $item)
							<tr>
								<td>
									<div class="categoryIconContainer">
										<div class="imgIconContainer">
											@foreach($item->category1 as $category)
												@if (file_exists($category->image))
													<img src="{{ asset($category->image) }}" class="categoryIcon">
												@endif
											@endforeach
										</div>
									</div>
								</td>
								<td>
									<a href="{{ route('view.note',$item->id) }}">
										<p class="categoryTitle">{{ $item->name }}</p>
									</a>
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

<script>
document.addEventListener('DOMContentLoaded', (event) => {
    const button = document.getElementById('subcategoryButton');

    function handleSubcategories() {
		const visibility = $('#subCategoryContainer').css('display');

		$('#subCategoryContainer').slideToggle();

		if(visibility=="none"){
			button.innerHTML = '<i class="fa fa-caret-up"></i> Subcategories';
		}else{
			button.innerHTML = '<i class="fa fa-caret-down"></i> Subcategories';
		}

        button.removeEventListener('click', handleSubcategories);

        setTimeout(() => {
            button.addEventListener('click', handleSubcategories);
        }, 700);
    }
	
	if(button){
		button.addEventListener('click', handleSubcategories);
	}

	const category_id = document.querySelector('#category_id');
	category_id.addEventListener('change', event => {
		const selectedValue = event.target.value;
    	window.location.href = `/notes?id=${selectedValue}`;
	});

});
</script>

@endsection