<template>
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex gap-8 items-center max-md:flex-col">
      <!-- Left Content -->
      <div class="w-full lg:w-1/3">
        <div class="flex flex-col">
          <h1 class="text-4xl font-semibold uppercase leading-tight text-neutral-800">
            Discover Your Next
            <span class="font-extrabold text-teal-600 block mt-2">Adventure</span>
          </h1>

          <p class="mt-6 text-lg leading-relaxed text-slate-600">
            Embark on unforgettable outdoor experiences with premium gear and stunning locations.
            Campigo is your gateway to nature.
          </p>

      </div>
          <router-link to="/products" class="font-semibold text-lg inline-flex items-center gap-2 bg-teal-600 text-white px-8 py-3 mt-8 rounded-md group-hover:bg-red-700 duration-300 ease-in-out">
            Explore Now →
          </router-link>
        </div>

      <!-- Right Image -->
      <div class="w-full lg:w-2/3">
        <img
            src="/storage/app/public/img/herocampigo.png"
          alt="Outdoor adventure scene" class="w-full h-auto rounded-lg shadow-lg object-cover" loading="lazy" />
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'Hero'
}
</script>