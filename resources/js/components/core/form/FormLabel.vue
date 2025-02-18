<script>
export default {
    name: "FormLabel",
    props: {
        label: {
            type: String,
            required: true,
        }
    }
}
</script>

<template>
    <label
        class="absolute text-sm text-gray-600 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-800 px-2 peer-focus:px-2  peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1"
    > {{ label }}</label>
</template>

<style scoped lang="scss">

</style>
