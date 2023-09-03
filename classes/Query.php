<?php

class Query {
    public $search = '';
    public $paged = 1;
    public $limit = 10;
    public $offset = 0;

    public function __construct( $args = array() ) {
        $this->search = isset( $args[ 'search' ] ) ? $args['search'] : '';
        $this->paged = isset( $args[ 'paged' ] ) ? (int) $args[ 'paged' ] : 1;
        $this->limit = isset( $args[ 'limit' ] ) ? (int) $args[ 'limit' ] : 10;
        $this->offset = ( $this->paged-1 ) * $this->limit;
    }
    
}