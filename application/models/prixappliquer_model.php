<?php
class prixappliquer_model extends CI_Model
{
  public function __construct()
  {
   
  }
  public function get_prix_appliquer($param){
    return $this->db->where($param)->get('prixappliquer')->row_object();
  }
  
} 