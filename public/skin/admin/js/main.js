jQuery(function($) {
	$(".box-image").fancybox({
		'titleShow'     : false
	});
	
	var tinymceConfig = {
		script_url : baseUrl + 'skin/tiny_mce/tiny_mce.js',
		
		theme : "advanced",
		plugins : "ccSimpleUploader,autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",
		
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough",
		theme_advanced_buttons2 : "forecolor,backcolor,|,cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,undo,redo,|,link,unlink,anchor",
		theme_advanced_buttons3 : "image,ccSimpleUploader,tablecontrols,|,code",
		theme_advanced_buttons4 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : false,
		theme_advanced_resizing : true,
		
		relative_urls : false,
		convert_urls : false,
		
		file_browser_callback : "ccSimpleUploader",
		
		content_css : "css/content.css",

		template_external_list_url : "lists/template_list.js",

		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	};
	
	$('a[rel=external]').blank();
	
	$(':input:visible:first').focus().iziShadow();
	
	$(':input').focusin(function() {
		$(this).iziShadow();
	}).focusout(function() {
		$(this).iziShadow();
	});
	
	var $form = $('#find form');
	var $search = $('#find .search');
	if (action && action != 'find')
		$form.hide();
	else
		$search.addClass('click');
	
	$search.click(function() {
		if ($form.is(':visible')) {
			$form.slideUp();
			$(this).removeClass('click');
		} else {
			$form.slideDown();
			$(this).addClass('click');
		}
	});
	
	$('a.delete').click(function() {
		return confirm('Deseja realmente excluir?');
	});
	
	$('tbody tr').hover(
		function() {
			$(this).find('td').iziTrHover();
		},
		function() {
			$(this).find('td').iziTrHover();
		}
	);
	
	$('.check-all').click(function() {
		if ($(this).attr('checked'))
			$(this).parent().find('.columns .check').attr('checked', true);
		else
			$(this).parent().find('.columns .check').attr('checked', false);
	});
	
	$('.date').datepicker({
		regional: 'pt-BR',
		inline:		true,
		dateFormat:	'dd/mm/yy',
		changeYear:	true,
		yearRange:	'c-80:c'
	}).mask('99/99/9999');
	
	$('#li-servico-chave').hide();
	
	$('.text-hora').mask('99:99');
	$('.text-cep').mask('99999-999');
	$('.text-telefone').mask('99 9999-9999');
	
	$('.item-add').click(function() {
		var $list = $(this).parent().next('.list-item');
		var $last = $list.find('> ul > li:last'),
			texto = $last.find('.tinymce').val(),
			$clone = $last.clone();
		
		if ($last.length)
			$last = $list.find('.uploadifyQueue > div:first');
		
		$clone.prepend('<a class="item-del" href="javascript:void(0);">Remover</a>');
		
		$list.find('> ul:first').append($clone);
		
		var $inputs = $clone.find(':input');
		$inputs.each(function(i) {
			var id = $(this).attr('id');
			
			if ($(this).is(':not(:checkbox):not(:radio)'))
				$(this).val('');
			else
				$(this).find(':checkbox, :radio').attr('checked', false);
			
			var name = $(this).attr('name');
			var match = name.match(/\[([0-9]+)\]/);
			if (match && match[1]) {
				var new_i = Number(match[1])+1;
				
				var new_id = id.replace(/\_[0-9]+/, '_' + new_i);
				if (new_i == 1)
					new_id = id + '_' + new_i;

				$(this).attr('id', new_id);
				
				$clone.find('label[for=' + id + ']').attr('for', new_id);
			
				name = name.replace(/\[([0-9]+)\]/, '[' + new_i + ']');
				$(this).attr('name', name);
				
				if (new_id.match(/imagem/)) {
					$(this)
						.show()
						.parent()
							.find('.uploadifyQueue, object, .hint').remove().end()
							.append('<div id="' +  $(this).attr('id') + '-queue" class="uploadifyQueue"/>');;
					$('#'+new_id).iziUploadifyOne();
				} else if (new_id.match(/descricao/)) {
					$('#'+new_id).next('.mceEditor').remove();
					$('#'+new_id).val('').show().tinymce(tinymceConfig);
				}
			}
		});
		$last.find('.tinymce').val(texto);
	});
	
	$('.item-del').live('click', function() {
		$(this).parent().remove();
	});
	
	// iziUploadifyOne Image
	$('.upload-image').each(iziUploadifyOne);
	
	// iziUploadifyOne File
	$('.upload-file').each(function() {
		var $this = $(this);
		var path = baseUrl + 'media/ext/';
		var match = $this.val().match(/\.([a-z]+)$/);
		if (match != null) {
			switch (match[1]) {
				case 'docx': case 'doc':
					path += 'doc.png';
					break;
				case 'xlsx': case 'xls':
					path += 'xls.png';
					break;
				case 'pdf':
					path += 'pdf.png';
					break;
				default:;
			}
		}
		
		if (!$this.parent().find('.uploadifyQueue').length)
			$this.parent().append('<div id="' + $this.attr('id') + '-queue" class="uploadifyQueue"/>');
		
		if ($this.val().length) {
			$this.appendQueueItem(path, 'file');
		} else {
			$this.iziUploadifyOne();
		}
		
	});
	
	// iziUploadifyAll Images
	$('#uploadify-foto').each(function() {
		var $this = $(this);
		
		$('.uploadifyQueueItem .cancel').removeQueueItem();
		
		$this.iziUploadifyAll();
	});
	
	$('.select-imagem_galeria_id').change(function() {
		var value = $(this).val();
		if (value)
			$('#list-foto').hide();
		else
			$('#list-foto').show();
	});
	
	$('.tinymce').tinymce(tinymceConfig);
	
	//confirmação senha
	$('#form-usuario').submit(function (){
		
		var senha = $('#usuario-senha').val();
		var confirmasenha = $('#usuario-confirmasenha').val();
		
		if (senha != confirmasenha){
			
			alert('A confirmação da senha não confere!');
			
			return false;
			
		}
		
	});
	
	$('.select-estado_id').change(function() {
		ajaxCidade($(this));
	});
	
	$('#form-evento .ordem, #form-loteamento .ordem').bind('keypress keyup keydown', function() {
    	$(this).val($(this).val().replace(/[^0-9]/g, ''));
	});
	
	$('#form-evento, #form-loteamento').submit(function() {
    	var ordem = $('.ordem').val();
    	if(ordem == '') {
    		$('.ordem').addClass('error-ordem');
    		return false;
    	}
	});
	
	//uploadfy publicado
	$('.uploadifyQueueItem').each(function() {
		if ($(this).find('.set-privacy input').val() == 'nao')
			$(this).addClass('privacy-added');
	});
	
	$('.set-privacy a').live({click: function() {
			var $parent = $(this).parent();
			var $item = $(this).closest('.uploadifyQueueItem');
			var id = $item.attr('id').replace('uploadify-foto', '');
			
			if ($(this).hasClass('privacy-add')) {
				$item.removeClass('privacy-added');
				$parent.html($('<a class="privacy-remove" href="javascript:void(0)">Não Publicado</a>'))
					.append($('<input value="nao" name="imagem[' + id + '][publicado]" type="hidden"/>'));
			} else {
				$item.addClass('privacy-added');
				$parent.html($('<a class="privacy-add" href="javascript:void(0)">Publicado</a>'))
					.append($('<input value="sim" name="imagem[' + id + '][publicado]" type="hidden"/>'));
			}
		}
	});
	
	$('.uploadifyQueueItem').live({
		mouseenter: function() {
			$(this).find('.set-privacy').stop(false, true).slideDown(250);
		},
		mouseleave: function() {
			$(this).find('.set-privacy').stop(false, true).slideUp(250);
		}
	});
	
	//select atributo
	$(".chzn-select").chosen({no_results_text: "Nenhum resultado Encontrado!"});

	//tipo campo valor atributo
	$('#loteamento_atributo-atributo_id').change(function() {
		var opcao = $('#loteamento_atributo-atributo_id option:selected').val();
		
		ajaxTipoCampo(opcao);
	});
	$('#imovel_atributo-atributo_id').change(function() {
		var opcao = $('#imovel_atributo-atributo_id option:selected').val();
		
		ajaxTipoCampo(opcao);
	});
	
	$('.valorReal').priceFormat({
	    prefix: 'R$ ',
	    centsSeparator: ',',
	    thousandsSeparator: '.'
	});
	
	$('.inteiro').live('keypress keyup keydown', function() {
    	$(this).val($(this).val().replace(/[^0-9]/g, ''));
	});
});

