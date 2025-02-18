<script>

import {StatusOnlineIcon} from "@heroicons/vue/solid";

export default {
    name: "Alert",
    props: {
        icon: {
            type: String,
            default: ''
        },
        message: {
            type: String,
            default: ''
        },
        timer: {
            type: Number,
            default: 2000
        }
    },
    data() {
        return {
            show: true
        }
    },
    components: {
         StatusOnlineIcon
    },
    mounted(){
        this.show = false;
    },
    methods: {
        closeAlert() {
            this.show = false
        }
    }
}
</script>

<template>
    <div class="alert_wrapper inline-flex w-[calc(100%-150px)]">
        <div v-if="show === true" class="w-[calc(100%)]">
            <div class="w-[calc(25%)] m-auto">
                <p class="font-sans uppercase font-medium">
                    <span v-if="icon === 'success'" class="inline-flex align-bottom pt-1 text-green-700">
                        <StatusOnlineIcon class="w-5"></StatusOnlineIcon>
                    </span>
                    <span class="ml-3"> {{ message }} </span>
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.alert_wrapper {
    p {
        font-size: 10px;
        letter-spacing: 1px;
    }
}
</style>
