<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div class="topSidebar">
            <img src="{{asset('backend/assets/images/logo2.png')}}" class="logo-icon" alt="logo icon">
            Admin
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
        </div>
    </div>
    
    <ul class="metismenu" id="menu">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <div class="parent-icon"><i class='bx bx-desktop'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>
        <li>
            <a href="{{ route('categories') }}">
                <div class="parent-icon"><i class='bx bx-list-ul'></i>
                </div>
                <div class="menu-title">{{ __('messages.Categories') }}</div>
            </a>
        </li>
        <li>
            <a href="{{ route('notes') }}">
                <div class="parent-icon"><i class='bx bx-edit'></i>
                </div>
                <div class="menu-title">Notes</div>
            </a>
        </li>
        <li>
            <div class="sidemenu-line"></div>
        </li>
        @php
            use App\Models\Category;
            $categories = Category::latest()->orderBy('name','asc')->get();
        @endphp 

        @foreach($categories as $key => $item)
            <a href="{{ route('notes', ['id' => $item->id]) }}">
                @if (file_exists($item->image))
                    <img src="{{ asset($item->image) }}" style="width: 40px;height:auto !important" >  
                @else
                    <img id="showImage" src="{{ asset('upload/no_image.jpg')}}" alt="Admin" style="width:50px;"  >                 
                @endif
                <div class="menu-title">{{ $item->name }}</div>
            </a>
        @endforeach

           
        </li>
       
    </ul>
</div>