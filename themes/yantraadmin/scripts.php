<script type="text/javascript">
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');

        if (window.innerWidth > 768) {
            sidebar.classList.toggle('collapsed');
            content.classList.toggle('collapsed');
        } else {
            sidebar.classList.toggle('collapsed');
            content.classList.toggle('collapsed');
        }
    }

    // Function to handle submenu toggle
    document.querySelectorAll('.sidebar ul li a').forEach(item => {
        item.addEventListener('click', function(e) {
            const parent = item.parentElement;
            const submenu = parent.querySelector('.submenu');

            if (submenu) {
                e.preventDefault(); // Prevent default link behavior if needed

                // Remove 'active' class from other sibling menu items
                const siblings = Array.from(parent.parentElement.children);
                siblings.forEach(sibling => {
                    if (sibling !== parent && sibling.classList.contains('active')) {
                        sibling.classList.remove('active');
                    }
                });

                // Toggle 'active' class on the clicked menu item
                parent.classList.toggle('active');
            }
        });
    });
</script>
