<script>
import Table from "../components/core/Table.vue";
import {FilterIcon,
    DocumentTextIcon,
    EyeIcon, PencilIcon, TrashIcon, InformationCircleIcon, PlusIcon} from "@heroicons/vue/solid";
import Button from "../components/core/Button.vue";
import 'vue3-toastify/dist/index.css';
import Modal from "../components/core/Modal.vue";
import LeadForm from "../components/common/forms/LeadForm.vue";
import Search from "../components/common/Search.vue";
import Paginator from "../components/core/Paginator.vue";
import notifications from "../helpers/notifications.js";
import ShowLeadCard from "../components/common/cards/ShowLeadCard.vue";
import CardMobile from "../components/mobile/CardMobile.vue";
import Swal from "sweetalert2";
import LeadsMobile from "./mobile/LeadsMobile.vue";
import ImportLeadsForm from "../components/common/forms/ImportLeadsForm.vue";
import DragAndDrop from "../components/common/DragAndDrop.vue";

export default {
    name: "Leads",
    props: {
        data: {
            type: Object,
            required: true
        },
        options: [Array, Object],
        deals: {
            type: Object,
            required: true
        },
    },
    components: {
        DragAndDrop,
        ImportLeadsForm,
        LeadsMobile, DocumentTextIcon,
        ShowLeadCard, CardMobile,
        Paginator, Search, LeadForm, Modal, FilterIcon, PlusIcon, Button, Table,
                  EyeIcon, PencilIcon, TrashIcon, InformationCircleIcon },
    async mounted() {
        this.form.lead = this.lead_mock
        this.resizeListener()
        if (this.is_mobile) {
            await axios.get('api/v1/leads?rows=5&page=1').then((r) => {
                this.pagination = r.data
                this.leads = r.data.data
            })
        }
    },
    data() {
        return {
            is_mobile: false,
            headers: ['Company', 'Location', 'Industry', 'Web Page', 'Volume', 'Status'],
            body_columns_skip: ['name', 'phone', 'email', 'notes', 'id', 'notes_full', 'created_at', 'created_at_formated', 'full_location'],
            modal_text: '',
            actions: {
                modal: false,
                show_lead: false,
                create_lead: false,
                edit_lead: false,
                remove_lead: false,
                import_leads: false,
            },
            table_item: {},
            form: {
                lead: {},
            },
            errors: {
                name: false,
                company: false,
                phone: false,
                email: false,
                location: false
            },
            errors_text: {
                name: '',
                company: '',
                phone: '',
                email: '',
                location: ''
            },
            leads: this.data.data,
            pagination: this.data,
            form_not_changed: true
        }
    },
    computed: {
        lead_mock() {
            return {
                id: '',
                name: '',
                company: '',
                phone: '',
                email: '',
                notes: '',
                web_page: '',
                company_volume: '',
                locations_list: []
            }
        },
        modalText() {
            return (this.actions.show_lead || this.actions.edit_lead)
                ? this.table_item.company : this.modal_text
        }
    },
    watch: {
        async 'actions.modal'(state){
            let form_modify_action = false
            if (this.actions.edit_lead === true || this.actions.create_lead === true) {
                form_modify_action = true
            }

            if (this.form_not_changed === false
                && form_modify_action
                && state === false) {
                await Swal.fire({
                    title: "Save changes?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes"
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.updateLead(this.form.lead)
                        this.form_not_changed = true
                        Swal.fire({
                            title: "Changes saved!",
                            icon: "success"
                        });
                        this.form.lead = this.lead_mock
                    } else {
                        this.form_not_changed = true
                        this.form.lead = this.lead_mock
                    }
                });
            }

            if (this.form_not_changed === true && this.actions.edit_lead === true && state === false) {
                this.form.lead = this.lead_mock
            }

            if (state === false) this.cleanForm()
        }
    },
    methods: {
        resizeListener() {
            this.checkForMobile()
            window.addEventListener('resize', () => {
                this.checkForMobile()
            });
        },
        checkForMobile() {
            const screenWidth = window.innerWidth;
            if (screenWidth <= 640) {
                this.is_mobile = true
            } else {
                this.is_mobile = false
            }
        },
        cleanForm(){
            this.cleanActivity(this.actions)
            this.cleanActivity(this.errors)
        },
        unsavedChanges(status) {
            if (this.actions.edit_lead === true
                || this.actions.create_lead === true) {
                this.form_not_changed = status
            }
        },
        updateImportedLeads(leads) {
            this.leads = {...this.leads, ...leads.data}
            this.actions.modal = false
            notifications.success('Leads imported with success')
        },
        updateLeadsTable(form_data) {
            for (const leadKey in this.leads) {
                if (this.leads[leadKey].id === form_data.id) {
                    const remove_from_table = ['additional_phone', 'additional_email',
                                               'additional_location', 'created_at', 'created_at_formated'];
                    remove_from_table.forEach(key => delete form_data[key]);

                    this.leads[leadKey].company = form_data.company
                    this.leads[leadKey].name = form_data.name
                    this.leads[leadKey].email = form_data.email
                    this.leads[leadKey].phone = form_data.phone
                    this.leads[leadKey].notes = form_data.notes
                    this.leads[leadKey].web_page = form_data.web_page
                    this.leads[leadKey].company_volume = form_data.company_volume

                    if (form_data.full_location !== undefined) {
                        const location_details = form_data.full_location
                        const location_fields = ['country' , 'administrative_area_level_1']
                        const location = location_details.address_components.filter((item) => {
                            const result = item.types.some(value => location_fields.includes(value));
                            if (result) return item
                        })
                        this.leads[leadKey].locations_list =
                            [ location[0].long_name + ' - ' + location[1].long_name ]
                    }

                    this.leads[leadKey].comodities_list =
                        form_data.comodities_list.map((comodity) => { return comodity.name })
                }
            }
        },
        parseErrors(errors) {
            for (const key in this.errors) {
                this.errors[key] = false
            }
            for (const errorsKey in errors) {
                this.errors_text[errorsKey] = errors[errorsKey][0]
                this.errors[errorsKey] = true
            }
            let html = ''
            for (let key in errors) {
                if (errors.hasOwnProperty(key)) {
                    html += errors[key][0] + '<br/>'
                }
            }

            return html
        },
        closeModal() {
            this.actions.modal = false
        },
        cleanActivity(activity) {
            Object.keys(activity)
                .forEach(action => activity[action] = false);
        },
        showItem(data) {
            this.modal_text = 'Show Lead'
            this.table_item = data.item[0]
            this.actions.show_lead = true
            this.actions.modal = true
        },
        importItem() {
            console.log('IMPORT MODAL OPEN');
            this.modal_text = 'Import Leads'
            this.actions.modal = true
            this.actions.import_leads = true
        },
        createItem() {
            this.modal_text = 'Create Lead'
            this.actions.modal = true
            this.actions.create_lead = true
        },
        async editItem(item) {
            let lead = item[0]
            this.table_item = lead
            this.form.lead = {...lead}
            const url = `api/v1/leads/comodities/${lead.id}`
            await axios.get(url).then((r) => {
                if (r.status === 200) {
                    this.modal_text = 'Edit Lead'
                    this.form.lead.comodities_list = r.data
                }
            })
            const contacts_url = `api/v1/leads/additional-contacts/${lead.id}`
            await axios.get(contacts_url).then((r) => {
                if (r.status === 200) {
                    this.form.lead = Object.assign({}, this.form.lead, r.data);
                }
            })

            this.actions.modal = true
            this.actions.edit_lead = true
        },
        async removeItem(data) {
            this.actions.remove_lead = true
            const url = `api/v1/leads/${data.item}`
            await axios.delete(url).then((r) => {
                if (r.status === 200) {
                    notifications.success("Lead removed " + data.item)
                    this.leads = this.leads.filter((item) => {
                        return item.id !== data.item
                    })
                }
            })
        },
        async updateLead(form_data) {
            if (this.actions.edit_lead) {
                const url = 'api/v1/leads'
                await axios.put(url + '/' + form_data.id, form_data).then((r) => {
                    if (r.data.success) {
                        this.cleanActivity(this.errors)
                        this.updateLeadsTable(form_data)
                        this.actions.edit_lead = false
                        this.actions.modal = false
                        notifications.success(r.data.message)
                        this.form.lead = this.lead_mock
                    }
                }).catch((e) => {
                    if (e.response.status === 422) {
                        const errors = e.response.data.errors
                        const error_message = this.parseErrors(errors)
                        notifications.validationError(error_message)
                    }
                });
            } else {
                this.form.lead = form_data
            }
        },
        async saveLead(form_data) {
            const url = 'api/v1/leads'
            await axios.post(url, form_data).then((r) => {
                setTimeout(() => {
                    this.actions.modal = false
                }, 200)
                notifications.success('Lead created')
                this.leads.unshift(r.data)
                this.form.lead = this.lead_mock
            }).catch((e) => {
                if (e.response !== undefined && e.response.status === 422) {
                    const errors = e.response.data.errors
                    const error_message = this.parseErrors(errors)
                    notifications.validationError(error_message)
                };
            })
        },
        async submitForm(form_data) {
            this.form_not_changed = true
            if (this.actions.edit_lead) {
                await this.updateLead(form_data)
            } else if (this.actions.create_lead) {
                await this.saveLead(form_data)
            }
        },
        async changePage(page) {
            let rows = 15
            if (this.is_mobile) rows = 5

            await axios.get('api/v1/leads?rows='+rows+'&page=' + page)
                .then((r) => {
                    this.pagination = r.data
                    this.leads = r.data.data
                })
        }
    }
}
</script>

