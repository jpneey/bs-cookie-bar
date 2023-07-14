jQuery( function($) {
    const bs_cookie_name = 'jp_bs_cookie';
    const is_bs_cookie_set = () => {
        return document.cookie.indexOf('jp_bs_cookie=');
    }
    const init_jp_bs_cookie_bar = () => {
        if ( is_bs_cookie_set() === -1 ) {
            $('#bs-cookie-bar').fadeIn()
        }
        $(document).on( 'click', '#bs-cookie-bar-button', function () {
            var now = new Date();
            now.setMonth( now.getMonth() + 6 );
            document.cookie = "jp_bs_cookie=1;expires=" + now.toUTCString() + ";path=/";
            $('#bs-cookie-bar').fadeOut()
        })
    }
    init_jp_bs_cookie_bar()
})