<?php

class Table {
    public $class = null;
    public $search = null;
    public $query = null;
    public $columns = [];
    public $values = [];
    private $data = [];

    public function __construct( $class, $columns, $values ) {
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
        $this->values = $values;
    }

    public function prepare() {
        foreach( $this->class['results'] as $result ) {
            $rowData = []; // Initialize an empty array for each row of data

            foreach ($this->columns  as $columnKey => $columnTitle ) {
                if( isset( $this->values[$columnKey] ) && is_callable( $this->values[$columnKey] ) ) {
                    // Use the custom rendering function if it exists in $values
                    $rowData[$columnKey] = $this->values[$columnKey]( $result->$columnKey ?? null );
                } elseif( isset( $result->$columnKey ) ) { // Check if the column key exists in the result object
                        // Add the data to the row with the column key as the associative key
                        $rowData[$columnKey] = $result->$columnKey;
                } else {
                    // Set a default value or leave it empty as needed
                    $rowData[$columnKey] = '';
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
                        <button class="btn btn-outline-secondary" type="submit" aria-label="Search Button" id="button-search"><i class="bi bi-search"></i></button>
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
                        $( 'table input:checkbox' ).not( this ).prop( 'checked', this.checked );
                    } );
                    $( 'table input:checkbox:not(.cb-select-all)' ).change( function(e) {
                        if( $( 'table input:checkbox:not(.cb-select-all):checked' ).length === $( 'table input:checkbox:not(.cb-select-all)' ).length ) {
                            $( '.cb-select-all' ).prop( 'checked', true );
                        } else {
                            $( '.cb-select-all' ).prop( 'checked', false );
                        }
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