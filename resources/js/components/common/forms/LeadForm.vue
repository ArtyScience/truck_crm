<script>
import Button from "../../core/Button.vue";
import TextInput from "../../core/form/TextInput.vue";
import {LocationMarkerIcon, UserIcon, MailIcon, PhoneIcon} from "@heroicons/vue/solid";
import MutliSelectInput from "../../core/form/MutliSelectInput.vue";
import GoogleLocationInput from "../GoogleLocationInput.vue";
import utility from "../../../helpers/utility.js";

/*TODO: Refactor classes put in PCSS*/

export default {
    name: "LeadForm",
    emits: ['update:submit', 'unsaved:chages'],
    components: {GoogleLocationInput, MutliSelectInput, MailIcon,
        TextInput, Button, LocationMarkerIcon, UserIcon, PhoneIcon},
    props: {
        content: {
            type: Object,
            required: true
        },
        btn_text: String,
        errors: Object,
        errors_text: Object
    },
    data() {
        return {
            form: {locations_list: [], additional_phone: '', additional_email: '', additional_location: []},
            comodities: [],
            location: [],
            add_location: [],
            actions: {
                update_lead: false
            },
            old_form: {},
            show_add_phone: false,
            add_phone_btn_text: 'Add',
            show_add_email: false,
            add_email_btn_text: 'Add',
            show_add_location: false,
            add_location_btn_text: 'Add',
        }
    },
    methods: {
        addLocation() {
            this.show_add_location = !this.show_add_location
            if (!this.show_add_location) {
                this.add_location_btn_text = 'Add'
                delete this.form.additional_location
            } else {
                this.add_location_btn_text = 'Remove'
            }
        },
        addEmail() {
            this.show_add_email = !this.show_add_email
            if (!this.show_add_email) {
                this.add_email_btn_text = 'Add'
                delete this.form.additional_email
            } else {
                this.add_email_btn_text = 'Remove'
            }
        },
        addPhone() {
            this.show_add_phone = !this.show_add_phone
            if (!this.show_add_phone) {
                this.add_phone_btn_text = 'Add'
                delete this.form.additional_phone
            } else {
                this.add_phone_btn_text = 'Remove'
            }
        },
        saveNewComodity(comodity) {
            const url = 'api/v1/leads/comodities/create'
             axios.post(url, {name: comodity}).then((r) => {
                this.comodities.push(r.data)
            })
        },
        updateLocation(location) {
            this.form.full_location = location
            this.form.locations_list = [ location.formatted_address ]
        },
        updateAddLocation(location) {
            this.form.additional_location = location.formatted_address
        },
        updateLead() {
            this.$emit('update:submit', this.form);
            this.actions.update_lead = true
            setTimeout(()=>{
                this.actions.update_lead = false
            }, 3333)
        },
        updateComodities() {
            if (this.form.comodities_list !== undefined)
                this.form.comodities_list.map((item) => {
                    this.comodities = this.comodities
                        .filter((comodity) => {
                            return comodity.id !== item.id
                        })
                })
        },
        checkAdditionalFields() {
            if (this.form.additional_phone !== null
                && this.form.additional_phone.length > 0) {
                this.show_add_phone = true
                this.add_phone_btn_text = 'Remove'
            }
            if (this.form.additional_email !== null
                && this.form.additional_email.length > 0) {
                this.show_add_email = true
                this.add_email_btn_text = 'Remove'
            }
            if (this.form.additional_location !== null
                && this.form.additional_location.length !== 0) {
                this.add_location_btn_text = 'Remove'
                this.show_add_location = true
            }
        },
        async getLocation() {
            if (this.form.id !== "") {
                const url = 'api/v1/leads/location/' + this.form.id
                await axios.get(url).then((r) => {
                    this.location.push(r.data.location_name)
                })
            }
        },
    },
     async mounted() {
        this.form = Object.assign({}, this.form, this.content);
        const url = 'api/v1/leads/comodities'
        await axios.get(url).then((r) => { this.comodities = r.data })
        this.updateComodities(); await this.getLocation();
        this.checkAdditionalFields();
    },
    computed: {
        mainForm() {
            return { ...this.form }
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
        }
    }
}
</script>
<template>
    <form
        @keydown.enter="updateLead"
        class="edit_lead_wrapper px-2 md:px-3 py-2 mt-4">
        <div class="grid gap-4 mb-4 grid-cols-4">
            <div class="col-span-4">
                <TextInput
                    :add_input_classes="'h-fit'"
                    :add_label_classes="'block mb-2 text-sm font-medium ' +
                     'dark:text-white dark:bg-yellow-700 rounded float-left'"
                    :label="'Company'"
                    :error_text="errors_text.company"
                    :error="errors.company"
                    v-model:content="form.company"  required/>
            </div>
            <div class="col-span-2">
                <TextInput
                    :add_label_classes="'block mb-2 text-sm font-medium ' +
                     'dark:text-white dark:bg-cyan-700 rounded'"
                    :label="'Name'"
                    :error="errors.name"
                    :error_text="errors_text.name"
                    v-model:content="form.name"  required/>
            </div>
            <div class="col-span-2">
                <TextInput
                    :add_label_classes="'block mb-2 text-sm font-medium ' +
                     'dark:text-white dark:bg-cyan-700 rounded'"
                    :label="'Phone'"
                    :error_text="errors_text.phone"
                    :error="errors.phone"
                    v-model:content="form.phone"  required/>
            </div>
            <div class="col-span-2">
                <GoogleLocationInput
                    @update:location="updateLocation"
                    :value="location" :label="'Location'"/>
            </div>
            <div class="col-span-2">
                <TextInput
                    :add_label_classes="'block mb-2 text-sm font-medium ' +
                     'dark:text-white dark:bg-cyan-700 rounded'"
                    :label="'Email'"
                    :error_text="errors_text.email"
                    :error="errors.email"
                    v-model:content="form.email"  required/>
            </div>


            <div class="col-span-2">
                <TextInput
                    :add_input_classes="'h-fit'"
                    :add_label_classes="'block mb-2 text-sm font-medium ' +
                     'dark:text-white dark:bg-yellow-700 rounded float-left'"
                    :label="'Volume'"
                    v-model:content="form.company_volume"  required/>
            </div>


            <div class="col-span-2">
                <TextInput
                    :add_input_classes="'h-fit'"
                    :add_label_classes="'block mb-2 text-sm font-medium ' +
                     'dark:text-white dark:bg-yellow-700 rounded float-left'"
                    :label="'Web Page'"
                    v-model:content="form.web_page"  required/>
            </div>

            <div class="col-span-2">
                <TextInput
                    :add_input_classes="'h-fit'"
                    :add_label_classes="'block mb-2 text-sm font-medium ' +
                     'dark:text-white dark:bg-yellow-700 rounded float-left'"
                    :label="'Notes'"
                    v-model:content="form.notes"  required/>
            </div>
            <div class="relative col-span-2">
                <MutliSelectInput
                    @save:value="saveNewComodity"
                    v-model:value="form.comodities_list"
                    label="Industry"
                    v-model:content="comodities"/>
            </div>
            <div class="relative col-span-2">
                <h3 class="font-bold">Additional lead contacts:</h3>
            </div>
            <div class="relative col-span-4 flex justify-self-start">
                <Button type="info"
                        @clicked="addLocation"
                        :text="add_location_btn_text" class="mr-5">
                    <template #icon_after>
                        <LocationMarkerIcon class="ml-2" style="width: 15px;" />
                    </template>
                </Button>
                <Button type="info"
                        @clicked="addEmail"
                        :text="add_email_btn_text" class="mr-5">
                    <template #icon_after>
                        <MailIcon class="ml-2" style="width: 15px;" />
                    </template>
                </Button>
                <Button type="info"
                        @clicked="addPhone"
                        :text="add_phone_btn_text" class="mr-5">
                    <template #icon_after>
                        <PhoneIcon class="ml-2" style="width: 15px;" />
                    </template>
                </Button>
            </div>

            <div
                v-show="show_add_phone"
                class="col-span-4">
                <TextInput
                    :add_label_classes="'block mb-2 text-sm font-medium ' +
                     'dark:text-white dark:bg-cyan-700 rounded'"
                    :label="'Additional Phone'"
                    :error_text="errors_text.additional_phone"
                    :error="errors.additional_phone"
                    v-model:content="form.additional_phone"  required/>
            </div>
            <div
                v-show="show_add_email"
                class="col-span-4">
                <TextInput
                    :add_label_classes="'block mb-2 text-sm font-medium ' +
                     'dark:text-white dark:bg-cyan-700 rounded'"
                    :label="'Additional Email'"
                    :error_text="errors_text.additional_email"
                    :error="errors.additional_email"
                    v-model:content="form.additional_email"  required/>
            </div>
            <div
                v-show="show_add_location"
                class="col-span-4">
                <GoogleLocationInput
                    @update:location="updateAddLocation"
                    :value="form.additional_location" :label="'Additional Location'"/>
            </div>

            <div class="col-span-4">
                <Button
                        :loading="actions.update_lead"
                        @clicked="updateLead"
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
