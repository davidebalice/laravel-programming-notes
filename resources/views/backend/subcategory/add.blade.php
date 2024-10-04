@extends('admin.dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content"> 
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Subcategories</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Add subcategory</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">

        </div>
    </div>

    <a href="{{ route('subcategories') }}">
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
                            <form id="myForm" method="post" action="{{ route('store.subcategory') }}" enctype="multipart/form-data" >
                                @csrf

                                <div class="row mb-3" style="align-items: center">
                                    <div class="col-sm-2">
                                        <h6 class="mb-0">Subcategory name</h6>
                                    </div>
                                    <div class="form-group col-sm-10 text-secondary">
                                        <input type="text" name="name" class="form-control"   />
                                    </div>
                                </div>

                                <div class="row mb-3" style="align-items: center">
                                    <div class="col-sm-2">
                                        <h6 class="mb-0">Category</h6>
                                    </div>
                                    <div class="col-sm-10 text-secondary">
                                        <select name="category_id" class="form-control" required>
                                            <option value="" selected disabled>Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-10 text-secondary">
                                        <input type="submit" class="btn btn-primary px-4 buttonBase" value="Save" />
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

@endsection