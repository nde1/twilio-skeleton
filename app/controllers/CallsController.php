<?php

class CallsController extends BaseController {
	
	public function getQueues(){
    $client = new Services_Twilio(
        Config::get('twilio.AccountSid'), 
        Config::get('twilio.AuthToken') 
    );
    $queues = $client->account->queues;
?>
    <table>
    <thead>
    <tr>
        <th>Sid</th>
        <th>Friendly Name</th>
        <th>Callers On Hold</th>
        <th>Average Wait Time (in seconds)</th>
    </tr>
    </thead>
    <tbody>
<?php
    foreach($queues as $queue) {
?>
        <tr>
            <td><a href="/calls/queue?qid=<?php echo $queue->sid ?>"><?php echo $queue->sid?></a></td>
            <td><?php echo $queue->friendly_name?></td>
            <td><?php echo $queue->current_size." / ".$queue->max_size ?></td>
            <td><?php echo $queue->average_wait_time?></td>
        </tr>
<?php
    }
?>
    </tbody>
    </table>
<?php
}

public function getQueue(){
    $client = new Services_Twilio(
        Config::get('twilio.AccountSid'), 
        Config::get('twilio.AuthToken') 
    );
    $qid = $_GET['qid'];
    $has_calls=false;

    $queue = $client->account->queues->get($qid);
    $members = $client->account->queues->get($qid)->members;
    echo '<h1>'.$queue->friendly_name.' Callers</h1>';
?>
    <table>
    <thead>
    <tr>
        <th>CallSid</th>
        <th>Phone Number</th>
        <th>Wait Time</th>
        <th>Position</th>
    </tr>
    </thead>
    <tbody>
<?php
    foreach($members as $member) {
        $call = $client->account->calls->get( $member->call_sid );
        $has_calls=true;

?>
        <tr>
            <td><?php echo $member->call_sid?></a></td>
            <td><?php echo $call->from;?></td>
            <td><?php echo $member->wait_time?></td>
            <td><?php echo $member->position ?></td>
        </tr>
<?php
    }
?>
    </tbody>
    </table>
<?php
    if (!$has_calls){
        echo ("No callers currently in the queue");
    }
}
	public function getIndex() {
		$id = Input::get('id');
		return Calls::find($id);	
	}

	public function getSms(){
		$client = new Services_Twilio(
			Config::get('twilio.AccountSid'), 
			Config::get('twilio.AuthToken') 
		);
		try {
			$message= $client->account->messages->sendMessage(
				Config::get('twilio.FromNumber'), 
				'YourNumber',
				"Hey, Monkey Party at 6PM. Bring Bananas!"
			);
			echo $message->sid; // Twilio's identifier for the new message
		} catch (Services_Twilio_RestException $e) {
			echo $e->getMessage(); // A message describing the REST error
		}
	}
	
	public function getAll() {
		return Calls::all();
	}
	public function postIndex(){
		if (Input::has('CallSid')) {
			$input = Input::all();
			if ($input['CallSid'] == '' ) {
			    return Response::make('You need to fill all of the input fields', 400);
			}
			$call = new Calls;
			$call->CallSid = $input['CallSid'];
			$call->AccountSid=$input['AccountSid'];
			$call->CallFrom=$input['From'];
			$call->CallTo=$input['To'];
			$call->CallStatus=$input['CallStatus'];
			$call->ApiVersion=$input['ApiVersion'];
			$call->Direction=$input['Direction'];
	
			if ($input['FromCity'] != '' ) {
				$call->FromCity=$input['FromCity'];
				$call->FromState=$input['FromState'];
				$call->FromZip=$input['FromZip'];
				$call->FromCountry=$input['FromCountry'];
			} else {
				$call->FromCity="";
				$call->FromState="";
				$call->FromZip="";
				$call->FromCountry="";
			}
			$call->ToCity=$input['ToCity'];
			$call->ToState=$input['ToState'];
			$call->ToZip=$input['ToZip'];
			$call->ToCountry=$input['ToCountry'];
			$call->DateCreated 	= date('Y-m-d H:i:s');
			$call->save();
			return $call;
	    } else {
	        return Response::make('You need to fill all of the input fields', 400);
	    }
	}
	public function deleteIndex() {
		$id = Input::get('id');
		$call = Calls::find($id);
		$call->delete();
        return $id;
    }
}
