<script>
import Button from "../../core/Button.vue";
import TextInput from "../../core/form/TextInput.vue";
import {LocationMarkerIcon, PlusCircleIcon} from "@heroicons/vue/solid";
import MutliSelectInput from "../../core/form/MutliSelectInput.vue";
import GoogleLocationInput from "../GoogleLocationInput.vue";
import CheckboxInput from "../../core/form/CheckboxInput.vue";
import SelectInput from "../../core/form/SelectInput.vue";
import DateInput from "../../core/form/DateInput.vue";
import utility from "../../../helpers/utility.js";

/*TODO: Refactor classes put in PCSS*/

export default {
    name: "TaskForm",
    emits: ['update:submit', 'unsaved:chages'],
    components: {
        DateInput,
        SelectInput,
        CheckboxInput, GoogleLocationInput, MutliSelectInput, TextInput, Button,
        PlusCircleIcon, LocationMarkerIcon},
    props: {
        content: {
            type: Object,
            required: true
        },
        btn_text: String,
        errors: Object,
        errors_text: Object
    },
    methods: {
        selectLead(id) {
            this.form.lead_id = id
        },
        selectUser(id) {
            this.form.user_id = id
        },
        selectDeal(id) {
            this.form.deal_id = id
        },
        selectPriority(id) {
            this.form.priority = id
        },
        async getLeads() {
            const url = 'api/v1/leads/deals/get-leads';
            await axios.get(url).then((r) => {
                this.leads_options = r.data;
            });
        },
        async getDeals() {
            const url = 'api/v1/deal/all';
            await axios.get(url).then((r) => {
                this.deals_options = r.data;
            });
        },
        async getUsers() {
            const url = 'api/v1/user/all';
            await axios.get(url).then((r) => {
                this.users_options = r.data;
            });
        },
        updateTask() {
            this.$emit('update:submit', this.form);
        },
        updateDeal() {
            this.$emit('update:submit', this.form);
            setTimeout(()=>{
                this.actions.update_deal = false
            }, 3333)
        },
    },
    data() {
        return {
            leads: {},
            leads_options: [],
            deals_options: [],
            users_options: [],
            priority_options: [
                'Normal', 'Medium', 'Hight'
            ],
            form: { options: {},},
            comodities: [],
            actions: {
                update_deal: false
            },
            old_form: {},
            selected_priority: '',
        }
    },
     async mounted() {
        this.form = this.content
        await this.getLeads()
        await this.getUsers()
    },
    computed: {
        deals_type() {
            return [
                {
                    value: 1,
                    label: 'RFQ'
                },
                {
                    value: 2,
                    label: 'LIVE'
                },
                {
                    value: 3,
                    label: 'BID'
                }
            ]
        },
        equipment_type() {
            return [
                {
                    value: 1,
                    label: 'VAN'
                },
                {
                    value: 2,
                    label: 'Flatbed'
                }
            ]
        },
        shipment_type() {
            return  [
                {
                    value: 1,
                    label: 'LTL'
                },
                {
                    value: 2,
                    label: 'FTL'
                }
            ]
        },
        mainForm() {
            return { ...this.form }
        },
        priorities() {
            return [
                {
                    value: 0,
                    label: 'Normal'
                },
                {
                    value: 1,
                    label: 'Medium',
                },
                {
                    value: 2,
                    label: 'Hight',
                }
            ]
        },
    },
    watch: {
        'mainForm'(newVal) {
            // if (Object.keys(this.old_form).length === 0) {
            //     this.old_form = newVal
            // } else {
            //     const response = utility.compareObjects(newVal, this.old_form)
            //     this.$emit('unsaved:chages', response)
            // }
        }
    }
}
</script>
<template>
    <form
        @keydown.enter="updateDeal"
        class="edit_lead_wrapper px-2 md:px-3 py-2 mt-4">
        <div class="grid gap-4 mb-4 grid-cols-4">
            <div class="col-span-4">
               <div class="flex justify-between items-between">
                   <div class="w-1/3 mr-2">
                       <TextInput
                           :add_label_classes="'block mb-2 text-sm font-medium ' +
                     'dark:text-white dark:bg-cyan-700 rounded'"
                           :label="'Title*'"
                           :error="errors.title"
                           :error_text="errors_text.title"
                           v-model:content="form.title"
                           required/>
                   </div>
                   <div class="w-2/3 mr-2">
                       <TextInput
                           :error="errors.description"
                           :error_text="errors_text.description"
                           :add_label_classes="'block mb-2 text-sm font-medium ' +
                     'dark:text-white dark:bg-cyan-700 rounded'"
                           :label="'Description*'"
                           v-model:content="form.description"  required/>
                   </div>
               </div>
            </div>
            <div class="col-span-4">
                <div class="w-full mx-auto">
                    <div class="flex justify-center">
                        <div class="mr-2 w-1/4">
                            <SelectInput
                                :selected="form.lead_name"
                                label="Assign Lead"
                                @update:selected="selectLead"
                                :items="leads_options" />
                        </div>
                        <div class="mr-2 w-1/4">
                            <SelectInput
                                @update:selected="selectPriority"
                                label="Priority *"
                                :error="errors.priority"
                                :error_text="errors_text.priority"
                                :selected="priority_options[form.priority]"
                                :items="priorities" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-4">
                <hr class="h-px my-1 bg-gray-200 border-0 dark:bg-gray-700">
            </div>
            <div class="col-span-4">
                <DateInput
                    :no_past_date="true"
                    placeholder="Select task deadline"
                    :error="errors.deadline"
                    :error_text="errors_text.deadline"
                    v-model:date="form.deadline"
                />
            </div>
            <div class="col-span-4">
                <hr class="h-px my-1 bg-gray-200 border-0 dark:bg-gray-700">
            </div>
            <div class="col-span-4">
                <Button
                        :loading="actions.update_deal"
                        @clicked="updateTask"
                        type="primary"
                        :text="btn_text"
                        aditional_classes="float-right mt-4">
                    <template #icon_before>
                <span style="width: 15px; margin: 2px 4px 0;">
                    <PlusCircleIcon></PlusCircleIcon>
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
