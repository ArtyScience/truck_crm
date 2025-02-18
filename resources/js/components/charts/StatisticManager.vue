<script>
import {CurrencyDollarIcon} from "@heroicons/vue/solid";

export default {
    name: "StatisticManager",
    components: {CurrencyDollarIcon},
    props: {
        title: {
            type: String,
            required: true
        }
    }
}
</script>

<template>
    <div class="text-center">
        <div class="p-2 m-2">
            <h1>
                <span class="inline-flex">
                    {{ title }}
                    <span class="w-5 ml-2 mt-0.5">
                        <CurrencyDollarIcon/>
                    </span>
                </span>
            </h1>
        </div>
        <ul class="font-extralight text-xs">
            <li class="inline-flex mr-2">
                <button
                    class="
                    text-white dark:text-white
                    border border-solid border-white-500
                    bg-blue-500 hover:bg-blue-700  font-bold py-1 px-2 rounded">
                    Today
                </button>
            </li>
            <li class="inline-flex mr-2">
                <button
                    class="border border-solid border-sky-500 hover:bg-blue-700
                      text-gray-800 dark:text-white
                     font-bold py-1 px-2 rounded">
                    Last Week
                </button>
            </li>
            <li class="inline-flex mr-2">
                <button
                    class="border border-solid border-sky-500 hover:bg-blue-700
                      text-gray-800 dark:text-white
                     font-bold py-1 px-2 rounded">
                    Last Month
                </button>
            </li>
        </ul>
    </div>
</template>

<style scoped lang="scss">

</style>
