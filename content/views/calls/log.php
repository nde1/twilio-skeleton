<h1 class="page-header">Call Log</h1>
<p>Last 50 calls on your account</p>
<div class="table-responsive">
	<table class="table table-striped">
	<thead>
	<tr>
		<th>Number</th>
		<th>Start Time</th>
		<th>End Time</th>
		<th>Duration</th>
		<th>Called</th>
		<th>Status</th>
	</tr>
	</thead>
	<tbody>
<?php	
	foreach( $results as $call ){
?>
	<tr>
		<td><?= who_called($call->from); ?></td>
		<td><?= nice_date($call->start_time); ?></td>
		<td><?= nice_date($call->end_time); ?></td>
		<td><?= gmdate("H:i:s",$call->duration); ?></td>
		<td><?= who_called($call->to); ?></td>
		<td><?= be_nice($call->status); ?></td>
	</tr>
<?php
	}
?>
	</tbody>
	</table>
</div>