jQuery.fn.appendQueueItem = function(path, type) {
	var $this = $(this);
	var pathFile = baseUrl + 'media/' + controller + '/' + $this.val();
	if (type == 'image')
		path += $this.val();
	
	var file = $this.val().match(/^(.+)\./);
	if (file != null) {
		$('#' + $this.attr('id') + '-queue').append(
			[
				'<div id="' + controller + '-imagem',
				file[1],
				'" class="uploadifyQueueItem completed">',
				'<img src="',
				path,
				'" width="50" height="50"><a',
				(type == 'image' ? ' class="box-image"' : ''),
				' href="',
				pathFile,
				'"> Visualizar</a>',
				'<div class="cancel">',
				'<a href="javascript:void(0)"><img src="',
				baseUrl,
				'skin/uploadify/images/cancel.png" /></a>',
				'</div></div>'
			].join('')
		);
	}
	
	$this.hide().closest('li').find('.uploadifyQueueItem .cancel').removeQueueItem($this);
};

jQuery.fn.iziUploadifyOne = function() {
	var $this = $(this);
	var path = baseUrl + 'media/' + controller + '/';
	var match = $this.attr('class').match(/upload\-([a-z]+)/);
	var sizeLimit = 2097152;
	fileExt = '*.jpg;*.gif;*.png';
	fileDesc = 'Image Files (.jpg, .gif, .png)';
	
	
	if (match != null && match[1] == 'file') {
		sizeLimit = 20971520;
		fileExt = '*.doc;*.docx;*.xls;*.xlsx;*.pdf;';
		fileDesc = 'Image Files (.doc, .docx, .xls, .xlsx, .pdf)';
	}
	$('#banner-imagem').parent().append(
		'<div class="hint">Largura do banner: 970px</div>',
		'<div class="hint">Altura do banner: 450px</div>'
	);
	$this.parent().append('<div class="hint">Tamanho máximo de ' + (sizeLimit/1048576) + ' MB.</div>');
	
	$this.uploadify({
		'uploader'			: baseUrl + 'skin/uploadify/uploadify.swf',
		'script'			: baseUrl + 'skin/uploadify/uploadify.php',
		'cancelImg'			: baseUrl + 'skin/uploadify/images/cancel.png',
		'folder'			: path,
		'multi'				: false,
		'auto'				: true,
		'fileExt'			: fileExt,
		'fileDesc'			: fileDesc,
		'queueID'			: $this.attr('id') + '-queue',
		'removeCompleted'	: false,
		'sizeLimit'			: sizeLimit,
		'width'				: 98,
		'height'			: 29,
		'buttonImg'			: baseUrl + 'skin/uploadify/images/button.png',
		'onComplete'		: function(event, ID, fileObj, response, data) {
			var path = baseUrl + 'media/' + controller + '/';
			var match = $this.attr('class').match(/upload\-([a-z]+)/);
			if (match != null && match[1] == 'file') {
				path = baseUrl + 'media/ext/';
				var matchExt = response.match(/\.([a-z]+)$/);
				if (matchExt != null) {
					switch (matchExt[1]) {
						case 'docx': case 'doc':
							path += 'doc.png';
							break;
						case 'xlsx': case 'xls':
							path += 'xls.png';
							break;
						case 'pdf':
							path += 'pdf.png';
							break;
						default:;
					}
				}
			}

			if (fileObj && response) {
				$this.val(response);
				$('#' + $this.attr('id') + ID).prepend(
					$('<img/>')
						.attr('src', path + (match != null && match[1] == 'file' ? '' : response))
						.attr('width', 50)
						.attr('height', 50)
				);
				$('#' + $this.attr('id') + 'Uploader').css('visibility', 'hidden');
			}
		},
		'onCancel'			: function(event, ID, fileObj, data, remove, clearFast) {
			$this.val('');
			$('#' + $this.attr('id') + 'Uploader').css('visibility', 'visible');
		}
	});
};

