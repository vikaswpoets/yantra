<?php
 if(!isset($role)){
     $role = array();
 }
?>
<div class="content-wrapper-area">
    <div class="container-fluid">
        <div class="row g-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-2 card-breadcrumb">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="title-lg mb-0">Edit Role</h4>
                            <div class="justify-content-end">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item">
                                        <a href="<?= site_url('admin/users'); ?>">Users</a>
                                    </li>
                                    <li class="breadcrumb-item active">Edit Role</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">
                        <h3 class="title-sm mb-0">Role Details</h3>
                    </div>
                    <div class="card-body">
                        <form action="<?= site_url('admin/users/create') ?>" method="POST">
                            <div class="form-group">
                                <label class="label" for="role_id">ID:</label>
                                <input class="form-control" type="number" id="role_id" name="role_id" value="<?= $role['id']; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label class="label" for="role_name">Name:</label>
                                <input class="form-control" type="text" id="role_name" name="role_name" value="<?= $role['role_name']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="label" for="description">Description:</label>
                                <textarea class="form-control" id="description" name="description" rows="6"><?= $role['role_description']; ?></textarea>
                            </div>

                            <div class="form- justify-content-center txt-center">
                                <button class="btn btn-sm btn-primary" type="submit">Save Details</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h4 class="title-sm mb-0">Permissions Assigned</h4>
                    </div>
                    <div class="card-body">
                        <table>
                            <thead>
                                <tr><th>ID</th><th>Permission</th></tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <form id="add-permission">
                            <input type="hidden" name="roll_id" value="<?= $role['id']; ?>"/>
                            <div class="mb-2">
                                <label class="label fs-sm" for="ua-role">Add permission to user role <?= $role['role_name']; ?>.</label>
                            </div>
                            <div class="form-group d-flex mb-0">
                                <select class="form-control br-top-right-0 br-bottom-right-0" id="permissions" name="permissions" required>
                                    <option value="">Select Permission</option>
                                    <?php
                                    foreach ($permissions as $p){
                                        echo "<option value='{$p['id']}'>{$p['permission_name']}</option>";
                                    }
                                    ?>
                                </select>
                                <input class="btn btn-sm btn-primary br-top-lef-0 br-bottom-lef-0 w-25" type="submit" value="Add"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>