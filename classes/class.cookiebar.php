<?php

class JpBSCookieBar {

    public $page_url;
    public $page_slug;
    public $page_action;
    public $page_cap;
    
    public function __construct()
    {
        $this->init();
        
        add_action( 'admin_menu', array( $this, 'register_menu' ) );
        add_filter( 'plugin_action_links_bs-cookie-bar/bs-cookie-bar.php', array( $this, 'add_settings_link' ) );
    
        add_action( 'admin_post_' . $this->page_action, array( $this, 'save_changes' ));
        add_action( 'jp_bs_notices', array( $this, 'render_notices' ) );
    
        if ( get_option( BS_JP_BAR_ACTIVE, false ) ) {
            add_action( 'wp_footer', array( $this, 'render_cookie_bar' ), 30 );
            add_action( 'wp_enqueue_scripts', array( $this, 'frontend_enqueue' ) );
        }
    
        add_action( 'bs_jp_cookie_text', array( $this, 'render_text' ) );
        add_action( 'bs_jp_cookie_button', array( $this, 'render_button' ) );
    }
    
    public function init()
    {
        $this->page_cap     = 'manage_options';
        $this->page_action  = 'jp_bs_update';
        $this->page_slug    = 'jp-bs-cookie-bar';
        $this->page_url     = add_query_arg(
            'page',
            $this->page_slug,
            get_admin_url() . 'options-general.php'
        );
    }
    
    public function render_cookie_bar()
    {
    
        $layout = get_option( BS_JP_OPTION_LAYOUT, 0 );
        
        switch ( $layout )
        {
            case 4:
                $template = get_template_directory() . '/cookie-bar/cookie-bar-center.php';
                if ( ! file_exists( $template ) ) {
                    $template = BS_JP_PATH . '/template/cookie-bar-center.php';
                }
                break;
            case 3:
                $template = get_template_directory() . '/cookie-bar/cookie-bar-right.php';
                if ( ! file_exists( $template ) ) {
                    $template = BS_JP_PATH . '/template/cookie-bar-right.php';
                }
                break;
            case 2:
                $template = get_template_directory() . '/cookie-bar/cookie-bar-left.php';
                if ( ! file_exists( $template ) ) {
                    $template = BS_JP_PATH . '/template/cookie-bar-left.php';
                }
                break;
            case 1:
            default:
                $template = get_template_directory() . '/cookie-bar/cookie-bar.php';
                if ( ! file_exists( $template ) ) {
                    $template = BS_JP_PATH . '/template/cookie-bar.php';
                }
        }
    
        include_once $template;
    }
    
    public function frontend_enqueue()
    {
        wp_enqueue_style( 'jp-bs-cookie-bar-main', BS_JP_URL . '/asset/bs.min.css', array(), BS_JP_COOKIE_V );
        wp_enqueue_script( 'jp-bs-cookie-bar-main', BS_JP_URL . '/asset/src.min.js', array( 'jquery' ), BS_JP_COOKIE_V, true );
    }
    
    public function register_menu()
    {
        add_submenu_page(
            'options-general.php',
            'Bootstrap Cookie Bar',
            'Cookie bar',
            $this->page_cap,
            $this->page_slug,
            array( $this, 'render_page' ),
            80
        );
    }
    
    public function render_page()
    {
        do_action( 'jp_bs_admin_page' );
    }
    
    public function add_settings_link( $links )
    {
        $settings_link = "<a href='$this->page_url'>" . __( 'Settings' ) . '</a>';
        array_push(
            $links,
            $settings_link
        );
        return $links;
    }
    
    public function render_text()
    {
        $html = "<p>" . get_option( BS_JP_OPTION_TEXT, 'We use cookies to give you the best experience on our website. Cookies are files stored in your browser and are used by most websites to help personalise your web experience. By continuing to use our website, you are agreeing to our use of cookies.') . "</p>";
        echo apply_filters( "bs_jp_cookie_bar_html", $html );
    }
    
    public function render_button()
    {
        $html = sprintf('<a href="#bs-cookie-bar" id="bs-cookie-bar-button" class="button btn btn-primary">%s</a>', get_option( BS_JP_OPTION_BUTTON, 'Accept all'));
        echo apply_filters( "bs_jp_cookie_bar_button", $html );
    }
    
    public function render_notices()
    {
        $type = $_GET['notice'] ?? 0;
        switch ( $type )
        {
            case 4:
                $notice = array(
                    'kind'      => 'error',
                    'message'   => 'You\'re unauthorized to perform this action. Please contact your administrator.'
                );
                break;
            case 3:
                $notice = array(
                    'kind'      => 'error',
                    'message'   => 'Invalid nonce.'
                );
                break;
            case 2:
                $notice = array(
                    'kind'      => 'error',
                    'message'   => 'Something went wrong.'
                );
                break;
            case 1:
            default:
                $notice = array(
                    'kind'      => 'updated',
                    'message'   => 'Settings updated.'
                );
                break;
        }
    
        if ( $type ) {
            echo sprintf( '<div class="notices notice jp-notice %s">%s</div>', $notice['kind'], $notice['message'] );
        }
    }
    
    public function save_changes()
    {
        $text   = $_POST[ BS_JP_OPTION_TEXT ] ?? false;
        $button = $_POST[ BS_JP_OPTION_BUTTON ] ?? false;
        $layout = $_POST[ BS_JP_OPTION_LAYOUT ] ?? false;
        $active = $_POST[ BS_JP_BAR_ACTIVE ] ?? false;
        $notice = 1;
    
        if ( ! current_user_can( $this->page_cap ) ) {
            $this->handle_changes( 4 );
            return;
        }
    
        if ( ! wp_verify_nonce( $_POST['_wpnonce'] ?? 0, $this->page_action ) ) {
            $this->handle_changes( 3 );
            return;
        }
    
        $this->save( BS_JP_OPTION_TEXT, $text );
        $this->save( BS_JP_OPTION_BUTTON, $button );
        $this->save( BS_JP_OPTION_LAYOUT, $layout );
        $this->save( BS_JP_BAR_ACTIVE, $active );
        $this->handle_changes( $notice );
    
        return;
    }
    
    private function save( $key, $value )
    {
        if ( $key && $value )
        {
            update_option( $key, $value );
        } else {
            delete_option( $key );
        }
    }
    
    private function handle_changes( $notice ) {
        $url = add_query_arg(
            array(
                'page'      => $this->page_slug,
                'notice'    => $notice
            ),
            admin_url( 'options-general.php' )   
        );
        wp_safe_redirect( $url );
        exit;
    }
    
}
    
new JpBSCookieBar();