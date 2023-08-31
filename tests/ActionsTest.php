<?php
error_reporting(0);

use PHPUnit\Framework\TestCase;

require_once '../config.php';
require_once '../classes/Item.php';
require_once '../classes/User.php';

/**
 * vendor\bin\phpunit ActionsTest.php
 */

class ActionsTest extends TestCase {

    public function testUserActions() {

        // User Registration
        $user = new User( array(
            'modifiedDate' => date( 'Y-m-d H:i:s' ),
            'username' => 'test',
            'role' => 'basic',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'email' => 'test@email.com'
        ));

        $this->assertTrue($user->insert('testpassword', 'testpassword1') === false); // Passwords do not match
        $this->assertTrue($user->insert('testpassword', 'testpassword')); // Successful registration

        // Update User
        $user->username = 'testu';
        $user->role = 'subscriber';
        $user->firstname = 'Firstnameu';
        $user->lastname = 'Lastnameu';
        $user->email = 'testu@email.com';

        $this->assertTrue($user->update('testpassword1') === false); // Invalid password
        $this->assertTrue($user->update('testpassword')); // Update success

        // Update password
        $this->assertTrue($user->update('testpassword', 'testpassword1', 'testpassword2') === false); // Passwords do not match
        $this->assertTrue($user->update('testpassword', 'testpassword1', 'testpassword1')); // Successful update

        // Login
        $nonExistentUser = User::getByEmail('test@email.com');
        $this->assertNull($nonExistentUser); // User not found

        $existingUser = User::getByEmail('testu@email.com');
        $this->assertNotNull($existingUser); // User found
        $this->assertFalse($existingUser->verify('testpassword')); // Invalid password
        $this->assertTrue($existingUser->verify('testpassword1')); // Valid password

        // Delete User
        $this->assertTrue($existingUser->delete()); // Delete success
    }

    public function testItemActions() {
        // Add new Item
        $item = new Item( array(
            'publicationDate' => date( 'Y-m-d H:i:s' ),
            'title' => 'TestItem',
            'summary' => 'Item Summary',
            'content' => 'Item Content'
        ) );
        $this->assertTrue( $item->insert() );
        
        // Update Item
        $item->title = 'New Title';
        $item->summary = 'New Summary';
        $item->content = 'New Content';

        $this->assertTrue( $item->update() );

        // Delete Item
        $this->assertTrue( $item->delete() );
    }

}
