<?php $i = 1;
foreach ($data as $key => $data) : ?>
    <div class="col-md-12 p-0 m-0 mt-2">
        <table class="table-strepted tableProduitFini table-bordered p-0 w-100">
            <thead class="bg-secondary text-white">
                <tr>
                    <td class="choixdates"><?= $data->BC_DATE ?></td>
                    <td class="pos"><?= $data->BC_PE ?></td>
                    <td><?= $data->BC_CLIENT ?></td>
                    <td><?= $data->BC_CODE ?></td>
                    <td><?= $data->BC_DIMENSION ?></td>
                    <?php if ($data->BC_TYPEPRODUIT == "GAINES") : $unite = " | KG"; ?>
                        <td><?= $data->BC_QUNTITE ?> | KG </td>
                    <?php else : $unite = " | KG"; ?>
                        <td><?= $data->BC_QUNTITE ?> | PCS </td>
                    <?php endif; ?>
                    <td class="quantiteEntre"></td>
                    <td style="text-align: center;">
                        <a href="#" class="collapsed" data-toggle="collapse" data-target="#tab_<?= $i ?>"> <i class="fa fa-plus" aria-expanded="false"></i></a>
                    </td>
                </tr>
            </thead>
        </table>
    </div>
    <div class="col-md-12 p-0 m-0">
        <table class="table-bordered table-hover table-strepted w-100">
            <thead class="bg-primary text-white">
                <tr>
                    <!--<td>QUANTITE ENTREE</td>-->
                    <td>TAILLE</td>
                    <td>N°PO SURPLUS</td>
                    <td>QUANTITE SORTIE SURPLUS</td>
                    <td>QUANTITE SORTIE MAGASIN</td>
                    <td>QUANTITE LIVREE</td>
                    <td>RESTE A LIVRE</td>
                    <td>DATE DE LIVRAISON</td>
                    <td>N°BL</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                <?php
                $this->load->model('sortie_produits_finis');
                $this->load->model('sortie_surplus_finis');
                $this->load->model('global');
                $methodOk = $refnum != "";
                $rest_total = 0;
                if ($methodOk) {
                    $param = ['BC_ID' => $refnum];
                }
                $methodOk = $date != "";
                if ($methodOk) {
                    $param = ['SF_DATE' => $date];
                }
                $methodOk = $refnum == "" & $date == "";
                if ($methodOk) {
                    $param = ['SF_DATE' => date('Y-m-d')];
                }
                $sortie_produit_finis = $this->sortie_produits_finis->get_sortie_produits_finis($param);
                foreach ($sortie_produit_finis as $reponse) :
                ?>
                    <tr>
                        <td><?= $reponse->SF_TAILL ?></td>
                        <td></td>
                        <td><?php
                            $surplus_sum = $this->global->get_sum_colum(['SF_DESTINATION' => $reponse->BC_ID, 'SF_DATE' => $reponse->SF_DATE, "SF_BL" => $reponse->SF_BL], "SF_QUANTITE", "sortie_surplus_finis");
                            echo $surplus_sum->SF_QUANTITE;
                            ?></td>

                        <td><?php
                            $produit_fini_sum = $this->global->get_sum_colum(['BC_ID' => $reponse->BC_ID, 'SF_DATE' => $reponse->SF_DATE, "SF_BL" => $reponse->SF_BL], "SF_QUANTITE", "sortie_produits_finis");
                            echo $produit_fini_sum->SF_QUANTITE;
                            ?></td>

                        <td><?= $total_livre = $produit_fini_sum->SF_QUANTITE + $surplus_sum->SF_QUANTITE ?></td>
                        <td><?php 
                         $rest = explode("P", $data->BC_QUNTITE);
                         echo  (int)$rest[0] - (int)$total_livre;
                         $rest_total += (int)$rest[0] - (int)$total_livre;  ?></td>
                        <td><?=$reponse->SF_DATE?></td>
                       
                        <td><?=$reponse->SF_BL?></td>
                        <td></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot class="bg-danger text-white">
                <tr>
                    <td>SOMME</td>
                    <td></td>
                    <td><?= 0 ?></td>
                    <td><?= 0 ?></td>
                    <td><?= 0 ?></td>
                    <td><?=$rest_total ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        <label class="commande collapse"><?= 0 ?></label>
    </div>
<?php $i++;
endforeach ?>