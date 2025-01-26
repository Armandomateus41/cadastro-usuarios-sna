<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class SNA_Validator {

    public static function validate_cpf( $cpf ) {
        $cpf = preg_replace( '/[^0-9]/', '', $cpf );
        if ( strlen( $cpf ) != 11 || preg_match( '/(\d)\1{10}/', $cpf ) ) {
            return false;
        }

        for ( $t = 9; $t < 11; $t++ ) {
            $d = 0;
            for ( $c = 0; $c < $t; $c++ ) {
                $d += $cpf[ $c ] * ( ( $t + 1 ) - $c );
            }
            $d = ( ( 10 * $d ) % 11 ) % 10;
            if ( $cpf[ $c ] != $d ) {
                return false;
            }
        }

        return true;
    }

    public static function validate_email( $email ) {
        return filter_var( $email, FILTER_VALIDATE_EMAIL ) !== false;
    }

    public static function validate_document( $file ) {
        $allowed_types = [ 'image/jpeg', 'image/png', 'application/pdf' ];
        $max_size = 3 * 1024 * 1024;

        if ( ! in_array( $file['type'], $allowed_types ) || $file['size'] > $max_size ) {
            return false;
        }

        return true;
    }
}
