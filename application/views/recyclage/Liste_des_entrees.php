<div class="card">
	<div class="card-header bg-white">
		<b class="w-100 border-dark text-right col-md-6 pull-right">
			<input type="date" class="m-0 "id="debut">
			<input type="date" class="m-0 ml-2 "id="fin">
			<a href="#" class=" text-white ml-2 btn btn-success btn-sm" id="show_sortie"><i class="fa fa-tv"></i>&nbsp; AFFICHER</a>
		</b>
	</div>
	<div class="card-body">

		<table class="table table-hover table-strepted " id="dataTable">
			<thead class="bg-<?=$nav_color?> text-white">
				<tr>
					<th>DATE</th>
					<th>MACHINE</th>
					<th>OPERATEUR</th>
					<th>TYPE DE MATIERE</th>
					<th>TYPE DE DECHET</th>
					<th>POIDS</th>
					<th></th>
				</tr>
			</thead>
		</table>
	</div>
</div>