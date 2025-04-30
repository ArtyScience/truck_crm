<script>
import {ClockIcon, MailIcon, PaperClipIcon, ArrowRightIcon} from "@heroicons/vue/solid";

export default {
    name: "CampaignSteps",
    components: { ArrowRightIcon,
        MailIcon, PaperClipIcon, ClockIcon,
    },
    emits: ['update:campaign_steps'],
    props: {
        actions: Object,
        campaign_steps: {
            type: [Object, Array],
            required: true
        }
    },
    computed: {
        internalSteps: {
            get() {
                return this.campaign_steps;
            },
            set(value) {
                this.$emit('update:campaign_steps', value);
            },
        }
    },
    data() {
        return {
            steps: {}
        }
    },
}
</script>

<template>
<div>
    <ol class="flex items-center w-full justify-around space-x-2 text-sm font-medium text-center text-gray-500 bg-white  rounded-lg shadow-xs dark:text-gray-400 sm:text-base dark:bg-gray-800 dark:border-gray-700 sm:p-0.5 sm:space-x-4 rtl:space-x-reverse">
        <li v-for="(step, key) in internalSteps"
            :class="{'active_step': internalSteps[key]}"
            class="flex items-center dark:text-blue-500">
        <span class="flex items-center justify-center w-4 h-4 me-2 text-xs border border-gray-500 rounded-full shrink-0 dark:border-blue-500">
        </span>
        <span
            style="width: 13px; margin-left: 3px;">
           <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 15 15" fill="none">
              <rect x="0" y="10" width="15" height="5" fill="currentColor" />
              <rect x="5" y="5" width="10" height="5" fill="currentColor" />
              <rect x="10" y="0" width="5" height="5" fill="currentColor" />
            </svg>
        </span>
        </li>
    </ol>
</div>
</template>

<style scoped lang="scss">
.active_step {
    color: green;
    span {
        &:first-child {
            border: 1px solid green;
        }
    }
}
</style>
