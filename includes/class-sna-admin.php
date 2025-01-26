<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class SNA_Admin {
    public static function init() {
        add_action( 'admin_menu', [ __CLASS__, 'register_menu' ] );
        add_action( 'wp_ajax_sna_update_status', [ __CLASS__, 'update_status' ] );
        add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_admin_assets' ] );
    }

    public static function register_menu() {
        add_menu_page(
            __( 'Cadastros SNA', 'cadastro-usuarios-sna' ),
            __( 'Cadastros SNA', 'cadastro-usuarios-sna' ),
            'edit_others_posts',
            'sna-cadastros',
            [ __CLASS__, 'render_admin_page' ],
            'dashicons-admin-users',
            25
        );
    }

    public static function render_admin_page() {
        require SNA_PLUGIN_DIR . 'templates/admin-list.php';
    }

    public static function enqueue_admin_assets() {
        // Registrar estilo para a página de administração
        wp_enqueue_style( 'sna-admin-style', SNA_PLUGIN_URL . 'assets/css/admin-style.css', [], '1.0' );
    }

    public static function update_status() {
        // Validar permissões e entrada
        if ( ! current_user_can( 'edit_others_posts' ) || ! isset( $_POST['id'], $_POST['status'] ) ) {
            wp_send_json_error( __( 'Permissão negada ou dados inválidos.', 'cadastro-usuarios-sna' ) );
        }

        $id = intval( $_POST['id'] );
        $status = sanitize_text_field( $_POST['status'] );

        if ( SNA_Cadastro::update_status( $id, $status ) ) {
            wp_send_json_success( __( 'Status atualizado com sucesso!', 'cadastro-usuarios-sna' ) );
        }

        wp_send_json_error( __( 'Falha ao atualizar status.', 'cadastro-usuarios-sna' ) );
    }
}
