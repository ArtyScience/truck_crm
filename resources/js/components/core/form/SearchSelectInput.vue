<script>
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';

export default {
    name: "SearchSelectInput",
    emits: ['update:content'],
    components: {
        vSelect
    },
    props: {
        values: Object,
        content: String,
    },
    data() {
        return {
            selected: ''
        }
    },
    mounted() {
    },
    computed: {
        internalContent: {
            get() {
                return this.content;
            },
            set(value) {
                this.$emit('update:content', value);
            },
        },
    }
}
</script>

<template>
    <div class="search_select_wrapper">
        <v-select
            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
            v-model="internalContent"
            :options="values"
        />
        <label
               class="absolute text-sm text-gray-600 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-800 px-2 peer-focus:px-2  peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1"
               > Select TimeZone</label>
    </div>
</template>

<style lang="scss">
.search_select_wrapper {
    position: relative;
    width: 100%;
}

#vs1__listbox {
    font-size: 12px;
    max-width: 100%;
}

</style>
