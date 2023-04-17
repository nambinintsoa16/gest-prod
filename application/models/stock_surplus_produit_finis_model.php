<?php
class stock_surplus_produit_finis_model extends CI_Model
{
  public function __construct()
  {
   
  }
  public function get_stock_surplus_produit_finis($param=array()){
    return $this->db->where($param)->get('stock_surplus_produit_finis')->result_object();
  }
  public function get_detail_stock_surplus_produit_finis($param){
    return $this->db->where($param)->get('stock_surplus_produit_finis')->row_object();
    
  }
  public function insert_stock_surplus_produit_finis($data){
    return $this->db->insert('stock_surplus_produit_finis',$data);
  }
  public function update_stock_surplus_produit_finis($param,$data){
    return $this->db->where($param)->update('stock_surplus_produit_finis',$data);
  }
  
}





