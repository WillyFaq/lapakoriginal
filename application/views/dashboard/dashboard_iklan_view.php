<div id="container">
		<?php
		$json = json_decode(file_get_contents(base_url('assets/quotes.json')), true);
		//print_r($json);
		$rand = rand(0, sizeof($json)-1);
		$quote = $json[$rand];
	?>
	<h2><i class="fa fa-quote-left"> </i></h2>
	<div id="quoteContainer">
		<p id="quote_txt"><?= $quote['text']; ?></p>
		<p id="quoteGenius">- <?= $quote['author']; ?></p>			
	</div><!--end quoteContainer-->
</div><!--end container-->

<script type="text/javascript">
	$(document).ready(function(){
		var quote = [];
		$.getJSON( "assets/quotes.json", function( data ) {
			haduh(data);
		});
	});

	function haduh(quote) {
		

		var tid = setInterval(function(){
			var max = quote.length;
			var r = Math.floor(Math.random() * (max - 0) ) + 0;
			console.log(quote);
			console.log(r);
			var q = quote[r];
			
			$("#quote_txt").hide(500, function(){
				$("#quote_txt").html(q.text).promise().done(function(){
				    $("#quote_txt").show(500);
				});
			});
			$("#quoteGenius").hide(500, function(){
				$("#quoteGenius").html(q.author).promise().done(function(){
				    $("#quoteGenius").show(500);
				});
			});
		},10000);
	}

</script>