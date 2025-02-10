@extends('admin.dashboard')
@section('admin')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.31.1/min/vs/loader.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/quill-better-table@1.2.0/dist/quill-better-table.min.js"></script>

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="page-content pb-0 mb-0">
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
        <button class="btn btn-primary backButton buttonBase">
            < Back
        </button>
    </a>
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
			
			<input type="hidden" name="id" value="{{$note->id}}">
			
			<div class="row mb-3">
				<div class="form-group col-sm-12 text-secondary">
					<input type="text" name="name" class="form-control editInput" value="{{ $note->name }}" required>

					<select name="category1" id="category1" class="editSelect">
						@foreach($categories as $cat)
							<option value="{{ $cat->id }}"  @if(isset($note->category1[0]) && $cat->id == $note->category1[0]->id) selected @endif>{{ $cat->name }}</option>
						@endforeach
						<option value=""  @if(!isset($note->category1[0])) selected @endif> - No category - </option>
					</select>

					<select name="subcategory_id" id="subcategory" class="editSelect">
						@foreach($subcategories as $subcat)
							<option value="{{ $subcat->id }}"  @if(isset($note->subcategory_id)) selected @endif>{{ $subcat->name }}</option>
						@endforeach
						<option value=""  @if(!isset($note->subcategory_id)) selected @endif> - No subcategory - </option>
					</select>

					<select name="category2" class="editSelect">
						@foreach($categories as $cat)
							<option value="{{ $cat->id }}"  @if(isset($note->category2[0]) && $cat->id == $note->category2[0]->id) selected @endif>{{ $cat->name }}</option>
						@endforeach
						<option value=""  @if(!isset($note->category2[0])) selected @endif> - No category - </option>
					</select>
				</div>
			</div>

			<div class="editButtonContainer">
				<input type="submit" class="btn btn-primary px-4 saveButton buttonBase" value="Save" />
				<div onclick="$().closeTitleForm()" class="btn btn-primary px-4 buttonBase">Close</div>
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
											<div class="imgIconContainer">
												<img src="{{ asset($category->image) }}" class="categoryIcon">
											</div>
										@endif
									@endforeach
									
									@foreach($note->category2 as $category)
										@if (file_exists($category->image))
											<div class="imgIconContainer">
												<img src="{{ asset($category->image) }}" class="categoryIcon">
											</div>
										@endif
									@endforeach

									@foreach($note->category3 as $category)
										@if (file_exists($category->image))
											<div class="imgIconContainer">
												<img src="{{ asset($category->image) }}" class="categoryIcon">
											</div>
										@endif
									@endforeach

									<h5>{{ $note->name }}
										<a onclick="$().openTitleForm()" class="openTitleForm">
											<i class="bx bx bx-edit"></i>
										</a>
									</h5>
								</div>
							</div>

							<button class="btn btn-primary addButton buttonBase"> <i class="fa fa-plus-circle"></i>  Add Text or Code</button>
							<button class="btn btn-primary closeButton"><i class="fa fa-minus-circle"></i> Close</button>
								
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
											<div id="monaco-editor-code" data-textarea-id="code-new" class="monacoEditor"></div>
											<textarea name="text" id="code-new" class="form-control d-none" ></textarea>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-12 text-secondary">
										<input type="submit" class="btn btn-primary px-4 saveButton buttonBase" value="Add" />
									</div>
								</div>
							</form>

							<br />

							<div id="noteContainer">

								@if (count($texts) === 0)
									<p><br /><br />No text or code found<br /><br /></p>
								@endif

								@foreach ($texts as $text)
									<input type="hidden" id="type{{$text->id}}" value="{{ $text->type }}">

									<div class="noteRow">
										@if ($text->type === "text")
												<div class="buttonContainerText">
													<a onclick="$().showButtons({{$text->id}})" class="buttonBox buttonBoxText buttonBoxShow" id="buttonShowBox{{$text->id}}">
														<i class="bx bx-edit" data-toggle="tooltip" title="Edit show"></i>
													</a>
												</div>

												<div class="buttonContainerText" id="buttonsBox{{$text->id}}" style="display:none">
													<a onclick="$().openEditForm({{$text->id}})" class="buttonBox buttonBoxText">
														<i class="bx bx-edit" data-toggle="tooltip" title="Edit"></i>
													</a>
													<a href="{{ route('note.up', ['note_id' => $text->note_id, 'text_id' => $text->id]) }}" class="buttonBox buttonBoxText">
														<i class="bx bx-up-arrow" data-toggle="tooltip" title="Up"></i>
													</a>
													<a href="{{ route('note.down', ['note_id' => $text->note_id, 'text_id' => $text->id]) }}" class="buttonBox buttonBoxText">
														<i class="bx bx-down-arrow" data-toggle="tooltip" title="Down"></i>
													</a>
													<a href="{{ route('delete.text', ['note_id' => $text->note_id, 'text_id' => $text->id]) }}" class="buttonBox buttonBoxText" id="row_delete{{$text->id}}" >
														<i class="bx bx-trash" data-toggle="tooltip" title="Delete"></i>
													</a>
												</div>
											
											<div id="text{{ $text->id }}">{!! $text->text !!}</div>
										@else
											<div class="buttonWrapper">
												<div class="buttonContainer">
													<a onclick="$().showButtons({{$text->id}})" class="buttonBox " id="buttonShowBox{{$text->id}}">
														<i class="bx bx-edit" data-toggle="tooltip" title="Edit show"></i>
													</a>
												</div>
												<div class="buttonContainer" id="buttonsBox{{$text->id}}" style="display:none">
													<a onclick="$().saveCode({{$text->id}},{{$text->note_id}})" class="buttonBox">
														<i class="bx bx-save" data-toggle="tooltip" title="Save"></i>
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
												<button class="btnCopy" id="btnCopy{{ $text->id }}" style="display:none">
													<i class="bx bx-copy"></i><span id="btnCopyText{{ $text->id }}">Copy</span>
												</button>
												<select id="languageSelect{{ $text->id }}" style="display:none" class="languageSelect">
													<option value="bat" @if ($text->editor=="bat") selected @endif>Batch</option>
													<option value="c" @if ($text->editor=="c") selected @endif>C</option>
													<option value="cpp" @if ($text->editor=="cpp") selected @endif>C++</option>
													<option value="csharp" @if ($text->editor=="csharp") selected @endif>C#</option>
													<option value="css" @if ($text->editor=="css") selected @endif>CSS</option>
													<option value="dart" @if ($text->editor=="dart") selected @endif>Dart</option>
													<option value="dockerfile" @if ($text->editor=="dockerfile") selected @endif>Dockerfile</option>
													<option value="go" @if ($text->editor=="go") selected @endif>Go</option>
													<option value="graphql" @if ($text->editor=="graphql") selected @endif>GraphQL</option>
													<option value="handlebars" @if ($text->editor=="handlebars") selected @endif>Handlebars</option>
													<option value="html" @if ($text->editor=="html") selected @endif>HTML</option>
													<option value="ini" @if ($text->editor=="ini") selected @endif>INI</option>
													<option value="java" @if ($text->editor=="java") selected @endif>Java</option>
													<option value="javascript" @if ($text->editor=="javascript") selected @endif>JavaScript</option>
													<option value="json" @if ($text->editor=="json") selected @endif>JSON</option>
													<option value="kotlin" @if ($text->editor=="kotlin") selected @endif>Kotlin</option>
													<option value="mysql" @if ($text->editor=="mysql") selected @endif>MySQL</option>
													<option value="php" @if ($text->editor=="php") selected @endif>PHP</option>
													<option value="plaintext" @if ($text->editor=="plaintext") selected @endif>Plain Text</option>
													<option value="python" @if ($text->editor=="python") selected @endif>Python</option>
													<option value="ruby" @if ($text->editor=="ruby") selected @endif>Ruby</option>
													<option value="rust" @if ($text->editor=="rust") selected @endif>Rust</option>
													<option value="scss" @if ($text->editor=="scss") selected @endif>SCSS</option>
													<option value="sql" @if ($text->editor=="sql") selected @endif>SQL</option>
													<option value="typescript" @if ($text->editor=="typescript") selected @endif>TypeScript</option>
													<option value="vb" @if ($text->editor=="vb") selected @endif>Visual Basic</option>
													<option value="xml" @if ($text->editor=="xml") selected @endif>XML</option>
													<option value="yaml" @if ($text->editor=="yaml") selected @endif>YAML</option>
												</select>
											</div>
											<div id="monaco-editor{{ $text->id }}"  data-textarea-id="code{{ $text->id }}" class="monaco-editor-container" style="width:100%;height: auto;min-height: 104px; overflow: hidden;"></div>
											<textarea name="text" id="code{{ $text->id }}" class="form-control d-none">{!! $text->text !!}</textarea>
											<input type="hidden" id="editor{{ $text->id }}" value="{{ $text->editor }}">
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
/*
	document.addEventListener("DOMContentLoaded", function () {
        if (window.Quill && window.QuillBetterTable) {
			Quill.register('modules/better-table', window.QuillBetterTable);
		}
	});
*/
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
						automaticLayout: true,
						theme: 'vs-light',
						minimap: {enabled: false},
						scrollBeyondLastLine: false,
						readOnly: false,
						padding: {
							top: 10,
							left: 10,
							bottom: 10
						}
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
						automaticLayout: true,
						theme: 'vs-light',
						minimap: {enabled: false},
						scrollBeyondLastLine: false,
						readOnly: false,
						padding: {
							top: 10,
							left: 10,
							bottom: 10
						}
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
						automaticLayout: true,
						theme: 'vs-dark',
						minimap: {enabled: false},
						scrollBeyondLastLine: false,
						readOnly: false,
						padding: {
							top: 10,
							left: 10,
							bottom: 10
						}
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
						theme: 'snow',
						modules: {
							toolbar: [
								["bold", "italic", "underline", "strike", "font", "link", "image"],
								[{ table: true }],
								
								['blockquote'],
								['code-block'],
								[{ 'font': [] }],
								[{ 'size': ['small', 'medium', 'large', 'huge'] }],
								[{ 'color': [] }, { 'background': [] }],
								[{ 'align': [] }],
								['unordered', 'ordered'],
								[{ 'list': 'ordered' }, { 'list': 'bullet' }],
							],
							'better-table': {
								operationMenu: {
									items: ['insertRowAbove', 'insertRowBelow', 'insertColumnLeft', 'insertColumnRight', 'deleteRow', 'deleteColumn', 'deleteTable']
								}
							},
						}
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

		(function($)
		{
			$.fn.showButtons = function(id)
			{
				const buttonsBox = $('#buttonsBox'+id);
				buttonsBox.css('display','flex');
				buttonsBox.css('width','130px');
				const buttonShowBox = $('#buttonShowBox'+id);
				buttonShowBox.css('display','none');
				const btnCopy = $('#btnCopy'+id);
				if(btnCopy){
					btnCopy.css('display','flex');
				}
				const languageSelect = $('#languageSelect'+id);
				if(languageSelect){
					languageSelect.css('display','block');
				}
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
				const id = textareaId.replace('code','');
				const editorLanguage = document.getElementById('editor'+id).value || 'javascript';

                const editor = monaco.editor.create(editorContainer, {
                    value: initialValue,
                    language: editorLanguage,
					automaticLayout: true,
					minimap: {enabled: false},
					scrollBeyondLastLine: false,
                    theme: 'vs-dark',
					padding: {
						top: 10,
						left: 10,
						bottom: 10
					}
                });

                editor.onDidChangeModelContent(function() {
                    document.getElementById(textareaId).value = editor.getValue();
                });

				document.getElementById('btnCopy'+id).addEventListener('click', function() {
					const code = editor.getValue();

					navigator.clipboard.writeText(code).then(() => {
						$('#btnCopyText'+id).text('Copied');
						setTimeout(function() {
							$('#btnCopyText'+id).text('Copy');
						}, 3000);
					}).catch(err => {
						console.error('Errore durante la copia:', err);
					});
				});

				function updateEditorHeight() {
				const lineHeight = editor.getOption(monaco.editor.EditorOption.lineHeight);
				const lineCount = editor.getModel().getLineCount();
				const newHeight = lineHeight * lineCount + 20;
				
				const editorDomNode = editor.getDomNode();
				editorDomNode.style.height = `${newHeight}px`;
				editor.layout();

				$('#languageSelect'+id).on('change', function() {
					let selectedLanguage = $(this).val();
					monaco.editor.setModelLanguage(editor.getModel(), selectedLanguage);
					
					$.ajax({
						url: '/save-editor-language',
						method: 'POST',
						data: {
							id: id,
							language: selectedLanguage,
							_token: '{{ csrf_token() }}'
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
						error: function() {
							alert('Saving error');
						}
					});
				});
			}

			editor.onDidChangeModelContent(() => {
				updateEditorHeight();
			});

			updateEditorHeight();

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
								automaticLayout: true,
								lineNumbers: 'off',
								theme: 'vs-light',
								minimap: {enabled: false},
								scrollBeyondLastLine: false,
								readOnly: false,
								padding: {
									top: 10,
									left: 10,
									bottom: 10
								}
   							 });
						});
					} else if (selectedType === 'code') {
						require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.31.1/min/vs' }});
						require(['vs/editor/editor.main'], function() {
							editorCode.updateOptions({
								value: '',
								language: 'javascript',
								automaticLayout: true,
								lineNumbers: 'on',
								theme: 'vs-dark',
								minimap: {enabled: false},
								scrollBeyondLastLine: false,
								readOnly: false,
								padding: {
									top: 10,
									left: 10,
									bottom: 10
								}
   							 });
						});
					}
				});
		});
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