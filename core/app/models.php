<?php
/*
	We use Idiorm and Paris for our ORM system, so these are our basic models that we use to set up active record....
*/
class User extends Model{
	public function get_firstname(){
		$name = $this->name;
		list($fname, $lname) = explode(' ', $name);
		return $fname;
	}
	public function usermeta(){
		return $this->has_many('Usermeta');
	}
}
class Usermeta extends Model{
    public function user() {
        return $this->belongs_to('User');
    }
}

class Call extends Model{
	public static $_table = 'calls';
}

class Post extends Model{
    public function user() {
        return $this->belongs_to('User');
    }
	public function postmeta(){
		return $this->has_many('Postmeta');
	}
}
class Postmeta extends Model{
    public function post() {
        return $this->belongs_to('Post');
    }
}

