<script>
import { VuePreloader } from 'vue-preloader';
import '../../../../node_modules/vue-preloader/dist/style.css'
export default {
    name: "Preloader",
    components: { VuePreloader }
}
</script>

<template>
    <div class="preloader_wrapper">
        <VuePreloader
            background-color="#091a28"
            color="#ffffff"
            transition-type="fade-down"
            :loadingWrapper="true"
            :loading-speed="1"
            :transition-speed="300"

        ></VuePreloader>
    </div>
</template>

<style scoped lang="scss">
.preloader_wrapper {
}
</style>
