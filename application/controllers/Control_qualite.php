<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Control_qualite extends My_Controller
{
  public function __construct()
  {
    parent::__construct();
    
  }
  public function index()
  {
    $this->render_view('controlleur/accueil');
  }

  public function data_stock_deuxieme_choix(){
    $datas = array();
		$data = array();
		$this->load->model("global");
		$datas = $this->global->select_data_stock_deuxieme_choix();
		foreach ($datas as $row) {

			$sub_array = array();
			$sub_array[] = $row->N_PO;
			$sub_array[] = number_format($row->POIS, 2, ',', ' ');
			$sub_array[] = ($row->STOCK + $row->COUPE) - $row->SORTIE;
			$sub_array[] ="<a href='".$row->N_PO."' class='btn btn-info btn-sm view'><i class='fa fa-info'></i>&nbsp; Détail commande</a>";
			$data[] = $sub_array;
		}
		$output = array(
			"data" => $data
		);
		echo json_encode($output);

  }
  public function data_list_sortie_control_qualite(){
    $this->load->model('sortie_control_qualite');
    $datas = array();
		$data = array();
     
		$datas = $this->sortie_control_qualite->get_date_sortie_control_qualite();
	
		foreach ($datas as $row) {

			$sub_array = array();
			$sub_array[] = $row->CS_DATE;
			$sub_array[] = $row->CS_PO;
			$sub_array[] = $row->CS_DIM;
			$sub_array[] = $row->CS_QTT;
			$sub_array[] = $row->CS_BL;
			$sub_array[] ="<a href='".$row->CS_ID."' class='btn btn-danger btn-sm delete'><i class='fa fa-trash'></i>&nbsp;Supprimer</a>";
			$data[] = $sub_array;
		}
		$output = array(
			"data" => $data
		);
		echo json_encode($output);

  }

 public function save_sortie_control_qualite(){
    $this->load->model("sortie_control_qualite");
    $data = [
        "CS_DATE" => $this->input->post('CS_DATE'),
        "CS_QTT" => $this->input->post('CS_QTT'),
        "CS_DIM" => $this->input->post('CS_DIM'),
        "CS_PO" => $this->input->post('CS_PO'),
        "CS_BL" => $this->input->post('CS_BL'),
        "CS_PERS"=> $this->session->userdata('matricule')
    ];
 return	$this->sortie_control_qualite->insert_data_sortie_control_qualite($data);
 }
 public function delete_sortie_control_qualite(){
  $this->load->model("sortie_control_qualite");
  $refnum = $this->input->post('refnum');
  $param = ["CS_ID"=>$refnum];
  echo $this->sortie_control_qualite->delete_date_sortie_control_qualite($param);
 }
  public function Stock_deuxieme_choix(){
    $this->load->model("commande");
    $data = [
        'type' => $this->commande->type(),
        'type_de_matier' => $this->commande->type_de_matier()
      ];
    $this->render_view('control_qualite/Stock_deuxieme_choix',$data);
  }
  public function Entree(){
    $this->render_view('control_qualite/Entree');
  }
  public function Liste_des_entrees(){
    $this->render_view('control_qualite/Liste_des_entrees');
  }
  public function Sortie(){
    $this->render_view('control_qualite/Sortie');
  }
  public function Liste_des_sorties(){
    $this->render_view('control_qualite/Liste_des_sorties');
  }
}