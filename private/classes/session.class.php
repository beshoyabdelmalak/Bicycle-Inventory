<?php
  /**
   * Created by PhpStorm.
   * User: beshoy
   * Date: 2018-12-21
   * Time: 19:06
   */

  class Session {

    public $admin_id;
    public $username;


    public function __construct(){
      $this->check_stored_id();
      session_start();

    }

    public function logging_in($admin){
      //prevent sessions fixation attacks
      if($admin) {
        session_regenerate_id();
        $this->admin_id = $admin->id ;
        $_SESSION['admin_id'] = $admin->id;
        $this->username = $admin->username;
        $_SESSION['username'] = $admin->username;
      }
    }

    public function check_logged_in(){
      return isset($_SESSION['admin_id']);
    }

    public function logging_out(){
      unset($this->admin_id);
      unset($_SESSION['admin_id']);
      unset($this->username);
      unset($_SESSION['username']);
    }

    public function check_stored_id(){
      if(isset($_SESSION['admin_id']))
        $this->admin_id = $_SESSION['admin_id'];
    }

  }


  ?>