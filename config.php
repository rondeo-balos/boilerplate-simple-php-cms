<?php
ini_set( "display_errors", true );
date_default_timezone_set( "Asia/Manila" );  // http://www.php.net/manual/en/timezones.php

define( "DB_DSN", "mysql:host=localhost;dbname=test" ); // Change your database name
define( "DB_USERNAME", "username" );
define( "DB_PASSWORD", "password" );
define( "CLASS_PATH", "classes" );

require( CLASS_PATH . "/Item.php" );
require( CLASS_PATH . "/User.php" );
require( CLASS_PATH . "/Query.php" );
require( CLASS_PATH . "/Table.php" );

/*function handleException( $exception ) {
  echo "Sorry, a problem occurred. Please try later.\n".$exception->getMessage();
  error_log( $exception->getMessage() );
}

set_exception_handler( 'handleException' );*/