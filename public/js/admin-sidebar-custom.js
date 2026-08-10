// Custom Admin Sidebar JavaScript for Loy Madok

document.addEventListener('DOMContentLoaded', function() {
    // Handle submenu toggles
    const submenuLinks = document.querySelectorAll('.sidebar-menu .submenu > .nav-link');
    
    submenuLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const submenu = this.parentElement;
            const wasOpen = submenu.classList.contains('open');
            
            // Only close other submenus if they're not the same section
            // This keeps the same section open when navigating within it
            document.querySelectorAll('.sidebar-menu .submenu').forEach(function(otherSubmenu) {
                if (otherSubmenu !== submenu) {
                    otherSubmenu.classList.remove('open');
                }
            });
            
            // Toggle current submenu
            if (wasOpen) {
                submenu.classList.remove('open');
            } else {
                submenu.classList.add('open');
            }
        });
    });
    
    // Handle active state for submenu items
    const currentPath = window.location.pathname;
    const submenuItems = document.querySelectorAll('.sidebar-menu .submenu-nav .nav-link');
    
    submenuItems.forEach(function(item) {
        if (item.getAttribute('href') === currentPath || 
            item.getAttribute('href') === window.location.href) {
            // Add active class to the item
            item.parentElement.classList.add('active');
            
            // Open the parent submenu
            const parentSubmenu = item.closest('.submenu');
            if (parentSubmenu) {
                parentSubmenu.classList.add('open');
            }
        }
    });
    
    // Handle main menu active state
    const mainLinks = document.querySelectorAll('.sidebar-menu > li:not(.submenu) > .nav-link');
    mainLinks.forEach(function(link) {
        if (link.getAttribute('href') === currentPath || 
            link.getAttribute('href') === window.location.href) {
            link.parentElement.classList.add('active');
        }
    });
    
    // Auto-open submenu if any child is active
    document.querySelectorAll('.sidebar-menu .submenu-nav li.active').forEach(function(activeItem) {
        const parentSubmenu = activeItem.closest('.submenu');
        if (parentSubmenu) {
            parentSubmenu.classList.add('open');
        }
    });
});
