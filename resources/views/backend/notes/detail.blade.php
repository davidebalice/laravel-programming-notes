@extends('admin.dashboard')
@section('admin')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.31.1/min/vs/loader.min.js"></script>

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="page-content">
	<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
		<div class="breadcrumb-title pe-3">Detail</div>
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

	

	<form action="" method="GET">
		<select name="language" id="language-selector">
			<option value="javascript">JavaScript</option>
			<option value="html">HTML</option>
			<option value="css">CSS</option>
			<!-- Aggiungi altre lingue supportate -->
		</select>
		<input type="submit" value="Cambia lingua">
	</form>
</div>

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
					<div id="editTextareaDiv"></div>
					<textarea name="text" id="editTextarea" class="form-control d-none"></textarea>
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
				<div onclick="$().closeTitleForm()" class="closeEdit">close</div>
			</div>

			<input type="hidden" name="id" value="{{$note->id}}">
			
			<div class="row mb-3">
				<div class="form-group col-sm-12 text-secondary">
					<input type="text" name="name" class="form-control editInput" value="{{ $note->name }}" required>
					<select name="category1">
						@foreach($categories as $cat)
							<option value="{{ $cat->id }}"  @if(isset($note->category1[0]) && $cat->id == $note->category1[0]->id) selected @endif>{{ $cat->name }}</option>
						@endforeach
						<option value=""  @if(!isset($note->category1[0])) selected @endif> - No category - </option>
					</select>
					<select name="category2">
						@foreach($categories as $cat)
							<option value="{{ $cat->id }}"  @if(isset($note->category2[0]) && $cat->id == $note->category2[0]->id) selected @endif>{{ $cat->name }}</option>
						@endforeach
						<option value=""  @if(!isset($note->category2[0])) selected @endif> - No category - </option>
					</select>
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
										
										<div class="form-group col-sm-12 text-secondary">
											<div id="monaco-editor-code" data-textarea-id="code-new" style="width:100%;height: 300px; border: 1px solid #ddd;"></div>
											<textarea name="text" id="code-new" class="form-control d-none" style="width:100%;height: 300px; border: 1px solid #ddd;"></textarea>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-12 text-secondary">
										<input type="submit" class="btn btn-primary px-4 saveButton" value="+ Add" />
									</div>
								</div>
							</form>

							<br /><br />


							<div id="noteContainer">

								@if (count($texts) === 0)
									<p><br /><br />No text or code found<br /><br /></p>
								@endif

								@foreach ($texts as $text)
									<input type="hidden" id="type{{$text->id}}" value="{{ $text->type }}">

									<div class="noteRow">
										@if ($text->type === "text")
											<div class="buttonContainerText">
												<a href="#" onclick="$().openEditForm({{$text->id}})" class="buttonBox">
													<i class="bx bx-pencil" data-toggle="tooltip" title="Edit"></i>
												</a>
												<a href="{{ route('note.up', ['note_id' => $text->note_id, 'text_id' => $text->id]) }}" class="buttonBox">
													<i class="bx bx-up-arrow" data-toggle="tooltip" title="Up"></i>
												</a>
												<a href="{{ route('note.down', ['note_id' => $text->note_id, 'text_id' => $text->id]) }}" class="buttonBox">
													<i class="bx bx-down-arrow" data-toggle="tooltip" title="Down"></i>
												</a>
												<a href="{{ route('delete.text', ['note_id' => $text->note_id, 'text_id' => $text->id]) }}" class="buttonBox" id="row_delete{{$text->id}}" >
													<i class="bx bx-trash" data-toggle="tooltip" title="Delete"></i>
												</a>
											</div>
											<div id="text{{ $text->id }}">{!! $text->text !!}</div>
										@else
											<div class="buttonWrapper">
												<div class="buttonContainer">
													<a href="#" onclick="$().saveCode({{$text->id}},{{$text->note_id}})" class="buttonBox">
														<i class="bx bx-save" data-toggle="tooltip" title="Save"></i>
													</a>
													<!--a href="#" onclick="$().openEditForm({{$text->id}})" class="buttonBox">
														<i class="bx bx-pencil" data-toggle="tooltip" title="Edit"></i>
													</a-->
													<a href="{{ route('note.up', ['note_id' => $text->note_id, 'text_id' => $text->id]) }}" class="buttonBox">
														<i class="bx bx-up-arrow" data-toggle="tooltip" title="Up"></i>
													</a>
													<a href="{{ route('note.down', ['note_id' => $text->note_id, 'text_id' => $text->id]) }}" class="buttonBox">
														<i class="bx bx-down-arrow" data-toggle="tooltip" title="Down"></i>
													</a>
													<a href="{{ route('delete.text', ['note_id' => $text->note_id, 'text_id' => $text->id]) }}" class="buttonBox" id="row_delete{{$text->id}}" >
														<i class="bx bx-trash" data-toggle="tooltip" title="Delete"></i>
													</a>
												</div>
												<button class="btnCopy" data-clipboard-target="#code{{ $text->id }}">
													<i class="bx bx-copy"></i>Copy
												</button>
											</div>
											<!--pre class="formatCode"><code class="hljs" id="code{{ $text->id }}">{!! $text->text !!}</code></pre-->

											<div id="monaco-editor{{ $text->id }}"  data-textarea-id="code{{ $text->id }}" class="monaco-editor-container" style="width:100%; height:300px; border:1px solid #ddd;"></div>
											<textarea name="text" id="code{{ $text->id }}" class="form-control d-none">{!! $text->text !!}</textarea>

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
</div>


