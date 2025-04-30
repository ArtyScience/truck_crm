<script>
import {ArrowNarrowUpIcon} from "@heroicons/vue/solid";
import Button from "../core/Button.vue";

export default {
    name: "GoTop",
    components: {Button, ArrowNarrowUpIcon},
    methods: {
        scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    }
}
</script>

<template>
    <div class="m-2">
        <Button
            @click="scrollToTop"
            type="primary" aditional_classes="me-0 mb-0 px-1 py-1 rounded">
            <template #icon_after>
                <ArrowNarrowUpIcon style="width: 20px; height: 20px; padding: 2px 3px;" />
            </template>
        </Button>
    </div>
</template>

<style scoped lang="scss">
    .btn_wrapper {
        /*TODO: Refactor in all project, use only responsive values*/

        border-radius: 100px;
    }
</style>
