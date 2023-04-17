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
    <ul class="nav nav-pills nav-primary  nav-pills-no-bd nav-pills-icons" id="pills-tab-with-icon" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="pills-home-tab-icon" data-toggle="pill" href="#pills-home-icon" role="tab" aria-controls="pills-home-icon" aria-selected="true">
                EXTRUSION
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pills-profile-tab-icon" data-toggle="pill" href="#pills-profile-icon" role="tab" aria-controls="pills-profile-icon" aria-selected="false">
                IMPRESSION
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="pills-contact-tab-icon" data-toggle="pill" href="#pills-contact-icon" role="tab" aria-controls="pills-contact-icon" aria-selected="false">
                COUPE
            </a>
        </li>
    </ul>
</fieldset>
<div class="tab-content mt-2 mb-3" id="pills-with-icon-tabContent">
    <div class="tab-pane fade show active" id="pills-home-icon" role="tabpanel" aria-labelledby="pills-home-tab-icon">
        <?php foreach ($machine as $key => $machine) :
            $id_machine = str_replace(" ", "_", $machine->MA_DESIGNATION);
        ?>
            <div>
                <div class="col-md-12 p-2 bg-<?= $nav_color ?> text-white">
                    <span class="col-md-3"><?= $machine->MA_DESIGNATION ?> </span>
                    <span>&nbsp;&nbsp;<a href="#" class="text-white">Capacité de la machine : <?= $machine->CAPACITE ?></a></span>
                    <span>&nbsp;&nbsp;<a href="#" class="text-white">Production prévisionnelle : </a></span>
                    |<span>&nbsp;&nbsp;<a href="#" class="text-white">Términer : </a></span>
                    |<span>&nbsp;&nbsp;<a href="#" class="text-white">Reste à produire : </a></span>
                    <span class="pull-right" style="font-size:11px">
                        <p class="text-right collapsed" data-toggle="collapse" data-target="#tab_<?= $machine->MA_ID ?>"> <i class="fa fa-plus " aria-expanded="false"></i></p>
                    </span>
                </div>
                <fieldset class="border p-0 bg-white table-responsive">
                    <table class="w-100 table-bordered dataTable" id="<?= $id_machine ?>">
                        <thead class="bg-<?= $nav_color ?> text-white">
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
                                <th>RESTE</th>
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
    </div>
    <div class="tab-pane fade" id="pills-profile-icon" role="tabpanel" aria-labelledby="pills-profile-tab-icon">
        <p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.</p>
        <p>The Big Oxmox advised her not to do so, because there were thousands of bad Commas, wild Question Marks and devious Semikoli, but the Little Blind Text didn’t listen. She packed her seven versalia, put her initial into the belt and made herself on the way.
        </p>
    </div>
    <div class="tab-pane fade" id="pills-contact-icon" role="tabpanel" aria-labelledby="pills-contact-tab-icon">
        <p>Pityful a rethoric question ran over her cheek, then she continued her way. On her way she met a copy. The copy warned the Little Blind Text, that where it came from it would have been rewritten a thousand times and everything that was left from its origin would be the word "and" and the Little Blind Text should turn around and return to its own, safe country.</p>

        <p> But nothing the copy said could convince her and so it didn’t take long until a few insidious Copy Writers ambushed her, made her drunk with Longe and Parole and dragged her into their agency, where they abused her for their</p>
    </div>
</div>