<script type="text/javascript">
	let editorCode = null;

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
			e.trigger.textContent = 'Copied!';
			setTimeout(function() {
				e.clearSelection();
				e.trigger.textContent = 'Copy';
			}, 1500);
		});

		clipboard.on('error', function(e) {
			console.error('Error during copy: ', e.action);
		});

		const addButton = document.querySelector('.addButton');
		const closeButton = document.querySelector('.closeButton');

		addButton.addEventListener('click', event => {
			$('#addForm').slideDown();
			$('.addButton').hide();
			$('.closeButton').show();
			$('#noteContainer').hide();
			document.querySelector('input[name="type"][value="text"]').checked = true;
			if(editorCode==null){
				require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.31.1/min/vs' }});
				require(['vs/editor/editor.main'], function() {
					const editorContainerCode = document.getElementById('monaco-editor-code');
					editorCode = monaco.editor.create(editorContainerCode, {
						value: '',
						language: 'plain-text',
						lineNumbers: 'off',
						theme: 'vs-light',
						readOnly: false
					});
					editorCode.onDidChangeModelContent(() => {
						document.getElementById('code-new').value = editorCode.getValue();
					});
				});
			}
			else{
				require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.31.1/min/vs' }});
				require(['vs/editor/editor.main'], function() {
					const editorContainerCode = document.getElementById('monaco-editor-code');
					editorCode.updateOptions({
						language: 'plain-text',
						lineNumbers: 'off',
						theme: 'vs-light',
						readOnly: false
					});
				});
			}
		});

		closeButton.addEventListener('click', event => {
			$('#addForm').slideUp();
			$('.addButton').show();
			$('.closeButton').hide();
			$('#noteContainer').slideDown();
			if(editorCode!=null){
				require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.31.1/min/vs' }});
				require(['vs/editor/editor.main'], function() {
					const editorContainerCode = document.getElementById('monaco-editor-code');
					editorCode.updateOptions({
						value: '',
						language: 'javascript',
						lineNumbers: 'on',
						theme: 'vs-dark',
						readOnly: false
					});
				});
			}
		});

		(function($) {
   		 	$.fn.saveCode = function(id,id_note) {
			toastr.options = {
                "positionClass": "toast-bottom-right",
                "timeOut": "3000",
                "closeButton": true,
                "progressBar": true
            };
        	const codeValue = $('#code' + id).val();
				$.ajax({
					url: '/save/code',
					method: 'POST',
					data: {
						id: id,
						id_note: id_note,
						code: codeValue
					},
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					success: function(response) {
						if(response.message.includes("Demo")){
							Swal.fire({
								title: 'Demo mode',
								text: response.message,
								icon: 'error',
								confirmButtonText: 'Ok'
							});
						}
						else{
							toastr.success(response.message);
						}
					},
					error: function(xhr, status, error) {
						alert('Error during save: ' + error + status);
					}
				});
			}
		})(jQuery);

		(function($) {
			$.fn.openEditForm = function(id) {
				const type = $('#type' + id).val();

				const editForm = $('#editFormContainer');
				editForm.css('display', 'flex');

				const editTextarea = $('#editTextarea');
				const editTextareaDiv = $('#editTextareaDiv');

				$('#editId').val(id);
				if (type === "text") {
					editTextarea.html($('#text' + id).html());
				} else {
					editTextarea.html($('#code' + id).html());
				}

				if (!window.editor) {
					window.editor = new Quill('#editTextareaDiv', {
						theme: 'snow'
					});

					var quillEditor = document.getElementById('editTextarea');
					var initialContent =  quillEditor.value;
					editor.clipboard.dangerouslyPasteHTML(initialContent);
					quillEditor.value = initialContent;
					
					editor.on('text-change', function() {
						quillEditor.value = editor.root.innerHTML;
					});

					quillEditor.addEventListener('input', function() {
						editor.root.innerHTML = quillEditor.value;
					});
				}
				else{
					var content = (type === "text") ? $('#text' + id).html() : $('#code' + id).html();
					window.editor.clipboard.dangerouslyPasteHTML(content);
					document.getElementById('editTextarea').value = content;
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

        $('[data-toggle="tooltip"]').tooltip();
	});

    document.addEventListener('DOMContentLoaded', function() {

		require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.31.1/min/vs' }});
        require(['vs/editor/editor.main'], function() {
            const editors = document.querySelectorAll('.monaco-editor-container');

            editors.forEach(editorContainer => {
                const textareaId = editorContainer.dataset.textareaId;
                const initialValue = document.getElementById(textareaId).value || '';

                const editor = monaco.editor.create(editorContainer, {
                    value: initialValue,
                    language: 'javascript',
                    theme: 'vs-dark'
                });

                editor.onDidChangeModelContent(function() {
                    document.getElementById(textareaId).value = editor.getValue();
                });

				
            });

			const radioButtons = document.querySelectorAll('input[name="type"]');
			radioButtons.forEach(radio => {
				radio.addEventListener('change', () => {
					const selectedType = document.querySelector('input[name="type"]:checked').value;
					if (selectedType === 'text') {
						require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.31.1/min/vs' }});
						require(['vs/editor/editor.main'], function() {
							editorCode.updateOptions({
								value: '',
								language: 'plain-text',
								lineNumbers: 'off',
								theme: 'vs-light',
								readOnly: false
   							 });
						});
					} else if (selectedType === 'code') {
						require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.31.1/min/vs' }});
						require(['vs/editor/editor.main'], function() {
							editorCode.updateOptions({
								value: '',
								language: 'javascript',
								lineNumbers: 'on',
								theme: 'vs-dark',
								readOnly: false
   							 });
						});
					}
				});
		});
	});
});
</script>

@endsection