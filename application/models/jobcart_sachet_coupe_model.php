<?php
class jobcart_sachet_coupe_model extends CI_Model
{
  public function __construct()
  {
   
  }

  public function get_jobcart_sachet_coupe($param=array()){
    return $this->db->where($param)->get('jobcart_sachet_coupe')->result_object();
  }
  public function get_detail_jobcart_sachet_coupe($param){
    return $this->db->where($param)->get('jobcart_sachet_coupe')->row_object();
    
  }
  public function insert_jobcart_sachet_coupe($data){
    return $this->db->insert('jobcart_sachet_coupe',$data);
  }
  public function update_jobcart_sachet_coupe($param,$data){
    return $this->db->where($param)->update('jobcart_sachet_coupe',$data);
  }

  public function delete_jobcart_sachet_coupe($param){
    return $this->db->where($param)->delete('jobcart_sachet_coupe');
  }
  public function last_insert_id(){
    $data = $this->db->limit(1)
    ->order_by('JO_ID','DESC')
    ->get('jobcart_sachet_coupe')->row_object();
    if($data){
      return $data->JO_ID;
    }else{
     
      return 0;
    }
  }
  public function last_insert_ids($param=array()){
    $data = $this->db->limit(1)
    ->where($param)
    ->order_by('JO_IDS','DESC')
    ->get('jobcart_sachet_coupe')->row_object();
    if($data){
      return $data->JO_IDS;
    }else{
     
      return 0;
    }
  }

}
