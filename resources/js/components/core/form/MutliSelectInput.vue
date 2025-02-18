<script>
import Multiselect from "vue-multiselect";

/*TODO: PUT IN PCSS*/
const classes = 'bg-gray-50 border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-3.5 px-4 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500'

export default {
    name: "MutliSelectInput",
    components: {Multiselect},
    emits: ['update:value', 'update:content', 'save:value'],
    props: {
        content: {
            type: Object,
            required: true
        },
        value: [Object, String],
        label: String
    },
    computed: {
        classes() {
            return classes
        },
        internalValue: {
            get() {
                return this.value;
            },
            set(value) {
                this.$emit('update:value', value);
            },
        },
        internalContent: {
            get() {
                return this.content;
            },
            set(value) {
                this.$emit('update:content', value);
            }
        },
    },
    methods: {
        removeTag(option) {
            this.internalValue = this.internalValue.filter((item) => {
                return item.id !== option.id
            })
            this.internalContent.push(option)
        },
        updateSelected(selectedOption) {
            this.internalContent = this.internalContent.filter((item) => {
                return item.id !== selectedOption.id
            })
        },
        saveTag(value) {
            this.$emit('save:value', value);
        }
    }
}
</script>

<template>
    <div class="multiselect_wrapper">
        <Multiselect
            :show-no-results="false"
            :close-on-select="false"
            :clear-on-select="false"
            :preserve-search="true"
            :allow-empty="true"
            :searchable="true"
            :taggable="true"
            @tag="saveTag"
            :class="classes"
            :multiple="true"
            v-model="internalValue"
            track-by="id"
            label="name"
            :placeholder="label"
            @select="updateSelected"
            :options="internalContent">
            <template #tag="{ option, remove }">
                <span class="custom-tag">
                  <span>{{ option.name }}</span>
                  <button type="button" @click="removeTag(option)">x</button>
                </span>
            </template>
        </Multiselect>
    </div>
</template>

<style lang="scss">
.multiselect__placeholder {
    color: #000;
}

.custom-tag {
    margin-left: 5px;
    &:first-child {
        margin-left: 0px;
    }
    span {
        background-color: rgb(59 130 246 / var(--tw-bg-opacity));
        padding: 2px 5px;
        border-radius: 10px;
        color: white;
        margin-top: 3px;
        font-size: 12px;

        @media (max-width: 640px) {
            font-size: 11px;
        }
    }
    button {
        margin-left: 0px;
        font-size: 12px;
        padding: 0px 5px 7px;
        border-radius: 10px;
        color: black;
        font-weight: bold;
        margin-bottom: 4px;
    }
}

.dark {
    .custom-tag {
        button {
            color: white;
        }
    }
}

/* General Multiselect Styling */
.multiselect_wrapper {
    font-family: 'Arial', sans-serif;
    div {
        height: auto !important;
    }
    .multiselect--active {
        position: absolute;
        z-index: 10000000000000 !important;
        max-width: 80%;
    }
}

.multiselect_wrapper .multiselect__single {
    display: flex;
    align-items: center;
    padding: 5px 10px;
    background-color: #f5f5f5;
    border-radius: 5px;
}

.multiselect_wrapper .multiselect__placeholder {
    color: #999;
    font-size: 14px;
}

.multiselect_wrapper .multiselect__input {
    font-size: 14px;
    padding: 2px 3px;
    width: 50px;
    border: transparent;
    border-radius: 5px;
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;

    &::placeholder {
        font-size: 11px;
    }
}

.dark {
    .multiselect_wrapper .multiselect__input {
        background-color: #374151;

        &::placeholder {
            color: white;
            font-size: 11px;
        }
    }
}

/* Custom Dropdown Options */
.multiselect_wrapper .multiselect__option--highlight {
    background-color: rgb(59 130 246 / var(--tw-bg-opacity)); /* Highlighted option */
    color: white;
    padding: 2px 5px;
    border-radius: 10px;
}

.multiselect_wrapper .custom-option {
    display: flex;
    align-items: center;
    padding: 8px 10px;
    font-size: 14px;
    cursor: pointer;
}

.multiselect_wrapper .custom-option:hover {
    background-color: #e8e8e8; /* Option hover state */
}

.multiselect_wrapper .custom-option img.option-icon {
    width: 20px;
    height: 20px;
    margin-right: 10px;
    border-radius: 50%;
    border: 1px solid #ddd;
}

/* Styling for No Options */
.multiselect_wrapper .multiselect__option--disabled {
    font-style: italic;
    color: #aaa;
}

/* Custom Selected Value */
.multiselect_wrapper .selected-value {
    display: flex;
    align-items: center;
}

.multiselect_wrapper .selected-value img.option-icon {
    width: 20px;
    height: 20px;
    margin-right: 10px;
}

.multiselect_wrapper .multiselect__tag {
    background-color: rgb(59 130 246 / var(--tw-bg-opacity));
    color: white;
    padding: 5px;
    border-radius: 3px;
    margin-right: 5px;
    font-size: 12px;
}

.multiselect_wrapper .multiselect__tag:hover {
    background-color: #0056b3;
}

/* Custom Arrow Icon for Dropdown */
.multiselect_wrapper .multiselect__select {
    position: relative;
    padding-left: 10px;
}

.multiselect_wrapper .multiselect__select::before {
    content: '▼';
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 12px;
    color: #888;
}

.multiselect_wrapper .multiselect__select--opened::before {
    content: '▲';
}

.multiselect_wrapper .multiselect__content-wrapper {
    padding: 5px;
    border-radius: 5px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);

    @media (max-width: 640px) {
        font-size: 11px;
    }

    .multiselect__element {
        padding: 2px;
        cursor: pointer;
    }
}

.multiselect__content-wrapper {
    z-index: 5;
}



</style>
