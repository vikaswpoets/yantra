<table class="table table-striped">
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($users)): ?>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['id']) ?></td>
                <td><?= htmlspecialchars($user['name']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars($user['role']) ?></td>
                <td>
                    <!-- Action buttons (e.g., edit, delete) -->
                    <a href="/user/edit/<?= htmlspecialchars($user['id']) ?>" class="btn btn-sm btn-primary">Edit</a>
                    <a href="/user/delete/<?= htmlspecialchars($user['id']) ?>" class="btn btn-sm btn-danger">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5">No users found.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>