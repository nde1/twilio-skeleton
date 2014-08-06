<?php
class Signup extends \Jolt\Controller{
    public function my_name($name = 'default'){
        $this->app->render( 'page', array(
            "pageTitle"=>"Greetings ".$this->sanitize($name)."!",
            'title'=>'123',
            "body"=>"Greetings ".$this->sanitize($name)."!"
        ));
    }

	public function get(){
		$this->app->condition('signed_in');
		$me  = Model::factory('User')->where_equal( 'id', $this->app->store('user') )->find_one();
		$this->app->render( 'settings', array(
			"pageTitle"=>"Edit Profile",
			'me'=>$me,
			"myname" => ucwords( $me->display_name ),
		));
	}
	public function post(){
		$user = Model::factory('User')->where_equal('login', $_POST['email'])->find_one();
		if( isset($user->id) ){
			echo "Error: This user already exists";
			$this->app->redirect( $this->app->getBaseUri().'/signup?error=1');
		}else{
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

			$uid = $user->id;

			//	all other form fields we save as usermeta....
			foreach($_POST as $k=>$v){
				if( $k == 'pass' )	continue;
				$userm = Model::factory('Usermeta')->create();
				$userm->user_id = $uid;
				$userm->mkey = $k;
				$userm->mvalue = $v;
				$userm->save();
			}
		}
		$this->app->redirect( $this->app->getBaseUri().'/login');
	}
}