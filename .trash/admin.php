<?php

require( "config.php" );
session_start();
$action = isset( $_GET['action'] ) ? $_GET['action'] : "";
$username = isset( $_SESSION['username'] ) ? $_SESSION['username'] : "";

if ( $action != "login" && $action != "logout" && !$username ) {
  login();
  exit;
}

switch ( $action ) {
  case 'login':
    login();
    break;
  case 'logout':
    logout();
    break;
  case 'newItem':
    newItem();
    break;
  case 'editItem':
    editItem();
    break;
  case 'deleteItem':
    deleteItem();
    break;
  default:
    listItems();
}


function login() {

  $results = array();
  $results['pageTitle'] = "Admin Login | Widget News";

  if ( isset( $_POST['login'] ) ) {

    // User has posted the login form: attempt to log the user in

    if ( $_POST['username'] == ADMIN_USERNAME && $_POST['password'] == ADMIN_PASSWORD ) {

      // Login successful: Create a session and redirect to the admin homepage
      $_SESSION['username'] = ADMIN_USERNAME;
      header( "Location: admin.php" );

    } else {

      // Login failed: display an error message to the user
      $results['errorMessage'] = "Incorrect username or password. Please try again.";
      require( TEMPLATE_PATH . "/admin/loginForm.php" );
    }

  } else {

    // User has not posted the login form yet: display the form
    require( TEMPLATE_PATH . "/admin/loginForm.php" );
  }

}


function logout() {
  unset( $_SESSION['username'] );
  header( "Location: admin.php" );
}


function newItem() {

  $results = array();
  $results['pageTitle'] = "New Item";
  $results['formAction'] = "newItem";

  if ( isset( $_POST['saveChanges'] ) ) {

    // User has posted the item edit form: save the new item
    $item = new Item;
    $item->storeFormValues( $_POST );
    $item->insert();
    header( "Location: admin.php?status=changesSaved" );

  } elseif ( isset( $_POST['cancel'] ) ) {

    // User has cancelled their edits: return to the item list
    header( "Location: admin.php" );
  } else {

    // User has not posted the item edit form yet: display the form
    $results['item'] = new Item;
    require( TEMPLATE_PATH . "/admin/editItem.php" );
  }

}


function editItem() {

  $results = array();
  $results['pageTitle'] = "Edit Item";
  $results['formAction'] = "editItem";

  if ( isset( $_POST['saveChanges'] ) ) {

    // User has posted the item edit form: save the item changes

    if ( !$item = Item::getById( (int)$_POST['itemId'] ) ) {
      header( "Location: admin.php?error=itemNotFound" );
      return;
    }

    $item->storeFormValues( $_POST );
    $item->update();
    header( "Location: admin.php?status=changesSaved" );

  } elseif ( isset( $_POST['cancel'] ) ) {

    // User has cancelled their edits: return to the item list
    header( "Location: admin.php" );
  } else {

    // User has not posted the item edit form yet: display the form
    $results['item'] = Item::getById( (int)$_GET['itemId'] );
    require( TEMPLATE_PATH . "/admin/editItem.php" );
  }

}