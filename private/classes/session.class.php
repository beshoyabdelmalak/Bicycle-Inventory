<?php
  /**
   * Created by PhpStorm.
   * User: beshoy
   * Date: 2018-12-21
   * Time: 19:06
   */

  class Session {

    private $admin_id;
    private $username;
    public $last_login;
    //each session will last for 2 hours
    public const MAX_TIME = 60 *60*2 ;


    public function __construct(){
      session_start();
      //because the session object is being recreated every time the initialize file is required
      //so we check if there is some one already logged in
      $this->check_stored_id();
    }
    public function logging_in($admin){
      //prevent sessions fixation attacks
      if($admin) {
        session_regenerate_id();
        $this->admin_id = $admin->id ;
        $_SESSION['admin_id'] = $admin->id;
        $this->username = $admin->username;
        $_SESSION['username'] = $admin->username;
        $this->last_login = time();
        $_SESSION['last_login'] = time();
      }
    }

    public function check_logged_in(){
      return (isset($this->admin_id) && !$this->login_timeout());
    }

    public function logging_out(){
      unset($this->admin_id);
      unset($_SESSION['admin_id']);
      unset($this->username);
      unset($_SESSION['username']);
      unset($this->last_login);
      unset($_SESSION['last_login']);
    }

    private function check_stored_id(){
      if(isset($_SESSION['admin_id'])) {
        $this->admin_id = $_SESSION['admin_id'];
        $this->username = $_SESSION['username'];
        $this->last_login = $_SESSION['last_login'];
      }
    }

    public function get_username(){
      return $this->username;
    }

    private function login_timeout(){
      if(time() - $this->last_login > self::MAX_TIME)
        return true;
      else return false;
    }


    public function msg($msg = ""){
      if (!empty($msg)){
        //this is a set method
        $_SESSION['message'] = $msg;
        return true;
      }else{
        return $_SESSION['message'] ?? '';
      }
    }

    public function clear_msg(){
      unset($_SESSION['message']);
   }
  }


  ?>