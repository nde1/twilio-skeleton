<?php

class CallsController extends BaseController {
	
	public function getIndex() {
		$id = Input::get('id');
		return Calls::find($id);	
	}
	public function getSms(){
		$client = new Services_Twilio('AccountSid', 'AuthToken' );
		$fromNumber = 'TwilioNumber';
		$sms = $client->account->messages->sendMessage(
			$fromNumber, 
			'YourNumber',
			"Hey, Monkey Party at 6PM. Bring Bananas!"
		);
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
			$call->CallSid = $input('CallSid');
			$call->AccountSid=$input('AccountSid');
			$call->CallFrom=$input('From');
			$call->CallTo=$input('To');
			$call->CallStatus=$input('CallStatus');
			$call->ApiVersion=$input('ApiVersion');
			$call->Direction=$input('Direction');
	
			if ($input['FromCity'] != '' ) {
				$call->FromCity=$input('FromCity');
				$call->FromState=$input('FromState');
				$call->FromZip=$input('FromZip');
				$call->FromCountry=$input('FromCountry');
			} else {
				$call->FromCity="";
				$call->FromState="";
				$call->FromZip="";
				$call->FromCountry="";
			}
			$call->ToCity=$input('ToCity');
			$call->ToState=$input('ToState');
			$call->ToZip=$input('ToZip');
			$call->ToCountry=$input('ToCountry');
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