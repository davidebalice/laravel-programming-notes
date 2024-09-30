@extends('admin.dashboard')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content"> 
	<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
		<div class="breadcrumb-title pe-3">Edit post </div>
		<div class="ps-3">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0 p-0">
					<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
					</li>
					<li class="breadcrumb-item active" aria-current="page">Edit post </li>
				</ol>
			</nav>
		</div>
		<div class="ms-auto">
		
		</div>
	</div>
	
	<a href="{{ route('admin.blog.post') }}">
        <button class="btn btn-primary backButton">
            < Back
        </button>
    </a>

    <div class="containerBody">
		<div class="main-body">
			<div class="row">
				<div class="col-lg-12">
					<div class="card">
						<div class="card-body">
							<form id="myForm" method="post" action="{{ route('update.blog.post') }}" enctype="multipart/form-data" >
								@csrf

								<input type="hidden" name="id" value="{{ $posts->id }}">
								<input type="hidden" name="old_image" value="{{ $posts->image }}">

								<div class="row mb-3">
									<div class="col-sm-3">
										<h6 class="mb-0">Category</h6>
									</div>
									<div class="form-group col-sm-9 text-secondary">
										<select name="category_id" class="form-select" id="inputVendor">
											<option></option>
											@foreach($categories as $cat)
											<option value="{{ $cat->id }}" {{ $cat->id == $posts->category_id ? 'selected' : '' }} >{{ $cat->name }}</option>
											@endforeach
										</select>
									</div>
								</div>

								<div class="row mb-3">
									<div class="col-sm-3">
										<h6 class="mb-0">Blog Post</h6>
									</div>
									<div class="form-group col-sm-9 text-secondary">
										<input type="text" name="title" class="form-control"  value="{{ $posts->title }}" />
									</div>
								</div>
								
								<div class="row mb-3">
									<div class="col-sm-3">
										<h6 class="mb-0">Short description</h6>
									</div>
									<div class="form-group col-sm-9 text-secondary">
										<textarea name="short_description" class="form-control" id="inputProductDescription" rows="3">{{ $posts->short_description }}</textarea>
									</div>
								</div>

								<div class="row mb-3">
									<div class="col-sm-3">
										<h6 class="mb-0">Long description</h6>
									</div>
									<div class="form-group col-sm-9 text-secondary">
										<textarea id="mytextarea" name="long_description">{!! $posts->long_description !!}</textarea>
									</div>
								</div>

								<div class="row mb-3">
									<div class="col-sm-3">
										<h6 class="mb-0">Image </h6>
									</div>
									<div class="col-sm-9 text-secondary">
										<input type="file" name="image" class="form-control"  id="image"   />
									</div>
								</div>

								<div class="row mb-3">
									<div class="col-sm-3">
										<h6 class="mb-0"> </h6>
									</div>
									<div class="col-sm-9 text-secondary">
										@if (file_exists($posts->image))
										<img src="{{ asset($posts->image)}}" style="width: 150px;" alt="">
										@else
										<img src="{{ asset('upload/no_image.jpg')}}" style="width: 100px;" alt="">
										@endif
									</div>
								</div>

								<div class="row">
									<div class="col-sm-3"></div>
									<div class="col-sm-9 text-secondary">
										<input type="submit" class="btn btn-primary px-4" value="Save" />
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
    $(document).ready(function (){
        $('#myForm').validate({
            rules: {
                name: {
                    required : true,
                },
            },
            messages :{
                name: {
                    required : 'Enter category',
                },
            },
            errorElement : 'span',
            errorPlacement: function (error,element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight : function(element, errorClass, validClass){
                $(element).addClass('is-invalid');
            },
            unhighlight : function(element, errorClass, validClass){
                $(element).removeClass('is-invalid');
            },
        });
    });
</script>

<script type="text/javascript">
	$(document).ready(function(){
		$('#image').change(function(e){
			var reader = new FileReader();
			reader.onload = function(e){
				$('#showImage').attr('src',e.target.result);
			}
			reader.readAsDataURL(e.target.files['0']);
		});
	});
</script>

@endsection