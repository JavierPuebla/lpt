
<div class="bs-component">
	<div class="container-fluid" id='mainContainer'>
		
	</div>
</div>

<script type="text/javascript">
	$( window ).load(function() {
		console.log(<?php echo json_encode(array('atoms'=>$atoms,'pcles'=>$pcles,'events'=>$events,'user_id'=>$user_id)); ?>)
	});
</script>
</body>
</html>
