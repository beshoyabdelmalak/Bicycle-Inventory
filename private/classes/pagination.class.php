<?php
  /**
   * Created by PhpStorm.
   * User: beshoy
   * Date: 2018-12-25
   * Time: 23:39
   */

  class Pagination{

    private $count;
    private $current_page;
    private $per_page;


    public function __construct($per_page=3 , $current_page =0 , $count =0){
      $this->current_page = (int) $current_page;
      $this->per_page = (int)$per_page;
      $this->count = (int)$count;

    }

    public function offset(){
      return $this->per_page * ($this->current_page -1);
    }

    public function total_pages(){
      return ceil($this->count / $this->per_page);
    }


    public function next_page(){
      if ($this->current_page < $this->total_pages()){
        $next = $this->current_page +1 ;
        return $next;
      }else
        return false;
    }

    public function previous_page(){
      if ($this->current_page > 1){
        $prev =$this->current_page -1;
        return $prev;
      }else
        return false;
    }

    public function get_per_page(){
      return $this->per_page;
  }
    public function get_current_page(){
      return $this->current_page;
    }

  }


  ?>