<?php

require_once '../header.php';

if( !isset( $GLOBALS['page_title'] ) ) $GLOBALS['page_title'] = 'Admin Dashboard';

function content() {
    global $page;
    ?>
    <style>
        .cursor-pointer {
            cursor: pointer;
        }
        nav .btn-outline-light {
            border: none;
        }
        nav .arrow {
            transition: 0.3s ease;
        }
        nav .rotate {
            transform: rotate(180deg);
        }
        nav a {
            text-decoration: none;
        }

        .fs-7 {
            font-size: 0.9rem !important;
        }
        tr .visible-on-hover {
            opacity: 0;
        }
        a {
            text-decoration: none;
        }
        tr:hover .visible-on-hover {
            opacity: 1;
        }
        [order="asc"] i.bi:before {
            content: "\f571";
        }

        @media (min-width: 992px) {
            .container-fluid .main-page {
                padding-left: 300px;
            }
        }
        @media (max-width: 991.98px) {
            .container-fluid .main-page {
                padding-top: 70px;
            }
        }
    </style>
    <button type="button" class="btn btn-dark px-3 m-2 open-sidebar position-absolute">
        <i class="bi bi-filter-left fs-3"></i>
    </button>

    <div class="sidebar fixed-top bg-dark p-3" style="width: 300px; height: 100vh;">
        <div class="row align-items-center">
            <div class="col-2">
                <i class="bi bi-app-indicator rounded-3 bg-primary text-white px-2 py-1 fs-4"></i>
            </div>
            <div class="col">
                <span class="text-white fw-bold"><?php echo get_option( 'site_title' ) ?></span>
            </div>
            <div class="col col-2 text-end">
                <i class="bi bi-x cursor-pointer d-lg-none d-md-block open-sidebar text-white fs-3" ></i>
            </div>
        </div>
        <hr class="dropdown-divider bg-light my-3">
        <nav>
            <a href="./admin.php">
                <div class="d-block text-start cursor-pointer btn btn-outline-light my-2">
                    <i class="bi bi-speedometer align-middle"></i>
                    <span class="align-middle ms-2">Dashboard</span>
                </div>
            </a>
            <div class="d-flex justify-content-between align-items-center cursor-pointer btn btn-outline-light my-2 toggle-dropdown">
                <div class="d-flex align-items-center">
                    <i class="bi bi-stickies align-middle"></i>
                    <span class="align-middle ms-2">Items</span>
                </div>
                <span class="text-sm arrow rotate text-sm">
                    <i class="bi bi-chevron-down"></i>
                </span>
            </div>
            <div class="d-block submenu d-none">
                <div class="d-block text-start mx-3 my-1 cursor-pointer btn btn-outline-light">All Items</div>
                <div class="d-block text-start mx-3 my-1 cursor-pointer btn btn-outline-light">Add New</div>
                <div class="d-block text-start mx-3 my-1 cursor-pointer btn btn-outline-light">Categories</div>
            </div>
            <div class="d-flex justify-content-between align-items-center cursor-pointer btn btn-outline-light my-2 toggle-dropdown">
                <div class="d-flex align-items-center">
                    <i class="bi bi-people align-middle"></i>
                    <span class="align-middle ms-2">Users</span>
                </div>
                <span class="text-sm arrow rotate text-sm">
                    <i class="bi bi-chevron-down"></i>
                </span>
            </div>
            <div class="d-block submenu d-none">
                <a href="./users.php"><div class="d-block text-start mx-3 my-1 cursor-pointer btn btn-outline-light">All Users</div></a>
                <div class="d-block text-start mx-3 my-1 cursor-pointer btn btn-outline-light">Add New</div>
                <div class="d-block text-start mx-3 my-1 cursor-pointer btn btn-outline-light">Profile</div>
            </div>
            <div class="d-flex justify-content-between align-items-center cursor-pointer btn btn-outline-light my-2 toggle-dropdown">
                <div class="d-flex align-items-center">
                    <i class="bi bi-sliders align-middle"></i>
                    <span class="align-middle ms-2">Settings</span>
                </div>
                <span class="text-sm arrow rotate text-sm">
                    <i class="bi bi-chevron-down"></i>
                </span>
            </div>
            <div class="d-block submenu d-none">
                <a href="./options.php"><div class="d-block text-start mx-3 my-1 cursor-pointer btn btn-outline-light">General</div></a>
            </div>
            <div class="d-block text-start cursor-pointer btn btn-outline-light my-2">
                <i class="bi bi-box-arrow rotate-in-right align-middle"></i>
                <span class="align-middle ms-2">Log out</span>
            </div>
        </nav>
    </div>

    <div class="container-fluid p-lg-5">
        <div style="" class="main-page">
            <?php
                if( function_exists( 'page' ) ) {
                    page();
                } else {
                    echo "Welcome!";
                }
            ?>
        </div>
    </div>

    <script>
        jQuery( document ).ready( function($) {
            $( '.toggle-dropdown' ).on( 'click', function() {
                dropdown( this );
            } );

            function dropdown( el ) {
                $( el ).next( '.submenu' ).toggleClass( 'd-none' );
                $( el ).find( '.arrow' ).toggleClass( 'rotate' );
            }

            $( '.open-sidebar' ).on( 'click', function() {
                $( '.sidebar' ).toggleClass( 'd-none' );
            } );
        } );

        function sortTable(table, order, col) {
            var asc   = order === 'asc',
                tbody = table.find('tbody');

            tbody.find('tr').sort(function(a, b) {
                if (asc) {
                    return $('td:nth-of-type('+col+')', a).text().localeCompare($('td:nth-of-type('+col+')', b).text());
                } else {
                    return $('td:nth-of-type('+col+')', b).text().localeCompare($('td:nth-of-type('+col+')', a).text());
                }
            }).appendTo(tbody);
        }
    </script>
<?php }

require_once '../footer.php';