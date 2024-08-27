<!-- Top Header Area -->
<header class="top-header-area d-flex align-items-center justify-content-between">
    <div class="left-side-content-area d-flex align-items-center">
        <!-- Mobile Logo -->
        <div class="mobile-logo">
            <a href="index.html"><img src="<?= theme_url('/'); ?>/img/core-img/small-logo.png" alt="Mobile Logo"></a>
        </div>

        <!-- Triggers -->
        <div class="flapt-triggers">
            <div class="menu-collasped" id="menuCollasped">
                <i class='bx bx-grid-alt'></i>
            </div>
            <div class="mobile-menu-open" id="mobileMenuOpen">
                <i class='bx bx-grid-alt'></i>
            </div>
        </div>

        <!-- Left Side Nav -->
        <ul class="left-side-navbar d-flex align-items-center">
            <li class="hide-phone app-search">
                <input type="text" class="form-control" placeholder="Search...">
                <span class="bx bx-search-alt"></span>
            </li>
        </ul>
    </div>

    <div class="right-side-navbar d-flex align-items-center justify-content-end">
        <!-- Mobile Trigger -->
        <div class="right-side-trigger" id="rightSideTrigger">
            <i class='bx bx-menu-alt-right'></i>
        </div>

        <!-- Top Bar Nav -->
        <ul class="right-side-content d-flex align-items-center">
            <li class="nav-item dropdown">
                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false"><span><i
                            class='bx bx-world'></i></span></button>
                <div class="dropdown-menu language-dropdown dropdown-menu-right">
                    <div class="user-profile-area">
                        <a href="#" class="dropdown-item mb-15">
                            <span>USA</span></a>
                        <a href="#" class="dropdown-item mb-15">
                            <span>German</span></a>
                        <a href="#" class="dropdown-item mb-15">
                            <span>Italian</span></a>
                        <a href="#" class="dropdown-item">
                            <span>Russian</span></a>
                    </div>
                </div>
            </li>

            <li class="nav-item dropdown">
                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false"><i class='bx bx-envelope'></i></button>
                <div class="dropdown-menu dropdown-menu-right">
                    <!-- Message Area -->
                    <div class="top-message-area">
                        <!-- Heading -->
                        <div class="message-heading">
                            <div class="heading-title">
                                <h6 class="mb-0">All Messages</h6>
                            </div>
                            <span>10</span>
                        </div>

                        <div class="message-box" id="messageBox">
                            <a href="#" class="dropdown-item">
                                <i class='bx bx-dollar-circle'></i>
                                <div>
                                    <span>Did you know?</span>
                                    <p class="mb-0 font-12">Adipisicing elit. Ipsa, porro!</p>
                                </div>
                            </a>

                            <a href="#" class="dropdown-item">
                                <i class='bx bx-shopping-bag'></i>
                                <div>
                                                <span>Congratulations!
                                                </span>
                                    <p class="mb-0 font-12">Consectetur adipisicing elit.</p>
                                </div>
                            </a>

                            <a href="#" class="dropdown-item">
                                <i class='bx bx-wallet-alt'></i>
                                <div>
                                    <span>Hello Obeta</span>
                                    <p class="mb-0 font-12">Consectetur adipisicing elit.</p>
                                </div>
                            </a>

                            <a href="#" class="dropdown-item">
                                <i class='bx bx-border-all'></i>
                                <div>
                                    <span>Your order is placed</span>
                                    <p class="mb-0 font-12">Consectetur adipisicing elit.</p>
                                </div>
                            </a>
                            <a href="#" class="dropdown-item">
                                <i class='bx bx-wallet-alt'></i>
                                <div>
                                    <span>Haslina Obeta</span>
                                    <p class="mb-0 font-12">Consectetur adipisicing elit.</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </li>

            <li class="nav-item dropdown">
                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false"><i class='bx bx-bell bx-tada'></i> <span
                        class="active-status"></span></button>
                <div class="dropdown-menu dropdown-menu-right">
                    <!-- Top Notifications Area -->
                    <div class="top-notifications-area">
                        <!-- Heading -->
                        <div class="notifications-heading">
                            <div class="heading-title">
                                <h6>Notifications</h6>
                            </div>
                            <span>11</span>
                        </div>

                        <div class="notifications-box" id="notificationsBox">
                            <a href="#" class="dropdown-item">
                                <i class='bx bx-shopping-bag'></i>
                                <div>
                                    <span>Your order is placed</span>
                                    <p class="mb-0 font-12">Consectetur adipisicing elit. Ipsa, porro!</p>
                                </div>
                            </a>

                            <a href="#" class="dropdown-item">
                                <i class='bx bx-wallet-alt'></i>
                                <div>
                                    <span>Haslina Obeta</span>
                                    <p class="mb-0 font-12">Consectetur adipisicing elit. Ipsa, porro!</p>
                                </div>
                            </a>

                            <a href="#" class="dropdown-item">
                                <i class='bx bx-dollar-circle'></i>
                                <div>
                                    <span>Your order is Dollar</span>
                                    <p class="mb-0 font-12">Consectetur adipisicing elit. Ipsa, porro!</p>
                                </div>
                            </a>

                            <a href="#" class="dropdown-item">
                                <i class='bx bx-border-all'></i>
                                <div>
                                    <span>Your order is placed</span>
                                    <p class="mb-0 font-12">Consectetur adipisicing elit. Ipsa, porro!</p>
                                </div>
                            </a>
                            <a href="#" class="dropdown-item">
                                <i class='bx bx-wallet-alt'></i>
                                <div>
                                    <span>Haslina Obeta</span>
                                    <p class="mb-0 font-12">Consectetur adipisicing elit. Ipsa, porro!</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </li>

            <li class="nav-item dropdown">
                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Profile Image</button>
                <div class="dropdown-menu profile dropdown-menu-right">
                    <!-- User Profile Area -->
                    <div class="user-profile-area">
                        <a href="#" class="dropdown-item"><i class="bx bx-user font-15"
                                                             aria-hidden="true"></i> My profile</a>
                        <a href="#" class="dropdown-item"><i class="bx bx-wallet font-15"
                                                             aria-hidden="true"></i> My wallet</a>
                        <a href="#" class="dropdown-item"><i class="bx bx-wrench font-15"
                                                             aria-hidden="true"></i> settings</a>
                        <a href="#" class="dropdown-item"><i class="bx bx-power-off font-15"
                                                             aria-hidden="true"></i> Sign-out</a>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</header>