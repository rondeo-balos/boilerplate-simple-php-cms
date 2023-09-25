<?php

class Table {
    public $class = null;
    public $search = null;
    public $query = null;
    public $columns = [];
    private $data = [];

    public function __construct( $class, $columns ) {
        $this->search = isset( $_GET[ 'search' ] ) ? '&search=' . $_GET['search'] : '';
        $this->query = new Query(
            array(
                'search' => isset( $_GET[ 'search' ] ) ? $_GET['search'] : '',
                'paged' => isset( $_GET[ 'paged' ] ) ? (int) $_GET[ 'paged' ] : 1,
                'limit' => isset( $_GET[ 'limit' ] ) ? (int) $_GET[ 'limit' ] : 10
            )
        );
        $this->class = $class::getPagedList( $this->query );
        $this->columns = $columns;
    }

    public function prepare() {
        foreach( $this->class['results'] as $result ) {
            $rowData = []; // Initialize an empty array for each row of data

            foreach ($this->columns as $columnKey => $columnTitle) {
                // Check if the column key exists in the result object
                if (isset($result->$columnKey)) {
                    // Add the data to the row with the column key as the associative key
                    $rowData[$columnKey] = $result->$columnKey;
                }
            }

            // Add the row of data to the data array
            $this->data[] = $rowData;
        }
    }

    public function display() { ?>
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
        
        <table class="table table-hover"> <?php
            $this->thead();
            foreach( $this->data as $key => $value ) {
                echo "<tr>";
                echo "<td><input class='form-check-input cursor-pointer' type='checkbox' value='$key'></td>";
                foreach ($this->columns as $columnKey => $columnTitle) {
                    echo "<td>".$value[$columnKey]."</td>";
                }
                echo "</tr>";
            }
            $this->thead();
        ?> </table>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-end">
                    <li class="page-item"><a class="page-link" href="?paged=1<?= $this->search; ?>" aria-label="First Page">«</a></li>
                    <li class="page-item <?php if($this->query->paged <= 1){ echo 'disabled'; } ?>">
                        <a class="page-link" href="<?php if($this->query->paged <= 1){ echo '#'; } else { echo "?paged=".($this->query->paged - 1); } ?><?= $this->search; ?>" aria-label="Previous">‹</a>
                    </li>
                    <li class="page-item disabled"><a class="page-link"><?= $this->query->paged." of ".$this->class['totalPages']; ?></a></li>
                    <li class="page-item <?php if($this->query->paged >= $this->class['totalPages']){ echo 'disabled'; } ?>">
                        <a class="page-link" href="<?php if($this->query->paged >= $this->class['totalPages']){ echo '#'; } else { echo "?paged=".($this->query->paged + 1); } ?><?= $this->search; ?>" aria-label="Next">›</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="?paged=<?= $this->class['totalPages']; ?><?= $this->search; ?>" aria-label="Last Page">»</a></li>
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
        <?php
    }

    private function thead() {
        ?>
            <thead>
                <tr class="table-dark">
                    <th><input class="form-check-input cursor-pointer cb-select-all" type="checkbox"></th>
                    <?php foreach( $this->columns as $columnKey => $columnTitle ) { ?>
                        <th><?= $columnTitle ?> <a href='#sort' class="visible-on-hover" order="asc"><i class="bi bi-sort-alpha-down-alt"></i></a></th>
                    <?php } ?>
                </tr>
            </thead>
        <?php
    }

}