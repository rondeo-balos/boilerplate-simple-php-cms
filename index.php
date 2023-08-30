<?php

require( "config.php" );
$action = isset( $_GET['action'] ) ? $_GET['action'] : "";

switch ( $action ) {
  case 'archive':
    archive();
    break;
  case 'viewItem':
    viewItem();
    break;
  default:
    homepage();
}


function archive() {
  $results = array();
  $data = Item::getList();
  $results['items'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $results['pageTitle'] = "Item Archive | Widget News";
  require( TEMPLATE_PATH . "/archive.php" );
}

function viewItem() {
  if ( !isset($_GET["itemId"]) || !$_GET["itemId"] ) {
    homepage();
    return;
  }

  $results = array();
  $results['item'] = Item::getById( (int)$_GET["itemId"] );
  $results['pageTitle'] = $results['item']->title . " | Widget News";
  require( TEMPLATE_PATH . "/viewItem.php" );
}

function homepage() {
  $results = array();
  $data = Item::getList( HOMEPAGE_NUM_ARTICLES );
  $results['items'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $results['pageTitle'] = "Widget News";
  require( TEMPLATE_PATH . "/homepage.php" );
}