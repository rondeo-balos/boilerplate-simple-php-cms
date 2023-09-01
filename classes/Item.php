<?php

/**
 * Class to handle items
 */

class Item {

  /**
  * @var int The item ID from the database
  */
  public $id = null;

  /**
  * @var string When the item was published
  */
  public $publicationDate = null;

  /**
  * @var string Full title of the item
  */
  public $title = null;

  /**
  * @var string A short summary of the item
  */
  public $summary = null;

  /**
  * @var string The HTML content of the item
  */
  public $content = null;

  /**
   * @var string The status of the item
   */
  public $status = null;


  /**
  * Sets the object's properties using the values in the supplied array
  *
  * @param assoc The property values
  */

  public function __construct( $data=array() ) {
    if ( isset( $data['id'] ) ) $this->id = (int) $data['id'];
    if ( isset( $data['publicationDate'] ) ) $this->publicationDate = $data['publicationDate'];
    if ( isset( $data['title'] ) ) $this->title = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['title'] );
    if ( isset( $data['summary'] ) ) $this->summary = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['summary'] );
    if ( isset( $data['content'] ) ) $this->content = $data['content'];
    if ( isset( $data['status'] ) ) $this->status = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['status'] );
  }


  /**
  * Sets the object's properties using the edit form post values in the supplied array
  *
  * @param assoc The form post values
  */

  public function storeFormValues ( $params ) {

    // Store all the parameters
    $this->__construct( $params );
  }


  /**
  * Returns an Item object matching the given item ID
  *
  * @param int The item ID
  * @return Item|false The item object, or false if the record was not found or there was a problem
  */

  public static function getById( $id ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT * FROM items WHERE id = :id";
    $st = $conn->prepare( $sql );
    $st->bindValue( ":id", $id, PDO::PARAM_INT );
    $st->execute();
    $row = $st->fetch();
    $conn = null;
    if ( $row ) return new Item( $row );
  }


  /**
  * Returns all (or a range of) Item objects in the DB
  *
  * @param int Optional The number of rows to return (default=all)
  * @return Array|false A two-element array : results => array, a list of Item objects; totalRows => Total number of items
  */

  public static function getList( $numRows=1000000 ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM items ORDER BY publicationDate DESC LIMIT :numRows";

    $st = $conn->prepare( $sql );
    $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
    $st->execute();
    $list = array();

    while ( $row = $st->fetch() ) {
      $item = new Item( $row );
      $list[] = $item;
    }

    // Now get the total number of items that matched the criteria
    $sql = "SELECT FOUND_ROWS() AS totalRows";
    $totalRows = $conn->query( $sql )->fetch();
    $conn = null;
    return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );
  }


  /**
  * Inserts the current Item object into the database, and sets its ID property.
  */

  public function insert() {

    // Does the Item object already have an ID?
    if ( !is_null( $this->id ) ) {
      //trigger_error ( "Item::insert(): Attempt to insert an Item object that already has its ID property set (to $this->id).", E_USER_ERROR );
      return false;
    }

    // Insert the Item
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "INSERT INTO items ( publicationDate, title, summary, content, status ) VALUES ( :publicationDate, :title, :summary, :content, :status )";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":publicationDate", $this->publicationDate, PDO::PARAM_STR );
    $st->bindValue( ":title", $this->title, PDO::PARAM_STR );
    $st->bindValue( ":summary", $this->summary, PDO::PARAM_STR );
    $st->bindValue( ":content", $this->content, PDO::PARAM_STR );
    $st->bindValue( ":status", $this->status, PDO::PARAM_STR );
    $st->execute();
    $this->id = $conn->lastInsertId();
    $conn = null;
    return true;
  }


  /**
  * Updates the current Item object in the database.
  */

  public function update() {

    // Does the Item object have an ID?
    if ( is_null( $this->id ) ) {
      //trigger_error ( "Item::update(): Attempt to update an Item object that does not have its ID property set.", E_USER_ERROR );
      return false;
    }
   
    // Update the Item
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "UPDATE items SET publicationDate=:publicationDate, title=:title, summary=:summary, content=:content, status=:status WHERE id = :id";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
    $st->bindValue( ":publicationDate", $this->publicationDate, PDO::PARAM_STR );
    $st->bindValue( ":title", $this->title, PDO::PARAM_STR );
    $st->bindValue( ":summary", $this->summary, PDO::PARAM_STR );
    $st->bindValue( ":content", $this->content, PDO::PARAM_STR );
    $st->bindValue( ":status", $this->status, PDO::PARAM_STR );
    $st->execute();
    $conn = null;
    return true;
  }


  /**
  * Deletes the current Item object from the database.
  */

  public function delete() {

    // Does the Item object have an ID?
    if ( is_null( $this->id ) ){
      //trigger_error ( "Item::delete(): Attempt to delete an Item object that does not have its ID property set.", E_USER_ERROR );
      return false;
    }

    // Delete the Item
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $st = $conn->prepare ( "DELETE FROM items WHERE id = :id LIMIT 1" );
    $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
    return true;
  }

}