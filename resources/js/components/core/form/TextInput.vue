<script>

import Button from "../Button.vue";
export default {
    name: "TextInput",
    inheritAttrs: false,
    emits: ['update:content', 'save:search', 'key:down'],
    components: {Button},
    props: {
        content: [String, Number],
        search: Boolean,
        search_result: {
            type: Array,
            default: []
        },
        placeholder: {
            type: String,
            default: '',
        },
        label: {
            type: String,
            default: ''
        },
        add_label_classes: String,
        add_input_classes: String,
        error: Boolean,
        error_text: String,
        input_type: {
            type: String,
            default: 'text'
        }
    },
    computed: {
        internalSearch: {
            get() {
                return this.search_result;
            },
            set(value) {
                this.$emit('save:search', value);  // Emits the update event for content
            },
        },
        internalContent: {
            get() {
                return this.content;
            },
            set(value) {
                this.$emit('update:content', value);  // Emits the update event for content
            },
        },
    },
    methods: {
        saveSearch(search_result) {
            this.$emit('save:search', search_result)
        },
        updateInput(internalContent) {
            this.$emit('key:down', internalContent)
        }
    },
    mounted() {
    }
}
</script>
<!--TODO: REFACTOR STYLES< PUT IT IN PCSS-->
<template>
    <div class="input_wrapper relative" :class="{'error': error}">
        <input
            @keydown="updateInput(internalContent)"
            :type="input_type"
               v-model="internalContent"
               :placeholder="placeholder"
               :class="add_input_classes"
               class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder="" />
        <label v-if="label" :for="label"
               class="absolute text-sm text-gray-600 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-800 px-2 peer-focus:px-2  peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1"
               :class="add_label_classes"> {{ label }}</label>
        <span v-if="error" class="error_wrapper bg-gray-800 text-red-400 dark:bg-gray-800 dark:text-red-400">{{ error_text }}</span>
        <div v-if="search"
            class="search_wrapper">
            <ul
                v-if="internalSearch.length"
                v-for="result in internalSearch" class="flex">
                <li class="mr-5">
                    <Button
                     @clicked="saveSearch(result)"
                    size="xs"
                    type="info">
                    <template #icon_after>
                        <span class="w-[20px]">+</span>
                    </template>
                </Button></li>
                <li class="mr-3">{{ result.name }}</li>
                <li class="mr-3">{{ result.email }}</li>
                <li class="mr-3">{{ result.phone }}</li>
            </ul>
            <ul v-else>
                <li>No results found</li>
            </ul>
        </div>
    </div>
</template>

<style lang="scss">
.search_wrapper {
    width: 100%;
    position: absolute;
    z-index: 1000;
    background-color: #FFF !important;
    border: 1px solid lightgrey;
    border-bottom-right-radius: 5px;
    border-bottom-left-radius: 5px;
    font-size: 11px;
    padding: 2px;
    padding-left: 20px;
    color: grey;
    margin-top: 3px;
}

.relative {
    .error_wrapper {
        text-align: center;
        z-index: 1000;
        max-width: 200px;
        position: absolute;
        top: 0;
        right: 0;
        padding: 1px 5px 0;
        font-size: 9px;
        letter-spacing: 1px;
        text-transform: uppercase;
        border: 1px solid white;
        border-radius: 5px;
        font-weight: bold;
    }
    &.error {
        input {
            border: 1px solid red;
        }
    }
}

@media (max-width: 640px) {
    .input_wrapper {
        input {
            padding: 5px;
            margin: 2px;
            margin-bottom: 0;
            font-size: 12px !important;
        }
    }
}
</style>
