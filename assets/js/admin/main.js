jQuery(function($) {
	$('a.delete').click(function() {
		return confirm('Deseja realmente excluir?');
	});
});
