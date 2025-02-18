<script>
import { GoogleAutocomplete } from "../../directives/google_autocomplete.js";
import validation from "../../helpers/validation.js";

export default {
    name: "GoogleLocationInput",
    components: {GoogleAutocomplete},
    emits: ['update:location', 'update:value'],
    props: {
        label: String,
        value: [String, Array],
        error: Boolean,
        error_text: String
    },
    computed: {
        internalValue: {
            get() {
                return this.value
            },
            set(value) {
                this.$emit('update:value', value)
            }
        }
    },
    data() {
        return {
            key: 'AIzaSyAb726TP38q1UpX8-O-hRdeeNobXO4pAcc',
        }
    },
    methods: {
        update(event) {
            this.$emit('update:location', event)
        },
    },
}
</script>

<template>
    <div
        v-if="internalValue" :class="{'error': error}"
        class="relative col-span-2">
        <label v-if="label"
               class="location_label absolute text-sm text-gray-600 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-800 px-2 peer-focus:px-2 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1 block mb-2 text-sm font-medium dark:text-white dark:bg-cyan-700 rounded block mb-2 text-sm font-medium  dark:bg-green-700 rounded float-left"
               > {{ label }}</label>
        <GoogleAutocomplete
            ref="google_location"
            :class="'block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer'"
            @set="update($event)"
            :isFullPayload="true"
            :value="internalValue"
            :api-key="key"
        />
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
        input {
            border: 1px solid red;
        }
    }
}

input {
    font-weight: bold;
    color: darkgreen;
}
@media (max-width: 640px) {
    input {
        padding: 5px;
        margin: 2px;
        margin-bottom: 0;
        font-size: 12px !important;
    }
}
</style>
