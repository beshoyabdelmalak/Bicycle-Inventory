<?php

class Bicycle {

  //----start of Active Record design pattern -----
    static protected $database;
    static protected $db_columns =['id','brand', 'model', 'year', 'category', 'color' , 'price' , 'gender', 'weight_kg' ,
        'condition_id' , 'description'];

    static public function set_database($database){
      self::$database = $database;
    }

    static public function find_by_sql($sql){
        $result = self::$database->query($sql);
        if(!$result){
            exit("Database Error");
        }
        $object_array = [];

        while($record = $result->fetch_assoc()){
            $object_array [] = self::instantiate($record);
        }
        $result->free();
        return $object_array;
    }

    static protected function instantiate($record){
        $object = new self ($record);
        return $object;
    }


    static public function find_all(){
        $sql = 'select * from bicycles' ;
        return self::find_by_sql($sql);
    }

    static public function find_by_id($id){
        $sql = "select * from bicycles ";
        $sql .= "where id='". self::$database->escape_string($id) ."'" ;
        $obj_array = self::find_by_sql($sql);
        if(!empty($obj_array)){
            //array_shift just return the first item of the array
            return array_shift($obj_array);
        }else{
            return false;
        }
    }

    public function save(){
      if (isset($this->id))
        $result = $this->update();
      else
        $result = $this->create();
      return $result;
    }

    public function create(){
      $attributes = $this->sanitize_input();
      $sql = "INSERT INTO bicycles(";
      $sql .= join(', ', array_keys($attributes));
      $sql .= ") VALUES ('";
      $sql .= join("', '", array_values($attributes));
      $sql .= "')";
      $result = self::$database->query($sql);

      if ($result){
        $this->id = self::$database->insert_id;
      }else{
        //echo self::$database->errno();
        redirect_to(url_for("/staff/bicycles/new.php"));
      }
      return $result;
    }

    public function update(){
      $sql ='UPDATE bicycles SET ';
      $attributes = $this->sanitize_input();
      $attributes_pair = [];
      foreach ($attributes as $key => $value){
          $attributes_pair [] = "{$key} = '{$value}'  ";
         //$attributes_pair [] = "{$key} = {$value}  ";

      }
      $sql .= join(', ', $attributes_pair);
      $sql .=" WHERE id ='" . self::$database->escape_string($this->id) . "' LIMIT 1";
      $result = self::$database->query($sql);
      if (!$result){
        redirect_to(url_for("/staff/bicycles/edit.php"));
      }
      return $result;
    }


    protected function attributes(){
      $attributes = [];
      foreach(self::$db_columns as $value){
          if ($value == 'id'){continue ;}
          $attributes[$value] = $this->$value ;
        }
      return $attributes ;
    }
//
//    protected function modified_attributes($attributes){
//      $modifed_attributes = [];
//      foreach($attributes as $key => $value){
//        if (property_exists($this , $key) && !is_null($value)){
//          $modified_attributes [] = "{$key} = {$value}";
//        }
//      }
//      return $modified_attributes ;
//    }

    public function merge_attributes($args){
      foreach ($args as $key => $value){
        if (property_exists($this , $key) && !is_null($value)){
          $this->$key = $value;
        }
      }
    }


    protected function sanitize_input(){
      $attributes = $this->attributes();
      $sanitized_attributes = [];
      foreach ($attributes as $key => $value){
        $sanitized_attributes [$key] = self::$database->escape_string($value);
      }
      return $sanitized_attributes ;
    }

  //----- end of Active Record design pattern----
    public $id;
    public $brand;
    public $model;
    public $year;
    public $category;
    public $color;
    public $description;
    public $gender;
    public $price;
    public $weight_kg;
    public $condition_id;

    public const CATEGORIES = ['Road', 'Mountain', 'Hybrid', 'Cruiser', 'City', 'BMX'];

    public const GENDERS = ['Mens', 'Womens', 'Unisex'];

    public const CONDITION_OPTIONS = [
      1 => 'Beat up',
      2 => 'Decent',
      3 => 'Good',
      4 => 'Great',
      5 => 'Like New'
    ];

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

    public function name(){
      return "{$this->brand} {$this->model} {$this->year}";
    }

    public function weight_kg() {
      return number_format($this->weight_kg, 2) . ' kg';
    }

    public function set_weight_kg($value) {
      $this->weight_kg = floatval($value);
    }

    public function weight_lbs() {
      $weight_lbs = floatval($this->weight_kg) * 2.2046226218;
      return number_format($weight_lbs, 2) . ' lbs';
    }

    public function set_weight_lbs($value) {
      $this->weight_kg = floatval($value) / 2.2046226218;
    }

    public function condition() {
      if($this->condition_id > 0) {
        return self::CONDITION_OPTIONS[$this->condition_id];
      } else {
        return "Unknown";
      }
    }

  }

  ?>
