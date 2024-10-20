<!-- Navbar -->
<nav class="bg-white sticky top-0 z-50 flex justify-between items-center py-6 px-10">
    <h1 class="text-3xl font-extrabold text-teal-700">Campigo</h1>
    
    <!-- Mobile menu button (hamburger icon) -->
    <div class="lg:hidden">
        <button id="mobile-menu-button" class="text-gray-700 focus:outline-none">
            <i class="fas fa-bars text-lg"></i>
        </button>
    </div>
    
    <!-- Links (hidden on small screens, shown on large screens) -->
    <ul id="nav-links" class="hidden lg:flex space-x-6 text-gray-700 font-semibold">
        <li><a href="#" class="hover:text-black">Home</a></li>
        <li><a href="#" class="hover:text-black">Products</a></li>
        <li><a href="#" class="hover:text-black">Contacts</a></li>
    </ul>

    <!-- Book Now Button -->
    <a href="{{ route('register') }}" class="hidden lg:block bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 text-white px-4 py-2 rounded-full font-bold shadow-lg hover:bg-gradient-to-l transition duration-300 ease-in-out transform hover:scale-105">
        Book Now
    </a>
</nav>

<!-- Mobile Menu (Initially hidden) -->
<div id="mobile-menu" class="lg:hidden hidden bg-white py-4 px-10 text-gray-700 space-y-4">
    <a href="#" class="block hover:text-black">Home</a>
    <a href="#" class="block hover:text-black">Products</a>
    <a href="#" class="block hover:text-black">Contacts</a>
    <a href="{{ route('register') }}" class="block bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 text-white px-4 py-2 rounded-full font-bold shadow-lg hover:bg-gradient-to-l transition duration-300 ease-in-out transform hover:scale-105">
        Book Now
    </a>
</div>

<!-- Mobile Menu Script -->
<script>
    const menuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    menuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
</script>
