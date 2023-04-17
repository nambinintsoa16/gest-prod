<?php
class operateur_model extends CI_Model
{
  public function __construct()
  {
   
  }
  public function get_operateur($param){
    return $this->db->where($param)->get('operateur')->result_object();
  }
  
} 


