<h1 class="page-header">Call Log</h1>
<div class="table-responsive">
	<table class="table table-striped">
	<thead>
	<tr>
		<th scope="col">Date</th>
		<th scope="col">Campaign</th>
		<th scope="col">From</th>
		<th scope="col">To</th>
		<th scope="col">From City</th>
		<th scope="col">From State</th>
		<th scope="col">From Zip</th>
		<th scope="col">Duration (seconds)</th>
		<th scope="col">Agent Call</th>
		<th scope="col">Recording</th>	
	</tr>
	</thead>
	<tbody>
<?php	
	$twilio_numbers = get_all_twilio_numbers();
	foreach( $calls as $call ){
		if ( array_key_exists( format_phone($call['CallTo']), $twilio_numbers) ){
			$campaign = $twilio_numbers[ format_phone($call['CallTo']) ];
		}else{
			$campaign = "";
		}
?>
	<tr>
		<td nowrap><?= $call['DateCreated'] ?></td>
		<td><?= $campaign ?></td>"
		<td><?= who_called($call['CallFrom']) ?></td>
		<td><?= who_called($call['CallTo']) ?></td>
		<td><?= $call['FromCity'] ?></td>
		<td><?= $call['FromState'] ?></td>
		<td><?= $call['FromZip'] ?></td>
		<td><?= $call['DialCallDuration'] ?></td>
		<td><?= be_nice($call['DialCallStatus']) ?></td>
<?php	if ($call['RecordingUrl']!=""){	?>
			<td><audio src="<?= $call['RecordingUrl'] ?>" controls preload="auto" autobuffer style="width:10em;"></audio></td>
<?php	}else{	?>
			<td></td>
<?php	}	?>
	</tr>
<?php
	}
?>
	</tbody>
	</table>
</div>