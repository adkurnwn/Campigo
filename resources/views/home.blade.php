<x-app-layout>

    <body class="bg-gray-100 ">
    
        <div class="p-8">
    
            <!-- Hero Section -->
            <section class="bg-gradient-to-r from-teal-100 via-green-100 to-blue-100 px-4 md:px-10 py-16 rounded-2xl flex flex-col md:flex-row items-center justify-between relative">
                <div class="text-center md:text-left">
                    <h2 class="text-3xl md:text-5xl font-bold text-teal-800 mb-6">Discover Your Next Adventure</h2>
                    <div class="flex justify-center md:justify-start mt-6 space-x-8 text-gray-700">
                        <div class="flex flex-col">
                            <span class="text-xl font-semibold">70+</span>
                            <span class="text-gray-500">Outdoor Items</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xl font-semibold">100+</span>
                            <span class="text-gray-500">Customers</span>
                        </div>
                    </div>
                    <div class="mt-6 relative">
                        <input type="text" placeholder="What are you looking for?" class="w-full md:w-80 p-4 border rounded-full">
                    </div>
                </div>
                <div class="absolute right-0 top-0">
                    <img src="{{ asset('storage/img/header_tent.png') }}" alt="Camp Gear" class="rounded-lg w-full max-w-2xl h-0 sm:h-64 object-cover">
                </div>
            </section>
            
    
            <!-- Best Selling Section -->
            <section class="px-4 md:px-10 py-16 rounded-2xl">
                <h3 class="text-2xl font-semibold mb-8">Best Selling Bundle</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 justify-center">
                    <!-- Product 1 -->
                    <div class="p-6 bg-gray-100 rounded-lg">
                        <img src="https://placehold.co/200x250" alt="Tent" class="mb-4">
                        <h4 class="font-semibold">Bundle 1</h4>
                        <p class="text-gray-500 mb-4">Rp 330,000.00</p>
                        <button
                            class="text-white bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 rounded-full font-bold shadow-lg hover:bg-gradient-to-l transition duration-300 ease-in-out transform hover:scale-105 px-4 py-2">See
                            more <i class="fas fa-arrow-right ml-2"></i></button>
                    </div>
                    <!-- Additional products... -->
                    <div class="p-6 bg-gray-100 rounded-lg">
                        <img src="https://placehold.co/200x250" alt="Tent" class="mb-4">
                        <h4 class="font-semibold">Bundle 2</h4>
                        <p class="text-gray-500 mb-4">Rp 400,000.00</p>
                        <button
                            class="text-white bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 rounded-full font-bold shadow-lg hover:bg-gradient-to-l transition duration-300 ease-in-out transform hover:scale-105 px-4 py-2">See
                            more <i class="fas fa-arrow-right ml-2"></i></button>
                    </div>
    
                    <div class="p-6 bg-gray-100 rounded-lg">
                        <img src="https://placehold.co/200x250" alt="Tent" class="mb-4">
                        <h4 class="font-semibold">Bundle 3</h4>
                        <p class="text-gray-500 mb-4">Rp 500,000.00</p>
                        <button
                            class="text-white bg-gradient-to-r from-teal-600 via-green-600 to-blue-600 rounded-full font-bold shadow-lg hover:bg-gradient-to-l transition duration-300 ease-in-out transform hover:scale-105 px-4 py-2">See
                            more <i class="fas fa-arrow-right ml-2"></i></button>
                    </div>
                </div>
            </section>
    
    
            <!-- About Us Section -->
            <section class="mt-12 mb-12 px-4 md:px-10 py-16 bg-gray-50 rounded-2xl">
                <h3 class="text-center text-2xl font-semibold mb-8">About us</h3>
                <p class="text-center text-gray-500 mb-12">Order now and appreciate the beauty of nature</p>
                <div class="flex justify-center space-x-12">
                    <!-- Feature 1 -->
                    <div class="text-center">
                        <i class="fas fa-seedling text-4xl text-green-500"></i>
                        <h4 class="font-semibold mt-4">Large Assortment</h4>
                        <p class="text-gray-500 mt-2">We offer many different types of products with fewer variations in
                            each category.</p>
                    </div>
                    <!-- Feature 2 -->
                    <div class="text-center">
                        <i class="fas fa-shipping-fast text-4xl text-green-500"></i>
                        <h4 class="font-semibold mt-4">Fast & Free Shipping</h4>
                        <p class="text-gray-500 mt-2">4-day or less delivery time, free shipping and an expedited delivery
                            option.</p>
                    </div>
                    <!-- Feature 3 -->
                    <div class="text-center ">
                        <i class="fas fa-headset text-4xl text-green-500"></i>
                        <h4 class="font-semibold mt-4">24/7 Support</h4>
                        <p class="text-gray-500 mt-2">Answers to any business-related inquiry 24/7 and in real-time.</p>
                    </div>
                </div>
            </section>
    
            <!-- Categories Section -->
            <section
                class="mt-12 md:px-10 rounded-2xl bg-gradient-to-r from-teal-100 via-green-100 to-blue-100 px-10 py-16">
                <h3 class="text-center text-2xl font-semibold mb-12">Categories</h3>
                <div class="flex space-x-8 justify-center">
                    <!-- Category 1 -->
                    <div class="text-center transition duration-300 ease-in-out transform hover:scale-105">
                        <img src="https://placehold.co/250x350" alt="Natural Plants" class="rounded-lg mb-4">
                        <h4 class="font-semibold">Tents</h4>
                    </div>
                    <!-- Category 1 -->
                    <div class="text-center transition duration-300 ease-in-out transform hover:scale-105">
                        <img src="https://placehold.co/250x350" alt="Natural Plants" class="rounded-lg mb-4">
                        <h4 class="font-semibold">Wears</h4>
                    </div>
                    <!-- Category 3 -->
                    <div class="text-center transition duration-300 ease-in-out transform hover:scale-105">
                        <img src="https://placehold.co/250x350" alt="Artificial Plants" class="rounded-lg mb-4">
                        <h4 class="font-semibold">Cooking Set</h4>
                    </div>
                </div>
            </section>
    
        </div>
        <x-footer></x-footer>
    
    </body>
</x-app-layout>
