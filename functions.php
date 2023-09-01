<?php

$scripts = [];
$styles = [];

function enqueue_script( $src, $options = array() ) {
    global $scripts;
    $imp_options = implode(' ', array_map( function ( $key, $value ) {
        return "$key=\"$value\"";
    }, array_keys($options), $options) );

    $scripts[] = '<script src="' . $src . '" ' . $imp_options . ' ></script>';
}

function render_scripts() {
    global $scripts;
    foreach( $scripts as $key => $value ) {
        echo $value;
    }
}

function enqueue_style( $src, $options = array() ) {
    global $styles;
    $imp_options = implode(' ', array_map( function ( $key, $value ) {
        return "$key=\"$value\"";
    }, array_keys($options), $options) );

    $styles[] = '<link href="' . $src . '" rel="stylesheet" ' . $imp_options . ' />';
}

function render_styles() {
    global $styles;
    foreach( $styles as $key => $value ) {
        echo $value;
    }
}

function get_option( $key ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT value FROM options WHERE `key` = :key";
    $st = $conn->prepare( $sql );
    $st->bindValue( ":key", filter_var( $key, FILTER_SANITIZE_STRING ), PDO::PARAM_STR );
    $st->execute();
    $row = $st->fetch();
    $conn = null;
    return $row['value'];
}

function get_roles() {
    return unserialize( get_option( 'roles' ) );
}

function get_status() {
    return unserialize( get_option( 'status' ) );
}

function set_title( $title ) {
    $GLOBALS['page_title'] = $title;
}

function get_title() {
    return $GLOBALS['page_title'];
}