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

        <div class="row">
            <div class="col-md-8"></div>
            <div class="col-md-4">
                <form method="GET">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="search" placeholder="Search" aria-label="Search" aria-describedby="button-search">
                        <button class="btn btn-outline-secondary" type="button" aria-label="Search Button" id="button-search"><i class="bi bi-search"></i></button>
                    </div>
                </form>
            </div>
        </div>
        
        <table class="table table-hover">
            <?php
                thead();
                $search = isset( $_GET[ 'search' ] ) ? '&search=' . $_GET['search'] : '';
                $query = new Query(
                    array(
                        'search' => isset( $_GET[ 'search' ] ) ? $_GET['search'] : '',
                        'paged' => isset( $_GET[ 'paged' ] ) ? (int) $_GET[ 'paged' ] : 1,
                        'limit' => isset( $_GET[ 'limit' ] ) ? (int) $_GET[ 'limit' ] : 10
                    )
                );
                $users = User::getPagedList( $query );

                foreach( $users['results'] as $key => $value ) {
                    echo "<tr>
                        <td><input class='form-check-input cursor-pointer' type='checkbox' value='$value->id'></td>
                        <td>$value->id</td>
                        <td>$value->username</td>
                        <td>$value->firstname $value->lastname</td>
                        <td><a href='mailto:$value->email'>$value->email</a></td>
                        <td>".get_roles()[$value->role]."</td>
                        <td>0</td>
                        <td>
                            <div class='visible-on-hover'>
                                <a href='#' class='btn btn-dark btn-sm'>View</a> 
                                <a href='#' class='btn btn-primary btn-sm'>Edit</a></div>
                            </div>
                        </td>
                    </tr>";
                }
                thead();

            ?>
        </table>
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-end">
                <li class="page-item"><a class="page-link" href="?paged=1<?php echo $search; ?>" aria-label="First Page">«</a></li>
                <li class="page-item <?php if($query->paged <= 1){ echo 'disabled'; } ?>">
                    <a class="page-link" href="<?php if($query->paged <= 1){ echo '#'; } else { echo "?paged=".($query->paged - 1); } ?><?php echo $search; ?>" aria-label="Previous">‹</a>
                </li>
                <li class="page-item disabled"><a class="page-link"><?php echo "$query->paged of $users[totalPages]"; ?></a></li>
                <li class="page-item <?php if($query->paged >= $users['totalPages']){ echo 'disabled'; } ?>">
                    <a class="page-link" href="<?php if($query->paged >= $users['totalPages']){ echo '#'; } else { echo "?paged=".($query->paged + 1); } ?><?php echo $search; ?>" aria-label="Next">›</a>
                </li>
                <li class="page-item"><a class="page-link" href="?paged=<?php echo $users['totalPages']; ?><?php echo $search; ?>" aria-label="Last Page">»</a></li>
            </ul>
        </nav>
        <script>
            jQuery( document ).ready( function($) {
                $( '.cb-select-all' ).click( function(e) {
                    $( 'input:checkbox' ).not( this ).prop( 'checked', this.checked );
                } );
                $( '[href="#sort"]' ).click( function(e) {
                    e.preventDefault();
                    sortTable( $('table'), $( this ).attr( "order" ), $( this ).parent().index() );
                    $(this).attr("order", $(this).attr("order") === 'asc' ? 'desc' : 'asc');
                } );
            } );
        </script>
    <?php }