@extends('admin.dashboard')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content"> 
	<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
		<div class="breadcrumb-title pe-3">Detail  </div>
		<div class="ps-3">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0 p-0">
					<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
					</li>
					<li class="breadcrumb-item active" aria-current="page">Detail  </li>
				</ol>
			</nav>
		</div>
		<div class="ms-auto">
		
		</div>
	</div>
	
	<a href="{{ route('notes') }}">
        <button class="btn btn-primary backButton">
            < Back
        </button>
    </a>

	
	<form id="editForm" method="post" action="{{ route('store.text') }}" class="editForm">
		@csrf

		<input type="hidden" name="id" value="{{ $note->id }}">
		
		<div class="row mb-3">
			<div class="form-group col-sm-12 text-secondary">
				
				<br />
				<div class="radioContainer">
					<input type="radio" name="type" value="text" checked> Text
					<p> &nbsp; </p>
					<input type="radio" name="type" value="code"> Code
				</div>
				<br />
				<textarea name="text" class="form-control" rows="3" required></textarea>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 text-secondary">
				<input type="submit" class="btn btn-primary px-4 saveButton" value="+ Add" />
			</div>
		</div>
	</form>



    <div class="containerBody">
		<div class="main-body">
			<div class="row">				 
				<div class="col-lg-12">
					<div class="card">
						<div class="card-body">

							<div class="row mb-3">
								<div class="col-12 titleDiv">
									@foreach($note->category1 as $category)
										@if (file_exists($category->image))
											<img src="{{ asset($category->image) }}" class="categoryIcon">  
										@endif
									@endforeach
									@foreach($note->category2 as $category)
										@if (file_exists($category->image))
											<img src="{{ asset($category->image) }}" class="categoryIcon">  
										@endif
									@endforeach
									@foreach($note->category3 as $category)
										@if (file_exists($category->image))
											<img src="{{ asset($category->image) }}" class="categoryIcon">  
										@endif
									@endforeach
									<h5>{{ $note->name }}</h5>
								</div>
							</div>


							<button class="btn btn-primary addButton">+ Add Text or Code</button>
							<button class="btn btn-primary closeButton">- Close</button>

								
							<form id="addForm" method="post" action="{{ route('store.text') }}" class="addForm">
								@csrf

								<input type="hidden" name="id" value="{{ $note->id }}">
								
								<div class="row mb-3">
									<div class="form-group col-sm-12 text-secondary">
										
										<br />
										<div class="radioContainer">
											<input type="radio" name="type" value="text" checked> Text
											<p> &nbsp; </p>
											<input type="radio" name="type" value="code"> Code
										</div>
										<br />
										<textarea name="text" class="form-control" rows="3" required></textarea>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-12 text-secondary">
										<input type="submit" class="btn btn-primary px-4 saveButton" value="+ Add" />
									</div>
								</div>
							</form>

							<br /><br />

							@if (count($texts) === 0)
								<p><br /><br />No text or code found<br /><br /></p>
							@endif


							@foreach ($texts as $text)
								@if ($text->type === "text")
									<p>{{ $text->text }}</p>
								@else
									<button class="btn-copy" data-clipboard-target="#code{{ $text->id }}">Copy</button>
									<pre class="formatCode"><code class="hljs" id="code{{ $text->id }}">{{ $text->text }}</code></pre>
								@endif

								<a href="{{ route('note.up', ['note_id' => $text->note_id, 'text_id' => $text->id]) }}">UP</a>
								| 
								<a href="{{ route('note.down', ['note_id' => $text->note_id, 'text_id' => $text->id]) }}">DOWN</a>
								| 
								<a href="#" onclick="$().openEditForm({{$text->id}})">
									<i class="bx bx-pencil"></i>
								</a>




								@php
								/*
								<a href="{{ route('edit.text',$text->id) }}">
									<i class="bx bx-pencil "></i>
								</a>
								<a href="{{ route('delete.text',$text->id) }}" id="delete{{$text->id}}" >
									<i class="fa fa-trash "></i>
								</a>
								*/
								@endphp
								

							@endforeach

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
    $(document).ready(function (){
        $('#addForm').validate({
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

		const clipboard = new ClipboardJS('.btn-copy');

		clipboard.on('success', function(e) {
			e.trigger.textContent = 'Copiato!';
			setTimeout(function() {
				e.clearSelection();
				e.trigger.textContent = 'Copia';
			}, 1500);
		});

		clipboard.on('error', function(e) {
			console.error('Errore durante la copia: ', e.action);
		});

		const addButton = document.querySelector('.addButton');
		const closeButton = document.querySelector('.closeButton');

		addButton.addEventListener('click', event => {
			$('#addForm').slideDown();
			$('.addButton').hide();
			$('.closeButton').show();
		});

		closeButton.addEventListener('click', event => {
			$('#addForm').slideUp();
			$('.addButton').show();
			$('.closeButton').hide();
		});

		(function($) 
		{
			$.fn.openEditForm = function(id) 
			{
				const editForm = $('#editForm');

				editForm.css('display','block');
			}
		})(jQuery);	

		

	});
</script>

@endsection