<script>
import Button from "../../core/Button.vue";
import TextInput from "../../core/form/TextInput.vue";
import {LocationMarkerIcon, UserIcon} from "@heroicons/vue/solid";
import MutliSelectInput from "../../core/form/MutliSelectInput.vue";
import GoogleLocationInput from "../GoogleLocationInput.vue";
import CheckboxInput from "../../core/form/CheckboxInput.vue";
import SelectInput from "../../core/form/SelectInput.vue";
import DateInput from "../../core/form/DateInput.vue";
import utility from "../../../helpers/utility.js";
import SearchSelectInput from "../../core/form/SearchSelectInput.vue";

/*TODO: Refactor classes put in PCSS*/

export default {
    name: "DealForm",
    emits: ['update:submit', 'unsaved:chages'],
    components: {
        SearchSelectInput,
        DateInput,
        SelectInput,
        CheckboxInput, GoogleLocationInput, MutliSelectInput, TextInput, Button, LocationMarkerIcon, UserIcon},
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
        selectEquipment(id) {
            const equipment = this.equipment_type.filter((item) => {
                return item.value === id;
            })
            this.form.equipment_type = equipment[0].label
        },
        selectDeal(id) {
            const deal = this.deals_type.filter((item) => {
                return item.value === id;
            })
            this.form.deal_type = deal[0].label
        },
        selectShipment(id) {
            const shipment = this.shipment_type.filter((item) => {
                return item.value === id;
            })
            this.form.shipment_type = shipment[0].label
        },
        selectCurrency(id) {
            const currency = this.currencies.filter((item) => {
                return item.value === id;
            })
            this.form.currency = currency[0].label
        },
        selectLead(id) {
            this.form.lead_id = id
            const lead = this.leads_options.filter((item) => {
                return item.value === id;
            })
            this.form.lead_tmp = lead[0].label
        },
        async getLeads() {
            const url = 'api/v1/leads/deals/get-leads';
            await axios.get(url).then((r) => {
                this.leads_options = r.data;
            });
        },
        updateCheckbox(item) {
            this.form[item] = !this.form[item]
        },
        updatePickUpLocation(location) {
            this.form.pick_up_location = location
            this.form.pick_up_location_tmp = location.formatted_address
        },
        updateDeliveryLocation(location) {
            this.form.delivery_location = location
            this.form.delivery_location_tmp = location.formatted_address
        },
        updateDeal() {
            this.actions.update_deal = true
            this.$emit('update:submit', this.form);
            setTimeout(()=>{
                this.actions.update_deal = false
            }, 3333)
        },
        saveFormLead(lead) {
            this.form.lead_id = lead.id
            this.form.company_contact = lead.name
            this.lead_search = false
            this.lead_search_result = []
        },
        searchLead(search) {
            if (search.length > 2) {
                this.lead_search = true
                const search_by_contact = this.form.company_contact
                const url = 'api/v1/deal/get-leads-by-contact/' + search_by_contact
                axios.get(url).then((r) => {
                    this.lead_search_result = r.data.leads
                })
            } else {
                this.lead_search_result = []
                this.lead_search = false
            }
        }
    },
    data() {
        return {
            leads: {},
            leads_options: [],
            old_form: {},
            form: { options: {},},
            comodities: [],
            actions: { update_deal: false },
            lead_search: false,
            lead_search_result: []
        }
    },
     async mounted() {
        this.form = this.content
        await this.getLeads()
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
        currencies() {
            return [
                {
                    value: 'USD',
                    label: 'USD'
                },
                {
                    value: 'CAD',
                    label: 'CAD'
                }
            ];
        }
    },
    watch: {
        'mainForm'(newVal) {
            if (Object.keys(this.old_form).length === 0) {
                this.old_form = newVal
            } else {
                const response = utility.compareObjects(newVal, this.old_form)
                this.$emit('unsaved:chages', response)
            }
        },
        // 'form.company_contact'(searchValue, oldValue) {
        //     if (searchValue.length > 3 && oldValue !== searchValue) {
        //         this.lead_search = true
        //         const search_by_contact = this.form.company_contact
        //         const url = 'api/v1/deal/get-leads-by-contact/' + search_by_contact
        //         axios.get(url).then((r) => {
        //             this.lead_search_result = r.data.leads
        //         })
        //     } else {
        //         this.lead_search_result = []
        //     }
        // }
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
                       <SelectInput
                           @update:selected="selectDeal"
                           :error="errors.deal_type"
                           :error_text="errors_text.deal_type"
                           label="Deal Type"
                           :selected="form.deal_type"
                           :items="deals_type" />
                   </div>
                   <div class="w-2/3">
                       <TextInput
                           @key:down="searchLead"
                           @save:search="saveFormLead"
                           v-model:search="lead_search"
                           v-model:search_result="lead_search_result"
                           :add_label_classes="'block mb-2 text-sm font-medium ' +
                                                'dark:text-white dark:bg-cyan-700 rounded'"
                           :label="'Company Contact (Lead)'"
                           :error_text="errors_text.company_contact"
                           :error="errors.company_contact"
                           v-model:content="form.company_contact"  required/>
                   </div>
               </div>
            </div>
            <div class="col-span-2">
                <GoogleLocationInput
                    @update:location="updatePickUpLocation"
                    :error="errors.pick_up_location"
                    :error_text="errors_text.pick_up_location"
                    :value="form.pick_up_location_tmp" :label="'Pick Up Location'"/>
            </div>
            <div class="col-span-2">
                <GoogleLocationInput
                    @update:location="updateDeliveryLocation"
                    :error="errors.delivery_location"
                    :error_text="errors_text.delivery_location"
                    :value="form.delivery_location_tmp" :label="'Delivery Location'"/>
            </div>
            <div class="col-span-2">
                <DateInput
                    :label="'Pick up Date'"
                    :error="errors.pick_up_date"
                    :error_text="errors_text.pick_up_date"
                    :no_past_date="true"
                    placeholder="Select PickUp Date"
                    v-model:date="form.pick_up_date"
                />
            </div>
            <div class="col-span-2">
                <DateInput
                    :label="'Delivery Date'"
                    :error="errors.delivery_date"
                    :error_text="errors_text.delivery_date"
                    :no_past_date="true"
                    placeholder="Select Delivery Date"
                    v-model:date="form.delivery_date"
                />
            </div>
            <div class="col-span-4">
                <div class="w-full mx-auto">
                    <div class="flex justify-center">
                        <div class="mr-1 w-1/5">
                            <SelectInput
                                @update:selected="selectCurrency"
                                label="Currency"
                                :selected="form.currency"
                                :items="currencies" />
                        </div>
                        <div class="mr-2 w-1/5">
                            <SelectInput
                                @update:selected="selectEquipment"
                                :error="errors.equipment_type"
                                :error_text="errors_text.equipment_type"
                                label="Equipment Type"
                                :selected="form.equipment_type"
                                :items="equipment_type" />
                        </div>
                        <div class="mr-2 w-1/5">
                            <SelectInput
                                @update:selected="selectShipment"
                                :error="errors.shipment_type"
                                :error_text="errors_text.shipment_type"
                                label="Shipment Type"
                                :selected="form.shipment_type"
                                :items="shipment_type" />
                        </div>
                        <div class="mr-2 w-1/5">
                            <TextInput
                                :add_input_classes="'h-fit'"
                                :add_label_classes="'block mb-2 text-xs font-medium ' +
                    'dark:text-white dark:bg-yellow-700 rounded float-left'"
                                :label="'$ Income:'" :error_text="errors_text.income"
                                :error="errors.income"
                                v-model:content="form.income" required/>
                        </div>
                        <div class="mr-2 w-1/5">
                            <TextInput
                                :add_input_classes="'h-fit'"
                                :add_label_classes="'block mb-2 text-xs font-medium ' +
                        'dark:text-white dark:bg-yellow-700 rounded float-left'"
                                :label="'$ Outcome:'" :error_text="errors_text.outcome" :error="errors.outcome"
                                v-model:content="form.outcome" required/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-4">
                <hr class="h-px my-1 bg-gray-200 border-0 dark:bg-gray-700">
            </div>
            <div class="col-span-4">
                <div class="w-2/3 mx-auto">
                    <div class="flex justify-between">
                        <CheckboxInput
                            @update:state="updateCheckbox('pick_up')"
                            class="border border-gray-300 rounded"
                            :state="form.pick_up"
                            :text="'Pick up'" />
                        <CheckboxInput
                            @update:state="updateCheckbox('delivery')"
                            :state="form.delivery"
                            class="border border-gray-300 rounded"
                            :text="'Delivery'" />
                        <CheckboxInput
                            @update:state="updateCheckbox('haz')"
                            :state="form.haz"
                            class="border border-gray-300 rounded" :text="'HAZ'" />
                        <CheckboxInput
                            @update:state="updateCheckbox('tarp')"
                            :state="form.tarp"
                            class="border border-gray-300 rounded" :text="'TARP'" />
                        <CheckboxInput
                            @update:state="updateCheckbox('temp')"
                            :state="form.temp"
                            class="border border-gray-300 rounded" :text="'TEMP'" />
                    </div>
                </div>
            </div>
            <div class="col-span-4">
                <TextInput
                    :add_input_classes="'h-fit'"
                    :add_label_classes="'block mb-2 text-sm font-medium ' +
                     'dark:text-white dark:bg-yellow-700 rounded float-left'"
                    :label="'Comment'"
                    v-model:content="form.comment"  required/>
            </div>
            <div class="col-span-4">
                <Button
                        :loading="actions.update_deal"
                        @clicked="updateDeal"
                        type="primary"
                        :text="btn_text"
                        aditional_classes="float-right mt-4">
                    <template #icon_before>
                <span style="width: 15px; margin: 2px 4px 0;">
                    <UserIcon />
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
