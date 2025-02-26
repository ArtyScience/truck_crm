<script>
import Button from "./Button.vue";
import {XIcon} from "@heroicons/vue/solid";

export default {
    name: "Modal",
    emits: ['update:state'],
    props: {
        state: {
            type: Boolean,
            required: false
        },
        title: String,
        title_icon: String,
        title_dynamic: String
    },
    components: { Button, XIcon },
    methods: {
        closeModal() {
            this.$emit('update:state', false)
        }
    },
}
</script>

<template>
    <div v-if="state"
         tabindex="-1" aria-hidden="true" class="
         modal_wrapper
         overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50
          justify-center items-center w-full   max-h-full">

        <div
            style="width: 70%; margin-top: 1%;"
            class="content_wrapper">
            <!-- Modal content -->
            <div
                @keydown.esc="closeModal"
                class="relative bg-white rounded-lg
                    shadow-lg shadow-black dark:bg-gray-700 border border-gray-900">
                <!-- Modal header -->
                <div
                    class="
                    header_wrapper
                    flex items-center justify-between p-4 md:p-5
                    border-gray-400 border-b rounded-t dark:border-gray-600
                    bg-black">
                    <h3 v-if="title"
                        class="text-lg uppercase font-semibold
                        text-gray-100 dark:text-white">
                        {{ title }}

                        <span  v-if="title_dynamic"
                               class="tile_dynamic">
                            {{ title_dynamic }}
                        </span>
                    </h3>
                    <Button
                        @click="closeModal"
                        :type="'close'">
                        <template #icon_before>
                            <XIcon />
                        </template>
                    </Button>
                </div>
                <!-- Modal body -->
                <slot name="body"></slot>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.modal_wrapper {
    background-color: rgba(0, 0, 0, 0.5);
    height: 100%;
    .content_wrapper {
        margin: 0 auto;

        .tile_dynamic {
            background-color: rgb(194 65 12 / var(--tw-bg-opacity, 1));
            border-radius: 5px;
            padding: 3px;
            margin-left: 5px;
            font-size: 15px;
        }
    }

    @media (max-width: 640px) {
        .content_wrapper {
            width: 100%;
            margin-top: 0;
        }

        .header_wrapper {
            padding: 5px;
            padding-left: 10px;
            padding-right: 10px;
        }
    }
}
</style>
