<script>
import Button from "../../core/Button.vue";
import TextInput from "../../core/form/TextInput.vue";
import {LocationMarkerIcon, PlusCircleIcon, PlayIcon, ClockIcon} from "@heroicons/vue/solid";
import MutliSelectInput from "../../core/form/MutliSelectInput.vue";
import GoogleLocationInput from "../GoogleLocationInput.vue";
import CheckboxInput from "../../core/form/CheckboxInput.vue";
import SelectInput from "../../core/form/SelectInput.vue";
import DateInput from "../../core/form/DateInput.vue";

import timezones from "../../../database/timezones.json";
import SearchSelectInput from "../../core/form/SearchSelectInput.vue";
import notifications from "../../../helpers/notifications.js";

/*TODO: Refactor classes put in PCSS*/

export default {
    name: "CampaignScheduleForm",
    emits: ['update:submit', 'unsaved:chages'],
    components: {
        SearchSelectInput,
        DateInput, ClockIcon,
        SelectInput, PlayIcon,
        CheckboxInput, GoogleLocationInput, MutliSelectInput, TextInput, Button,
        PlusCircleIcon, LocationMarkerIcon},
    props: {
        content: {
            type: Object,
            required: true
        },
        btn_text: String,
        errors: Object,
        errors_text: Object,
        is_loading: Boolean
    },
    methods: {
        updateForm(form) {
            this.form.days = this.days
            this.$emit('update:submit', form);
        },
        chooseAllDays() {
            for (let day in this.days) {
                this.days[day] = !this.days[day]
            }
        },
        updateStart(time) {
            this.start_hour = time
            this.form.start_hour = this.start_hour
        },
        updateEnd(time) {
            this.end_hour = time
            this.form.end_hour = this.end_hour
        }
    },
    async mounted() {
        this.form = this.content
        if (this.form.campaign_id) {
            const url = 'api/v1/campaign/get-scheduller/' + this.form.campaign_id;
            axios.get(url).then((r) => {
                this.form = {...r.data.campaign_scheduller}
                this.days = JSON.parse(r.data.campaign_scheduller.days)
                this.start_hour = r.data.campaign_scheduller.start_hour
                this.end_hour =  r.data.campaign_scheduller.end_hour
            }).catch((e) => {
                if (e.status === 404) {
                    notifications.info(e.response.data.error)
                }
            })
        }
    },

    data() {
        return {
            form: {},
            timezones,
            days: {
                mon: false,
                tue: false,
                wedn: false,
                thur: false,
                fry: false,
                sat: false,
                sun: false,
            },
            start_hour: '',
            end_hour: ''
        }
    },
}
</script>
<template>
    <form
        class=" px-2 md:px-3 py-2 mt-4">
        <div class="grid gap-4 mb-4 grid-cols-4">
            <div class="col-span-4">
               <div class="flex justify-between items-between">
                   <SearchSelectInput
                       v-model:content="form.timezone"
                       :values="timezones"  />
               </div>

                <div class="flex justify-between items-between">
                    <h5 class="ml-2.5">Choose Days:</h5>
                </div>

                <div class="flex items-stretch justify-around text-red-900">
<!--                    <CheckboxInput :text="'All'" @click="chooseAllDays" />-->
                    <CheckboxInput :text="'Monday'" v-model:state="days.mon" />
                    <CheckboxInput :text="'Tuesday'" v-model:state="days.tue" />
                    <CheckboxInput :text="'Wednesday'" v-model:state="days.wedn" />
                    <CheckboxInput :text="'Thursday'" v-model:state="days.thur" />
                    <CheckboxInput :text="'Friday'" v-model:state="days.fry" />
                    <CheckboxInput :text="'Saturday'" v-model:state="days.sat" />
                    <CheckboxInput :text="'Sunday'" v-model:state="days.sun"/>
                </div>
            </div>


            <div class="col-span-4">
                <h5 class="ml-2.5">Choose Start Time:</h5>
                <DateInput
                    @update:date="updateStart"
                    v-model:date="start_hour"
                    :time_picker="true" :format="'HH:mm'" />
            </div>

            <div class="col-span-4">
                <h5 class="ml-2.5">Choose End Time:</h5>
                <DateInput
                    @update:date="updateEnd"
                    v-model:date="end_hour" :time_picker="true" :format="'HH:mm'" />
            </div>

            <div class="col-span-4">
                <hr class="h-px my-1 bg-gray-200 border-0 dark:bg-gray-700">
            </div>
            <div class="col-span-4">
                <Button
                        :loading="is_loading"
                        @clicked="updateForm(form)"
                        type="success"
                        :text="'Set Time'"
                        aditional_classes="float-right mt-4">
                    <template #icon_before>
                    <span style="width: 15px; margin: 2px 4px 0;">
                        <ClockIcon></ClockIcon>
                    </span>
                    </template>
                </Button>
            </div>
        </div>
    </form>
</template>

<style scoped lang="scss">
@media (max-width: 640px) {
    .edit_lead_wrapper {
        padding: 5px 5px 0px;
        margin: 0;

        .grid {
            margin-bottom: 5px;
        }
    }
}
</style>
