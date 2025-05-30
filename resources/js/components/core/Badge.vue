<script>
export default {
    name: "Badge",
    props: {
        text: [String, Number],
        color: {
            type: String,
            default() {
                return "bg-blue-100 text-blue-800";
            }
        },
        width: {
            type: String,
            default() {
                return "px-2.5 py-0.5";
            }
        }
    }
}
</script>

<template>
    <div class="badge_wrapper
          dark:text-blue-300 rounded dark:bg-blue-900"
          :class="color, width">
        {{ text }}
        <slot name="icon"></slot>
    </div>
</template>

<style scoped lang="scss">
@media (max-width: 640px) {
    .badge_wrapper {
        font-size: 9px;
        font-weight: bold;
        text-shadow: 1px 1px 1px white;
    }
}

</style>