jQuery.fn.iziUploadifyAll = function() {
	var $this = $(this);
	var path = baseUrl + 'media/' + controller + '/';
	var sizeLimit = 2097152;
	
	$this.parent().find('.uploadifyQueue').before('<div class="hint">Tamanho máximo de ' + (sizeLimit/1048576) + ' MB por imagem.</div>');
	
	if (!$this.parent().find('.uploadifyQueue').length)
		$this.parent().append('<div id="' + $this.attr('id') + '-queue" class="uploadifyQueue"/>');
	
	$this.uploadify({
		'uploader'			: baseUrl + 'skin/uploadify/uploadify.swf',
		'script'			: baseUrl + 'skin/uploadify/uploadify.php',
		'cancelImg'			: baseUrl + 'skin/uploadify/images/cancel.png',
		'folder'			: path,
		'multi'				: true,
		'auto'				: true,
		'fileExt'			: '*.jpg;*.gif;*.png',
		'fileDesc'			: 'Image Files (.jpg, .gif, .png)',
		'queueID'			: $this.attr('id') + '-queue',
		'removeCompleted'	: false,
		'sizeLimit'			: sizeLimit,
		'width'				: 98,
		'height'			: 29,
		'buttonImg'			: baseUrl + 'skin/uploadify/images/button.png',
		'onComplete'		: function(event, ID, fileObj, response, data) {
			var path = baseUrl + 'media/' + controller + '/';
			
			if (fileObj && response) {
                if (controller == 'sp-pagina') {
                    $('#' + $this.attr('id') + ID).prepend(
                        $('<img/>')
                            .attr('src', path + response)
                            .attr('width', 50)
                            .attr('height', 50)
                    ).append(
                        $('<div class="box-ordem"><label>Ordem</label><input type="text" class="text ordem" value="1" name="imagem[' + ID + '][ordem]" /></div>'),
                        $('<input type="hidden" name="imagem[' + ID + '][nome]" value="' + fileObj.name + '" />'),
                        $('<input type="hidden" name="imagem[' + ID + '][imagem]" value="' + response + '" />'),
                        $('<div class="set-privacy"><a class="privacy-add" href="javascript:void(0)">Publicado</a><input value="sim" name="imagem[' + ID + '][publicado]" type="hidden"/></div>')
                    );
                } else if (controller != 'evento') {
					$('#' + $this.attr('id') + ID).prepend(
						$('<img/>')
						.attr('src', path + response)
						.attr('width', 50)
						.attr('height', 50)
					).append(
						$('<input type="radio" name="imagem_principal" value="' + ID + '" class="radio" />'),
						$('<div class="box-ordem"><label>Ordem</label><input type="text" class="text ordem" value="1" name="imagem[' + ID + '][ordem]" /></div>'),
						$('<input type="hidden" name="imagem[' + ID + '][nome]" value="' + fileObj.name + '" />'),
						$('<input type="hidden" name="imagem[' + ID + '][imagem]" value="' + response + '" />'),
						$('<input type="hidden" value="media" name="imagem[' + ID + '][local]" />'),
						$('<div class="set-privacy"><a class="privacy-add" href="javascript:void(0)">Publicado</a><input value="sim" name="imagem[' + ID + '][publicado]" type="hidden"/></div>')
					);
				} else {
					$('#' + $this.attr('id') + ID).prepend(
						$('<img/>')
						.attr('src', path + response)
						.attr('width', 50)
						.attr('height', 50)
					).append(
						$('<div class="box-ordem"><label>Ordem</label><input type="text" class="text ordem" value="1" name="imagem[' + ID + '][ordem]" /></div>'),
						$('<input type="hidden" name="imagem[' + ID + '][imagem]" value="' + response + '" />')
					);
				}
			}
		},
		'onAllComplete'		: function(event, data) {
			if (data.errors && !data.filesUploaded) {
				$this.val();
			}
		}
	})
	$('#form-evento .ordem, #form-loteamento .ordem, #form-imovel .ordem').live('keypress keyup keydown', function() {
    	$(this).val($(this).val().replace(/[^0-9]/g, ''));
	});
};

