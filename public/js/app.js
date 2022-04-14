
/* sur changement de la données dans la liste deroulante de ville ...*/
$(document).on('change', '#trip_city', function () {
    /* chargement des lieux de la ville concernée */
    chargerListeLieux();
})

/* fonction permettant de recuperer les lieux en fonction de la ville selectionnée */
function chargerListeLieux() {
    $.ajax({
        method: "POST",
        url: "/sortir/public/ajax/rechercheLieuByVille", /* appel de la fonction rechercheLieuByVille du controlleur AjaxController*/
        data: {
            'city_id': $('#trip_city').val() //recupération de la ville dans la data
        }
    }).done(function (response) {
        $('#trip_place').html(''); //initialisation de la liste des lieux
        //chargement des lieux fournis dans la response, dans la liste deroulante
        for (var i = 0; i < response.length; i++) {
            var lieu = response[i];
            let option = $('<option value="' + place["id"] + '">' + place["name"] + '</option>');
            $('#trip_place').append(option);
        }
    })
}