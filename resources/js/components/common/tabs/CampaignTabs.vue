<script>
import {ClockIcon, MailIcon, PaperClipIcon} from "@heroicons/vue/solid";

export default {
    name: "CampaignTabs",
    emits: ['update:tab'],
    props: {
        actions: Object
    },
    components: {MailIcon, PaperClipIcon, ClockIcon},
    methods: {
        activateTab(tab_name) {
            this.$emit('update:tab', tab_name)
        },
    },
    mounted() {
    },
    computed: {
        active_tab() {
            return 'text-blue-900 border-blue-900 hover:text-green-800'
        },
    }
}
</script>

<template>
    <div>
        <div class="border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
                <li
                    @click="activateTab('attach_lead')"
                    :class="actions.attach_lead ? active_tab : ''"
                    class="me-2">
                                    <span  class="
                                    cursor-pointer
                                    border-b-2
                                    active inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                                        <PaperClipIcon style="width: 15px;" />
                                       Leads
                                    </span>
                </li>
                <li class="me-2">
                    <a href="#"
                       @click="activateTab('change_template')"
                       :class="actions.change_template ? active_tab : ''"
                       class="
                                        border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300
                                       inline-flex items-center justify-center p-4 dark:text-blue-500 dark:border-blue-500 group rounded-t-lg" aria-current="page">
                        <MailIcon style="width: 15px;" />
                        Email Template
                    </a>
                </li>
                <li class="me-2">
                    <a href="#"
                       @click="activateTab('open_schedule')"
                       :class="actions.open_schedule ? active_tab : ''"
                       class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group">
                        <ClockIcon style="width: 15px;" />
                        Campaign Time
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>

<style scoped lang="scss">

</style>
