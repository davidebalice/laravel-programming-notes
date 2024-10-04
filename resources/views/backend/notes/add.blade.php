@extends('admin.dashboard')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">

<div class="page-content"> 
	<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
		<div class="breadcrumb-title pe-3">Add note</div>
		<div class="ps-3">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0 p-0">
					<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
					</li>
					<li class="breadcrumb-item active" aria-current="page">Add note</li>
				</ol>
			</nav>
		</div>
		<div class="ms-auto">
		
		</div>
	</div>
				
	<a href="{{ route('notes') }}">
        <button class="btn btn-primary backButton buttonBase">
            < Back
        </button>
    </a>

    <div class="containerBody">
		<div class="main-body">
			<div class="row">
				<div class="col-lg-12">
					<div class="card">
						<div class="card-body">
							<form id="myForm" method="post" action="{{ route('store.note') }}" enctype="multipart/form-data" >
								@csrf

                                <div class="row mb-3" style="align-items: center">
									<div class="col-sm-2">
										<h6 class="mb-0">Title</h6>
									</div>
									<div class="form-group col-sm-10 text-secondary">
										<input type="text" name="name" class="form-control"   />
									</div>
								</div>
			
                                <div class="row mb-3" style="align-items: center">
									<div class="col-sm-2">
										<h6 class="mb-0">Category</h6>
									</div>
									<div class="form-group col-sm-10 text-secondary">
										<select name="category1" id="category1" class="form-select">
											<option></option>
											@foreach($categories as $cat)
												<option value="{{ $cat->id }}">{{ $cat->name }}</option>
											@endforeach
										</select>
									</div>
								</div>

                                <div class="row mb-3" style="align-items: center">
									<div class="col-sm-2">
										<h6 class="mb-0">Subcategory</h6>
									</div>
									<div class="form-group col-sm-10 text-secondary">
										<select name="subcategory_id" id="subcategory" class="form-select">
											<option></option>
											@foreach($subcategories as $subcat)
												<option value="{{ $subcat->id }}">{{ $subcat->name }}</option>
											@endforeach
										</select>
									</div>
								</div>


                                <div class="row mb-3" style="align-items: center">
									<div class="col-sm-2">
										<h6 class="mb-0">Category 2</h6>
									</div>
									<div class="form-group col-sm-10 text-secondary">
										<select name="category2" class="form-select" id="inputVendor2">
											<option></option>
											@foreach($categories as $cat)
												<option value="{{ $cat->id }}">{{ $cat->name }}</option>
											@endforeach
										</select>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-2"></div>
									<div class="col-sm-10 text-secondary">
										<input type="submit" class="btn btn-primary px-4 buttonBase" value="Insert" />
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
                    required : 'Please Enter Category Name',
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

		$('#category1').on('change', function() {
			var categoryId = $(this).val();
			if(categoryId) {
				$.ajax({
					url: '/get-subcategories/' + categoryId,
					type: 'GET',
					success: function(data) {
						$('#subcategory').empty();
						$('#subcategory').append('<option value="">- No subcategory -</option>');
						$.each(data, function(key, value) {
							$('#subcategory').append('<option value="' + value.id + '">' + value.name + '</option>');
						});
					}
				});
			} else {
				$('#subcategory').empty();
				$('#subcategory').append('<option value="">- No subcategory -</option>');
			}
		});
	});
</script>

@endsection