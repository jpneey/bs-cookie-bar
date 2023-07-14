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
                    <h1>Bootstrap 5 Cookie bar <small>v <?php echo BS_JP_COOKIE_V ?></small></h1>
                    <div class="notice jp-notice">This plugin is intended for <code>developers</code> and requires a theme that loads the <a href="https://getbootstrap.com/" target="_blank">bootstrap 5.x</a> library.</div>
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
                <h2>Author Notes</h2>
            </div>
            <p>Light and simple cookie bar for bootstrap 5.x powered themes. This plugin <b>does not</b> add any CSS and is <b>meant to be styled and extended per theme.</b></p>
            <p>This uses default boostrap helper classes like <code>position-fixed</code> etc.. as a base and <b>~300 bytes</b> of javascript code.</p>
        </div>
        <?php
    }

    public function panel_appearance_form()
    {
        ?>
        <div class="panel">
            <div class="panel-section">
                <h2>Configuration</h2>
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

                <div class="panel-section has-top">
                    <h2>Appearance</h2>
                </div>

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
            <h4 class="faq" style="margin: 0; padding-bottom: 8px;">Template List</h4>
            <p>Template files can be found within the <code>/bs-cookie-bar/template/</code> directory.</p>
            <h4 class="faq" style="margin: 0; padding-bottom: 8px;">How to Edit Files</h4>
            <p>Edit files in an <b>upgrade-safe</b> way using <b>overrides</b>. Copy the template into a directory within your theme named <code>/cookie-bar</code> keeping the same file structure.</p>
            <p>Example: To override the main template, copy: <code>wp-content/plugins/bs-cookie-bar/template/cookie-bar.php</code> to <code>wp-content/themes/{yourtheme}/cookie-bar/cookie-bar.php</code></p>
            <p>The copied file will now override the default template file.</p>
            <div class="jp-notice notice warning">Warning: Do not edit these files within the core plugin itself as they are overwritten during the upgrade process and any customizations will be lost</div>
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