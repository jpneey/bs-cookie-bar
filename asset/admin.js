jQuery( function ($) {
        
    $('[data-preview]').attr( 'data-preview', $('#jp-bs-layout').val() )

    $('form').on( 'submit', function () {
        $(this).addClass( 'submitting' )
    })

    $('#jp-bs-layout').on( 'change', function (){
        $('[data-preview]').attr( 'data-preview', $('#jp-bs-layout').val() )
    })
})