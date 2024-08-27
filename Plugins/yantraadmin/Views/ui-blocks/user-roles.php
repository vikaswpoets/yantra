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
                            <h4 class="title-lg mb-0">User Roles</h4>
                            <div class="justify-content-end">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item">
                                        <a href="<?= site_url('admin/users'); ?>">Users</a>
                                    </li>
                                    <li class="breadcrumb-item active">User Roles</li>
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
                                foreach ($roles as $role){
                                    echo "<tr><td>{$role['id']}</td>" .
                                        "<td>{$role['role_name']} <a href='".site_url('admin/users/role?id='.$role['id'])."' class='btn btn-sm btn-info'>Edit</a></td>" .
                                        "<td>{$role['role_description']}</td></tr>";
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4 class="title-sm mb-0">Assign role to a user</h4>
                    </div>
                    <div class="card-body">
                        <form action="/assign-role" method="POST">
                            <div class="form-group">
                                <label class="label" for="ua-username">Username / email:</label>
                                <input class="form-control" type="text" id="ua-username" name="username" required>
                            </div>
                            <div class="form-group">
                                <label class="label" for="ua-role">Role:</label>
                                <select class="form-control" id="ua-role" name="role">
                                    <option value="">Select Role</option>
                                    <?php
                                    foreach ($roles as $role){
                                        echo "<option value='{$role['id']}'>{$role['role_name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <button type="submit">Assign Role</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="title-sm mb-0">Add Role</h3>
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
                                <button class="btn btn-sm btn-primary" type="submit">Create Role</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>