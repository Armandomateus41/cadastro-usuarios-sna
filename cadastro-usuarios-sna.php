<?php
/**
 * Plugin Name: Cadastro de Usuários SNA
 * Author: Armando Mateus
 * Description: Gerenciar registros de usuários com validação e aprovação.
 * Version: 1.0
 * License: GPL2
 * Text Domain: cadastro-usuarios-sna
 */

// Bloqueia acesso direto ao arquivo.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define as constantes do plugin.
define( 'SNA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SNA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Inclui os arquivos principais.
require_once SNA_PLUGIN_DIR . 'includes/class-sna-cadastro.php';
require_once SNA_PLUGIN_DIR . 'includes/class-sna-frontend.php';
require_once SNA_PLUGIN_DIR . 'includes/class-sna-admin.php';
require_once SNA_PLUGIN_DIR . 'includes/class-sna-validator.php';

// Inicializa as classes.
SNA_Cadastro::init();
SNA_Frontend::init();
SNA_Admin::init();
