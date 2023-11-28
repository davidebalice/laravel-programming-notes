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
                    <li class="breadcrumb-item active" aria-current="page">Categories</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ route('add.category') }}" class="btn btn-primary">Add category</a> 				 
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
                            <th>Publish</th>
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
                            <td class="dtr-control" style="width:70px">
                                <div class="form-check form-switch">
                                    <input class="form-check-input activeSwtich" type="checkbox" role="switch" data-item-id="active_{{ $item->id }}" 
                                    //prettier-ignore
                                    @if ($item->active)
                                        checked
                                    @endif
                                    id="active_{{ $item->id }}">
                                </div>
                            </td>
                            
                            <td style="width:110px">  
                                @if (file_exists($item->image))
                                    <img src="{{ asset($item->image) }}" style="width: 100px;height:auto !important" >  
                                @else
                                    <img id="showImage" src="{{ asset('upload/no_image.jpg')}}" alt="Admin" style="width:100px;"  >                 
                                @endif
                            </td>
                            <td style="width:68%">{{ $item->name }}</td>
                            <td>
                                @if(Auth::user()->can('category.edit'))
                                    <a href="{{ route('edit.category',$item->id) }}" class="btn-sm btn-info btnCms" title="Edit"> 
                                        <i class="fa fa-pencil"></i> 
                                    </a>
                                @endif   

                                @if(Auth::user()->can('category.edit'))
                                    @if ($item->position<=1)
                                        <a href="#" class="btn-sm btn-info btnCms button_disable2">
                                            <i class="fa fa-arrow-up"></i>
                                        </a>
                                    @else
                                        <a href="{{url('admin/category/sort/up/'.$item->id)}}" class="btn-sm btn-info btnCms">
                                            <i class="fa fa-arrow-up"></i>
                                        </a>
                                    @endif
        
                                    @if ($item->position==$totRecords)
                                        <a href="#" class="btn-sm btn-info btnCms button_disable2">
                                            <i class="fa fa-arrow-down"></i>
                                        </a>
                                    @else
                                        <a href="{{url('admin/category/sort/down/'.$item->id)}}" class="btn-sm btn-info btnCms">
                                            <i class="fa fa-arrow-down"></i>
                                        </a>
                                    @endif
                                @endif

                                @if(Auth::user()->can('category.delete'))	
                                    <a href="{{ route('delete.category',$item->id) }}" class="btn-sm btn-danger btnCms2" id="delete{{$item->id}}" title="Delete" >
                                        <i class="fa fa-trash"></i>
                                    </a>
                                @endif   
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