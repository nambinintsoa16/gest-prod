$(document).ready(function() {
    $('#refnum_commande').autocomplete({
        source: base_url + "Commercial/autocomplet_commande",

    });
    $.post(base_url + 'stock/data_historique_livraison', function(data) {
        $('#data_containt').empty().append();
    });

    $('.changeDate').on('click', function() {
        var date = $('.choixdate').val();
        var refnum = $('#refnum_commande').val();
        // chargement();
        $.post(base_url + "stock/data_historique_livraison", { date, refnum }, function(data) {
            $('.print').attr('href', base_url + "Magasiner/printLivraison?date=" + date + "&refnum=" + refnum);
            $('#data_containt').empty().append(data);
            deleteTras();
            // closeDialog();
        });

    });

    function deleteTras() {
        $('.delete').on('click', function(event) {
            event.preventDefault();
            var page = "HISTORIQUE DE LIVRAISON";
            var date = $('.choixdates').text();
            var po = $('.pos').text();
            chargement();
            $.get($(this).attr('href'), function() {
                $.post(base_url + "Magasiner/page", { page: page, date: date, po: po }, function(data) {
                    $('.main').empty().append(data);
                    deleteTras();
                    closeDialog();
                });
            });
        });
    }



    function chargement() {
        var htmls = '<div class="text-center" style="font-size:14px;"><span class="text-center">Traitement en cours ...</span></div><div class="spinner"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div>';
        $.dialog({
            "title": "",
            "content": htmls,
            "show": true,
            "modal": true,
            "close": false,
            "closeOnMaskClick": false,
            "closeOnEscape": false,
            "dynamic": false,
            "height": 150,
            "fixedDimensions": true
        });
    }

    function closeDialog() {
        $('.jconfirm').remove();
    }

});