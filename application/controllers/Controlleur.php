<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Controlleur extends My_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->library('pdf');
    $this->load->library('SimpleXLSX');
    $this->load->model('stock_matier_premier');
    $this->load->model('entree_matiere_premiere');
  }
  public function index()
  {
    $this->render_view('controlleur/accueil');
  }
  public function createUser()
  {
    $this->load->model('user_fonction');
    $fonction = $this->user_fonction->get_user_fonction_all();
    $data = ["fonction" => $fonction];
    $this->render_view('controlleur/utilisateur/form_create_user', $data);
  }


  public function create_function_form()
  {
    return $this->load->view("controlleur/utilisateur/form_create_fonction");
  }
  public function create_fonction()
  {
    $this->load->model('user_fonction');
    $fact = $this->input->post('fonction');
    $data = ["foct_designation" => $fact];
    echo $this->user_fonction->insert_user_fonction($data);
  }
  public function delete_user()
  {
    $this->load->model("users");
    $refnum = $this->input->post('refnum');
    echo $this->users->delete_utilisateur(["id" => $refnum]);
  }

  public function listUsers()
  {
    $this->render_view('controlleur/utilisateur/form_liste_users');
  }
  public function data_list_users()
  {
    $this->load->model("users");
    $datas = $this->users->get_utilisateur_data();
    $data = array();
    foreach ($datas as $row) {

      $sub_array = array();
      $sub_array[] = $row->matricule;
      $sub_array[] = $row->nom;
      $sub_array[] = $row->prenom;
      $sub_array[] = $row->societe;
      $sub_array[] = $row->fonction_users;
      $sub_array[] = "<a id='$row->id' href='#' class='btn btn-danger user_delete btn-sm'><i class='fa fa-trash'></i>&nbsp;Supprimer</a>";
      $data[] = $sub_array;
    }
    $output = array(
      "data" => $data
    );
    echo json_encode($output);
  }
  public function create_user()
  {
    $this->load->model("users");
    $nom = $this->input->post("nom");
    $prenom = $this->input->post("prenom");
    $matricule = $this->input->post("matricule");
    $refnum_fonction = $this->input->post("fonction_users");
    $societe = $this->input->post("societe");
    $fonction = $this->input->post("fonction");
    $data =  [
      "matricule" => $matricule,
      "nom" => $nom,
      "prenom" => $prenom,
      "societe" => $societe,
      "fonction_users" => $fonction,
      "mot_de_passe" => "0000",
      "fonction" => $refnum_fonction,
      "status" => "Actif"
    ];
    echo $this->users->insert_utilisateur($data);
  }
  public function suivie_machine_extrusion()
  {
    $this->load->model('machine');
    $param = [
      "MA_SPECIFIQUE" => "EXTRUSION",
      "MA_STATUT" => "on"
    ];
    $data = [
      "machine" => $this->machine->get_machine($param)
    ];
    $this->render_view("controlleur/suivi_machine/extrusion", $data);
  }
  public function se_to_time($sec)
  {
    return sprintf('%02d:%02d:%02d', floor($sec / 3600), floor($sec / 60 % 60), floor($sec % 60));
  }
  public function time_to_sec($time)
  {
    list($h, $m, $s) = explode(":", $time);

    $seconds = 0;
    $seconds += (intval($h) * 3600);
    $seconds += (intval($m) * 60);
    $seconds += (intval($s));
    return $seconds;
  }
  public function data_suivi_machine_list()
  {
    $this->load->model('global');
    $param = [
      "MA_SPECIFIQUE" => "EXTRUSION",
      "MA_STATUT" => "on"
    ];
    $i = 0;
    $debut = $this->input->get("debut");
    $fin = $this->input->get("fin");
    $data_machine = $this->machine->get_machine($param);

    foreach ($data_machine as  $machine) {
      $sub_array = array();

      if ($debut != "" and $fin != "") {
        $poids = $this->global->get_sum_colum("(EX_DATE BETWEEN '$debut' AND '$fin') AND EX_N_MACH like '$machine->MA_DESIGNATION'", "EX_PDS_SOMME", "sachet_extrusion");
        $deche = $this->global->get_sum_colum("(EX_DATE BETWEEN '$debut' AND '$fin') AND EX_N_MACH like '$machine->MA_DESIGNATION'", "EX_DECHETS", "sachet_extrusion");
        $dure = $this->global->get_somme_time("EX_DUREE", "(EX_DATE BETWEEN '$debut' AND '$fin') AND EX_N_MACH like '$machine->MA_DESIGNATION'", "sachet_extrusion");
      } else if ($debut != "") {
        $poids = $this->global->get_sum_colum(["EX_DATE" => $debut, "EX_N_MACH" => $machine->MA_DESIGNATION], "EX_PDS_SOMME", "sachet_extrusion");
        $deche = $this->global->get_sum_colum(["EX_DATE" => $debut, "EX_N_MACH" => $machine->MA_DESIGNATION], "EX_DECHETS", "sachet_extrusion");
        $dure = $this->global->get_somme_time("EX_DUREE", ["EX_DATE" => $debut, "EX_N_MACH" => $machine->MA_DESIGNATION], "sachet_extrusion");
      } else {
        $debut_show = date("Y-m");
        $poids = $this->global->get_sum_colum("EX_DATE like '$debut_show%' AND EX_N_MACH ='$machine->MA_DESIGNATION'", "EX_PDS_SOMME", "sachet_extrusion");
        $deche = $this->global->get_sum_colum("EX_DATE like '$debut_show%' AND EX_N_MACH ='$machine->MA_DESIGNATION'", "EX_DECHETS", "sachet_extrusion");
        $dure = $this->global->get_somme_time("EX_DUREE", "EX_DATE like '$debut_show%' AND EX_N_MACH ='$machine->MA_DESIGNATION'", "sachet_extrusion");
      }

      if ($poids->EX_PDS_SOMME != 0) {
        $rebut = ($deche->EX_DECHETS * 100) / $poids->EX_PDS_SOMME;
      } else {
        $rebut = 0;
      }
      $i++;
      $sub_array = array();
      $sub_array[] = $machine->MA_DESIGNATION;
      $sub_array[] = $poids->EX_PDS_SOMME != NULL ? $poids->EX_PDS_SOMME : 0;
      $sub_array[] = $deche->EX_DECHETS != NULL ? $deche->EX_DECHETS : 0;
      $sub_array[] = number_format($rebut, '2');
      $sub_array[] = $dure->format_heure != NULL ? $dure->format_heure : "00:00:00";
      $data[] = $sub_array;
    }
    $output = array(
      "data" => $data
    );
    echo json_encode($output);
  }
  public function data_suivi_machine_impression_list()
  {
    $this->load->model('global');
    $param = [
      "MA_SPECIFIQUE" => "IMPRESSION_EXTRUSION",
      "MA_STATUT" => "on"
    ];
    $i = 0;
    $debut = $this->input->get("debut");
    $fin = $this->input->get("fin");
    $data_machine = $this->machine->get_machine($param);


    foreach ($data_machine as  $machine) {
      if ($debut != "" and $fin != "") {
        $poids = $this->global->get_sum_colum("(EI_DATE BETWEEN '$debut' AND '$fin') AND EI_MACH like '$machine->MA_DESIGNATION'", "EI_PDS_SOMME", "sachet_impression");
        $deche = $this->global->get_sum_colum("(EI_DATE BETWEEN '$debut' AND '$fin') AND EI_MACH like '$machine->MA_DESIGNATION'", "EI_METRE_SOMME", "sachet_impression");
        $dure = $this->global->get_somme_time("EI_DUREE", "(EI_DATE BETWEEN '$debut' AND '$fin') AND EI_MACH like '$machine->MA_DESIGNATION'", "sachet_impression");
      } else if ($debut != "") {
        $poids = $this->global->get_sum_colum(["EI_DATE" => $debut, "EI_MACH" => $machine->MA_DESIGNATION], "EI_PDS_SOMME", "sachet_impression");
        $deche = $this->global->get_sum_colum(["EI_DATE" => $debut, "EI_MACH" => $machine->MA_DESIGNATION], "EI_METRE_SOMME", "sachet_impression");
        $dure = $this->global->get_somme_time("EI_DUREE", ["EI_DATE" => $debut, "EI_MACH" => $machine->MA_DESIGNATION], "sachet_impression");
      } else {
        $debut_show = date("Y-m");
        $poids = $this->global->get_sum_colum("EI_DATE like '$debut_show%' AND EI_MACH ='$machine->MA_DESIGNATION'", "EI_PDS_SOMME", "sachet_impression");
        $deche = $this->global->get_sum_colum("EI_DATE like '$debut_show%' AND EI_MACH ='$machine->MA_DESIGNATION'", "EI_METRE_SOMME", "sachet_impression");
        $dure = $this->global->get_somme_time("EI_DUREE", "EI_DATE like '$debut_show%' AND EI_MACH ='$machine->MA_DESIGNATION'", "sachet_impression");
      }
      $sub_array = array();
      $sub_array[] = $machine->MA_DESIGNATION;
      $sub_array[] = $poids->EI_PDS_SOMME != NULL ? $poids->EI_PDS_SOMME : 0;
      $sub_array[] = $deche->EI_METRE_SOMME != NULL ? $deche->EI_METRE_SOMME : 0;
      $sub_array[] = $dure->format_heure != NULL ? $dure->format_heure : "00:00:00";
      $data[] = $sub_array;
    }
    $output = array(
      "data" => $data
    );
    echo json_encode($output);
  }
  public function data_suivi_machine_coupe_list()
  {
    $this->load->model('global');
    $param = [
      "MA_SPECIFIQUE" => "COUPE_EXTRUSION",
      "MA_STATUT" => "on"
    ];
    $i = 0;
    $debut = $this->input->get("debut");
    $fin = $this->input->get("fin");
    $data_machine = $this->machine->get_machine($param);


    foreach ($data_machine as  $machine) {
      if ($debut != "" and $fin != "") {
        $poids = $this->global->get_sum_colum("(ED_DATE BETWEEN '$debut' AND '$fin') AND ED_MACHINE like '$machine->MA_DESIGNATION'", "ED_PIOD_ENTRE_SOMME", "sachet_coupe");
        $mettrage = $this->global->get_sum_colum("(ED_DATE BETWEEN '$debut' AND '$fin') AND ED_MACHINE like '$machine->MA_DESIGNATION'", "ED_METRAGE_SOMME", "sachet_coupe");
        $dure = $this->global->get_somme_time("ED_DURE", "(ED_DATE BETWEEN '$debut' AND '$fin') AND ED_MACHINE like '$machine->MA_DESIGNATION'", "sachet_coupe");
        $piece = $this->global->get_sum_colum("(ED_DATE BETWEEN '$debut' AND '$fin') AND ED_MACHINE like '$machine->MA_DESIGNATION'", "ED_1ER_CHOIX_SOMME", "sachet_coupe");
        $deche = $this->global->get_sum_colum("(ED_DATE BETWEEN '$debut' AND '$fin') AND ED_MACHINE like '$machine->MA_DESIGNATION'", "ED_DECHE_COUPE", "sachet_coupe");
      } else if ($debut != "") {
        $poids = $this->global->get_sum_colum(["ED_DATE" => $debut, "ED_MACHINE" => $machine->MA_DESIGNATION], "ED_PIOD_ENTRE_SOMME", "sachet_coupe");
        $mettrage = $this->global->get_sum_colum(["ED_DATE" => $debut, "ED_MACHINE" => $machine->MA_DESIGNATION], "ED_METRAGE_SOMME", "sachet_coupe");
        $dure = $this->global->get_somme_time("ED_DURE", ["ED_DATE" => $debut, "ED_MACHINE" => $machine->MA_DESIGNATION], "sachet_coupe");
        $piece = $this->global->get_sum_colum(["ED_DATE" => $debut, "ED_MACHINE" => $machine->MA_DESIGNATION], "ED_1ER_CHOIX_SOMME", "sachet_coupe");
        $deche = $this->global->get_sum_colum(["ED_DATE" => $debut, "ED_MACHINE" => $machine->MA_DESIGNATION], "ED_DECHE_COUPE", "sachet_coupe");
      } else {
        $debut_show = date("Y-m");
        $poids = $this->global->get_sum_colum("ED_DATE like '$debut_show%' AND ED_MACHINE ='$machine->MA_DESIGNATION'", "ED_PIOD_ENTRE_SOMME", "sachet_coupe");
        $mettrage = $this->global->get_sum_colum("ED_DATE like '$debut_show%' AND ED_MACHINE ='$machine->MA_DESIGNATION'", "ED_METRAGE_SOMME", "sachet_coupe");
        $piece = $this->global->get_sum_colum("ED_DATE like '$debut_show%' AND ED_MACHINE ='$machine->MA_DESIGNATION'", "ED_1ER_CHOIX_SOMME", "sachet_coupe");
        $dure = $this->global->get_somme_time("ED_DURE", "ED_DATE like '$debut_show%' AND ED_MACHINE ='$machine->MA_DESIGNATION'", "sachet_coupe");
        $deche = $this->global->get_sum_colum("ED_DATE like '$debut_show%' AND ED_MACHINE ='$machine->MA_DESIGNATION'", "ED_DECHE_COUPE", "sachet_coupe");
      }
      $sub_array = array();
      $sub_array[] = $machine->MA_DESIGNATION;
      $sub_array[] = $poids->ED_PIOD_ENTRE_SOMME != NULL ? $poids->ED_PIOD_ENTRE_SOMME : 0;
      $sub_array[] = $mettrage->ED_METRAGE_SOMME != NULL ? $mettrage->ED_METRAGE_SOMME : 0;
      $sub_array[] = $piece->ED_1ER_CHOIX_SOMME != NULL ? $piece->ED_1ER_CHOIX_SOMME : 0;
      $sub_array[] = $deche->ED_DECHE_COUPE != NULL ? $deche->ED_DECHE_COUPE : 0;
      $sub_array[] = $dure->format_heure != NULL ? $dure->format_heure : "00:00:00";
      $data[] = $sub_array;
    }
    $output = array(
      "data" => $data
    );
    echo json_encode($output);
  }
  public function data_list_use_matiere_print()
  {
    $this->load->model("matiere_impression_use");
    $param = array();
    $refnum = $this->input->get("refnum");
    if ($refnum != "") {
      $param = ["MI_PO" => $refnum];
    }
    $datas = $this->matiere_impression_use->get_matiere_impression_use($param);
    $data = array();
    foreach ($datas as $row) {
      $sub_array = array();
      $sub_array[] = $row->MI_DATE;
      $sub_array[] = $row->MI_DESIGNATION;
      $sub_array[] = $row->MI_QUANTITE;
      $sub_array[] = $row->MI_PRIX;
      $sub_array[] = "<a href='#' id='$row->MI_ID' class='btn btn-danger btn-sm delete_Imprim_matiere'><i class='fa fa-trash'></i>&nbsp; Supprimer</a>";
      $data[] = $sub_array;
    }
    $output = array(
      "data" => $data
    );
    echo json_encode($output);
  }
  public function statu_commande_data_list()
  {
    $this->load->model("commande");
    $this->load->model("global");
    $debut = $this->input->get('debut');
    $fin  = $this->input->get('fin');

    $data = array();
    if ($debut != "" && $fin != "") {
      $datas = $this->commande->select_commande_all("(BC_DATE BETWEENd '$debut' AND '$fin') ORDER BY BC_DATE ASC");
    } else if ($debut != "") {
      $datas = $this->commande->select_commande_all("BC_DATE = '$debut' ORDER BY BC_DATE ASC");
    } else {
      $debut = date('Y-m');
      $datas = $this->commande->select_commande_all("BC_DATE like '%$debut%' ORDER BY BC_DATE ASC");
    }

    foreach ($datas as $row) {
      $sub_array = array();
      $bg = "bg-danger";
      $porcent_extru = 0;
      $sub_array[] = $row->BC_PE;
      $sub_array[] = $row->BC_TYPEPRODUIT;
      $sub_array[] = $row->BC_DIMENSION;
      $sub_array[] = $row->BC_QUNTITE;
      $sub_array[] = $row->BC_STATUT;
      $poids_net = $this->global->get_sum_colum([" EX_BC_ID" => $row->BC_PE], "EX_PDS_NET", "sachet_extrusion");
      if ($row->BC_POISENKGSAVECMARGE != 0 && $poids_net != null) {
        $porcent_extru = ($poids_net->EX_PDS_NET * 100) / (float)$row->BC_POISENKGSAVECMARGE;
      }
      if ($porcent_extru < 15 and  0 > $porcent_extru) {
        $bg = "bg-warning";
      } else if ($porcent_extru > 14) {
        $bg = "bg-success";
      }
      $sub_array[] = '<div class="progress">
      <div class="progress-bar ' . $bg . '" role="progressbar"
        style="width: ' . number_format($porcent_extru, 2) . '%" aria-valuenow="25"
        aria-valuemin="0" aria-valuemax="100">' . number_format($porcent_extru, 2) . '%</div>
    </div>';


      $poide_impression = $this->global->get_sum_colum(["BC_ID" => $row->BC_PE], "EI_PDS_SOMME", "sachet_impression");
      if ($row->BC_POISENKGSAVECMARGE != 0) {
        $porcent_impression = ($poide_impression->EI_PDS_SOMME * 100) / (float)$row->BC_POISENKGSAVECMARGE;
      }
      if ($porcent_impression < 15 and  0 > $porcent_impression) {
        $bg = "bg-warning";
      } else if ($porcent_impression > 14) {
        $bg = "bg-success";
      }
      $sub_array[] = '<div class="progress">
    <div class="progress-bar ' . $bg . '" role="progressbar"
      style="width: ' . number_format($porcent_impression, 2) . '%" aria-valuenow="25"
      aria-valuemin="0" aria-valuemax="100">' . number_format($porcent_impression, 2) . '%</div>
  </div>';

      $sub_array[] = "TEST";
      $st_produit_fini = $this->global->get_sum_colum(["BC_ID" => $row->BC_PE], "STF_QUANTITE", "stock_produits_finis_plasmad");
      $sub_array[] = $st_produit_fini->STF_QUANTITE;
      $st_surplus = $this->global->get_sum_colum(["BC_ID" => $row->BC_PE], "STF_QUANTITE", "stock_surplus_produit_finis");
      $sub_array[] = $st_surplus->STF_QUANTITE;
      $data[] = $sub_array;
    }
    $output = array(
      "data" => $data
    );
    echo json_encode($output);
  }
  public function data_list_suivi_commande()
  {
    $this->load->model("global");
    $debut = $this->input->get('debut');
    $fin = $this->input->get('fin');
    $datas = array();
    if (!empty($debut)  && !empty($fin)) {
      $datas = $this->commande->select_commande_all("BC_DATE BETWEEN '" . $debut . "' AND '" . $fin . "'");
    } else if (!empty($debut)) {
      $datas = $this->commande->select_commande_all("BC_DATE like '" . $debut . "'");
    } else {
      $datas = $this->commande->select_commande_all("BC_DATE like '" . date('Y-m') . "%'");
    }

    $data = array();
    foreach ($datas as $key => $row) {
      $sub_array = array();

      $poid_net_extrusion = $this->global->get_sum_colum(["EX_BC_ID" => $row->BC_PE], "EX_PDS_NET", "sachet_extrusion");
      $poid_somme_extrusion = $this->global->get_sum_colum(["EX_BC_ID" => $row->BC_PE], "EX_PDS_SOMME", "sachet_extrusion");
      $poid_somme_impression = $this->global->get_sum_colum(["BC_ID" => $row->BC_PE], "EI_PDS_SOMME", "sachet_impression");
      $poid_entre_coupe = $this->global->get_sum_colum(["BC_ID" => $row->BC_PE], "ED_PIOD_ENTRE_SOMME", "sachet_coupe");
      $total_sortie_matiere = $this->global->get_sum_colum(["SM_REFERENCE" => $row->BC_PE], "SM_QUANTITE", "sortie_matiere_premiere");

      $sub_array[] =  $row->BC_PE;
      $sub_array[] =  $row->BC_TYPEPRODUIT;
      $sub_array[] = number_format($total_sortie_matiere->SM_QUANTITE, 2, ',', ' ');
      $sub_array[] = number_format($poid_net_extrusion->EX_PDS_NET, 2, ',', ' ');
      $sub_array[] = number_format($poid_net_extrusion->EX_PDS_NET - $poid_net_extrusion->EX_PDS_NET, 2, ',', ' ');
      $sub_array[] = number_format($poid_somme_extrusion->EX_PDS_SOMME, 2, ',', ' ');
      $sub_array[] = number_format($poid_somme_impression->EI_PDS_SOMME, 2, ',', ' ');
      $sub_array[] = number_format($poid_somme_extrusion->EX_PDS_SOMME - $poid_somme_impression->EI_PDS_SOMME, 2, ',', ' ');
      $sub_array[] = $poid_somme_impression->EI_PDS_SOMME != null && $poid_somme_impression->EI_PDS_SOMME  != 0 ? number_format($poid_somme_impression->EI_PDS_SOMME, 2, ',', ' ') : number_format($poid_somme_extrusion->EX_PDS_SOMME, 2, ',', ' ');
      $sub_array[] = number_format($poid_entre_coupe->ED_PIOD_ENTRE_SOMME, 2, ',', ' ');
      $sub_array[] = $poid_somme_impression->EI_PDS_SOMME != null && $poid_somme_impression->EI_PDS_SOMME  != 0 ? number_format($poid_somme_impression->EI_PDS_SOMME - $poid_entre_coupe->ED_PIOD_ENTRE_SOMME, 2, ',', ' ') : number_format($poid_somme_extrusion->EX_PDS_SOMME - $poid_entre_coupe->ED_PIOD_ENTRE_SOMME, 2, ',', ' ');
      $data[] = $sub_array;
    }
    $output = array(
      "data" => $data
    );
    echo json_encode($output);
  }
  public function modif_list_matier_en_attent_de_validation()
  {
    $this->load->model('validation_matiere');
    $refnum = $this->input->get("refnum");
    $param = ["ID_MAV" => $refnum];

    $datas = $this->validation_matiere->get_validation_matiere($param);
    $data = array();
    foreach ($datas as $row) {

      $sub_array = array();
      $sub_array[] = $row->ID_MAV;
      $sub_array[] = $row->DES_MIAV;
      $sub_array[] = $row->QTT_MIAV;
      $sub_array[] = $row->PRIX_MIAV;
      $sub_array[] = '<a href="' . $row->ID_MAV . '" class="btn btn-danger btn-sm valider m-auto"><i class="fa fa-trash"></i>&nbsp;Supprimer</a>';
      $data[] = $sub_array;
    }
    $output = array(
      "data" => $data
    );
    echo json_encode($output);
  }
  public function data_list_matier_en_attent_de_validation()
  {
    $this->load->model('validation_matiere');
    $this->load->model('matiere_detail_attent_validation');
    $refnum = $this->input->post("refnum");
    $param = ["STATUT_MAV" => "NON VALIDER"];
    if ($refnum != "") {
      $param = ["STATUT_MAV" => "NON VALIDER", "PO_MAV" => $refnum];
    }
    $datas = $this->validation_matiere->get_validation_matiere($param);
    $data = array();
    foreach ($datas as $row) {
      $sub_array = array();
      $sub_array[] = $row->DATE_MAV;
      $sub_array[] = $row->DEMANDE_MAV;
      $sub_array[] = $row->PO_MAV;
      $sub_array[] = $row->MAC_MAV;
      $sub_array[] = $row->DES_MIAV;
      $sub_array[] = $row->QTT_MIAV;
      $sub_array[] = $row->STATUT_MAV;
      $sub_array[] = '<a id="' . $row->ID_MAV . '" href="#" class="btn btn-primary btn-sm valider"><i class="fa fa-check"></i> Valider</a> &nbsp; <a href="#" id="' . $row->ID_MAV . '" class="btn btn-danger btn-sm delete"><i class="fa fa-trash"></i> Supprimer</a>';
      $data[] = $sub_array;
    }
    $output = array(
      "data" => $data
    );
    echo json_encode($output);
  }
  public function insert_validation_matiere()
  {
    $refnum = $this->input->post("refnum");
    $quantite = $this->input->post("quantite");
    $prix = $this->input->post("prix");
    $matiere = $this->input->post("matiere");
    $machine = $this->input->post("machine");
    $param = [
      "DATE_MAV" => date("Y-m-d"),
      "DEMANDE_MAV" => $this->session->userdata("matricule"),
      "PO_MAV" => $refnum,
      "MAC_MAV" => $machine,
      "DES_MIAV" => $matiere,
      "PRIX_MIAV" => $prix,
      "QTT_MIAV" => $quantite,
      "STATUT_MAV" => "NON VALIDER",
    ];
    echo $this->validation_matiere->insert_validation_matiere($param);
  }
  public function delete_matiere_attent_validation()
  {
    $refnum = $this->input->post("refnum");
    $param = ["ID_MAV" => $refnum];
    echo $this->validation_matiere->delete_validation_matiere($param);
  }
  public function valide_sortie_matiere()
  {
    $this->load->model("sortie_matiere_premiere");
    $this->load->model("validation_matiere");
    $refnum = $this->input->post("refnum");
    $destinataire = $this->input->post("destinataire");

    if ($destinataire == "")
      $destinataire = "PLASMAD";

    $methodOk = false;

    $validation = $this->validation_matiere->get_detail_validation_matiere(["ID_MAV" => $refnum]);
    $methodOk = $validation != null;

    if ($methodOk) {
      $matiere = $this->stock_matier_premier->get_detail_stock_matier_premier(["ST_DESIGNATION" => $validation->DES_MIAV]);
      $methodOk = $matiere != null;
    }


    if ($methodOk) {
      $param = [
        "RF_MATIERE" => $matiere->ST_ID,
        "SM_DATE" => $validation->DATE_MAV,
        "SM_MAGASINIER" => $validation->DEMANDE_MAV,
        "SM_MATIER" => $validation->DES_MIAV,
        "SM_QUANTITE" => $validation->QTT_MIAV,
        "SM_DESTINATAIRE" => $validation->PO_MAV,
        "EM_TYPE" => "SORTIE",
        "SM_VALEUR"=>$validation->PRIX_MIAV,
        "SM_MACHINE" => $validation->MAC_MAV,
        "SM_REFERENCE" => null
      ];
      $methodOk = $this->sortie_matiere_premiere->insert_sortie_matiere_premiere($param);
    }

    if ($methodOk) {
      $quantite = $matiere->ST_QUANTITE - $validation->QTT_MIAV;
      $methodOk = $this->stock_matier_premier->update_stock_matier_premier(["ST_ID" => $matiere->ST_ID], ["ST_QUANTITE" => $quantite]);
    }

    if ($methodOk) {
      echo $this->validation_matiere->update_validation_matiere(["ID_MAV" => $refnum], ["STATUT_MAV" => "VALIDER"]);
    }
  }
  public function data_liste_entre_produit_fini()
  {
    $this->load->model('entree_produits_finis');
    $refnum = $this->input->get('refnum');
    $param = ["BC_ID" => $refnum];
    $datas = $this->entree_produits_finis->get_entree_produits_finis($param);
    $data = array();
    foreach ($datas as $row) {
      $sub_array = array();
      $sub_array[] = $row->EF_ID;
      $sub_array[] = $row->EF_DATE;
      $sub_array[] = $row->EF_MAGASIN;
      $sub_array[] = $row->BC_ID;
      $sub_array[] = $row->EF_QUANTITE;
      $sub_array[] = $row->EF_TAILL;
      $sub_array[] = $row->STF_OBSE;
      $sub_array[] = "<a href='#' class='delete btn btn-sm btn-danger' id='$row->EF_ID'><i class='fa fa-trash'></i>&nbsp;Supprimer</a>";
      $data[] = $sub_array;
    }
    $output = array(
      "data" => $data
    );
    echo json_encode($output);
  }
  public function data_liste_sortie_produit_fini()
  {
    $this->load->model('sortie_produits_finis');
    $refnum = $this->input->get('refnum');
    $param = ["BC_ID" => $refnum];
    $datas = $this->sortie_produits_finis->get_sortie_produits_finis($param);
    $data = array();
    foreach ($datas as $row) {
      $sub_array = array();
      $sub_array[] = $row->SF_ID;
      $sub_array[] = $row->SF_DATE;
      $sub_array[] = $row->SF_MAGASIN;
      $sub_array[] = $row->BC_ID;
      $sub_array[] = $row->SF_QUANTITE;
      $sub_array[] = $row->SF_TAILL;
      $sub_array[] = $row->STF_OBSE;
      $sub_array[] = "<a href='#' class='delete btn btn-sm btn-danger' id='$row->SF_ID'><i class='fa fa-trash'></i>&nbsp;Supprimer</a>";
      $data[] = $sub_array;
    }
    $output = array(
      "data" => $data
    );
    echo json_encode($output);
  }
  public function data_list_sortie_materiel()
  {
    $this->load->model('sortie_matiere_premiere');

    $debut = $this->input->get('dateCost');
    $fin = $this->input->get('dateCostFin');
    $reference = $this->input->get('reference');

    if ($reference != "" && $debut != "" && $fin != "") {
      $sql = "SM_MATIER like '$reference' AND SM_DATE BETWEEN '$debut' AND '$fin'";
    } else if ($reference != "" && $debut != "") {
      $sql = "SM_MATIER like '$reference'  AND SM_DATE LIKE '$debut' ";
    } else if ($reference != "") {
      $sql = "SM_MATIER like '$reference'";
    } else if ($debut != "" && $fin != "") {
      $sql = "SM_DATE BETWEEN '$debut' AND '$fin'";
    } else if ($debut != "") {
      $sql = "SM_DATE LIKE '$debut' ";
    } else {
      $sql = "1";
    }
    $type = "";
    $datas = $this->sortie_matiere_premiere->get_sortie_matiere_premiere($sql);
    $matiere = array();
    $i = 0;
    foreach ($datas as $key => $datas) {
      if (array_key_exists($datas->SM_MATIER, $matiere)) {
        $matiere[$datas->SM_MATIER]['quantite'] += $datas->SM_QUANTITE;
        $matiere[$datas->SM_MATIER]['PO'] .= " / " . $datas->SM_REFERENCE;
        $i++;
      } else {
        $matiere[$datas->SM_MATIER]['quantite'] = $datas->SM_QUANTITE;
        $matiere[$datas->SM_MATIER]['PO'] = $datas->SM_REFERENCE;
        $i++;
      }
    }
    $data = array();
    foreach ($matiere as $key => $matiere) {
      $sub_array = array();
      $sub_array[] = $key;
      $sub_array[] = $matiere['quantite'];
      $sub_array[] = "KG";
      $sub_array[] = $matiere['PO'];
      $data[] = $sub_array;
    }
    $output = array(
      "data" => $data
    );
    echo json_encode($output);
  }

  public function delete_mouvement_produit_fini()
  {
    $refnum = $this->input->post("refnum");
    $type = $this->input->post("type");
    switch ($type) {
      case 'entree':
        $this->delete_entree_data($refnum);
        break;
      case 'sortie':
        $this->delete_sortie_data($refnum);
        break;
    }
  }

  public function save_entre_produit_fini()
  {
    $this->load->model("entree_produits_finis");
    $this->load->model("stock_produit_finis");
    $this->load->model("commande");
    $date = $this->input->post("date");
    $refnum = $this->input->post("refnum");
    $taille = $this->input->post("taille");
    $entree = $this->input->post("entree");
    $type = $this->input->post("type");
    $obs = $this->input->post("obs");


    $detail_produit = $this->stock_produit_finis->get_detail_stock_produit_finis(["STF_TAIL" => $taille, "BC_ID " => $refnum]);
    $methodOk = $detail_produit != null;

    if ($methodOk) {
      $quantite = $detail_produit->STF_QUANTITE + $entree;
      $methodOk = $this->stock_produit_finis->update_stock_produit_finis(["STF_ID" => $detail_produit->STF_ID], ["STF_QUANTITE" => $quantite]);
    }

    if (!$methodOk) {
      $commande = $this->commande->select_commande(["BC_PE" => $refnum]);
      $methodOk = $commande != null;
      if ($methodOk) {
        $data_insert = [
          "BC_ID" => $refnum,
          "STF_QUANTITE" => $entree,
          "STF_TAIL" => $taille,
          "STF_CLIENT" => $commande->BC_CLIENT,
          "STF_DIM" => $commande->BC_DIMENSION,
          "STF_DATE" => $date,
          "STF_VALEUR" => $commande->BC_PRIX,
          "STF_ORIGIN" => "PLASMAD"
        ];
        $methodOk = $this->stock_produit_finis->insert_stock_produit_finis($data_insert);
      }
    }

    if ($methodOk) {
      $param = [
        "EF_DATE" => $date,
        "EF_MAGASIN" => $this->session->userdata('matricule'),
        "BC_ID" => $refnum,
        "EF_TAILL" => $taille,
        "EF_QUANTITE" => $entree,
        "EF_TYPE" => $type,
        "EF_REMARQUE" => "",
        "STF_OBSE" => $obs
      ];
      $methodOk = $this->entree_produits_finis->insert_entree_produits_finis($param);
    }

    

    echo $methodOk;
  }

  public function delete_entree_data($refnum)
  {
    $this->load->model("entree_produits_finis");
    $this->load->model("stock_produit_finis");
    $entree = $this->entree_produits_finis->get_detail_entree_produits_finis(["EF_ID" => $refnum]);
    $methodOk = $entree != null;
    if ($methodOk) {
      $detail_produit = $this->stock_produit_finis->get_detail_stock_produit_finis(["STF_TAIL" => $entree->EF_TAILL, "BC_ID " => $entree->BC_ID]);
      $methodOk = $detail_produit != null;
      if ($methodOk) {
        $quantite = $detail_produit->STF_QUANTITE;
        $quantite -= $entree->EF_QUANTITE;
        $methodOk = $this->stock_produit_finis->update_stock_produit_finis(["STF_ID" => $detail_produit->STF_ID], ["STF_QUANTITE" => $quantite]);
      }
    }

    if ($methodOk) {
      $this->entree_produits_finis->get_delete_entree_produits_finis(["EF_ID" => $refnum]);
    }
    echo $methodOk;
  }
  public function delete_sortie_data($refnum)
  {
    $this->load->model("sortie_produits_finis");
    $this->load->model("stock_produit_finis");
    $sortie = $this->sortie_produits_finis->get_detail_sortie_produits_finis(["SF_ID" => $refnum]);
    $methodOk = $sortie != null;
    if ($methodOk) {
      $detail_produit = $this->stock_produit_finis->get_detail_stock_produit_finis(["STF_TAIL" => $sortie->SF_TAILL, "BC_ID " => $sortie->BC_ID]);
      $methodOk = $detail_produit != null;
      if ($methodOk) {
        $quantite = $detail_produit->STF_QUANTITE;
        $quantite += $sortie->SF_QUANTITE;
        $methodOk = $this->stock_produit_finis->update_stock_produit_finis(["STF_ID" => $detail_produit->STF_ID], ["STF_QUANTITE" => $quantite]);
      }
    }

    if ($methodOk) {
      $this->sortie_produits_finis->delete_sortie_produits_finis(["SF_ID" => $refnum]);
    }
    echo $methodOk;
  }
  public function array_unique($array, $unique_key)
  {
    if (!is_array($array))
      return $array;

    $unique_keys = array();
    foreach ($array as $key => $items) {
      if (!in_array($items[$unique_key], $unique_keys))
        $unique_keys[$items[$unique_key]] = $items;
    }
    return $unique_keys;
  }
  public function data_daily()
  {
    $this->load->model("global");

    $extrusion = $this->global->select_data_joint_colum($requette = array(), "BC_DATE,BC_DIMENSION,BC_DATELIVRE,BC_LIEULIVRE,BC_CODE,BC_QUNTITE,BC_DATE,BC_PE,BC_DIMENSION, EX_DATE AS 'DATE'", "commande.BC_PE=sachet_extrusion.EX_BC_ID", "sachet_extrusion", "commande");
    $impression = $this->global->select_data_joint_colum($requette = array(), "BC_DATE,BC_DIMENSION,BC_DATELIVRE,BC_LIEULIVRE,BC_CODE,BC_QUNTITE,BC_DATE,BC_PE,BC_DIMENSION, EI_DATE AS 'DATE'", "commande.BC_PE=sachet_impression.BC_ID", "sachet_impression", "commande");
    $coup = $this->global->select_data_joint_colum($requette = array(), "BC_DATE,BC_DIMENSION,BC_DATELIVRE,BC_LIEULIVRE,BC_CODE,BC_QUNTITE,BC_DATE,BC_PE,BC_DIMENSION, ED_DATE AS 'DATE'", "commande.BC_PE=sachet_coupe.BC_ID", "sachet_coupe", "commande");
    $reponse = array_merge($extrusion, $impression, $coup);
    $commande = $this->array_unique($reponse, "BC_PE");
    $data = ["data" => $commande];
    $this->load->view('controlleur/suivi_production/data_daily', $data);
  }
  public function entree_produit_fini()
  {
    $this->render_view("controlleur/suivi_produit_fini/Entree");
  }
  public function Recap_matiere()
  {
    $this->render_view("controlleur/suivi_matiere/Recap_matiere");
  }
  public function Supprimer_transaction()
  {
    $this->render_view("controlleur/suivi_produit_fini/Supprimer_transaction");
  }
  public function Valider_sortie_matiere()
  {
    $this->render_view("controlleur/suivi_matiere/valider_sortie_matiere");
  }
  public function suivie_machine_impression()
  {
    $this->render_view("controlleur/suivi_machine/impression");
  }
  public function suivie_machine_coupe()
  {
    $this->render_view("controlleur/suivi_machine/coupe");
  }
  public function Donnee_de_production()
  {
    $this->render_view("controlleur/Suivi_production/Donnee_de_production");
  }
  public function Daily_production_follow_up()
  {
    $this->render_view("controlleur/Suivi_production/Daily_production_follow_up");
  }
  public function Statut_commande()
  {
    $this->render_view("controlleur/Suivi_production/Statut_commande");
  }
  public function Encres_et_solvants()
  {
    $this->render_view("controlleur/Suivi_production/Encres_et_solvants");
  }
  public function suivi_commande()
  {
    $this->render_view("controlleur/suivi_commande");
  }
}
