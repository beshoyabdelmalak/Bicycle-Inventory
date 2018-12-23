<?php
  /**
   * Created by PhpStorm.
   * User: beshoy
   * Date: 2018-12-19
   * Time: 11:10
   */

  class Admin extends DatabaseObject{

    //----start of Active Record design pattern -----
    //static protected $database;
    static protected $db_columns = ['id','first_name', 'last_name', 'email', 'username', 'hashed_password'];
    static protected $table_name = 'admins';
    public $required_password = true;
    //public $errors = [];




    //----- end of Active Record design pattern----
    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $username;
    public $password;
    public $confirm_password;
    public $hashed_password;



    public function __construct($args=[]) {
      //$this->brand = isset($args['brand']) ? $args['brand'] : '';
//      $this->brand = $args['brand'] ?? '';
//      $this->model = $args['model'] ?? '';
//      $this->year = $args['year'] ?? '';
//      $this->category = $args['category'] ?? '';
//      $this->color = $args['color'] ?? '';
//      $this->description = $args['description'] ?? '';
//      $this->gender = $args['gender'] ?? '';
//      $this->price = $args['price'] ?? 0;
//      $this->weight_kg = $args['weight_kg'] ?? 0.0;
//      $this->condition_id = $args['condition_id'] ?? 3;

      //Caution: allows private/protected properties to be set
      foreach($args as $k => $v) {
        if(property_exists($this, $k) && !is_null($v)) {
          $this->$k = $v;
        }
      }
    }

    public function fullname(){
      return "{$this->first_name} {$this->last_name}";
    }


    protected function validate(){
      $error = false;
      $attributes = $this->attributes();
      foreach ($attributes as $key => $value) {
        if (is_blank($value)) {
          $error = true;
        }

      }
      if ($error === true) {
        $this->errors [] = 'fields with * should not be left blank';
      }

      //validation fo the first name
      if(!has_length($this->first_name, array('min'=>2 , 'max' => 255))) {
        $this->errors [] = 'first name should be more than 2 and less than 255 letters ';
      }

      //validation for the last name
      if(!has_length($this->last_name, array('min'=>2 , 'max'=>255))){
        $this->errors[] = 'last name should be more than 2 and less than 255 letters ';
      }

      //validation for the user name
      if (!has_length($this->username, array('min'=>8 , 'max'=>255))){
        $this->errors[] = 'username should be more than 8 and less than 255 letters ';
      }elseif(!has_unique_username($this->username, $this->id ?? 0)){
        $this->errors [] = 'the username you chose is already taken' ;
      }

      //validate the email
      if(!has_valid_email_format($this->email)){
        $this->errors[] = 'this is not a valid email format';
      }

      //validate the password
      if($this->required_password == true) {
        if (!has_length_greater_than($this->password, 6)) {
          $this->errors[] = 'password must be more than 6 characters ';
        } elseif (!preg_match('/[A-Z]/', $this->password)) {
          $this->errors[] = 'password must include at least one uppercase character ';
        } elseif (!preg_match('/[a-z]/', $this->password)) {
          $this->errors[] = 'password must include at least one lowercase character ';
        } elseif (!preg_match('/[0-9]/', $this->password)) {
          $this->errors[] = 'password must include at least one number ';
        } elseif (!preg_match('/[^A-Za-z0-9\s]/', $this->password)) {
          $this->errors[] = 'password must include at least one symbol ';
        }
      }

      //validate confirm password
      if($this->password !== $this->confirm_password){
        $this->errors [] = 'the password does not match';
      }

    }

    protected function hash_password(){
      $this->hashed_password = password_hash($this->password , PASSWORD_BCRYPT);
    }

    public function create (){
      $this->hash_password();
      return parent::create();
    }

    public function update (){
      $this->hash_password();
      if ($this->password =='') {
        $this->required_password = false;
      }else {
        $this->hash_password();
      }
      return parent::update();
    }

    static public function find_by_username($username){
      $sql = "select * from ".static::$table_name. ' ';
      $sql .= "where username='". self::$database->escape_string($username) ."'" ;
      $obj_array = static::find_by_sql($sql);
      if(!empty($obj_array)){
        //array_shift just return the first item of the array
        return array_shift($obj_array);
      }else{
        return false;
      }
    }

    public function verify($password){
      return password_verify($password, $this->hashed_password);
    }


  }











  ?>