<div class="content-wrapper-area">
    <div class="container-fluid">
        <div class="row g-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-2 card-breadcrumb">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="title-lg mb-0">User Create</h4>
                            <div class="justify-content-end">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item">
                                        <a href="<?= site_url('admin/users'); ?>">Users</a>
                                    </li>
                                    <li class="breadcrumb-item active">User Create</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <form id="create-user" method="post">
                            <label for="username">Username:</label>
                            <input type="text" id="username" name="username" required>

                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" required>

                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password" required>

                            <label for="role">Role:</label>
                            <select id="role" name="role">
                                <option value="">Select Role</option>
                                <?php
                                foreach ($roles as $role){
                                    echo "<option value='{$role['id']}'>{$role['role_name']}</option>";
                                }
                                ?>
                            </select>

                            <button type="submit">Create User</button>
                        </form>

                    </div>
                </div>
            </div>
            <div class="col-md-6"></div>
        </div>
    </div>
</div>

<?php
add_action('footer-scripts',function (){
    ?>

    <script type="text/javascript">
        // Initialize the FormHandler
        const formHandler = new yantra.FormHandler('#create-user', true);
        // Add event listeners for custom form events
        formHandler.addEventListener('success', (response) => {
            if (response.ok) {
                let res = JSON.parse(response.content);
                alert(res);
            }
        });

        formHandler.addEventListener('error', (error) => {
            console.error('Form submission error:', error);
            yantra.globalQueue = yantra.globalQueue.catch(() => {});
        });
    </script>

    <?php
});
?>