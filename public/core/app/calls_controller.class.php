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
    	$welcome = $this->app->option('twilio.welcome');
		header('Content-type: text/xml');
		echo '<?xml version="1.0" encoding="UTF-8"?>';
		// Set the numbers to call
		$numbers = get_numbers();
		$number_index = isset($_REQUEST['number_index']) ? $_REQUEST['number_index'] : "0";
		$DialCallStatus = isset($_REQUEST['DialCallStatus']) ? $_REQUEST['DialCallStatus'] : "";
		if( $this->app->option('twilio.hangup') == 'false' ){
//			If we never hangup, then we start our call from the first person in the list...
			if( $number_index >= count($numbers) ){
				$number_index = 0;
			}
		}
		if( $DialCallStatus != "completed" && $number_index < count($numbers) ){ 
?>
		<Response>
			<Dial action="/listener/attemptcall?number_index=<?php echo $number_index+1 ?>">
				<Number url="/listener/screencall">
				<?php echo $numbers[$number_index] ?>
				</Number>
			</Dial>
		</Response>
<?php
		} else {
?>
		<Response>
			<Hangup/>
		</Response>
<?php
		}
    }
    public function screencall(){
		header('Content-type: text/xml');
		echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
		<Response>
			<Gather action="/listener/completecall">
				<Say>Press any key to accept this call</Say>
			</Gather>
			<Hangup/>
		</Response>
<?php	    
    }
    public function completecall(){
		header('Content-type: text/xml');
		echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
		<Response>
			<Say>Connecting</Say>
		</Response>
<?php
    }
    public function attemptcall(){
		header('Content-type: text/xml');
		echo '<?xml version="1.0" encoding="UTF-8"?>';
		// Set the numbers to call
		$numbers = get_numbers();
		$number_index = isset($_REQUEST['number_index']) ? $_REQUEST['number_index'] : "0";
		$DialCallStatus = isset($_REQUEST['DialCallStatus']) ? $_REQUEST['DialCallStatus'] : "";

		if( $this->app->option('twilio.hangup') == 'false' ){
//			If we never hangup, then we start our call from the first person in the list...
			if( $number_index >= count($numbers) ){
				$number_index = 0;
			}
		}
		if( $DialCallStatus != "completed" && $number_index < count($numbers) ){ 
?>
		<Response>
			<Dial action="/listener/attemptcall?number_index=<?php echo $number_index+1 ?>">
			<Number url="/listener/screencall">
					<?php echo $numbers[$number_index] ?>
				</Number>
			</Dial>
		</Response>
<?php
		} else {
?>
		<Response>
			<Hangup/>
		</Response>
<?php
		}
	}
	function log(){
		$action = isset($_GET['action']) ? $_GET['action'] : 'today';
//		$results = get_usage( $action );
		$results = $this->app->store('client')->account->calls->getPage(0, 50, array())->getItems();
		$this->app->render( 'calls/log', array(
			"pageTitle"=>"Call Log",
			'results'=>$results,
		),'inside');
	}
}