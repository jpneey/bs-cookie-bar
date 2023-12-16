<?php

class JpBSCookieBar_dashboard extends JpBSCookieBar {

    public function __construct()
    {
        
        $this->init();

        add_action( 'jp_bs_admin_page', array( $this, 'render_page' ) );
        add_action( 'jp_bs_panels', array( $this, 'panel_author_note' ), 11 );
        add_action( 'jp_bs_panels', array( $this, 'panel_appearance_form' ), 13 );
        add_action( 'jp_bs_panels', array( $this, 'panel_developers' ), 17 );
        add_action( 'admin_enqueue_scripts', array( $this, 'backend_enqueue' ) );
    }

    public function render_page()
    {
        ?>
        <div class="jp-page">
            <header>
                <div class="container">
                    <h1>Cookie bar <small>v <?php echo BS_JP_COOKIE_V ?></small></h1>
                </div>
            </header>

            <main>
                <div class="container">
                    <?php
                        do_action( 'jp_bs_notices' );
                        do_action( 'jp_bs_panels' );
                    ?>
                </div>
            </main>
        </div>
        <?php
    }

    public function panel_author_note()
    {
        ?>
        <div class="panel">
            <div class="panel-section">
                <h2>Why?</h2>
            </div>
            <p>If you just want to show a cookie bar without all other unnecessary functionality, then this plugin is for you - a light weight and developer friendly cookie bar!</p>
            <p>This plugin only adds around <b>297 bytes</b> of javascript and <b>285 bytes</b> of CSS for a total of <b>~ 1 kb</b> ensuring faster loading times compared to other cookie bar plugins</p>
        </div>
        <?php
    }

    public function panel_appearance_form()
    {
        ?>
        <div class="panel">
            <div class="panel-section">
                <h2>Settings</h2>
            </div>
            <form action="<?php echo admin_url('admin-post.php') ?>" method="POST">

                <label>Cookie text</label>
                <textarea class="input" name="<?php echo BS_JP_OPTION_TEXT ?>" rows="3"><?php echo get_option( BS_JP_OPTION_TEXT, 'We use cookies to give you the best experience on our website. Cookies are files stored in your browser and are used by most websites to help personalise your web experience. By continuing to use our website, you are agreeing to our use of cookies.') ?></textarea>
                
                <div class="jp-row">
                    <div class="jp-col">
                        <label>Button text</label>
                        <input type="hidden" name="action" value="<?php echo $this->page_action ?>" >
                        <input type="text" class="input" name="<?php echo BS_JP_OPTION_BUTTON ?>" placeholder="Accept all" value="<?php echo get_option( BS_JP_OPTION_BUTTON, '') ?>" />
                        <?php wp_nonce_field( $this->page_action ); ?>
                    </div>
                    <div class="jp-col">
                        <label>Cookie bar status</label>
                        <select name="<?php echo BS_JP_BAR_ACTIVE ?>" class="input">
                            <option value="0">Disabled</option>
                            <option value="1" <?php if ( get_option( BS_JP_BAR_ACTIVE ) == 1 ) { echo 'selected'; } ?>>Enabled</option>
                        </select>
                    </div>
                </div>

                <h3>Appearance</h3>

                <div class="jp-row">
                    <div class="jp-col">
                        <div class="jp-preview" style="background-image: url(<?php echo get_template_directory_uri() ?>/screenshot.png);" data-preview="<?php echo get_option( BS_JP_OPTION_LAYOUT, 0 ) ?>">
                            <div class="jp-bar"></div>
                        </div>
                        <select name="<?php echo BS_JP_OPTION_LAYOUT ?>" class="input" id="jp-bs-layout">
                            <option value="0">Default</option>
                            <option value="1" <?php if ( get_option( BS_JP_OPTION_LAYOUT ) == 1 ) { echo 'selected'; } ?>>Full</option>
                            <option value="2" <?php if ( get_option( BS_JP_OPTION_LAYOUT ) == 2 ) { echo 'selected'; } ?>>Widget Left</option>
                            <option value="3" <?php if ( get_option( BS_JP_OPTION_LAYOUT ) == 3 ) { echo 'selected'; } ?>>Widget Right</option>
                        </select>
                    </div>
                </div>

                <input type="submit" name="publish" id="publish" class="button button-primary button-large" value="Save changes"><span class="spinner"></span>
            </form>
        </div>
        <?php
    }

    public function panel_developers()
    {
        ?>
        <div class="panel" id="faqs">
            <div class="panel-section">
                <h2>Developers</h2>
            </div>
            <h3>Template List</h3>
            <p>Template files can be found within the <code>/bs-cookie-bar/template/</code> directory.</p>
            <h3>How to Edit Files</h3>
            <p>Edit files in an <b>upgrade-safe</b> way using <b>overrides</b>. Copy the template into a directory within your theme named <code>/cookie-bar</code> keeping the same file structure.</p>
            <p>Example: To override the main template, copy: <code>wp-content/plugins/bs-cookie-bar/template/cookie-bar.php</code> to <code>wp-content/themes/{yourtheme}/cookie-bar/cookie-bar.php</code></p>
            <p>The copied file will now override the default template file.</p>
            <div class="jp-notice notice warning">Warning: Do not edit these files within the core plugin itself as they are overwritten during the upgrade process and any customizations will be lost</div>
            <h3>Filter Hooks</h3>
            <ul>
                <li><code>bs_jp_cookie_bar_html</code> - Filter the cookie bar text. Parameter <code>$html</code> - raw html text</li>
                <li><code>bs_jp_cookie_bar_button</code> - Filter the cookie bar button(s). Parameter <code>$html</code> - raw html button(s)</li>
            </ul>
            
        </div>
        <?php
    }

    public function backend_enqueue( $hook )
    {
        if ( $hook == 'settings_page_jp-bs-cookie-bar' ) {
            wp_enqueue_style( 'jp-bs-cookie-bar-admin', plugin_dir_url( __FILE__ ) . '../asset/admin.css', array(), BS_JP_COOKIE_V );
            wp_enqueue_script( 'jp-bs-cookie-bar-admin', plugin_dir_url( __FILE__ ) . '../asset/admin.js', array( 'jquery' ), BS_JP_COOKIE_V, true );
        }
    }


}

new JpBSCookieBar_dashboard();