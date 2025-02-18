<script>
import {FilterIcon} from "@heroicons/vue/solid";
import Button from "../core/Button.vue";

export default {
    name: "Search",
    components: {Button, FilterIcon},
    props: {
        placeholder: String
    }
}
</script>

<template>
    <div class="search_bar m-auto">
        <form>
            <div class="relative">
                <div class="search_icon absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-900 dark:text-gray-400" aria-hidden="true"
                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="search"
                       class="block w-full p-1 pl-10 text-sm text-gray-900 border border-gray-900 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       :placeholder="placeholder" required/>
                <Button
                    class="absolute top-0 right-0"
                    type="primary">
                    <template #icon_before>
                        <FilterIcon class="filter_icon m-0 p-0" />
                    </template>

                </Button>
            </div>
        </form>
    </div>
</template>

<style scoped lang="scss">
.search_bar {
    .filter_icon {
        width: 19px;
        margin-top: 1.5px;
    }

    width: 50%;
}

@media (max-width: 640px) {
    .search_bar {
        input {
            padding: 2px;
        }
        .search_icon {
            display: none;
        }
        .filter_icon {
            width: 17px;
        }
    }
}
</style>
