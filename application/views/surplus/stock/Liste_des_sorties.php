<fieldset class="col-md-12 border mt-2 w-100 pt-3 bg-white">
    <b class="pull-right">
        <input type="date" name="" id="date_debut"> 
        <input type="date" name="" id="date_fin">
        <button class="btn btn-success btn-sm mb-1" id="affiche_data"><i class="fa fa-tv"></i> Afficher</button>
    </b>    
</fieldset>

<fieldset class="col-md-12 border m-0 p-0 mt-2 w-100 pt-3 bg-white">
    <table class=" table-sm table-bordered table-hover table-strepted  w-100 m-0" id="table_liste_sortie">
        <thead class="bg-<?= $nav_color ?> text-white">
            <tr>
                <th>ID</th>
                <th>DATE</th>
                <th>PO</th>
                <th>MAGASINIER</th>
                <th>BL</th>
                <th>TAILLE</th>
                <th>QTT</th>
                <th>DESTINATION</th>
                <th>OBSERVATIION</th>
            </tr>
        </thead>
        <tbody>

        </tbody>

    </table>
</fieldset>