<div class="container h-100">
    <div class="row h-100 align-items-center justify-content-center">
        <div class="col-sm-10 col-md-7 col-lg-5">
            <!-- Middle Box -->
            <div class="middle-box">
                <div class="card-body">
                    <div class="log-header-area card p-4 mb-4 text-center">
                        <h5>Welcome Back !</h5>
                        <p class="mb-0">Sign in to continue.</p>
                    </div>

                    <div class="card">
                        <div class="card-body p-4">
                            <form id="login" action="<?= site_url('admin/login') ?>" method="post">
                                <div class="form-group mb-3">
                                    <label class="text-muted" for="yantra_username">Email address</label>
                                    <input class="form-control" autocomplete="username" type="email" id="yantra_username" name="username"  placeholder="Enter your email">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="text-muted" for="yantra_password">Password</label>
                                    <input class="form-control" autocomplete="password" type="password" id="yantra_password" name="password" placeholder="Enter your password">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="remember-me">
                                        <input type="checkbox" id="remember-me" name="remember-me"> Remember Me On this device
                                    </label>
                                </div>

                                <div class="form-group mb-3">
                                    <button class="btn btn-primary btn-lg w-100" type="submit">Login</button>
                                </div>

                                <div class="text-center">
                                    <span class="me-1">Don't have an account?</span>
                                    <a class="fw-bold" href="<?= site_url('signup') ?>">Sign up</a>
                                </div>
                            </form>
                        </div>
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
    // Initialize the FormHandler
    const formHandler1 = new yantra.FormHandler('#login', true);
    // Add event listeners for custom form events
    formHandler1.addEventListener('success', (response) => {
        if (response.ok) {
            let res = JSON.parse(response.content);
            if (res.redirectUrl !== "undefined" && res.redirectUrl.length>0) {
                window.location.href = res.redirectUrl;
            } else {
                console.log(res); // Handle other successful responses
            }
        }
    });

    formHandler1.addEventListener('error', (error) => {
        console.error('Form submission error:', error);
        // Handle form submission errors gracefully
        yantra.globalQueue = yantra.globalQueue.catch(() => {});
    });
</script>
<?php });  ?>