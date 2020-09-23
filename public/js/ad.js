$('#add-image').click(function () {

    //Je récupère le nombre de champ deja existant
    const index = $('#widgets-counter').val();

    //recuperer le prototype des entrées
    const tmpl = $('#ad_images').data('prototype').replace(/__name__/g, index);

    //Injecter le code dans la div
    $('#ad_images').append(tmpl);

    //Increment Value of widget-counter
    $('#widgets-counter').val(Number(index) + 1);

    //call the function delete
    deleteInputImage();
})

function deleteInputImage() {
    $('button[data-action="delete"]').click(function () {
        const target = this.dataset.target;
        $(target).remove();
    })
}

function updateCounter() {
    const count = $('#ad_images div.form-group').length;
    $('#widgets-counter').val(Number(count));
}

updateCounter();
deleteInputImage();