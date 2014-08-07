<?php
$_GET['route'] = isset($_GET['route']) ? '/'.$_GET['route'] : '/';

// Check for composer installed
if (file_exists('vendor/autoload.php')){
	include_once('vendor/autoload.php');
}else{
	echo '{"error":"Composer Install"}';
	header('HTTP/1.1 500 Internal Server Error', true, 500);
	return False;
}

include("core/system/runtime.php");

$app = new Jolt\Jolt();
$app->option('source', 'config.ini');

if( $app->option('twilio.enabled') != false ){
	$client = new Services_Twilio($app->option('twilio.accountsid'), $app->option('twilio.authtoken') );
	$fromNumber = $app->option('twilio.fromNumber');
	
	//	store Twilio client and our Twilio fromNumber in our session store...
	$app->store('client',$client);
	$app->store('fromNumber',$fromNumber);
}

if( $app->option('simperium.enabled') != false ){
	$simperium = new Simperium\Simperium( $app->option('simperium.appid'), $app->option('simperium.token') );
	//	store our Simperium client in our session store...
	$app->store('simperium',$simperium);
}

if( $app->option('pusher.enabled') != false ){
	$pusher = new Pusher(
		$app->option('pusher.key'),
		$app->option('pusher.secret'),
		$app->option('pusher.appid')
	);

	//	store our Pusher client in our session store...
	$app->store('pusher',$pusher);
}

if( $app->option('pdo.enabled') != false ){
	ORM::configure( $app->option('pdo.connect') );
}

if( $app->option('db.enabled') != false ){
	ORM::configure('mysql:host='.$app->option('db.host').';dbname='.$app->option('db.name'));
	ORM::configure('username', $app->option('db.user') );
	ORM::configure('password', $app->option('db.pass') );
}

//	Logout	--------------------------------------------------------------------------------------------

$app->get('/logout', function() use ($app){
	$app->store('user',0);
	$app->redirect( $app->getBaseUri().'/login');
});

//	Conditions	--------------------------------------------------------------------------------------------

$app->condition('signed_in', function () use ($app) {
	$app->redirect( $app->getBaseUri().'/login',!$app->store('user'));
});

//	Login	--------------------------------------------------------------------------------------------

$app->get('/login', function() use ($app){
	$app->render( 'login', array(),'blank' );
});
$app->post('/login', function() use ($app){
	$user = Model::factory('User')->where_equal('login', $_POST['user'])->find_one();
	if( $user->pass == passhash($_POST['pass']) ){
		$app->store("user",$user->id);
		$app->redirect( $app->getBaseUri().'/dashboard');
	}else{
		$app->redirect( $app->getBaseUri().'/login');
	}
});
//	Register	--------------------------------------------------------------------------------------------

$app->get('/signup', function() use ($app){
	$app->render( 'register', array(),'blank' );
});
$app->post('/signup', 'signup#post' );


//	Twilio listener	--------------------------------------------------------------------------------------------

$app->route('/listener', 'callController#answer');
$app->route('/calls', 'callController#log');

//	Twimlets	--------------------------------------------------------------------------------------------

$app->route('/voicemail', 'twimletsController#voicemail');
$app->route('/forward', 'twimletsController#forward');
$app->route('/conference', 'twimletsController#conference');
$app->route('/menu', 'twimletsController#menu');
$app->route('/findme', 'twimletsController#findme');
$app->route('/simulring','twimletsController#simulring');
$app->route('/callme','twimletsController#callme');
$app->route('/message','twimletsController#message');
$app->route('/echo','twimletsController#echoml');
$app->route('/holdmusic','twimletsController#holdmusic');
$app->route('/whisper','twimletsController#whisper');

//	Logged in area	--------------------------------------------------------------------------------------------

$app->get('/dashboard', function() use ($app){
	$app->condition('signed_in');
	$me = Model::factory('User')->where_equal('id', $app->store('user'))->find_one();
	$app->render( 'dashboard', array(
		"pageTitle" => "My Dashboard",
		"me" => $me,
	),'inside' );
});

//	User	--------------------------------------------------------------------------------------------

$app->get('/user', 'userController#mylist');
$app->get('/user/edit/:user_id', 'userController#edit');
$app->post('/user/edit/:user_id', 'userController#save');
$app->get('/user/new', 'userController#addnew');
$app->post('/user/new', 'userController#savenew');
$app->get('/user/delete/:user_id', 'userController#delete');


//	home page --------------------------------------------------------------------------------------------

$app->get('/', function() use ($app){
	$app->render( 'login', array(),'blank' );
});

//	404 page  --------------------------------------------------------------------------------------------
$app->get('.*',function() use ($app){
	$app->error(404, $app->render('404',  array(
		"pageTitle"=>"404 Not Found",
	),'layout'));
});

$app->listen();