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
?>
		<Response>
			<Say>Hi</Say>
			<Hangup/>
		</Response>
<?php
    }
}