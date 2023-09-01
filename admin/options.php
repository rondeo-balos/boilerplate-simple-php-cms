<?php
    $GLOBALS['page_title'] = 'General Settings';
    require_once 'admin.php';

    function page() {
        ?>
        <h1><?php echo get_title() ?></h1>
        <p class="mb-5">Configure Site's settings</p>
        <form>
            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-3 row">
                        <label for="site_title" class="col-sm-4 col-form-label fw-bold">Site Title</label>
                        <div class="col-sm-8">
                        <input type="text" class="form-control" id="site_title" name="site_title" value="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6"></div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-3 row">
                        <label for="default_role" class="col-sm-4 col-form-label fw-bold">New User Default Role</label>
                        <div class="col-sm-8">
                        <select class="form-control" id="default_role" name="default_role">
                            <?php foreach( get_roles() as $key => $value ) {
                                echo "<option value='$key'>$value</option>";
                            } ?>
                        </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6"></div>
            </div>
            
            <button type="submit" class="btn btn-dark mb-3 mt-5">Save Settings</button>
            
        </form>
    <?php }