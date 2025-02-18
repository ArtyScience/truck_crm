<script>
/*TODO: Make a Core Card*/
import {ChevronDownIcon, MapIcon, ArrowDownIcon, ArrowUpIcon,
    PhoneIcon, MailIcon, UserCircleIcon, NewspaperIcon, TruckIcon} from "@heroicons/vue/solid";
import Badge from "../../core/Badge.vue";

export default {
    name: "ShowUserCard",
    props: {
        item: Object
    },
    components: {
        Badge, MapIcon, MailIcon,
        ChevronDownIcon, PhoneIcon,
        ArrowDownIcon, ArrowUpIcon,
        UserCircleIcon, NewspaperIcon,
        TruckIcon
    },
}
</script>

<template>
    <div
        class="show_lead_wrapper mt-4 space-y-4 p-4">
        <div class="flex justify-between items-center bg-gray-100 dark:bg-gray-800 p-3 rounded-lg">
            <span class="font-semibold text-gray-500 dark:text-white">
                <span class="float-left mr-2"><UserCircleIcon style="width: 20px;" /></span>
                Name:</span>
            <span class="text-gray-500 dark:text-white">{{ item.name }}</span>
        </div>
        <div class="flex justify-between items-center bg-gray-100 dark:bg-gray-800 p-3 rounded-lg">
            <span class="font-semibold text-gray-500 dark:text-white">
                <span class="float-left mr-2"><MailIcon style="width: 20px;" /></span>
                Email:</span>
            <span class="text-gray-500 dark:text-white">{{ item.email }}</span>
        </div>
        <div class="flex justify-between items-center bg-gray-100 dark:bg-gray-800 p-3 rounded-lg">
            <span class="font-semibold text-gray-500 dark:text-white">
                <span class="float-left mr-2"><NewspaperIcon style="width: 20px;" /></span>
                Role:</span>
            <span class="text-gray-500 dark:text-white">
                {{ item.role }}</span>
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
