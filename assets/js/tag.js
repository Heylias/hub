$('#add-tag').click(function(){
    const index = +$("#widgets-counter-2").val();

    const tmpl = $('#fiction_tags').data('prototype').replace(/__name__/g, index);

    $('#fiction_tags').append(tmpl);

    $('#widgets-counter-2').val(index+1);

    handleDeleteButtons();

});    

function updateCounter(){
    const count = +$('#fiction_tags div.form-group').length;

    $('#widgets-counter-2').val(count);
}


function handleDeleteButtons(){
    $('button[data-action="delete"]').click(function(){
        const target = this.dataset.target;
        $(target).remove();
    });
}

updateCounter();
handleDeleteButtons();