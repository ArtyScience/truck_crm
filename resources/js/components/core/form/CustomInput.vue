<script>
export default {
    name: "CustomInput",
    props: {
        title: String,        // Props for `v-model:title`
        content: String,      // Props for `v-model:content`
    },
    emits: ['update:title', 'update:content'],  // Emits events to update values
    computed: {
        internalTitle: {
            get() {
                return this.title;
            },
            set(value) {
                this.$emit('update:title', value);  // Emits the update event for title
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
};
</script>

<template>
    <div style="color: red;">
        <input v-model="internalTitle" placeholder="Enter title"/>
        <textarea v-model="internalContent" placeholder="Enter content"></textarea>
    </div>
</template>

<style scoped lang="scss">

</style>


