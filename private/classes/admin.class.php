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
    //public $errors = [];




    //----- end of Active Record design pattern----
    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $username;
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
      if (!numeric($attributes['weight_kg']) || !numeric($attributes['price']))
        $this->errors[] = "weight and price must be numeric ";
    }

  }











  ?>