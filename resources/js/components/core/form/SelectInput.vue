<script>
export default {
    name: "SelectInput",
    props: {
        items: {
            type: [Object, Array],
            required: true
        },
        sected_text: {
            type: String,
            default: 'Please select'
        },
        label: String,
        selected: String,
        error: Boolean,
        error_text: String
    },
    emits: ['update:selected', 'update:label'],  // Emit events for selected option and description
    computed: {
        internalSelected: {
            get() {
                return this.selected;
            },
            set(value) {
                this.$emit('update:selected', value);  // Emit the selected option when changed
            },
        },
        internalLabel: {
            get() {
                return this.label;
            },
            set(value) {
                this.$emit('update:label', value);  // Emit the description when changed
            },
        },
    }
}
</script>

<!--TODO: Implement css styles into PCSS-->
<template>
    <div class="relative" :class="{'error': error}">
        <label
            class="absolute text-sm text-gray-600 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-800 px-2 peer-focus:px-2 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1 block mb-2 text-sm font-medium dark:text-white dark:bg-yellow-700 rounded float-left">
            {{ internalLabel }}
        </label>
        <select v-model="internalSelected"
                class="h-fit block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer">
            <option v-if="internalSelected" selected disabled>{{ internalSelected }}</option>
            <option v-for="item in items" class="text-red-900" :value="item.value">
                {{ item.label }}
            </option>
        </select>

        <span v-if="error" class="error_wrapper bg-gray-800 text-red-400 dark:bg-gray-800 dark:text-red-400">{{ error_text }}</span>
    </div>
</template>

<style scoped lang="scss">
.relative {
    .error_wrapper {
        text-align: right;
        z-index: 1000;
        max-width: 150px;
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
        select {
            border: 1px solid red;
        }
    }
}
</style>
