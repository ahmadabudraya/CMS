<?php

class Session{
	
	public static function init(){
		session_start();
	}

	public static function set($key, $value){
		$_SESSION[$key] = $value;
	}

	public static function get($key){
		if(isset($_SESSION[$key])){
			return $_SESSION[$key];
		}else{
			return false;
		}
	}
	public static function checkSession(){
		if(self::get('login')== false){
			header('location: log.php');
		}
	}
	public static function checkLogin(){
		if(self::get("login")){
			header('location: dashboard.php');
		}
	}
	public static function destroy(){
		session_destroy();
		session_unset();
		header('Location: log.php');
	}
}