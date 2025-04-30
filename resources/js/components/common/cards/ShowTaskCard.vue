<script>
/*TODO: Make a Core Card*/
import {ChevronDownIcon, MapIcon, ArrowDownIcon,
    ClockIcon,
    ArrowUpIcon, PhoneIcon, MailIcon, UserCircleIcon, NewspaperIcon, TruckIcon} from "@heroicons/vue/solid";
import Badge from "../../core/Badge.vue";

export default {
    name: "ShowTaskCard",
    props: {
        item: Object
    },
    components: {
        Badge, MapIcon, MailIcon,
        ChevronDownIcon, PhoneIcon,
        ArrowDownIcon, ArrowUpIcon,
        UserCircleIcon, NewspaperIcon,
        TruckIcon, ClockIcon
    },
    computed: {
        comodities() {
            return this.item.comodities_list
        },
    },
    data() {
        return {
            actions: {
                expand_notes: false
            },
            full_location: ''
        }
    },
    methods: {
        expandNotes() {
            this.actions.expand_notes = !this.actions.expand_notes
        }
    },
    async mounted() {

    }
}
</script>

<template>
    <div
        class="show_lead_wrapper mt-4 space-y-4 p-4">
        <div class="flex justify-between items-center bg-gray-100 dark:bg-gray-800 p-3 rounded-lg">
            <span class="font-semibold text-gray-500 dark:text-white">
                <span class="float-left mr-2"><UserCircleIcon style="width: 20px;" /></span>
                User Name:</span>
            <span class="text-gray-500 dark:text-white">{{ item.user_name }}</span>
        </div>
        <div class="flex justify-between items-center bg-gray-100 dark:bg-gray-800 p-3 rounded-lg">
            <span class="font-semibold text-gray-500 dark:text-white">
                <span class="float-left mr-2"><NewspaperIcon style="width: 20px;" /></span>
                Ttile:</span>
            <span class="text-gray-500 dark:text-white">{{ item.title }}</span>
        </div>
        <div class="flex justify-between items-center bg-gray-100 dark:bg-gray-800 p-3 rounded-lg">
            <span class="font-semibold text-gray-500 dark:text-white">
                <span class="float-left mr-2"><NewspaperIcon style="width: 20px;" /></span>
                Desctiption:</span>
            <span class="text-gray-500 dark:text-white">
                {{ item.description }}</span>
        </div>

        <div class="flex justify-between items-center bg-gray-100 dark:bg-gray-800 p-3 rounded-lg">
            <span class="font-semibold text-gray-500 dark:text-white">
                <span class="float-left mr-2"><NewspaperIcon style="width: 20px;" /></span>
                Deal name:</span>
            <span class="text-gray-500 dark:text-white">
                {{ item.deal_name }}</span>
        </div>

        <div class="flex justify-between items-center bg-gray-100 dark:bg-gray-800 p-3 rounded-lg">
            <span class="font-semibold text-gray-500 dark:text-white">
                <span class="float-left mr-2"><NewspaperIcon style="width: 20px;" /></span>
                Lead name:</span>
            <span class="text-gray-500 dark:text-white">
                {{ item.lead_name }}</span>
        </div>

        <div class="flex justify-between items-center bg-gray-100 dark:bg-gray-800 p-3 rounded-lg">
            <span class="font-semibold text-gray-500 dark:text-white">
                <span class="float-left mr-2"><ClockIcon style="width: 20px;" /></span>
                Deadline:</span>
            <span class="text-gray-500 dark:text-white">
                {{ item.deadline }}</span>
        </div>

    </div>
</template>

<style scoped lang="scss">
.show_lead_wrapper {
    font-size: 14px;
    margin-top: 0;
    padding: 5px;

    .location_text_wrapper {
        ul {
             li {
                 div {
                     background-color: rgb(22 163 74 / var(--tw-bg-opacity));
                     color: white;
                     text-shadow: 1px 1px 1px #383737;
                 }
             }
        }
    }

    @media (max-width: 640px) {
        font-size: 12px;

        .flex {
            margin-top: 5px;
            padding: 3px;

            .notes_top_text {
                font-size: 10px;
                text-align: left;
            }

            .location_text_wrapper {
                max-width: 250px;
                text-align: center;
            }

            .comodities_wrapper {
                width: 70%;
            }

        }
    }
}
</style>
