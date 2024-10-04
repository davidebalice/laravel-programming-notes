@extends('admin.dashboard')
@section('admin')

<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Categories</div>
        <div class="">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Categories</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="btn-group mb-4">
        <a href="{{ route('add.category') }}" class="btn btn-primary buttonBase">
            <i class="fa fa-plus-circle"></i>  <span>Add category</span>
        </a>
    </div>
        
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="tableView" class="table table-bordered tableView" style="width:100%">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Category</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                    @php
                        $i=1;
                        $class_row="even";
                        $totRecords = count($categories)
                    @endphp

                    @if ($totRecords==0)
                    <tr>
                        <td colspan="5">
                            <h5 class="py-5 pl-4">No result</h5>
                        </td>
                    </tr>
                    @endif
                        @foreach($categories as $key => $item)
                        <tr>
                            <td style="width:80px">
                                <div class="imgIconContainer">
                                    @if (file_exists($item->image))
                                        <img src="{{ asset($item->image) }}" style="width:44px;height:auto !important" >
                                    @else
                                        <img id="showImage" src="{{ asset('upload/no_image.jpg')}}" alt="Admin" style="width:44px;"  >
                                    @endif
                                </div>
                            </td>
                            <td style="width:68%">
                                <div class="categoryTitle2">
                                    {{ $item->name }}
                                </div>
                            </td>
                            <td>
                                <div class="categoryButtons">
                                    <a href="{{ route('edit.category',$item->id) }}" class="btn-sm btn-info btnCms" title="Edit"> 
                                        <i class="fa fa-pencil"></i> 
                                    </a>
                                
                                    <a href="{{ route('delete.category',$item->id) }}" class="btn-sm btn-danger btnCms2" id="delete{{$item->id}}" title="Delete" >
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
      });
</script>