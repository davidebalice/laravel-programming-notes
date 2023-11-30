@extends('admin.dashboard')
@section('admin')

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
	
	<div id="editFormContainer" class="editFormContainer">
		<form id="editForm" method="post" action="{{ route('update.text') }}" class="editForm">
			@csrf
			<div class="closeContainer">
				<div onclick="$().closeForm()" class="closeEdit">close</div>
			</div>
			<input type="hidden" name="id" id="editId">
			<input type="hidden" name="note_id" value="{{$note->id}}">
			
			<div class="row mb-3">
				<div class="form-group col-sm-12 text-secondary">
					<textarea name="text" id="editTextarea" class="form-control editTextarea" rows="3" required></textarea>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12 text-secondary">
					<input type="submit" class="btn btn-primary px-4 saveButton" value="Save" />
				</div>
			</div>
		</form>
	</div>

	<div id="titleFormContainer" class="editFormContainer">
		<form id="titleForm" method="post" action="{{ route('update.note') }}" class="editForm">
			@csrf
			<div class="closeContainer">
				<div onclick="$().closeForm()" class="closeEdit">close</div>
			</div>

			<input type="hidden" name="id" value="{{$note->id}}">
			
			<div class="row mb-3">
				<div class="form-group col-sm-12 text-secondary">
					<input type="text" name="name" class="form-control editInput" value="{{ $note->name }}" required>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12 text-secondary">
					<input type="submit" class="btn btn-primary px-4 saveButton" value="Save" />
				</div>
			</div>
		</form>
	</div>

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
									<h5>{{ $note->name }}
									
										<a href="#" onclick="$().openTitleForm()">
											<i class="bx bx-pencil"></i>
										</a>
									
									</h5>
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
								<input type="hidden" id="type{{$text->id}}" value="{{ $text->type }}">

								<div class="noteRow">
									@if ($text->type === "text")
										<div class="buttonContainerText">
											<a href="#" onclick="$().openEditForm({{$text->id}})" class="buttonBox">
												<i class="bx bx-pencil"></i>
											</a>
											<a href="{{ route('note.up', ['note_id' => $text->note_id, 'text_id' => $text->id]) }}" class="buttonBox">
												<i class="bx bx-up-arrow"></i>
											</a>
											<a href="{{ route('note.down', ['note_id' => $text->note_id, 'text_id' => $text->id]) }}" class="buttonBox">
												<i class="bx bx-down-arrow"></i>
											</a>
										</div>
										<p id="text{{ $text->id }}">{{ $text->text }}</p>
									@else
										<div class="buttonWrapper">
											<div class="buttonContainer">
												<a href="#" onclick="$().openEditForm({{$text->id}})" class="buttonBox">
													<i class="bx bx-pencil"></i>
												</a>
												<a href="{{ route('note.up', ['note_id' => $text->note_id, 'text_id' => $text->id]) }}" class="buttonBox">
													<i class="bx bx-up-arrow"></i>
												</a>
												<a href="{{ route('note.down', ['note_id' => $text->note_id, 'text_id' => $text->id]) }}" class="buttonBox">
													<i class="bx bx-down-arrow"></i>
												</a>
											</div>
											<button class="btnCopy" data-clipboard-target="#code{{ $text->id }}">
												<i class="bx bx-copy"></i>Copy
											</button>
										</div>
										<pre class="formatCode"><code class="hljs" id="code{{ $text->id }}">{{ $text->text }}</code></pre>
									@endif
								</div>
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

		const clipboard = new ClipboardJS('.btnCopy');

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
				const type = $('#type'+id).val();

				const editForm = $('#editFormContainer');
				editForm.css('display','flex');

				const editTextarea = $('#editTextarea');
				
				$('#editId').val(id);
				if(type==="text"){
					editTextarea.html($('#text'+id).html());
				}
				else{
					editTextarea.html($('#code'+id).html());
				}
			}
		})(jQuery);	

		(function($) 
		{
			$.fn.closeForm = function() 
			{
				const editForm = $('#editFormContainer');
				editForm.css('display','none');
			}
		})(jQuery);	

		(function($) 
		{
			$.fn.openTitleForm = function() 
			{
				const editForm = $('#titleFormContainer');
				editForm.css('display','flex');
			}
		})(jQuery);	

		(function($) 
		{
			$.fn.closeTitleForm = function() 
			{
				const editForm = $('#titleFormContainer');
				editForm.css('display','none');
			}
		})(jQuery);	
	});
</script>

@endsection