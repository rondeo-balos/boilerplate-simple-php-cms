<?php
    $GLOBALS['page_title'] = 'Users';
    require_once 'admin.php';

    function page() {
        global $roles;
        ?>
        <h1><?php echo get_title() ?></h1>
        <p class="mb-5">Users registered on the site</p>

        <table class="table table-hover">
            <thead>
                <tr class="table-dark">
                    <th><input class="form-check-input cursor-pointer cb-select-all" type="checkbox"></th>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Items</th>
                </tr>
            </thead>
            <?php
                $users = User::getList();

                foreach( $users['results'] as $key => $value ) {
                    echo "<tr>
                        <td><input class='form-check-input cursor-pointer' type='checkbox' value='$value->id'></td>
                        <td>$value->id</td>
                        <td>
                            $value->username<br>
                            <div class='visible-on-hover fs-7'><a href='#'>View</a> | <a href='#'>Edit</a></div>
                        </td>
                        <td>$value->firstname $value->lastname</td>
                        <td><a href='mailto:$value->email'>$value->email</a></td>
                        <td>".get_roles()[$value->role]."</td>
                        <td>0</td>
                    </tr>";
                }
            ?>
            <thead>
                <tr class="table-dark">
                    <th><input class="form-check-input cursor-pointer cb-select-all" type="checkbox"></th>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Items</th>
                </tr>
            </thead>
        </table>
        <script>
            jQuery( document ).ready( function($) {
                $( '.cb-select-all' );
            } );
        </script>
    <?php }