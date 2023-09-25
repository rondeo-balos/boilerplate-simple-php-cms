<?php
    $GLOBALS['page_title'] = 'Users';
    require_once 'admin.php';

    function page() {
        global $roles;
        ?>
        <h1><?php echo get_title() ?></h1>
        <p class="mb-5">Users registered on the site</p>

        <?php
            $columns = [
                'id' => 'ID',
                'username' => 'Username',
                'firstname' => 'Firstname',
                'lastname' => 'Lastname',
                'email' => 'Email',
                'role' => 'Role',
                'action' => 'Actions'
            ];
            $values = [
                'id' => 'id',
                'username' => 'username',
                'email' => function( $value ) {
                    return "<a href='mailto:{$value}'>{$value}</a>";
                },
                'role' => function( $value ) {
                    return get_roles()[$value];
                },
                'action' => function( $value ) {
                    return "<div class='visible-on-hover'>
                            <a href='#' class='btn btn-dark btn-sm'>View</a> 
                            <a href='#' class='btn btn-primary btn-sm'>Edit</a></div>
                        </div>";
                }
            ];
            $table = new Table( 'User', $columns, $values );
            $table->prepare();
            $table->display();
        ?>
    <?php }