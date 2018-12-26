<?php
  /**
   * Created by PhpStorm.
   * User: beshoy
   * Date: 2018-12-17
   * Time: 11:10
   */
  class DatabaseObject{
   static protected $database;
   static protected $table_name ='' ;
   static protected $db_columns =[];
   public $errors  = [];

    static public function set_database($database){
      //by using self, it refers to the databaseobject (parent class)
      //in the interaction with the database, it is ok that the parent class
      //who uses the database
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
      //static here refers to the child class that this method is called from
      //so that it will be dynamic to for every class that inherit from this class
      $object = new static ($record);
      return $object;
    }


    static public function find_all(){
      $sql = 'select * from '.static::$table_name. ' ' ;
      return static::find_by_sql($sql);
    }

    static public function find_by_id($id){
      $sql = "select * from ".static::$table_name. ' ';
      $sql .= "where id='". self::$database->escape_string($id) ."'" ;
      $obj_array = static::find_by_sql($sql);
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
      $this->validate();
      if(!empty($this->errors))
        return false;
      $attributes = $this->sanitize_input();
      $sql = "INSERT INTO ".static::$table_name."(";
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
      $this->validate();
      if(!empty($this->errors))
        return false;
      $sql ='UPDATE '.static::$table_name.' SET ';
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

    public function delete(){
      $sql = 'DELETE FROM '.static::$table_name.' ';
      $sql .= "WHERE id='" . self::$database->escape_string($this->id) . "' " ;
      $sql .= "LIMIT 1";
      $result = self::$database->query($sql);
      if (!result)
        redirect_to(url_for("/staff/bicycles/delete.php?id=" . $this->id));
      return $result;

      //after deleting the instance object will still exist, even though
      //the record is not in the database any more
      //this could be useful as in :
      //we could print out : echo $this->name is deleted
    }


    protected function attributes(){
      $attributes = [];
      foreach(static::$db_columns as $value){
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


    protected function validate()
    {
        //to be overridden with each child class
      $this->errors = [];
      return errors ;

    }

    static function get_count(){
      $sql ='SELECT COUNT(*) FROM '.static::$table_name;
      $result = self::$database->query($sql);
      $row = $result->fetch_array();
      return array_shift($row);
    }

  }



  ?>