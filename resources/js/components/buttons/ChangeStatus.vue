<script>
export default {
    name: "ChangeStatus",
    data() {
        return {

        }
    },
    props: {
        role_id: {
            type: String,
            required: true
        },
        permission_id: {
            type: String,
            required: true
        },
        status: {
            type: String,
            required: true
        },
    },
    mounted() {
    },
    methods: {
        open() {

        }
    }
}
</script>
<template>
    <div @click="open()">
        <slot></slot>
    </div>
</template>

<style scoped lang="scss">

</style>