<template>
    <div
        @keydown.esc="closeModal"
        class="leads_wrapper relative">
        <Modal
            v-model:state="actions.modal"
            :title="modalText">
            <template #body>
                <div v-if="actions.create_lead || actions.edit_lead">
                    <LeadForm
                        :btn_text="modal_text"
                        :errors="errors"
                        :errors_text="errors_text"
                        :content="form.lead"
                        @unsaved:chages="unsavedChanges"
                        @update:submit="submitForm"
                    />
                </div>
                <div v-else-if="actions.show_lead"
                     @keydown.esc="closeModal"
                     style="height: auto;">
                    <ShowLeadCard :item="table_item" />
                </div>
                <div v-if="actions.import_leads">
                    <ImportLeadsForm
                    @update:leads="updateImportedLeads"
                    />
                </div>
            </template>
        </Modal>

        <div class="row-auto">
            <div class="flex justify-items-right">
                <div class="w-1/5">
                    <div class="float-right">
                        <Button
                            @click="createItem"
                            type="primary"
                            title="Create Lead"
                            class="cursor-pointer inline-block">
                            <template #icon_after>
                                <PlusIcon class="" style="width: 15px;"/>
                            </template>
                        </Button>
                        <Button
                            @click="importItem"
                            type="info"
                            title="Import Leads"
                            class="cursor-pointer inline-block ml-3" >
                            <template #icon_after>
                                <DocumentTextIcon class="" style="width: 15px;"/>
                            </template>
                        </Button>
                    </div>
                </div>

                <Search :placeholder="'Search in Leads'" />
            </div>
        </div>

        <div >
<!--            <DragAndDrop :status_deals_list="deals" />-->
        </div>

        <div v-if="!is_mobile" class="leads_table_wrapper">
            <Table @show:item="showItem"
                   @remove:item="removeItem"
                   @edit:item="editItem"
                   :show="true"
                   :edit="true"
                   :remove="true"
                   :call="true"
                   v-model:data="leads"
                   :headers="headers"
                   :body_columns_skip="body_columns_skip">

                <template #pagination>
                    <Paginator
                        @page-changed="changePage"
                        :itemsPerPage="pagination.per_page"
                        :total-items="pagination.total" />
                </template>

            </Table>
        </div>
        <div v-else-if="is_mobile">
            <div>
                <LeadsMobile
                    @show:item="showItem"
                    @remove:item="removeItem"
                    @edit:item="editItem"
                    v-model:data="leads" />

                <div class="text-center mt-3">
                    <Paginator
                        @page-changed="changePage"
                        :itemsPerPage="pagination.per_page"
                        :total-items="pagination.total" />
                </div>
            </div>
        </div>
    </div>
</template>
