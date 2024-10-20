<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campigo</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="flex justify-between items-center py-8 px-6">
        <div class="text-3xl font-extrabold text-teal-700">Campigo</div>
        <nav class="space-x-8">
            <a href="#" class="text-lg text-gray-700 hover:text-teal-600 transition duration-300 ease-in-out">Home</a>
            <a href="#" class="text-lg text-gray-700 hover:text-teal-600 transition duration-300 ease-in-out">Services</a>
            <a href="#" class="text-lg text-gray-700 hover:text-teal-600 transition duration-300 ease-in-out">Reviews</a>
            <a href="#" class="text-lg text-gray-700 hover:text-teal-600 transition duration-300 ease-in-out">Benefits</a>
            <a href="#" class="text-lg text-gray-700 hover:text-teal-600 transition duration-300 ease-in-out">Contact</a>
        </nav>
        <a href="{{ route('register') }}" class="bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 text-white px-6 py-3 rounded-full font-bold shadow-lg hover:bg-gradient-to-l transition duration-300 ease-in-out transform hover:scale-105">
            Book Now
        </a>
    </header>
    

    <!-- Main Content -->
    <main class="flex flex-col md:flex-row items-center justify-between bg-white shadow-xl rounded-lg overflow-hidden">
        <!-- Replace the image below with the actual image -->
        <div class="p-10 md:p-16 text-center md:text-left">
            <h1 class="text-5xl font-bold text-teal-800 mb-6">Discover Your Next Adventure</h1>
            <p class="text-lg text-gray-600 mb-8">
                Embark on unforgettable outdoor experiences with premium gear and stunning locations. Campigo is your gateway to nature.
            </p>
            <div class="flex space-x-6 justify-center md:justify-start">
                <div class="flex flex-col items-center space-y-2">
                    <i class="fas fa-bolt text-2xl text-teal-700"></i>
                    <p class="text-gray-500 text-sm">Unforgettable Adventures</p>
                </div>
                <div class="flex flex-col items-center space-y-2">
                    <i class="fas fa-star text-2xl text-teal-700"></i>
                    <p class="text-gray-500 text-sm">Discover Nature</p>
                </div>
                <div class="flex flex-col items-center space-y-2">
                    <i class="fas fa-compass text-2xl text-teal-700"></i>
                    <p class="text-gray-500 text-sm">Explore Hidden Gems</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Catalog Section -->
    <section class="bg-gray-50 py-16">
        <h1 class="text-5xl font-extrabold mb-16 text-center text-transparent bg-clip-text bg-gradient-to-r from-teal-500 via-green-500 to-blue-500">Our Catalog</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <!-- Catalog Item -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 ease-in-out transform hover:scale-105">
                <div class="p-6 text-center">
                    <h2 class="text-2xl font-bold text-teal-700 mb-4">Basic Camping Kit</h2>
                    <p class="text-gray-600 mb-4">A perfect starter kit for beginner campers, including a tent, sleeping bag, and basic cooking set.</p>
                    <p class="text-lg font-semibold text-teal-500">Rp.380.000/day</p>
                </div>
            </div>
            <!-- Repeat similar structure for other catalog items -->
        </div>
    </section>

    <!-- Services Section -->
    <section class="mb-16 text-center">
        <h1 class="text-5xl font-extrabold mb-6 text-transparent bg-clip-text bg-gradient-to-r from-green-500 via-teal-500 to-blue-500">Our Services</h1>
        <ul class="list-none space-y-6">
            <li class="flex items-center justify-center space-x-3 transform hover:scale-110 transition-transform duration-300">
                <i class="fas fa-check-circle text-teal-500 text-2xl"></i>
                <span class="text-lg font-medium text-gray-700">Ultimate Outdoor Adventure Gear</span>
            </li>
            <li class="flex items-center justify-center space-x-3 transform hover:scale-110 transition-transform duration-300">
                <i class="fas fa-check-circle text-teal-500 text-2xl"></i>
                <span class="text-lg font-medium text-gray-700">Affordable Rental Packages Available Now</span>
            </li>
            <li class="flex items-center justify-center space-x-3 transform hover:scale-110 transition-transform duration-300">
                <i class="fas fa-check-circle text-teal-500 text-2xl"></i>
                <span class="text-lg font-medium text-gray-700">Luxury Camping Rental Options</span>
            </li>
        </ul>
        <button class="mt-10 px-8 py-3 bg-gradient-to-r from-green-600 via-teal-600 to-blue-600 text-white font-semibold rounded-full shadow-lg hover:bg-gradient-to-l hover:shadow-xl transition duration-300 ease-in-out transform hover:scale-105">
            View More
        </button>
    </section>

    <!-- Brand Section -->
    <section class="mt-16 bg-gradient-to-b from-gray-50 to-gray-100 py-16 px-8 rounded-lg shadow-inner">
        <h2 class="text-4xl font-bold text-center mb-12 text-transparent bg-clip-text bg-gradient-to-r from-teal-500 via-green-500 to-blue-500">Trusted by Leading Brands</h2>
        <div class="text-center">
            <p class="mb-8 text-lg text-gray-600">
                Discover innovative camping gear from top brands, combining style and function for a modern, unforgettable adventure.
            </p>
            <button class="px-8 py-3 bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 text-white rounded-full font-bold hover:bg-gradient-to-l shadow-lg hover:shadow-xl transition duration-300 ease-in-out transform hover:scale-105">
                Join Us Today
            </button>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="bg-white py-16 text-center">
        <h1 class="text-5xl font-extrabold mb-12 text-transparent bg-clip-text bg-gradient-to-r from-teal-600 via-green-600 to-blue-600">Why Choose Us?</h1>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
            <!-- Benefit Item -->
            <div class="flex flex-col items-center p-6 bg-gray-100 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 ease-in-out transform hover:scale-105">
                <i class="fas fa-cog text-5xl text-teal-500 mb-4"></i>
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Stunning Locations</h2>
                <p class="text-gray-600">Enjoy picturesque locations that bring you closer to nature for an immersive camping experience.</p>
            </div>
            <!-- Repeat similar structure for other benefits -->
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="bg-gradient-to-b from-gray-100 to-gray-200 p-12 rounded-lg shadow-lg w-full max-w-4xl mx-auto mt-20">
        <h1 class="text-center text-4xl font-bold mb-12 text-transparent bg-clip-text bg-gradient-to-r from-teal-600 via-green-600 to-blue-600">Contact Us</h1>
        <form class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 font-semibold">Full Name</label>
                    <input type="text" placeholder="Your name" class="w-full p-4 rounded-lg border border-gray-300 text-gray-800 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block mb-2 font-semibold">Email</label>
                    <input type="email" placeholder="Your email" class="w-full p-4 rounded-lg border border-gray-300 text-gray-800 focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
            </div>
            <div>
                <label class="block mb-2 font-semibold">Message</label>
                <textarea placeholder="Your message" class="w-full p-4 rounded-lg border border-gray-300 text-gray-800 focus:outline-none focus:ring-2 focus:ring-teal-500 h-40"></textarea>
            </div>
            <div class="text-center">
                <button class="bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 text-white font-semibold px-8 py-3 rounded-full shadow-lg hover:bg-gradient-to-l hover:shadow-xl transition duration-300 ease-in-out transform hover:scale-105">
                    Send Message
                </button>
            </div>
        </form>
    </section>

    <!-- Footer -->
    <footer class="bg-teal-600 text-white py-8 text-center">
        <p>&copy; 2024 Campigo. All Rights Reserved.</p>
    </footer>
</body>
</html>
