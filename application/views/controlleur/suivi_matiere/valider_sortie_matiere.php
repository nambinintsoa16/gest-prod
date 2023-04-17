<fieldset class="col-md-12 border m-0 p-0 mt-2 w-100 pt-3 pb-3 bg-white shadow-sm text-right pr-2">
<b><a href="#" class="btn btn-primary add"><i class="icon icon-plus"></i>&nbsp;Nouveau matiere</a></b>
</fieldset>
<fieldset class="col-md-12 border m-0 p-0 mt-2 w-100 pt-3 pb-3 bg-white shadow-sm">
    <table class=" table-sm table-bordered table-hover table-strepted  w-100 m-0" id="DataTable">
        <thead class="bg-<?= $nav_color ?> text-white">
            <tr>
                <th>DATE</th>
                <th>MAGASINER </th>
                <th>RECEPTIONNAIRE</th>
                <th>MACHINE </th>
                <th>ARTICLE</th>
                <th>QTT</th>
                <th>STATUT</th>
                <th></th>
            </tr>
        </thead>
        <tbody>

        </tbody>

    </table>
</fieldset>

<div class="modal fade" id="ajouterProduit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-<?=$nav_color?>">
                <h5 class="modal-title" id="exampleModalLongTitle">AJOUTER NOUVEAU MATIER</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row m-0">
                    <div class="form-group col-md-4">
                        <label>N°PO</label>
                        <input type="text" class="form-control form-control-sm po" id="refnum_commande">
                    </div>
                    <div class="form-group col-md-8">
                        <label>Matiere</label>
                        <input type="text" class="form-control form-control-sm matier">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Machine</label>
                        <input type="text" class="form-control form-control-sm machine">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Quantité</label>
                        <input type="number" class="form-control form-control-sm quanatite">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Prix</label>
                        <input type="number" class="form-control form-control-sm prix" disabled>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="hide_modal">Fermer</button>
                <button type="button" class="btn btn-success addMat">Valider</button>
            </div>
        </div>
    </div>
</div>