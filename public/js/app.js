$("#sortie_ville").change(function () {
    var villeSelector = $(this).val();
    var ville = $(this);

    $.ajax({
        url: '/gestion_sorties/public/sortie/lieux',
        type: "GET",
        dataType: "JSON",
        data: {villeid:villeSelector},
        async : true,

        success: function (lieux) {
            console.log(lieux);
            var lieuSelect = $("#sortie_lieu");

            lieuSelect.html('');

            lieuSelect.append('<option value> Selectionner lieu de ' + ville.find("option:selected").text() + ' ...</option>');

            $.each(lieux, function (key, lieu) {
                lieuSelect.append('<option value="' + lieu.id + '">' + lieu.nom + '</option>');
            });
        },
        error: function (err) {
            alert("Une erreur est survenue ...");
        }
    });
});
