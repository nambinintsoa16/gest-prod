<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Gaines extends My_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("stock_gaines_plasmad");
		$this->load->model("entree_gaines");
		$this->load->model("sortie_gaines");
		
    }
	public function Stock_gaine(){
		$this->render_view("gaines/stock/Stock");
	}
	public function Entree(){
		$this->render_view("gaines/stock/Entree");
	}
	public function Liste_des_entrees(){
		$this->render_view("gaines/stock/Liste_des_entrees");
	}
	public function Sortie(){
		$this->render_view("gaines/stock/Sortie");
	}
	public function Liste_des_sorties(){
		$this->render_view("gaines/stock/Liste_des_sorties");
	}
	public function autocomplet_taille_gaines(){
		$this->load->model('global');
		$mot = $this->input->get('term');
		$resultat = array();
		$data = $this->global->get_distinct_colum("STF_TAIL like '%$mot%'  LIMIT 10", "STF_TAIL", "stock_gaines_plasmad");
		foreach ($data as $key => $data) {
			$resultat[] = $data->STF_TAIL;
		}
		echo json_encode($resultat);
		
	}

	public function detail_gaines_sortie(){
		$this->load->model('global');
		$refnum_pe = $this->input->post("refnum_pe");
        $reponse =$this->commande->select_commande(["BC_PE"=>$refnum_pe]); 
		$methodOk = $reponse != null;
		$resultat = ['message'=>false];
		if($methodOk){
			$resultat['client']=$reponse->BC_CLIENT;
			$resultat['dim']=$reponse->BC_DIMENSION;
			$resultat['code']=$reponse->BC_CODE;
			$resultat['tail']= $this->global->get_distinct_colum(["BC_ID"=>$reponse->BC_PE],"STF_TAIL AS 'tail'","stock_gaines_plasmad"); 
			
		}
		echo json_encode($resultat);
	}

	public function save_sortie(){
		$date = $this->input->post("date");
		$refnum = $this->input->post("refnum");
		$client = $this->input->post("client");
		$dim = $this->input->post("dim");
		$taille = $this->input->post("taille");
		$quantite = $this->input->post("quantite");
		$obs = $this->input->post("obs");
		$BL = $this->input->post("BL");

		$reponse = $this->stock_gaines_plasmad->get_detail_stock_gaines_plasmad(["BC_ID"=>$refnum,"STF_TAIL"=>$taille]);
		$methodOk = $reponse !=null;
        if($methodOk){
			$reponse->STF_QUANTITE = $reponse->STF_QUANTITE - $quantite;
			$methodOk = $this->stock_gaines_plasmad->update_stock_gaines_plasmad(["STF_ID"=>$reponse->STF_ID],$reponse);
			if($methodOk){
				$data_sortie = [
					"SF_DATE"=>$date, 
					"SF_BL"=>$BL, 
					"SF_MAGASIN"=>$this->session->userdata("matricule"),
					"BC_ID"=>$refnum, 
					"SF_TAILL"=>$taille, 
					"SF_QUANTITE"=>$quantite, 
					"STF_OBSE"=>$obs, 
					"SF_DIM"=>$dim, 
					"SF_CLIENT"=>$client
				];
				$this->sortie_gaines->insert_data_sortie_gaines($data_sortie);
			}
		}


	}
	
	public function save_entree(){
		$date = $this->input->post('date');
		$refnum = $this->input->post('refnum');
		$client = $this->input->post('client');
		$Codeclient = $this->input->post('Codeclient');
		$dim = $this->input->post('dim');
		$taille = $this->input->post('taille');
		$entre = $this->input->post('entre');
		$obs = $this->input->post('obs');
		
        $data_entree = [
			"EF_DATE`"=>$date, 
			"EF_MAGASIN"=>$this->session->userdata('matricule'),
			"BC_ID"=>$refnum, 
			"EF_TAILL"=>$taille, 
			"EF_CLIENT"=>$client,
			"EF_DIM"=>$dim,
			"EF_QUANTITE"=>$entre, 
			"EF_TYPE"=>"entre",
			"EF_REMARQUE"=>"entre_gaine", 
			"STF_OBSE"=>$obs
		];
		$this->entree_gaines->insert_data_entree_gaines($data_entree);
		$reponse = $this->stock_gaines_plasmad->get_detail_stock_gaines_plasmad(["BC_ID"=>$refnum,"STF_TAIL"=>$taille]);
		$methodOk = $reponse !=null;
        if($methodOk){
			$reponse->STF_QUANTITE = $reponse->STF_QUANTITE + $entre;
			echo $this->stock_gaines_plasmad->update_stock_gaines_plasmad(["STF_ID"=>$reponse->STF_ID],$reponse);
		}else{
			$data = [
					"BC_ID"=>$refnum,
					"STF_QUANTITE"=>$entre,
					"STF_TAIL"=>$taille,
					"STF_CLIENT"=>$client,
					"STF_DIM"=>$dim,
					"STF_DATE"=>$date,
					"STF_VALEUR"=>0
					];
		   echo $this->stock_gaines_plasmad->insert_stock_gaines_plasmad($data);
		}
		
	}
	public function data_liste_entre_ganes(){
		$this->load->model('commande');
		$date_debut = $this->input->post('dateDebut');
		$date_fin = $this->input->post('dateFin');
		$methodOk = !empty($dateDebut) && !empty($dateFin);
		if ($methodOk) {
			$datas = $this->entree_gaines->get_date_entree_gaines(("EF_DATE BETWEEN $date_debut AND $date_fin"));
		} else {
			
			$datas = $this->entree_gaines->get_date_entree_gaines();
		}

		$data = array();
		foreach ($datas as $row) {
			if($row->EF_QUANTITE !=0){
			$sub_array = array();
			$sub_array[] = $row->EF_ID;
			$sub_array[] = $row->EF_DATE;
			$sub_array[] = $row->EF_MAGASIN;
			$sub_array[] = $row->BC_ID;
			$sub_array[] = $row->EF_TAILL;
			$sub_array[] = $row->EF_QUANTITE;
			$commande = $this->commande->select_commande(['BC_PE' => $row->BC_ID]);
            $commande ? $sub_array[] =	 $commande->BC_PRIX : $sub_array[] = 0;
			$sub_array[] = $row->STF_OBSE;
			$sub_array[] = "<a href='$row->BC_ID' class='btn btn-info infoProduit btn-sm'><i class='fa fa-info'><i/></a>";
			$data[] = $sub_array;
		}
		}
		$output = array(
			"data" => $data
		);
		echo json_encode($output);
	}

	public function data_liste_sortie_ganes(){
		$this->load->model('commande');
		$date_debut = $this->input->post('dateDebut');
		$date_fin = $this->input->post('dateFin');
		$methodOk = !empty($dateDebut) && !empty($dateFin);
		if ($methodOk) {
			$datas = $this->sortie_gaines->get_date_sortie_gaines(("SF_DATE BETWEEN $date_debut AND $date_fin"));
		} else {
			
			$datas = $this->sortie_gaines->get_date_sortie_gaines();
		}

		$data = array();
		foreach ($datas as $row) {
			if($row->SF_QUANTITE !=0){
			$sub_array = array();
			$sub_array[] = $row->SF_ID;
			$sub_array[] = $row->SF_DATE;
			$sub_array[] = $row->SF_MAGASIN;
			$sub_array[] = $row->BC_ID;
			$sub_array[] = $row->SF_TAILL;
			$sub_array[] = $row->SF_QUANTITE;
			$sub_array[] = $row->SF_BL;
			$commande = $this->commande->select_commande(['BC_PE' => $row->BC_ID]);
            $commande ? $sub_array[] =	 $commande->BC_PRIX : $sub_array[] = 0;
			$sub_array[] = $row->STF_OBSE;
			$sub_array[] = "<a href='$row->BC_ID' class='btn btn-info infoProduit btn-sm'><i class='fa fa-info'><i/></a>";
			$data[] = $sub_array;
		}
		}
		$output = array(
			"data" => $data
		);
		echo json_encode($output);
	}
	public function data_liste_stock_ganes(){

		$date_debut = $this->input->post('dateDebut');
		$date_fin = $this->input->post('dateFin');
		$methodOk = !empty($dateDebut) && !empty($dateFin);
		if ($methodOk) {
			$datas = $this->stock_gaines_plasmad->get_stock_gaines_plasmad(("STF_DATE BETWEEN $date_debut AND $date_fin"));
		} else {
			
			$datas = $this->stock_gaines_plasmad->get_stock_gaines_plasmad();
		}


		$data = array();
		foreach ($datas as $row) {
			if($row->STF_QUANTITE !=0){
			$sub_array = array();
			$sub_array[] = $row->STF_DATE;
			$sub_array[] = $row->BC_ID;
			$sub_array[] = $row->STF_CLIENT;
			$sub_array[] = $row->STF_DIM;
			$sub_array[] = $row->STF_TAIL;
			$sub_array[] = $row->STF_QUANTITE;
			$commande = $this->commande->select_commande(['BC_PE' => $row->BC_ID]);
            $commande ? $sub_array[] =	 $commande->BC_PRIX : $sub_array[] = 0;
			$sub_array[] = "<a href='$row->BC_ID' class='btn btn-info infoProduit btn-sm'><i class='fa fa-info'><i/></a>";
			$data[] = $sub_array;
		}
		}
		$output = array(
			"data" => $data
		);
		echo json_encode($output);
	}



	
}