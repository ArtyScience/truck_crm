<script>

import {ClockIcon} from "@heroicons/vue/solid";

export default {
    name: "Time",
    data() {
        return {
            currentTime: ''
        }
    },
    components: {ClockIcon},
    methods: {
        updateTime() {
            const now = new Date();
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const seconds = now.getSeconds().toString().padStart(2, '0');
            this.currentTime = `${hours}:${minutes}:${seconds}`;
        }
    },
    created() {
        // Call updateTime immediately to display the time on component creation.
        this.updateTime();

        // Update the time every second (1000 milliseconds).
        setInterval(this.updateTime, 1000);
    }
}
</script>

<template>
    <div class="time_wrapper inline-flex mt-0">
        <span class="inline-block align-baseline
                text-gray-500 dark:text-gray-200 mr-2 mt-1" >{{ currentTime }}</span>
        <span class="inline-flex text-yellow-600 clock_icon"><ClockIcon /></span>
    </div>
</template>

<style scoped lang="scss">
.time_wrapper {
    span {
        &:first-child {
            width: 62.5px;
        }

        &:nth-child(2) {
            width: 25px;
        }
    }

    @media (max-width: 640px) {
        width: 75px;
        justify-content: space-between;
        span {
            &:first-child {
                width: auto;
                padding-right: 2px;
                margin-right: 2px;
            }

            &:nth-child(2) {
                width: auto;
            }
        }

        text-align: right;
        font-size: 13px;

        .inline-block {
            margin-right: 0;
        }

        .clock_icon {
            text-align: right;
            float: right;
            width: 15px;
            svg {
                width: 15px;
            }
        }
    }
}
</style>
