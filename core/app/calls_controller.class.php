<?php
class callController extends \Jolt\Controller{
    public function my_name($name = 'default'){
		$this->app->render( 'page', array(
			"pageTitle"=>"Greetings ".$this->sanitize($name)."!",
			'title'=>'123',
			"body"=>"Greetings ".$this->sanitize($name)."!"
		));
    }
    public function answer(){
		$call = Model::factory('Call')->create();
		$call->CallSid = $_REQUEST['CallSid'];
		$call->AccountSid=$_REQUEST['AccountSid'];
		$call->CallFrom=$_REQUEST['From'];
		$call->CallTo=$_REQUEST['To'];
		$call->CallStatus=$_REQUEST['CallStatus'];
		$call->ApiVersion=$_REQUEST['ApiVersion'];
		$call->Direction=$_REQUEST['Direction'];

		if (isset($_REQUEST['FromCity'])){
			$call->FromCity=$_REQUEST['FromCity'];
			$call->FromState=$_REQUEST['FromState'];
			$call->FromZip=$_REQUEST['FromZip'];
			$call->FromCountry=$_REQUEST['FromCountry'];
		} else {
			$call->FromCity="";
			$call->FromState="";
			$call->FromZip="";
			$call->FromCountry="";
		}
		$call->ToCity=$_REQUEST['ToCity'];
		$call->ToState=$_REQUEST['ToState'];
		$call->ToZip=$_REQUEST['ToZip'];
		$call->ToCountry=$_REQUEST['ToCountry'];
		$call->DateCreated 	= date('Y-m-d H:i:s');
		$call->save();

		header('Content-type: text/xml');
		echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
		<Response>
			<Say>Thanks for calling</Say>
			<Hangup/>
		</Response>
<?php
    }
	function log(){
		$log  = Model::factory('Call')->find_many();
		$calls = array();
		foreach($log as $row){
			$call = array();
			$call['CallSid'] = $row->CallSid;
			$call['CallFrom'] = $row->CallFrom;
			$call['CallTo'] = $row->CallTo;
			$call['FromCity'] = $row->FromCity;
			$call['FromState'] = $row->FromState;
			$call['FromZip'] = $row->FromZip;
			$call['DialCallDuration'] = $row->DialCallDuration;
			$call['DialCallStatus'] = $row->DialCallStatus;
			$call['RecordingUrl'] = $row->RecordingUrl;
			$call['DateCreated'] = $row->DateCreated;
			$calls[] = $call;
		}
		$this->app->render( 'calls/log', array(
			"pageTitle"=>"Call Log",
			'calls'=>$calls,
		),'inside');
	}
}