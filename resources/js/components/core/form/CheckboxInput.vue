<script>
export default {
    name: "CheckboxInput",
    emits: ['update:state'],
    props: {
      state: Boolean,
      text: String
    },
    computed: {
        checkboxState: {
            get() {
                return this.state;
            },
            set(value) {
                this.$emit('update:state', value);
            },
        },
    },
}
</script>

<template>
    <div
        class="checkbox_wrapper flex items-center p-1">
        <input type="checkbox"
               v-model="checkboxState"
               class="text-blue-600 bg-gray-100 border-gray-300
               rounded dark:bg-gray-700 dark:border-gray-600">
<!--        TODO: Implement Label HINT-->
        <label for="checked-checkbox"
               v-if="text"
               class="ms-2 mr-1 text-xs font-medium uppercase
               text-gray-900 dark:text-gray-300 ">{{ text }}</label>
    </div>
</template>

<style scoped lang="scss">
.checkbox_wrapper {
    overflow: hidden;
    input {
        cursor: pointer;
        &:focus {
            outline: none;
            box-shadow: none;
        }
        &:checked {
            color: #095580;
        }
        &:hover {
            border-color: #095580;
        }
    }
}
</style>
