<script>
import Button from "../../core/Button.vue";
import TextInput from "../../core/form/TextInput.vue";
import {LocationMarkerIcon, UserIcon, MailIcon, PhoneIcon} from "@heroicons/vue/solid";
import MutliSelectInput from "../../core/form/MutliSelectInput.vue";
import GoogleLocationInput from "../GoogleLocationInput.vue";
import utility from "../../../helpers/utility.js";
import notifications from "../../../helpers/notifications.js";

/*TODO: Refactor classes put in PCSS*/

export default {
    name: "ImportLeadsForm",
    emits: ['update:submit', 'unsaved:chages', 'update:leads'],
    components: {GoogleLocationInput, MutliSelectInput, MailIcon,
        TextInput, Button, LocationMarkerIcon, UserIcon, PhoneIcon},
    props: {
        btn_text: String,
        errors: Object,
        errors_text: Object
    },
    data() {
        return {
            file: null,
            form: {locations_list: [], additional_phone: '', additional_email: '', additional_location: []},
            comodities: [],
            location: [],
            add_location: [],
            actions: {
                import_leads: false
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
        handleFileUpload(event) {
            this.file = event.target.files[0]; // Get the selected file
        },
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
        async importLeads() {
            this.actions.import_leads = true

            if (!this.file) {
                alert("Please select a file");
                return;
            }

            const formData = new FormData();
            formData.append("file", this.file);

            try {
                const response = await axios.post("api/v1/leads/import-leads", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                }).catch((e) => {
                    if (e.response.status === 422) {
                        notifications.error(e.response.data.error)
                    }
                })
                this.$emit('update:leads', response.data.leads)
            } catch (error) {
                console.error("Upload failed:", error);
            }
            this.actions.import_leads = false
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
                <div class="relative overflow-x-auto">
                    <h3 class="mb-2"><b>Import columns example:</b></h3>
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Company</th>
                            <th scope="col" class="px-6 py-3">Name</th>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th scope="col" class="px-6 py-3">Phone</th>
                            <th scope="col" class="px-6 py-3">Notes</th>
                            <th scope="col" class="px-6 py-3">Location</th>
                            <th scope="col" class="px-6 py-3">Industry</th>
                            <th scope="col" class="px-6 py-3">Web-Page</th>
                            <th scope="col" class="px-6 py-3">Company Volume</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                            <td class="px-6 py-4">CocaCola</td>
                            <td class="px-6 py-4">John Doe</td>
                            <td class="px-6 py-4">johndoe@gmail.com</td>
                            <td class="px-6 py-4">878-123-3432</td>
                            <td class="px-6 py-4">Interested in load from Canada</td>
                            <td class="px-6 py-4">New York</td>
                            <td class="px-6 py-4">Technology</td>
                            <td class="px-6 py-4">www.google.com</td>
                            <td class="px-6 py-4">1000000</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-span-4">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                       for="file_input">Upload file</label>
                <input
                    @change="handleFileUpload"
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" type="file" />

            </div>
            <div class="col-span-4">
                <Button
                        :loading="actions.import_leads"
                        @clicked="importLeads"
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
