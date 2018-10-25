<?php
include 'Session.php';
include 'database.php';
class User{
	private $db;
	public function __construct(){
		$this->db = new Database();
	}
	public function filterData($val){
		$val = trim($val);
		$val = htmlentities($val);
		$val = stripslashes($val);
		$val = htmlspecialchars($val);
		$val = mysqli_real_escape_string($this->db->link,$val);
		$val = strtolower($val);
		return $val;

	} 
	public function changePassword($old, $new){
		$old = md5($old);
		$new = md5($new);
		$id  = Session::get('id');
		$sql = "SELECT * FROM users WHERE password = '$old' AND id = '$id'";
		$res = $this->db->select($sql);
		if(!$res->num_rows)return "The old password is incorrect!";
		$sql = "UPDATE users
				SET password = '$new'
				WHERE id = '$id'";
		$res = $this->db->update($sql);
		if($res){
			return "Your password is changed successfully!";
		}
		return false;
	}
	public function countUsers(){
		$sql = "SELECT * FROM users";
		$res = $this->db->select($sql);
		return $res->num_rows;
	}
	public function getUserById($id){
		$sql = "SELECT * FROM users WHERE id = $id";
		$res = $this->db->select($sql);
		$user =  $res->fetch_assoc();
		return $user;
	}
	public function checkIfExist($username, $email){
		$sql = "SELECT * FROM users WHERE email = '$email' OR username = '$username' LIMIT 1";
		$res = $this->db->select($sql);
		return $res;
	}
	public function userRegistration($data){
		$name     = $this->filterData($data['fullname']);
		$username = $this->filterData($data['username']);
		$email 	  = $this->filterData($data['email']);
		$pass 	  = $this->filterData($data['password']);
		$repass   = $this->filterData($data['re-password']);
		$password = md5($data['password']);
		$date 	  = date('Y-m-d',time()); 

		if($name == "" or $username == "" or $email == "" or $pass == "" or $repass == ""){
			$msg = "<div class='alert alert-danger'><b>Error !</b> The fields must not be empty</div>";
			return $msg;
		}elseif($pass != $repass){
			$msg = "<div class='alert alert-danger'><b>Error !</b> The Passwords not identical</div>";
			return $msg;
		}

		$sql = "insert into users values('','$name', '$username', '$email', '$password',0, '$date')";
		if($this->checkIfExist($username, $email)){
			$msg = "<div class='alert alert-danger'><b>Error !</b> The user already exist</div>";
			return $msg;	
		}
		$res = $this->db->insert($sql);
		if($res){
			$msg = "<div class='alert alert-success'><b>Success !</b> registration successfully</div>";
			return $msg;	
		}
		return false;
	}
	private function getLoginUser($emailuser, $password){
		$sql = "SELECT * FROM users WHERE email = '$emailuser' OR username = '$emailuser' AND password = '$password' AND RegStatus = 1 LIMIT 1";
		$res = $this->db->select($sql);
		if($res){
			return $res->fetch_assoc();
		}
		return false;

	}
	
	public function userLogin($data){
		$emailuser = $this->filterData($data['emailuser']);
		$password  = md5($data['password']);
		if(empty($emailuser) or empty($password)){
			$msg = "<div class='alert alert-danger'><b>Error !</b> The fields must not be empty </div>";
			return $msg;	
		}
		if(!$this->checkIfExist($emailuser,$emailuser)){
			$msg = "<div class='alert alert-danger'><b>Error !</b> The email/password not valid</div>";
			return $msg;
		}

		$result = $this->getLoginUser($emailuser, $password);
		if($result){
			Session::init();
			Session::set('login',true);
			Session::set('id', $result['id']);
			Session::set('fullname', $result['full_name']);
			Session::set('username', $result['username']);
			Session::set('loginmsg', "<div class='alert alert-success'><b>Success !</b> You are loggedIn!</div>");
			header('location: dashboard.php');
		}
		return false;
	} 

	public function updateAccountSettings($data){
		$full_name  = $this->filterData($data['full_name']);
		$username   = $this->filterData($data['username']);
		$email 	    = $this->filterData($data['email']);
		//$updated_at = date('Y-m-d H:i:s', time());
		$id 	    = intval(Session::get('id')); 
		$sql = "UPDATE users 
				SET
				full_name = '$full_name',
				username  = '$username',
				email 	  = '$email'
				WHERE id = $id";
		$res = $this->db->update($sql);
		if($res){
			Session::set('fullname',$full_name);
			Session::set('username', $username);
			header('location:'.$_SERVER['HTTP_REFERER']);
			return 'Updated Successfully!';
		}
		return false;
	}
	public function getPendingUsers(){
		$sql = "SELECT * FROM users WHERE RegStatus = 0";
		$res = $this->db->select($sql);
		if($res){
			return $res;
		}
		return false;
	}
	
}