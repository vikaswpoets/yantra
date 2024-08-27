<div class="sidebar" id="sidebar">
    <ul>
        <li><a href="<?= site_url('admin'); ?>"><span class="icon"><i class="fas fa-home"></i></span><span class="label">Dashboard</span></a></li>
        <li><a href="<?= site_url('admin/update'); ?>"><span class="icon"><i class="fas fa-sync-alt"></i></span><span class="label">Updates</span></a></li>
        <li class="parent-menu"><a href="#"><span class="icon"><i class="fas fa-edit"></i></span><span class="label">Posts</span></a>
            <ul class="submenu">
                <li><a href="<?= site_url('admin/posts'); ?>">All Posts</a></li>
                <li><a href="<?= site_url('admin/posts/create'); ?>">Add New</a></li>
                <li><a href="<?= site_url('admin/posts/categories'); ?>">Categories</a></li>
                <li><a href="<?= site_url('admin/posts/tags'); ?>">Tags</a></li>
            </ul>
        </li>
        <li><a href="<?= site_url('admin/comments'); ?>"><span class="icon"><i class="fas fa-comments"></i></span><span class="label">Comments</span></a></li>
        <li class="parent-menu"><a href="<?= site_url('admin/media'); ?>"><span class="icon"><i class="fas fa-photo-video"></i></span><span class="label">Media</span></a>
            <ul class="submenu">
                <li><a href="<?= site_url('admin/media'); ?>">Library</a></li>
                <li><a href="<?= site_url('admin/media/create'); ?>">Add New</a></li>
            </ul>
        </li>
        <li class="parent-menu"><a href="#"><span class="icon"><i class="fas fa-file-alt"></i></span><span class="label">Pages</span></a>
            <ul class="submenu">
                <li><a href="<?= site_url('admin/pages'); ?>">All Pages</a></li>
                <li><a href="<?= site_url('admin/pages/create'); ?>">Add New</a></li>
            </ul>
        </li>
        <li class="parent-menu"><a href="<?= site_url('admin/appearance'); ?>"><span class="icon"><i class="fas fa-paint-brush"></i></span><span class="label">Appearance</span></a>
            <ul class="submenu">
                <li><a href="<?= site_url('admin/appearance/themes'); ?>">Themes</a></li>
                <li><a href="<?= site_url('admin/user/create'); ?>">Add New</a></li>
                <li><a href="<?= site_url('admin/user/profile'); ?>">Your Profile</a></li>
            </ul>
        </li>
        <li class="parent-menu"><a href="<?= site_url('admin/plugins'); ?>"><span class="icon"><i class="fas fa-plug"></i></span><span class="label">Plugins</span></a>
            <ul class="submenu">
                <li><a href="<?= site_url('admin/plugins/list'); ?>">Installed Plugins</a></li>
                <li><a href="<?= site_url('admin/plugins/create'); ?>">Add New</a></li>
            </ul>
        </li>
        <li class="parent-menu"><a href="#"><span class="icon"><i class="fas fa-users"></i></span><span class="label">Users</span></a>
            <ul class="submenu">
                <li><a href="<?= site_url('admin/users'); ?>">All Users</a></li>
                <li><a href="<?= site_url('admin/users/user'); ?>">Add User</a></li>
                <li><a href="<?= site_url('admin/users/roles'); ?>">User Roles</a></li>
                <li><a href="<?= site_url('admin/users/permissions'); ?>">Permissions</a></li>
                <li><a href="<?= site_url('admin/profile'); ?>">Your Profile</a></li>
            </ul>
        </li>
        <li class="parent-menu"><a href="#"><span class="icon"><i class="fas fa-tools"></i></span><span class="label">Tools</span></a>
            <ul class="submenu">
                <li><a href="<?= site_url('admin/tools'); ?>">Available Tools</a></li>
                <li><a href="#">Import</a></li>
                <li><a href="#">Export</a></li>
                <li><a href="#">Site Health</a></li>
            </ul>
        </li>
        <li class="parent-menu"><a href="#"><span class="icon"><i class="fas fa-cogs"></i></span><span class="label">Settings</span></a>
            <ul class="submenu">
                <li><a href="<?= site_url('admin/settings'); ?>">General</a></li>
                <li><a href="#">Writing</a></li>
                <li><a href="#">Reading</a></li>
                <li><a href="#">Discussion</a></li>
                <li><a href="#">Media</a></li>
                <li><a href="#">Permalinks</a></li>
                <li><a href="#">Privacy</a></li>
            </ul>
        </li>
        <li><a href="<?= site_url('admin/logout'); ?>"><span class="icon"><i class="fas fa-sign-out-alt"></i></span><span class="label">Logout</span></a></li>
    </ul>
</div>
