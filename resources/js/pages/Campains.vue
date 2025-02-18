<script>
/* TODO: REFACTOR NAME */
import Button from "../components/core/Button.vue";
import {PlusIcon, PaperClipIcon, TrashIcon, PlayIcon, StopIcon,
    ClockIcon, SpeakerphoneIcon, MailIcon} from "@heroicons/vue/solid";
import Search from "../components/common/Search.vue";
import DealForm from "../components/common/forms/DealForm.vue";
import Modal from "../components/core/Modal.vue";
import CampainForm from "../components/common/forms/CampainForm.vue";
import notifications from "../helpers/notifications.js";
import common from "../helpers/common.js";
import CampaignAttachLeadForm from "../components/common/forms/CampaignAttachLeadForm.vue";
import CampaignTemplateForm from "../components/common/forms/CampaignTemplateForm.vue";
import CampaignScheduleForm from "../components/common/forms/CampaignScheduleForm.vue";
import CampaignTabs from "../components/common/tabs/CampaignTabs.vue";
import CampaignSteps from "../components/common/steps/CampaignSteps.vue";
import CampaignStatisticsCard from "../components/common/cards/CampaignStatisticsCard.vue";

export default {
    name: "Campains",
    components: {
        CampaignTabs, CampaignSteps, CampaignScheduleForm, StopIcon,
        CampaignTemplateForm, PlayIcon, CampaignAttachLeadForm, Modal, DealForm, Search,
        TrashIcon, SpeakerphoneIcon, CampaignStatisticsCard,
        PlusIcon, Button, CampainForm,MailIcon, PaperClipIcon, ClockIcon
    },
    props: {
        content: Object
    },
    data() {
        return {
            campaign_id: null,
            actions: {
                modal: false,
                create: false,
                edit: false,
                attach_lead: false,
                change_template: false,
                open_schedule: false,
            },
            form: {
                campaign: {},
                campaign_schedule: {},
                attached_lead: [],
            },
            leads: {},
            campaigns: {},
            campaign_leads: {},
            campaign_tabs_state: [],
            errors: {},
            errors_text: {},
            modal: { title: '', title_dynamic: '' },
            schedule_loading: false,
            campaign_start_loading: { id: "", status: true },
        }
    },
    computed: {
        campain_mock() {
            return { id: '', name: '', status: false }
        },
        current_campaign_name() {
            let campaign_name;
            if (this.campaign_id !== null) {
                this.campaigns.map((campaign) => {
                    if (campaign.id == this.campaign_id)
                        campaign_name = campaign.name
                })
            }
            return campaign_name
        },
        tab_status() {
            return this.actions.attach_lead
                || this.actions.change_template
                || this.actions.open_schedule
        }
    },
    async mounted() {
        this.form.campaign = this.campain_mock
        this.campaigns = this.content
        await this.getLeads()
    },
    watch: {
        'actions.modal'(state) {
            if (state === false) this.cleanForm()
        },
        'actions.attach_lead'(state) {
            if (state === true) {
                this.modal.title = 'Attach Leads to campaign'
                this.modal.title_dynamic = this.current_campaign_name
            }
        },
        'actions.change_template'(state) {
            if (state === true) {
                this.modal.title = 'Manage Template for Campaign'
                this.modal.title_dynamic = this.current_campaign_name
            }
        },
        'actions.open_schedule'(state) {
            if (state === true) {
                this.modal.title = 'Manage Schedule for Campaign'
                this.modal.title_dynamic = this.current_campaign_name
            }
        },
    },
    methods: {
        updateTabStatus(active_tab) {
            this.actions.attach_lead = false
            this.actions.change_template = false
            this.actions.open_schedule = false
            this.actions[active_tab] = true
            if (this.actions.open_schedule) {
                this.form.campaign_schedule.campaign_id = this.campaign_id
            }
        },
        attachLeadsToCampaign(name, id) {
            this.campaign_id = id
            this.actions.modal = true
            this.actions.attach_lead = true
            this.updateSelctedCampaign(name, id)
        },
        openSchedule(name, id) {
            this.campaign_id = id
            this.actions.open_schedule = true
            this.actions.modal = true
            this.updateSelctedCampaign(name, id)
            this.form.campaign_schedule = {
                campaign_id: id,
            }
        },
        openTemplate(name, id) {
            this.campaign_id = id
            this.actions.change_template = true
            this.actions.modal = true
            this.updateSelctedCampaign(name, id)
        },
        updateSelctedCampaign(name, id) {
            this.form.campaign = {
                id: id,
                name: name
            }
        },
        async getLeads() {
            const url = 'api/v1/leads/deals/get-leads';
            await axios.get(url).then((r) => {
                this.leads = r.data;
            });
        },
        cleanForm(){
            common.cleanActivity(this.actions)
            common.cleanActivity(this.errors)
            this.form = {
                campaign: {},
                attached_lead: [],
                campaign_schedule: {}
            }
            this.modal.title = ''
            this.modal.title_dynamic = ''
        },
        createCampaign() {
            this.modal.title = 'Create Campaign'
            this.actions.modal = true
            this.actions.create = true
        },
        /*TODO: IMPLEMENT ONLY FOR ADMIN*/
        // removeCampaign(id) {
        //     axios.delete('api/v1/campaign/' + id).then((r) => {
        //         if (r.status === 200) {
        //             notifications.success(r.data.message)
        //             this.campaigns = this.campaigns.filter((item) => {
        //                 return item.id !== id
        //             })
        //         }
        //     })
        // },
        saveAttachedLeads(data) {
            const url = 'api/v1/campaign/save-leads'
            axios.post(url, data).then((r) => {
                this.campaign_leads = r.data.attached_leads
                notifications.success('Leads attached');
                const url = 'api/v1/campaign/update-status'
                axios.post(url,
                    {
                        campaign_id: this.campaign_id,
                        status_update: {attach_leads: true}
                    }).then((r) => {
                        this.campaigns = this.campaigns.map((item) => {
                            if (item.id === this.campaign_id) {
                                item.steps_statuses = r.data.statuses
                            }
                            return item
                        })
                })
            })
        },
        async startScheduller(form) {
            form = {form, campaign_id: this.campaign_id}
            this.schedule_loading = true
            await axios.post('api/v1/campaign/store-scheduller', form)
                .then((r) => {
                    notifications.success(r.data.message)
                }).catch((e) => {
                if (e.response.status === 422) {
                    const errors = e.response.data.errors
                    const error_message = this.parseErrors(errors)
                    notifications.validationError(error_message)
                } else {
                    notifications.validationError(e.message)
                }
            })
            this.schedule_loading = false
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
        async startCampaign(campaign_id) {
            this.campaign_start_loading = {
                id: campaign_id,
                status: true
            }
            const url = 'api/v1/campaign/start-campaign'
            await axios.post(url, {id: campaign_id})
                .then((r) => {
                    notifications.success(r.data.message)
                    this.campaigns = this.campaigns.map((campaign) => {
                        if (campaign.id == campaign_id)
                            campaign.status = r.data.status

                        return campaign
                    })
                }).catch((e) => {
                    if (e.response !== undefined) {
                        notifications.error(e.response.data.message)
                    }
                })
            this.campaign_start_loading = {
                id: campaign_id,
                status: false
            }
        },
        async submitForm(form_data) {
            if (this.actions.create) {
                const url = 'api/v1/campaign'
                await axios.post(url, form_data).then((r) => {
                    r.data.steps_statuses =
                        {"attach_leads": false, "save_email": false, "set_time": false}
                    this.campaigns.push(r.data)
                    this.form.campaign = r.data
                    notifications.success('Campaign created with success')
                    this.actions.modal = false
                    this.actions.create = false

                    axios.post(url + '/attach-email', {
                        campaign_id: r.data.id
                    }).catch((e) => {
                        if (e.status === 500) {
                            notifications.error('SmartLead API Error')
                        }
                    })
                }).catch((e) => {
                    const errors_response = e.response.data.errors
                    const {html, errors, errors_text}
                        = common.formErrors(errors_response, this.errors_text)
                    this.errors = errors
                    this.errors_text = errors_text
                    notifications.validationError(html)
                })
            }
        }
    }
}
</script>

<template>
    <div class="">
        <Modal
            v-model:state="actions.modal"
            :title="modal.title"
            :title_dynamic="modal.title_dynamic">
            <template #body>
                <div v-if="actions.create || actions.edit">
                    <CampainForm
                        :errors="errors"
                        :errors_text="errors_text"
                        @update:submit="submitForm"
                        :btn_text="'Add new campain'"
                        :content="form.campaign" />
                </div>
                <div v-if="tab_status">
                    <CampaignTabs
                        @update:tab="updateTabStatus"
                        :actions="actions"  />
                    <div v-if="actions.attach_lead">
                        <CampaignAttachLeadForm
                            @update:submit="saveAttachedLeads"
                            :leads_table="campaign_leads"
                            :content="form" :leads="leads" />
                    </div>
                    <div v-if="actions.change_template">
                        <CampaignTemplateForm
                            :campaign_id="campaign_id" />
                    </div>
                    <div v-if="actions.open_schedule">
                        <CampaignScheduleForm
                            :is_loading="schedule_loading"
                            @update:submit="startScheduller"
                            :errors="errors"
                            :errors_text="errors_text"
                            :content="form.campaign_schedule" />
                    </div>
                </div>

            </template>
        </Modal>
        <div class="row-auto">
            <div class="flex justify-items-center">
                <Button
                    title="Create Campaign"
                    @clicked="createCampaign"
                    type="primary"
                    class="cursor-pointer ml-auto mt-1.5" >
                    <template #icon_after>
                        <PlusIcon
                            style="width: 15px;"/>
                    </template>
                </Button>
                <Search :placeholder="'Search in Campaigns'" />
            </div>
        </div>
        <div class="campain_wrapper">
           <div
               class="w-1/5 float-left m-1" v-for="campaign in campaigns">
               <div
                   style="min-height: 280px !important;"
                   class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                   <div
                       :title="campaign.status ? 'Active' : 'Inactive'"
                       class="relative float-right cursor-help">
                       <div v-if="campaign.status" class="w-[10px] h-[10px] bg-green-700 rounded-full animate-pulse"></div>
                       <div v-if="!campaign.status" class="w-[10px] h-[10px] bg-red-700 rounded-full animate-pulse"></div>
                   </div>
                   <div>
                       <h5 class="mb-2 italic tracking-tight text-gray-900 dark:text-white">
                           <SpeakerphoneIcon
                               class="m-3 text-yellow-600"
                               style="width: 25px; float:left;" />
                           <div class="pt-2 text-sm">
                               {{ campaign.name.slice(0, 15) }}
                           </div>
                       </h5>
                   </div>
                   <div class="flex w-full" style="min-height: 150px" >
                       <CampaignStatisticsCard
                           v-if="campaign.id"
                           :campaign_id="campaign.id" />
<!--                       <CampaignSteps v-model:campaign_steps="campaign.steps_statuses"  />-->
                   </div>
                   <div class="actions_wrapper flex justify-between">
                       <Button
                           @clicked="attachLeadsToCampaign(campaign.name, campaign.id)"
                           title="Attach Lead"
                           :size="'sm'"
                           type="primary">
                           <template #icon_after>
                               <PaperClipIcon style="width: 15px;" />
                           </template>
                       </Button>

                       <Button
                           title="Email Template"
                           @clicked="openTemplate(campaign.name, campaign.id)"
                           :size="'sm'"
                           type="info">
                           <template #icon_after>
                               <MailIcon style="width: 15px;" />
                           </template>
                       </Button>

                       <Button
                           title="Email Schedule"
                           @clicked="openSchedule(campaign.name, campaign.id)"
                           :size="'sm'" type="warning">
                           <template #icon_after>
                               <ClockIcon style="width: 15px;" />
                           </template>
                       </Button>

                       <Button
                           :loading="campaign_start_loading.id == campaign.id ? campaign_start_loading.status : false"
                           :title="!campaign.status ? 'Start Campaign' : 'Pause Campaign'"
                           @clicked="startCampaign(campaign.id)"
                           :size="'sm'" :type="!campaign.status ? 'success' : 'danger'">
                           <template #icon_after>
                               <div v-if="!campaign.status">
                                   <PlayIcon style="width: 15px;" />
                               </div>
                               <div v-else>
                                   <StopIcon style="width: 15px;" />
                               </div>
                           </template>
                       </Button>

<!--                       <Button-->
<!--                           @clicked="removeCampaign(campaign.id)"-->
<!--                           title="Remove Campaign"-->
<!--                           :size="'sm'"-->
<!--                           :type="'danger'">-->
<!--                           <template #icon_after>-->
<!--                               <TrashIcon style="width: 15px;" />-->
<!--                           </template>-->
<!--                       </Button>-->
                   </div>
               </div>
           </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.campain_wrapper {
    margin: 10px auto 0;
    overflow: hidden;
    padding-left: 200px;

    .actions_wrapper {
        width: 100%;
        padding-top: 10px;
    }

}
</style>
