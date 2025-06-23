document.addEventListener('DOMContentLoaded', function() {
    // Dropdown functionality
    const dropdowns = document.querySelectorAll('.dropdown');
    
    // Toggle dropdown menu
    dropdowns.forEach(dropdown => {
        const button = dropdown.querySelector('.profile-btn');
        const dropdownContent = dropdown.querySelector('.dropdown-content');
        
        // Toggle dropdown on click
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Close other open dropdowns
            dropdowns.forEach(d => {
                if (d !== dropdown) {
                    d.classList.remove('active');
                    const otherContent = d.querySelector('.dropdown-content');
                    if (otherContent) {
                        otherContent.style.display = 'none';
                    }
                }
            });
            
            // Toggle current dropdown
            dropdown.classList.toggle('active');
            if (content) {
                content.style.display = dropdown.classList.contains('active') ? 'block' : 'none';
            }
        });
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        dropdowns.forEach(dropdown => {
            if (!dropdown.contains(e.target)) {
                dropdown.classList.remove('active');
                const content = dropdown.querySelector('.dropdown-content');
                if (content) {
                    content.style.display = 'none';
                }
            }
        });
    });
    
    // Update cart count if needed
    function updateCartCount() {
        // This would typically come from your backend or localStorage
        // For now, we'll check localStorage or default to 0
        const cartCount = localStorage.getItem('cartCount') || 0;
        const cartElements = document.querySelectorAll('.cart-count');
        
        cartElements.forEach(element => {
            element.textContent = cartCount;
            element.style.display = cartCount > 0 ? 'flex' : 'none';
        });
    }
    
    // Initialize cart count
    updateCartCount();
});

// Add smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            window.scrollTo({
                top: targetElement.offsetTop - 100, // Account for fixed header
                behavior: 'smooth'
            });
        }
    });
});

// Add active class to current section in viewport
const sections = document.querySelectorAll('section[id]');

function setActiveSection() {
    const scrollPosition = window.scrollY + 200; // 200px offset for better UX
    
    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.offsetHeight;
        const sectionId = section.getAttribute('id');
        
        if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
            document.querySelector(`nav a[href*=${sectionId}]`).classList.add('active');
        } else {
            document.querySelector(`nav a[href*=${sectionId}]`).classList.remove('active');
        }
    });
}

// Run on scroll
window.addEventListener('scroll', setActiveSection);
