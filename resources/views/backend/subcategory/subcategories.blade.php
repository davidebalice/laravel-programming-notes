@extends('admin.dashboard')
@section('admin')

<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Categories</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Subategories</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ route('add.subcategory') }}" class="btn btn-primary">Add subcategory</a>
            </div>
        </div>
    </div>
        
    <hr/>



    Category: {{$category['name']}}


    <select name="category_id" id="category_id" class="form-control" required>
        <option value="" disabled>Select Category</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}"
            @if (isset($category['id']) && $category['id'] == $category->id)
                selected 
            @endif
            >
            
            {{ $category->name }}</option>
        @endforeach
    </select>




    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tableView" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Subcategory</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                    @php
                        $i=1;
                        $class_row="even";
                        $totRecords = count($subcategories)
                    @endphp

                    @if ($totRecords==0)
                    <tr>
                        <td colspan="5">
                            <h5 class="py-5 pl-4">No result</h5>
                        </td>
                    </tr>
                    @endif
                        @foreach($subcategories as $key => $item)
                        <tr>
                            <td style="width:68%">
                                <div class="categoryTitle2">
                                    {{ $item->name }}
                                </div>
                            </td>
                            <td>
                                <div class="categoryButtons">
                                    <a href="{{ route('edit.subcategory',$item->id) }}" class="btn-sm btn-info btnCms" title="Edit"> 
                                        <i class="fa fa-pencil"></i> 
                                    </a>
                                
                                    <a href="{{ route('delete.subcategory',$item->id) }}" class="btn-sm btn-danger btnCms2" id="delete{{$item->id}}" title="Delete" >
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        initializeSwitches('category');
        document.getElementById('category_id').addEventListener('change', function() {
        var categoryId = this.value;
        if (categoryId) {
            window.location.href = '/admin/subcategories/' + categoryId;
        }
        });
    });

</script>