<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class SNA_Frontend {
    public static function init() {
        add_shortcode( 'sna_form', [ __CLASS__, 'render_form' ] );
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
        add_action( 'wp_ajax_sna_submit_form', [ __CLASS__, 'handle_form_submission' ] );
        add_action( 'wp_ajax_nopriv_sna_submit_form', [ __CLASS__, 'handle_form_submission' ] );
    }

    public static function render_form() {
        ob_start();
        require SNA_PLUGIN_DIR . 'templates/frontend-form.php';
        return ob_get_clean();
    }

    public static function enqueue_scripts() {
        // Registrar script de validação do front-end
        wp_enqueue_script( 'sna-validator', SNA_PLUGIN_URL . 'assets/js/frontend-validator.js', [ 'jquery' ], '1.0', true );

        // Registrar estilo do formulário
        wp_enqueue_style( 'sna-frontend-style', SNA_PLUGIN_URL . 'assets/css/frontend-style.css', [], '1.0' );

        // Passar dados do AJAX para o JavaScript
        wp_localize_script( 'sna-validator', 'sna_ajax', [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
        ] );
    }

    public static function handle_form_submission() {
        // Verifique se é uma requisição AJAX
        if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
            wp_send_json_error( __( 'Acesso não permitido.', 'cadastro-usuarios-sna' ) );
        }

        // Sanitizar e validar os dados enviados
        $nome = sanitize_text_field( $_POST['nome'] ?? '' );
        $cpf = sanitize_text_field( $_POST['cpf'] ?? '' );
        $email = sanitize_email( $_POST['email'] ?? '' );
        $documento = $_FILES['documento'] ?? null;

        // Validações básicas
        if ( empty( $nome ) || empty( $cpf ) || empty( $email ) || empty( $documento ) ) {
            wp_send_json_error( __( 'Todos os campos são obrigatórios.', 'cadastro-usuarios-sna' ) );
        }

        if ( ! SNA_Validator::validate_cpf( $cpf ) ) {
            wp_send_json_error( __( 'CPF inválido.', 'cadastro-usuarios-sna' ) );
        }

        if ( ! SNA_Validator::validate_email( $email ) ) {
            wp_send_json_error( __( 'E-mail inválido.', 'cadastro-usuarios-sna' ) );
        }

        if ( ! SNA_Validator::validate_document( $documento ) ) {
            wp_send_json_error( __( 'Documento inválido. Envie JPG, PNG ou PDF com até 3MB.', 'cadastro-usuarios-sna' ) );
        }

        // Processar upload do documento
        $upload = wp_handle_upload( $documento, [ 'test_form' => false ] );
        if ( isset( $upload['error'] ) ) {
            wp_send_json_error( __( 'Erro ao enviar o documento.', 'cadastro-usuarios-sna' ) );
        }

        // Inserir no banco de dados
        $data = [
            'nome'      => $nome,
            'cpf'       => $cpf,
            'email'     => $email,
            'documento' => $upload['url'],
            'status'    => 'Aguardando',
        ];

        if ( ! SNA_Cadastro::insert_cadastro( $data ) ) {
            wp_send_json_error( __( 'Erro ao salvar os dados no banco.', 'cadastro-usuarios-sna' ) );
        }

        wp_send_json_success( __( 'Cadastro enviado com sucesso!', 'cadastro-usuarios-sna' ) );
    }
}
