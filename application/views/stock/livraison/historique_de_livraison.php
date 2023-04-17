<fieldset class="col-md-12 border mt-2 w-100 p-3 bg-white">
    <b class="pull-right">
        <input type="text" class="mr-3 po" name="po" placeholder="Entre N°PO" id="refnum_commande">
        <input type="date" class="mr-3 choixdate" name="date">
        <button type="submit" class="btn btn-success btn-sm changeDate">AFFICHER</button>
        <a href="<?= base_url("stock/exportentrelivrasoin") ?>" class="btn btn-sm btn-primary"><i class="fa fa-download"></i></a>
        <a href="<?= base_url("stock/printLivraison") ?>" class="btn btn-sm btn-primary print"><i class="fa fa-print"></i>Imprimer</a>
    </b>

</fieldset>
<fieldset class="border w-100 p-0 w-100 pt-3" id="data_containt">
    
</fieldset>