<?php
 if(!isset($permission)){
     $permission = array();
 }
?>
<div class="content-wrapper-area">
    <div class="container-fluid">
        <div class="row g-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-2 card-breadcrumb">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="title-lg mb-0">Edit Permission</h4>
                            <div class="justify-content-end">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item">
                                        <a href="<?= site_url('admin/users'); ?>">Users</a>
                                    </li>
                                    <li class="breadcrumb-item active">Edit Permission</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">
                        <h3 class="title-sm mb-0">Permission Details</h3>
                    </div>
                    <div class="card-body">
                        <form action="/create-role" method="POST">
                            <div class="form-group">
                                <label class="label" for="role_id">ID:</label>
                                <input class="form-control" type="number" id="role_id" name="role_id" value="<?= $permission['id']; ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label class="label" for="role_name">Name:</label>
                                <input class="form-control" type="text" id="role_name" name="role_name" value="<?= $permission['permission_name']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="label" for="description">Description:</label>
                                <textarea class="form-control" id="description" name="description" rows="6"><?= $permission['permission_description']; ?></textarea>
                            </div>

                            <div class="form- justify-content-center txt-center">
                                <button class="btn btn-sm btn-primary" type="submit">Save Details</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <div class="col-md-5">
            </div>

        </div>
    </div>
</div>