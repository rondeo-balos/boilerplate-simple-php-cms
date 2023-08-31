<?php

class User {

    public $id = null;
    public $modifiedDate = null;
    public $username = null;
    public $role = null;
    public $firstname = null;
    public $lastname = null;
    public $email = null;

    public function __construct( $data = array() ) {
        if ( isset( $data['id'] ) ) $this->id = (int) $data['id'];
        if ( isset( $data['modifiedDate'] ) ) $this->modifiedDate = $data['modifiedDate'];
        if ( isset( $data['username'] ) ) $this->username = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['username'] );
        if ( isset( $data['role'] ) ) $this->role = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['role'] );
        if ( isset( $data['firstname'] ) ) $this->firstname = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['firstname'] );
        if ( isset( $data['lastname'] ) ) $this->lastname = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['lastname'] );
        if ( isset( $data['email'] ) ) $this->email = filter_var( $data['email'], FILTER_VALIDATE_EMAIL );
    }

    public function storeFormValues ( $params ) {
        // Store all the parameters
        $this->__construct( $params );
    }

    public static function getById( $id ) {
        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $sql = "SELECT * FROM users WHERE id = :id";
        $st = $conn->prepare( $sql );
        $st->bindValue( ":id", $id, PDO::PARAM_INT );
        $st->execute();
        $row = $st->fetch();
        $conn = null;
        if( $row ) return new User( $row );
    }

    public static function getByEmail( $email ) {
        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $sql = "SELECT * FROM users WHERE email = :email";
        $st = $conn->prepare( $sql );
        $st->bindValue( ":email", $email, PDO::PARAM_STR );
        $st->execute();
        $row = $st->fetch();
        $conn = null;
        if( $row ) return new User( $row );
    }

    public static function getList( $numRows=1000000 ) {
        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM users ORDER BY modifiedDate DESC LIMIT :numRows";
    
        $st = $conn->prepare( $sql );
        $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
        $st->execute();
        $list = array();
    
        while ( $row = $st->fetch() ) {
            $item = new User( $row );
            $list[] = $item;
        }
    
        // Now get the total number of items that matched the criteria
        $sql = "SELECT FOUND_ROWS() AS totalRows";
        $totalRows = $conn->query( $sql )->fetch();
        $conn = null;
        return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );
    }

    public function insert( $password, $retype ) {
        $exists = User::getByEmail( $this->email );
        if( !is_null($exists) ) {
            //trigger_error( "User::insert(): Attempt to insert a User with an email already registered ($this->email).", E_USER_ERROR );
            return false;
        }

        // Does the Item object already have an ID?
        if ( !is_null( $this->id ) ) {
            //trigger_error( "User::insert(): Attempt to insert a User object that already has its ID property set (to $this->id).", E_USER_ERROR );
            return false;
        }
    
        // Insert the Item
        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $sql = "INSERT INTO users ( modifiedDate, username, password, role, firstname, lastname, email ) VALUES ( :modifiedDate, :username, :password, :role, :firstname, :lastname, :email )";
        $st = $conn->prepare ( $sql );
        $st->bindValue( ":modifiedDate", $this->modifiedDate, PDO::PARAM_STR );
        $st->bindValue( ":username", $this->username, PDO::PARAM_STR );
        if( $password != $retype ) {
            //trigger_error( "User::insert(): Password does not match. Please try again.", E_USER_ERROR );
            return false;
        }
        $st->bindValue( ":password", password_hash( $password, PASSWORD_BCRYPT ) );
        $st->bindValue( ":role", $this->role, PDO::PARAM_STR );
        $st->bindValue( ":firstname", $this->firstname, PDO::PARAM_STR );
        $st->bindValue( ":lastname", $this->lastname, PDO::PARAM_STR );
        $st->bindValue( ":email", $this->email, PDO::PARAM_STR );
        $st->execute();
        $this->id = $conn->lastInsertId();
        $conn = null;
        return true;
    }

    public function verify($password) {
        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $sql = "SELECT * FROM users WHERE id = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        $conn = null;
    
        if ($row && password_verify($password, $row['password'])) {
            return true;
        } else {
            return false;
        }
    }    

    public function update( $oldpass, $newpass = null, $retype = null ) {
        $passwordchange = !is_null( $oldpass ) && !is_null( $newpass );
        // Does the Item object have an ID?
        if ( is_null( $this->id ) ) {
            //trigger_error ( "Item::update(): Attempt to update an Item object that does not have its ID property set.", E_USER_ERROR );
            return false;
        }
       
        // Update the Item
        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $sql = "UPDATE users SET modifiedDate=:modifiedDate, username=:username, ".( $passwordchange ? "password=:password,":"")." role=:role, firstname=:firstname, lastname=:lastname, email=:email WHERE id = :id";
        $st = $conn->prepare ( $sql );
        $st->bindValue( ":modifiedDate", $this->modifiedDate, PDO::PARAM_STR );
        $st->bindValue( ":username", $this->username, PDO::PARAM_STR );
        if( !$this->verify( $oldpass ) ) {
            //trigger_error( "User::update(): Password incorrect. Please try again.", E_USER_ERROR );
            return false;
        }
        if( $passwordchange ) {
            if( $newpass != $retype ) {
                //trigger_error( "User::update(): Password does not match. Please try again.", E_USER_ERROR );
                return false;
            }
            $st->bindValue( ":password", password_hash( $newpass, PASSWORD_BCRYPT ) );
        }
        $st->bindValue( ":role", $this->role, PDO::PARAM_STR );
        $st->bindValue( ":firstname", $this->firstname, PDO::PARAM_STR );
        $st->bindValue( ":lastname", $this->lastname, PDO::PARAM_STR );
        $st->bindValue( ":email", $this->email, PDO::PARAM_STR );
        $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
        $st->execute();
        $conn = null;
        return true;
    }

    public function delete() {

        // Does the Item object have an ID?
        if ( is_null( $this->id ) ) {
            //trigger_error ( "User::delete(): Attempt to delete a User object that does not have its ID property set.", E_USER_ERROR );
            return false;
        }

        // Delete the Item
        $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
        $st = $conn->prepare ( "DELETE FROM users WHERE id = :id LIMIT 1" );
        $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
        $st->execute();
        $conn = null;
        return true;
    }
    
}