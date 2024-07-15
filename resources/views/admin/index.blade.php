@extends('admin.dashboard')
@section('admin')

<div class="page-content">
    <div class="row">
        <div class="col-xl-4 col-md-4">
            <div class="card dashCard dashCardBg2">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <div class="text-center">
                                <img src="{{ asset('backend/assets/images/laravel.png') }}" alt="Laravel" class="laravelLogo">
                            </div>
                            <h5 class="mt-4 text-white text-center">Personal programming notes<br />and snippets of code management<br />developed in Laravel 
                            </h5>
                        </div>
                    </div>                                            
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-4">
            <div class="card dashCard">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <h5 class="mb-0">View project on Github</h5>
                            <div class="text-center">
                                <img src="{{ asset('backend/assets/images/github.png') }}" alt="Github" class="githubLogo">
                            </div>
                            <a href="https://github.com/davidebalice/laravel-programming-notes" target="_blank">
                                <p class="githubLink">Github.com/davidebalice/laravel-programming-notes</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-4">
            <div class="card dashCard dashCardBg">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <div class="text-center">
                                <img src="{{ asset('backend/assets/images/logo.png') }}" alt="db" class="dbLogo">
                            </div>
                            <h3 class="mb-2 dashText1">Important</h3>
                            <h4 class="mb-2 dashText2">This CMS is in DEMO MODE, the crud operations area not allowed.</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="search-container-row">

        <img src="{{ asset('backend/assets/images/logo.png') }}" class="dbLogoSearch">

        <div class="search-container">
            <form action="{{ route('notes.search') }}" method="POST" class="search-container">
                @csrf
                <input id="searchInput" type="text" name="search" placeholder="Search..." class="search-box">
                <button class="search-button">Search</button>
            </form>
        </div>



      






    </div>
</div>

<script>
    $(document).ready(function() {
        $('#searchInput').autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: '/autocomplete.js',
                    dataType: 'script', 
                    cache: true, 
                    success: function() {
                        response(autocomplete);
                    }
                });
            },
            minLength: 2
        });
    });
</script>

@endsection