jQuery.fn.removeQueueItem = function(uploadify) {
	$(this).click(function() {
		$(this).parent().fadeOut(350, function() {
			$(this).remove();
			
			if (uploadify != undefined && uploadify.length) {
				uploadify.iziUploadifyOne();
				uploadify.val('');
			}
		});
	});
};

jQuery.fn.iziShadow = function() {
	if ($(this).hasClass('shadow'))
		$(this).removeClass('shadow');
	else
		$(this).addClass('shadow');
};

jQuery.fn.iziTrHover = function() {
	if ($(this).hasClass('hover'))
		$(this).removeClass('hover');
	else
		$(this).addClass('hover');
};

jQuery.fn.blank = function() {
	$(this).live('click', function() {
		window.open($(this).attr('href'));
		
		return false;
	});
};

function iziUploadifyOne($this) {
	if ($this[0] == undefined)
		var $this = $(this);
	
	var path = baseUrl + 'media/' + controller + '/';
	
	if (!$this.parent().find('.uploadifyQueue').length)
		$this.parent().append('<div id="' + $this.attr('id') + '-queue" class="uploadifyQueue"/>');
	
	if ($this.val().length) {
		$this.appendQueueItem(path, 'image');
	} else {
		$this.iziUploadifyOne();
	}
	
}

