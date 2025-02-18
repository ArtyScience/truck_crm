<script>
import Preloader from "../components/common/Preloader.vue";
export default {
    name: "PageLayout",
    components: {
        Preloader
    }
}
</script>

<template>
    <div class="layout_wrapper">
        <div class="preloader_wrapper">
<!--            <Preloader></Preloader>-->
        </div>
        <slot></slot>
    </div>
</template>

<style scoped lang="scss">
.layout_wrapper {
    position: relative;
    .preloader_wrapper {
        position: relative;
        .preloader {
            top: 0;
            background-color: black;
            width: 100%;
            height: 100vh;
            border: 1px solid red;
            position: absolute;
        }
    }
}
</style>
