<fieldset class="border w-100 p-2 rounded shadow-sm bg-white">
    <div class="row">
        <div class="form-group col-md-4">
            <label for="client">DATE</label>
            <input type="date" class="form-control form-control-sm date" name="date" id="date">
        </div>

        <div class="form-group col-md-4">
            <label for="client">MACHINES</label>
            <input type="text" class="form-control form-control-sm MACHINES" name="MACHINES" id="MACHINES">
        </div>
        <div class="form-group col-md-4">
            <label for="date_expir">POIDS</label>
            <input type="text" class="form-control form-control-sm POIDS form-control-sm" name="POIDS" id="POIDS">
        </div>
        <div class="form-group col-md-4">
            <label for="date_expir">DECHET SORTIE</label>
            <input type="text" class="form-control form-control-sm POIDSDECHET form-control-sm" name="POIDS" id="POIDS">
        </div>
        <div class="form-group col-md-4">
            <label for="date_expir">TYPE DE DECHET</label>
            <select class="form-control   DECHETS" name="DECHETS" id="DECHETS">
                <option>GAINE</option>
                <option>SACHET</option>
                <option>GAINE IMPRIMER</option>
                <option>SACHET IMPRIMER</option>
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="date_expir">MATIER OBTENUE</label>
            <select class="form-control   MATIEREE" name="MATIEREE" id="MATIEREE">
                <option>LDNATE</option>
                <option>LDGREY</option>

            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="date_expir">OPERATEUR</label>
            <input type="text" class="form-control form-control-sm OPERATEUR form-control-sm" name="OPERATEUR" id="OPERATEUR">
        </div>
        <div class="form-group col-md-12">
            <button type="submt" data-toggle="button" class="btn btn-success pull-right save"> <i class="fa fa-save"></i>&nbsp; ENREGISTRER</button>
            <button type="reset" class="btn btn-danger pull-right mr-2"> <i class="fa fa-times"></i>&nbsp; ANNULER</button>

        </div>

    </div>

</fieldset>