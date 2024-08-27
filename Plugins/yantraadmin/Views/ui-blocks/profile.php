<div class="content-wrapper-area">
    <div class="container-fluid">
        <div class="row g-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-2 card-breadcrumb">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="title-lg mb-0">Profile</h4>
                            <div class="justify-content-end">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item">
                                        <a href="javascript: void(0);">Account</a>
                                    </li>
                                    <li class="breadcrumb-item active">Profile</li>
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
                        <form action="http://lasdflas/asldfa" method="post">
                            <div class="container">
                                <div class="row">
                                    <!-- Username Field -->
                                    <div class="col-12 col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="username">Username</label>
                                            <input type="text" class="form-control"
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
                                            <input type="email" class="form-control"
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

                                    <!-- Contact Number Field -->
                                    <div class="col-12 col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="contact-no">Contact Number</label>
                                            <input type="tel" class="form-control"
                                                   id="contact-no"
                                                   name="contact_number"
                                                   placeholder="+888 222 544"
                                                   value="<?= $user->contact_no; ?>"
                                                   aria-label="Contact Number">
                                        </div>
                                    </div>

                                    <!-- Bio Field -->
                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="user-bio">Your Bio</label>
                                            <textarea id="user-bio" rows="3" class="form-control"
                                                      name="bio"
                                                      placeholder="Tell us about yourself"
                                                      aria-label="Your Bio"></textarea>
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

     </script>
<?php
 });
?>