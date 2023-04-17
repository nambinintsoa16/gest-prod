<style>
.dataTables_wrapper {
    padding: 2px !important;
}
* {
   scrollbar-width: thin;
   scrollbar-color: #346abf white;
}
  ::-webkit-scrollbar {
    width: 3px;
}

::-webkit-scrollbar-thumb {
  background-color: darkgrey;
  outline: 1px solid slategrey;
}
</style>

<fieldset class="border p-2 bg-white mb-2 text-right">
    <a href="<?= base_url("Planning\print_sachet_extrusion") ?>" class="btn btn-primary btn-sm"><i class="fa fa-print"></i>&nbsp; Imprimer</a>
    <a href="#" class="btn btn-success btn-sm" id="print_job_cart"><i class="fa fa-print"></i>&nbsp; Imprimer Job cart</a>
</fieldset>
<?php foreach ($machine as $key => $machine) : 
    $id_machine = str_replace(" ","_",$machine->MA_DESIGNATION);
    ?>
<div>
    <div class="col-md-12 p-2 bg-<?= $nav_color ?> text-white">
        <span class="col-md-3"><?= $machine->MA_DESIGNATION ?> </span>
        <span>&nbsp;&nbsp;<a href="#" class="text-white">Capacité de la machine :<span id="capacite_<?=$id_machine?>"><?=$machine->CAPACITE?></span></a></span>
        <span>&nbsp;&nbsp;<a href="#" class="text-white">Production prévisionnelle : <span id="previ_<?=$id_machine?>"></span></a></span>
        |<span>&nbsp;&nbsp;<a href="#" class="text-white">Términer : <span id="terminer_<?=$id_machine?>"></span></a></span>
        |<span>&nbsp;&nbsp;<a href="#" class="text-white">Reste à produire : <span id="rest_<?=$id_machine?>"></span></a></span>
        <span class="pull-right" style="font-size:11px">
            <p class="text-right collapsed" data-toggle="collapse" data-target="#tab_<?= $machine->MA_ID ?>"> <i class="fa fa-plus " aria-expanded="false"></i></p>
        </span>
    </div>
    <fieldset class="border p-2 bg-white m-0 mb-1 text-right w-100">
             <a href="#" class="btn btn-success btn-sm btn-update-statu" type="mise_en_prod" machine="<?=$id_machine?>"><i class="icon-wrench"></i> Mise en production</a>
			 <a href="#" class="btn btn-info btn-sm update_date" machine="<?=$id_machine?>"><i class="fa fa-upload"></i> Mettre à jour la date</a>
             <a href="#" class="btn btn-warning btn-sm edit_production" machine="<?=$id_machine?>"><i class="fa fa-edit"></i> Mettre à jour les données</a>
			 <a href="#" class="btn btn-dark btn-sm btn-update-statu" type="terminer" machine="<?=$id_machine?>"><i class="fa fa-check"></i> Términer</a>
			 <a href="#" class="btn btn-danger btn-sm btn-update-statu" type="supprimer" machine="<?=$id_machine?>"><i class="fa fa-times"></i> Supprimer</a>
             <button class="btn btn-primary add_prod  btn-sm" machine="<?=$id_machine?>"><i class="fa fa-plus-circle"></i>&nbsp;Ajouter</button>
    </fieldset>
    <fieldset class="border p-0 bg-white table-responsive">
        <table class="w-100 table-bordered dataTable" id="<?=$id_machine?>">
            <thead class="bg-<?=$nav_color?> text-white">
                <tr>
                    <th>ORDRE</th>
                    <th>JOB_CARD</th>
                    <th>DATE</th>
                    <th>N°PO</th>
                    <th>CLIENT</th>
                    <th>CODE_CLIENT</th>
                    <th>TYPE</th>
                    <th>MAT </th>
                    <th>DIMENSION</th>
                    <th>IMPRESSION</th>
                    <th>ECHANTILLON</th>
                    <th>METRAGE</th>
                    <th>DIMENSION_PROD</th>
                    <th>POIDS</th>
                    <th>POIDS_EN_PROD</th>
                    <th>TERMINER</th>
                    <th>RESTE</th>
                    <th>QUANTITE</th>
                    <th>EXT</th>
                    <th>KGS/H</th>
                    <th>REEL</th>
                    <th>DATE_DEBUT</th>
                    <th>DEBUT</th>
                    <th>DATE_FIN</th>
                    <th>FIN</th>
                    <th>DUREE</th>
                    <th>RESTE_TEMPS</th>
                    <th>OBS</th>
                    <th>EN_PROD</th>
        
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </fieldset>
 </div>
<?php endforeach; ?>