<?php
 if(!isset($roles)){
     $roles = array();
 }
?>
<div class="content-wrapper-area">
    <div class="container-fluid">
        <div class="row g-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-2 card-breadcrumb">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="title-lg mb-0">Permissions</h4>
                            <div class="justify-content-end">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item">
                                        <a href="<?= site_url('admin/users'); ?>">Users</a>
                                    </li>
                                    <li class="breadcrumb-item active">Permissions</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <table>
                            <thead>
                                <tr><th>ID</th><th>Name</th><th>Description</th></tr>
                            </thead>
                            <tbody>
                            <?php
                                foreach ($permissions as $p){
                                    echo "<tr><td>{$p['id']}</td>" .
                                        "<td>{$p['permission_name']} <a href='".site_url('admin/users/permission?id='.$p['id'])."' class='btn btn-sm btn-info'>Edit</a></td>" .
                                        "<td>{$p['permission_description']}</td></tr>";
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="title-sm mb-0">Add Permission</h3>
                    </div>
                    <div class="card-body">
                        <form action="/create-role" method="POST">
                            <div class="form-group">
                                <label class="label" for="role_name">Name:</label>
                                <input class="form-control" type="text" id="role_name" name="role_name" required>
                            </div>
                            <div class="form-group">
                                <label class="label" for="description">Description:</label>
                                <textarea class="form-control" id="description" name="description" rows="6"></textarea>
                            </div>

                            <div class="form-group justify-content-center txt-center">
                                <button class="btn btn-sm btn-primary" type="submit">Create Permission</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>