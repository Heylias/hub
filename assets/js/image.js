$('#add-image').click(function(){
    //compter combien j'ai de form-group pour les indices ex: fiction_gallery_0_url
    const index = +$("#widgets-counter").val(); // le + permet de transformer en nombre pcq val() rend tjrs un type string

    // récup le prototype des entrées data-prototype
    const tmpl = $('#fiction_gallery').data('prototype').replace(/__name__/g, index); // drapeau g pour indiquer qu'on va le faire plusieurs fois

    // injecter le code dans la div
    $('#fiction_gallery').append(tmpl);

    $('#widgets-counter').val(index+1);

    // gére le bouton supprimer 

    handleDeleteButtons();

});    

function updateCounter(){
    const count = +$('#fiction_gallery div.form-group').length;

    $('#widgets-counter').val(count);
}


function handleDeleteButtons(){
    $('button[data-action="delete"]').click(function(){
        const target = this.dataset.target; // dataset (les attributs data et je veux le target)
        $(target).remove();
    });
}

updateCounter();
handleDeleteButtons();