function ajaxCidade($this) {
	var $target = $('.select-cidade_id');
	
	$.ajax({
		url: baseUrl + 'admin/ajax/cidade/estado/' + $this.val(),
		type: 'POST',
		beforeSend: function() {
			$target.html('<option value="">Carregando...</option>');
		},
		success: function(data) {
			$target.html(data);
		},
		error: function() {
			$target.html('<option value="">Erro!</option>');
		}
	});
}

function ajaxPublicado() {
	var $this = $(this);
	var $form = $this.closest('form');
	
	$.ajax({
		url: $form.attr('action'),
		data: $form.serialize(),
		type: 'POST',
		beforeSend: function() {
			$this.attr('disabled', true);
		},
		success: function(data) {
			$this.attr('disabled', false);
		},
		error: function() {
			$this.attr('disabled', false);
		}
	});
}

function ajaxTipoCampo(opcao) {
	$.ajax({
	    url: baseUrl + "admin/" + controller + "/tipo-campo",
	    type: "GET",
	    data: { id:opcao },
	    dataType: "html",
	    beforeSend: function(){
			$('#li-' + controller + '_atributo-valor').html('<label for="' + controller + '_atributo-valor">Valor</label> Atualizando campo...');				
		},
	    success: function(sucesso){
	    	$('#li-' + controller + '_atributo-valor').html(sucesso);
	    	
	    	$('.valorReal').priceFormat({
	    	    prefix: 'R$ ',
	    	    centsSeparator: ',',
	    	    thousandsSeparator: '.'
	    	});
	    	$('.inteiro').live('keypress keyup keydown', function() {
	        	$(this).val($(this).val().replace(/[^0-9]/g, ''));
	    	});
	    	$('.date').datepicker({
	    		regional: 'pt-BR',
	    		inline:		true,
	    		dateFormat:	'dd/mm/yy',
	    		changeYear:	true,
	    		yearRange:	'c-80:c'
	    	}).mask('99/99/9999');
	    },
	});
}