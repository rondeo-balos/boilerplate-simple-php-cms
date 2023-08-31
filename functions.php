<?php

$scripts = [];
$styles = [];
function enqueue_script( $src, $options = array() ) {

    $imp_options = implode(' ', array_map( function ( $key, $value ) {
        return "$key=\"$value\"";
    }, array_keys($options), $options) );

    $scripts[] = '<script src="' . $src . '" ' . $imp_options . ' ></script>';
}

function enqueue_style( $src, $options = array() ) {
    $imp_options = implode(' ', array_map( function ( $key, $value ) {
        return "$key=\"$value\"";
    }, array_keys($options), $options) );

    $styles[] = '<link href="' . $src . '" rel="stylesheet" ' . $imp_options . ' />';
}