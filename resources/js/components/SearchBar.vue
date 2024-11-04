<template>
    <div>
        <div class="flex flex-col p-2 m-h-screen px-16 scale-75">

            <div class="bg-gradient-to-r from-teal-500 to-teal-600 items-center justify-between w-full flex rounded-full shadow-lg p-2 mb-5 sticky"
                style="top: 5px">

                <div>

                </div>

                <input
                    class="font-bold uppercase rounded-full w-full py-4 pl-4 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline lg:text-sm text-xs"
                    type="text" placeholder="Search">

                <div class="bg-gray-600 p-2 hover:bg-blue-400 cursor-pointer mx-2 rounded-full">

                    <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>

                </div>

            </div>

        </div>

    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
export default defineComponent({
    name: 'SearchBar',
    data() {
        return {
            open: false
        }
    },
    methods: {

    }
})
</script>