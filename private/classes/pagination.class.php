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

    public function next_link($url=''){
      if ($this->next_page()) {
        $out = "<a href ='".$url;
        $out .= '?page='. $this->next_page()."'";
        $out .= '> Next &raquo</a>';
        return $out;
      }
    }

    public function number_links($url=''){
      $out ='';
      for ($i = 1 ; $i <= $this->total_pages() ; $i++){
        if ($i == $this->get_current_page()){
          $out .= "<span class='selected'>";
          $out .= $i ."</span>";
        }else {
          $out .= "<a href ='" . $url;
          $out .= '?page=' . $i . "'";
          $out .= '> ' . $i . '</a>';
        }
      }
      return $out;
    }

    public function previous_link($url=''){
      if ($this->previous_page()) {
        $out = "<a href ='".$url;
        $out .= '?page='. $this->previous_page()."'";
        $out .= '> &laquo Prev</a>';
        return $out;
      }
    }

    public function links($url=''){
      $out = '';
      if($this->total_pages() > 1) {
        $out = "<div class='pagination' >";
        $out .= $this->next_link($url);
        $out .= $this->number_links($url);
        $out .= $this->previous_link($url);
        $out .= "</div>";
      }

      return $out;
    }

  }


  ?>