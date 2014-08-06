<?php
/*
 * User Controller
 * Handles all functions related to User.
 */
class userController extends \Jolt\Controller{
    public function my_name($name = 'default'){
		$this->app->render( 'page', array(
			"pageTitle"=>"Greetings ".$this->sanitize($name)."!",
			'title'=>'123',
			"body"=>"Greetings ".$this->sanitize($name)."!"
		));
    }
	public function mylist(){
		$this->app->condition('signed_in');
		$me  = Model::factory('User')->where_equal( 'id', $this->app->store('user') )->find_one();
		//	query user
		$user  = Model::factory('User')->find_many();
		$this->app->render( 'user/list', array(
			"pageTitle" => "User List",
			"me" => $me,
			'fromNumber'=> format_telephone( $this->app->store('fromNumber') ),
			'user' => $user
		),'inside');
	}
	public function edit( $user_id ){
		$this->app->condition('signed_in');
		$me  = Model::factory('User')->where_equal( 'id', $this->app->store('user') )->find_one();
		$user  = Model::factory('User')->where_equal( 'id', $user_id )->find_one();
		$this->app->render( 'user/form', array(
			"pageTitle" => "Edit User",
			"me" => $me,
			"action" => "/user/edit/".$user_id.( isset($_REQUEST['embed']) ? '?embed='.$_REQUEST['embed'] : null ),
			'user' => $user
		),
		( isset($_REQUEST['embed']) ? 'embedded' : 'inside' ) );
	}
	public function save( $user_id ){
		$this->app->condition('signed_in');
		$me  = Model::factory('User')->where_equal( 'id', $this->app->store('user') )->find_one();
		//	update user member
		$user  = Model::factory('User')->where_equal( 'id', $user_id )->find_one();
		if( isset($_POST['pass1']) && isset($_POST['pass2']) ){
			if( !empty($_POST['pass1']) && !empty($_POST['pass2']) ){
				$pass1 = $_POST['pass1'];
				$pass2 = $_POST['pass2'];
				if( $pass1 == $pass2 ){
					$_POST['pass'] = $pass1;
				}else{
					header("Location: /user/edit/{$user_id}".( isset($_REQUEST['embed']) ? '?embed='.$_REQUEST['embed'] : null ));
					exit;
				}
			}
		}
		$user  = Model::factory('User')->where_equal( 'id', $user_id )->find_one();
		if( isset($_POST['pass1']) && isset($_POST['pass2']) ){
			if( !empty($_POST['pass1']) && !empty($_POST['pass2']) ){
				$pass1 = $_POST['pass1'];
				$pass2 = $_POST['pass2'];
				if( $pass1 == $pass2 ){
					$_POST['pass'] = $pass1;
				}else{
					$uri = site_url();
					header("Location: {$uri}/user/edit/{$user_id}".( isset($_REQUEST['embed']) ? '?embed='.$_REQUEST['embed'] : null ));
					exit;
				}
			}
		}
		$user->login 		= $_POST['email'];
		$user->email 		= $_POST['email'];
		$user->name 		= $_POST['name'];
		$user->display_name = $_POST['name'];
		$user->phone 		= '---';
		$user->type			= $_POST['type'];
		if( isset($_POST['pass']) ){
			$user->pass	= passhash( $_POST['pass'] );
		}
		$user->save();

		$userm = $user->usermeta()->find_many();
		foreach($userm as $um){
			$um->delete();
		}

		//	all other form fields we save as usermeta....
		foreach($_POST as $k=>$v){
			$userm = Model::factory('Usermeta')->create();
			$userm->user_id = $user_id;
			$userm->mkey = $k;
			$userm->mvalue = $v;
			$userm->save();
		}
		echo '<script>top.location.href="/user";</script>';
		exit;
	}
	public function addnew( ){
		$this->app->condition('signed_in');
		$me  = Model::factory('User')->where_equal( 'id', $this->app->store('user') )->find_one();
		$this->app->render( 'user/form', array(
			"pageTitle" => "Add New User",
			"me" => $me,
			"action" => "/user/new".( isset($_REQUEST['embed']) ? '?embed='.$_REQUEST['embed'] : null ),
		),
		( isset($_REQUEST['embed']) ? 'embedded' : 'inside' ) );
#		print_r($user->id);
	}
	public function savenew(  ){
		$this->app->condition('signed_in');
		$me  = Model::factory('User')->where_equal( 'id', $this->app->store('user') )->find_one();
		if( isset($_POST['pass1']) && isset($_POST['pass2']) ){
			if( !empty($_POST['pass1']) && !empty($_POST['pass2']) ){
				$pass1 = $_POST['pass1'];
				$pass2 = $_POST['pass2'];
				if( $pass1 == $pass2 ){
					$_POST['pass'] = $pass1;
				}else{
					header("Location: /user/new".( isset($_REQUEST['embed']) ? '?embed='.$_REQUEST['embed'] : null ));
					exit;
				}
			}
			$user 				= Model::factory('User')->create();
			$user->login 		= $_POST['email'];
			$user->email 		= $_POST['email'];
			$user->name 		= $_POST['name'];
			$user->display_name = $_POST['name'];
			$user->pass			= passhash( $_POST['pass'] );
			$user->status 		= 1;
			$user->phone 		= $_POST['phone'];
			$user->activation_key = md5(uniqid(mt_rand(), true));
			$user->registered 	= date('Y-m-d H:i:s');
			$user->type			= 'user';
			$user->save();

			//	all other form fields we save as usermeta....
			foreach($_POST as $k=>$v){
				if( $k == 'pass' )	continue;
				$userm = Model::factory('Usermeta')->create();
				$userm->user_id = $uid;
				$userm->mkey = $k;
				$userm->mvalue = $v;
				$userm->save();
			}
#			header("Location: /user");
			echo '<script>top.location.href="/user";</script>';
			exit;
		}
	}
	public function delete( $user_id ){
		$this->app->condition('signed_in');
		$me  = Model::factory('User')->where_equal( 'id', $this->app->store('user') )->find_one();
		$user  = Model::factory('User')->where_equal( 'id', $user_id )->find_one();
		$user->delete();		
		header("Location: /user");
		exit;
	}
}