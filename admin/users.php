<?php
    $GLOBALS['page_title'] = 'Users';
    require_once 'admin.php';

    function thead() {
        ?>
            <thead>
                <tr class="table-dark">
                    <th><input class="form-check-input cursor-pointer cb-select-all" type="checkbox"></th>
                    <th>ID <a href='#sort' class="visible-on-hover" order="asc"><i class="bi bi-sort-alpha-down-alt"></i></a></th>
                    <th>Username <a href='#sort' class="visible-on-hover" order="asc"><i class="bi bi-sort-alpha-down-alt"></i></a></th>
                    <th>Name <a href='#sort' class="visible-on-hover" order="asc"><i class="bi bi-sort-alpha-down-alt"></i></a></th>
                    <th>Email <a href='#sort' class="visible-on-hover" order="asc"><i class="bi bi-sort-alpha-down-alt"></i></a></th>
                    <th>Role <a href='#sort' class="visible-on-hover" order="asc"><i class="bi bi-sort-alpha-down-alt"></i></a></th>
                    <th>Items <a href='#sort' class="visible-on-hover" order="asc"><i class="bi bi-sort-alpha-down-alt"></i></a></th>
                    <th>Actions <a href='#sort' class="visible-on-hover" order="asc"><i class="bi bi-sort-alpha-down-alt"></i></a></th>
                </tr>
            </thead>
        <?php
    }

    function page() {
        global $roles;
        ?>
        <h1><?php echo get_title() ?></h1>
        <p class="mb-5">Users registered on the site</p>

        <?php
            $table = new Table( 'User', [
                'id' => 'ID',
                'username' => 'Username',
                'firstname' => 'Firstname',
                'lastname' => 'Lastname',
                'email' => 'Email',
                'role' => 'Role' 
            ] );
            $table->prepare();
            $table->display();
        ?>
    <?php }