<?php
error_reporting(0);

use PHPUnit\Framework\TestCase;

require_once '../config.php';
require_once '../classes/Item.php';
require_once '../classes/User.php';

/**
 * vendor\bin\phpunit StressTest.php
 */

class StressTest extends TestCase {

    public function testUserActions() {
    
        for( $i = 0; $i <= 30; $i ++ ) {
            insert( $i, $this );
        }
        
    }

}

function insert( $i, $case ) {

    $user = new User( array(
        'modifiedDate' => date( 'Y-m-d H:i:s' ),
        'username' => 'test'.$i,
        'role' => 'basic',
        'firstname' => 'Firstname'.$i,
        'lastname' => 'Lastname'.$i,
        'email' => 'test@email.com'.$i
    ));

    $case->assertTrue($user->insert('testpassword', 'testpassword')); // Successful registration
}
