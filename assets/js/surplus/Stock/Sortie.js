$(document).ready(function() {
    $('#refnum').autocomplete({
        source: base_url + "stock/autocomplete_produit_plasmad"
    });

    $('#refnum_surplus').autocomplete({
        source: base_url + "stock/autocomplete_produit_surplus",
        select: function(data, iteme) {
            let refnum_pe = iteme.item.value.trim();
            $.post(base_url + "surplus/detail_commande_surplus", { refnum_pe }, function(data) {
                if (data.mesage == "false") {
                    alertMessage("Erreur", "PO Introvable", "error", "btn btn-danger");
                } else {
                    $('#client').val(data.client);
                    $('#dim').val(data.dim);
                    // $('.code').val(data.code);
                    $('#tail_suplus').empty();
                    data.tail.forEach(element => $('#tail_suplus').append("<option>" + element.tail + "</option>"));
                }
            }, 'json');
        }
    });

    $('#create_sortie_surplus').on('click', function(e) {
        e.preventDefault();
        chargement();
        var refnum = $('#refnum').val();
        var date_entre = $('#date_entre').val();
        var BL = $('#BL').val();
        var obs = $('#obs').val();
        var refnum_surplus = $('#refnum_surplus').val();
        var tail_suplus = $('#tail_suplus option:selected').val();
        var quantite_surplus = $('#quantite_surplus').val();

        $.post(base_url + 'stock/sortie_suplus_fini', { tail_suplus, refnum, quantite_surplus, refnum_surplus, date_entre, BL, obs }, function() {
            $('#tail_suplus').empty();
            $('input, #obs').val('');
            closeDialog();
            alertMessage("Succè!", "Enregistré!", "success", "btn btn-success");


        });

    })

    function alertMessage(title, message, icons, btn) {
        swal(title, message, {
            icon: icons,
            buttons: {
                confirm: {
                    className: btn
                }
            },
        });

    }

    function chargement() {
        var htmls = '<style> .spinner div{background-color:#6861ce;}</style><div class="text-center" style="font-size:14px;color:#6861ce;"><span class="text-center">Traitement en cours ...</span></div><div class="spinner color-secondary"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div>';
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