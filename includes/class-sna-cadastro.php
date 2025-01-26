<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class SNA_Cadastro {
    private static $table_name;

    public static function init() {
        global $wpdb;
        self::$table_name = $wpdb->prefix . 'sna_cadastros';

        register_activation_hook( __FILE__, [ __CLASS__, 'create_table' ] );
    }

    public static function create_table() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE " . self::$table_name . " (
            id INT NOT NULL AUTO_INCREMENT,
            nome VARCHAR(255) NOT NULL,
            cpf VARCHAR(14) NOT NULL,
            email VARCHAR(255) NOT NULL,
            documento LONGTEXT NOT NULL,
            status ENUM('Aguardando', 'Aprovado', 'Reprovado') DEFAULT 'Aguardando',
            PRIMARY KEY (id),
            UNIQUE KEY (cpf)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );
    }

    public static function insert_cadastro( $data ) {
        global $wpdb;
        return $wpdb->insert(
            self::$table_name,
            $data,
            [ '%s', '%s', '%s', '%s', '%s' ]
        );
    }

    public static function get_all_cadastros( $status = null ) {
        global $wpdb;
        $query = "SELECT * FROM " . self::$table_name;
        if ( $status ) {
            $query .= $wpdb->prepare( " WHERE status = %s", $status );
        }
        return $wpdb->get_results( $query, ARRAY_A );
    }

    public static function update_status( $id, $status ) {
        global $wpdb;
        return $wpdb->update(
            self::$table_name,
            [ 'status' => $status ],
            [ 'id' => $id ],
            [ '%s' ],
            [ '%d' ]
        );
    }
}
