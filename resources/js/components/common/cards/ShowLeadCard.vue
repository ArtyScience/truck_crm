<script>
/*TODO: Make a Core Card*/
import {ChevronDownIcon, MapIcon, ArrowDownIcon, ArrowUpIcon, PhoneIcon, MailIcon, UserCircleIcon, NewspaperIcon, TruckIcon} from "@heroicons/vue/solid";
import Badge from "../../core/Badge.vue";

export default {
    name: "ShowLeadCard",
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
        const url = 'api/v1/leads/location/' + this.item.id
        await axios.get(url).then((r) => {
             if (r.data.location_name !== undefined)
                 this.full_location = r.data.location_name + ', ' + r.data.postal_code
        })
    }
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
                <span class="float-left mr-2"><PhoneIcon style="width: 20px;" /></span>
                Phone:</span>
            <span class="text-gray-500 dark:text-white">
                {{ item.phone }}</span>
        </div>
        <div class="flex justify-between items-center bg-gray-100 dark:bg-gray-800 p-3 rounded-lg">
            <span class="font-semibold text-gray-500 dark:text-white">
                <span class="float-left mr-2"><MapIcon style="width: 20px;" /></span>
                Location:</span>
            <span class="location_text_wrapper">
                <ul v-if="full_location !== ''">
                    <li>
                        <Badge :text="full_location" />
                    </li>
                </ul>
            </span>
        </div>
        <div class="flex justify-between items-center bg-gray-100 dark:bg-gray-800 p-3 rounded-lg">
            <span class="font-semibold text-gray-500 dark:text-white">
                <span class="float-left mr-2"><NewspaperIcon style="width: 20px;" /></span>
                Notes:</span>
            <span
                v-if="item.notes !== null"
                class="" data-accordion="collapse">
                <h2>
                    <button
                        @click="expandNotes"
                        style="outline: none"
                        type="button" class="text-sm flex items-center justify-between w-full p-1 font-medium  text-gray-500  rounded-t-xl  dark:border-gray-700 dark:text-gray-400 gap-3" data-accordion-target="#accordion-collapse-body-1" aria-expanded="true" aria-controls="accordion-collapse-body-1">
                        <span class="notes_top_text">{{ item.notes }}</span>
                        <span style="width: 15px;"><ChevronDownIcon /></span>
                    </button>
                </h2>
                <div v-if="actions.expand_notes" aria-labelledby="accordion-collapse-heading-1">
                    <div class="p-2 rounded border border-b-0 border-gray-200 dark:border-gray-700 dark:bg-gray-900">
                        <p class="mb-2 text-gray-500 dark:text-gray-400 text-xs">{{ item.notes_full }}</p>
                    </div>
                </div>
            </span>
        </div>
        <div class="flex justify-between items-center bg-gray-100 dark:bg-gray-800 p-3 rounded-lg">
            <span class="font-semibold text-gray-500 dark:text-white">
                <span class="float-left mr-2">
                    <TruckIcon style="width: 20px;" />
                </span>
                Comodities:</span>
            <span class="comodities_wrapper text-gray-100 w-3/4">
                <ul class="list-none">
                    <li class="m-auto mb-0.5 float-left"
                        v-for="comodity in comodities"
                        :key="comodity">
                        <Badge :text="comodity" />
                    </li>
                </ul>
            </span>
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
