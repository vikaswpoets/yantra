<div class="content-wrapper-area">
    <div class="container-fluid">
        <div class="row g-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-2 card-breadcrumb">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="title-lg mb-0">Edit User</h4>
                            <div class="justify-content-end">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item">
                                        <a href="<?= site_url('admin/users'); ?>"> Users </a>
                                    </li>
                                    <li class="breadcrumb-item active">Edit User</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-7 col-md-7">
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Basic information</h6>
                    </div>
                    <div class="card-body">
                        <form id="user-update-form" action="<?= site_url('admin/users/user'); ?>" method="post">
                            <input type="hidden" name="user_id" value="<?= $user->ID; ?>">
                            <div class="container">
                                <div class="row">
                                    <!-- Username Field -->
                                    <div class="col-12 col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="username">Username</label>
                                            <input type="text" class="form-control" disabled
                                                   id="username"
                                                   name="username"
                                                   placeholder="Enter username"
                                                   value="<?= $user->user_login; ?>"
                                                   aria-label="Username">
                                        </div>
                                    </div>


                                    <!-- Email Field -->
                                    <div class="col-12 col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="user-email">Email Address</label>
                                            <input type="email" class="form-control" disabled
                                                   id="user-email"
                                                   name="email"
                                                   placeholder="example@domain.com"
                                                   value="<?= $user->user_email; ?>"
                                                   aria-label="Email Address">
                                        </div>
                                    </div>
                                    <!-- First Name Field -->
                                    <div class="col-12 col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="first-name">First Name</label>
                                            <input type="text" class="form-control"
                                                   id="first-name"
                                                   name="first_name"
                                                   placeholder="Enter first name"
                                                   value="<?= $user->first_name; ?>"
                                                   aria-label="First Name">
                                        </div>
                                    </div>

                                    <!-- Last Name Field -->
                                    <div class="col-12 col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="last-name">Last Name</label>
                                            <input type="text" class="form-control"
                                                   id="last-name"
                                                   name="last_name"
                                                   placeholder="Enter last name"
                                                   value="<?= $user->last_name; ?>"
                                                   aria-label="Last Name">
                                        </div>
                                    </div>

                                    <!-- Display Name Field -->
                                    <div class="col-12 col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="display-name">Display Name</label>
                                            <input type="text" class="form-control"
                                                   id="display-name"
                                                   name="display_name"
                                                   placeholder="Enter display name"
                                                   value="<?= $user->display_name; ?>"
                                                   aria-label="Display Name">
                                        </div>
                                    </div>


                                    <!-- Contact Number Field -->
                                    <div class="col-12 col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="contact-no">Contact Number</label>
                                            <input type="tel" class="form-control"
                                                   id="contact-no"
                                                   name="contact"
                                                   placeholder="+888 222 544"
                                                   value="<?= $user->contact_no; ?>"
                                                   aria-label="Contact Number">
                                        </div>
                                    </div>
                                    <!-- User Status Field -->
                                    <div class="col-12 col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="user-status">User status</label>
                                            <select name="user_status" id="user-status">
                                                <option value="0" <?= $user->user_status?'':'selected'; ?>>Inactive</option>
                                                <option value="1" <?= $user->user_status?'selected':''; ?>>Active</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="col-12 mt-2">
                                        <input type="submit" class="btn btn-primary" value="Save Changes">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-5 col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Profile Photo</h6>
                    </div>
                    <div class="card-body">
                        <form>
                           <div class="container">
                               <div class="row">
                                   <div class="col-12 mt-2">
                                       <div class="d-flex align-items-center mb-4">
                                           <div class="account-img">
                                               <img src="img/bg-img/per-3.jpg" alt="">
                                           </div>
                                           <div>
                                               <span class="fs-sm text-muted"> ( Upload a PNG or JPG, size limit is 100 KB. )</span>
                                               <div class="mt-3">
                                                   <a href="#!" class="btn btn-sm btn-primary">Upload</a>
                                                   <a href="#!"
                                                      class="btn btn-sm btn-danger ms-md-2">Remove</a>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h6 class="title-sm">User Roles</h6>
                    </div>
                    <div class="card-body">
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h6 class="title-sm">Permissions</h6>
                    </div>
                    <div class="card-body">
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php
 add_action('footer-scripts',function (){
     ?>
     <script type="text/javascript">
         const fh = new yantra.FormHandler('#user-update-form', true);
         fh.addEventListener('success', (response) => {
             if (response.ok) {
                 let res = JSON.parse(response.content);
                  console.log(res);
             }
         });
         fh.addEventListener('error', (error) => {
             console.error('Form submission error:', error);
             yantra.globalQueue = yantra.globalQueue.catch(() => {});
         });
     </script>
<?php
 });
?>