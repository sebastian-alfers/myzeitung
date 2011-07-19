<script>

	function lookup(inputString) {
		if(inputString.length == 0) { // esc btn) {
			// Hide the suggestion box.
			$('#search-suggest').hide();
		} else {
			inputString = $.trim(inputString);
			$.post("<?php echo FULL_BASE_URL.DS.'search/ajxSearch/'?>", {query: ""+inputString+""}, function(data){
				$('#search-suggest').show();
				$('#search-suggest').html(data);
			});
		}
	} // lookup

	$(document).bind('click', function(){
		if($('#search-suggest').is(":visible")){
			hideSuggestion();
		}

	});

	$('#inputString').focus(function(e){
		if($('#inputString').val() != '<?php echo __('Find', true);?>') {
			lookup($('#inputString').val());
		}
	});

	$('#inputString').keyup(function(e){
		if (e.keyCode == 27) { // esc btn
			hideSuggestion('');
			$('#inputString').val('');
		}
		else{
			lookup($('#inputString').val());
		}
	});

	$(document).bind('keyup', function(e){
		  if (e.keyCode == 27) { // esc btn
			  hideSuggestion();
			  $('#inputString').val('');
		   }
		});

	function hideSuggestion(value){
		$('#search-suggest').hide();
		lookup('');
		$('#search-suggest').html('');
	}
	</script>