<?php
class machine_model extends CI_Model
{
  public function __construct()
  {
   
  }
  public function get_machine($param){
    return $this->db->where($param)->get('machine')->result_object();
  }
  public function get_detail_machine($param){
    return $this->db->where($param)
    ->order_by("MA_DESIGNATION","ASC")
    ->get('machine')->row_object();
  }
  public function insert_machine($param){
    return $this->db->where($param)->insert('machine');
  }
  public function update_machine($param,$data){
    return $this->db->where($param)->update('machine',$data);
  }
} 