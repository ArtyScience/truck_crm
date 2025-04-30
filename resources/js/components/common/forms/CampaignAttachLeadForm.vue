<script>
import GoogleLocationInput from "../GoogleLocationInput.vue";
import MutliSelectInput from "../../core/form/MutliSelectInput.vue";
import TextInput from "../../core/form/TextInput.vue";
import Button from "../../core/Button.vue";
import {LocationMarkerIcon, UserIcon} from "@heroicons/vue/solid";
import Paginator from "../../core/Paginator.vue";
import Table from "../../core/Table.vue";
import notifications from "../../../helpers/notifications.js";

export default {
    name: "CampaignAttachLeadForm",
    emits: ['update:submit', 'unsaved:chages'],
    components: {
        Table,
        Paginator,
        GoogleLocationInput, MutliSelectInput,
        TextInput, Button,
        LocationMarkerIcon,
        UserIcon},
    props: {
        content: {
            type: Object,
            required: true
        },
        leads: Object,
        leads_table: Object,
        btn_text: String,
        errors: Object,
        errors_text: Object
    },
    data() {
        return {
            headers: ['ID', 'Name'],
            attached_leads: {},
            leads_options: [],
            form: [{
                id: 0,
                name: 'Default'
            }],
            actions: {
                lead_loading: false,
                remove_lead_loading: false,
            }
        }
    },
    async mounted() {
        this.form = this.content
        const campaign_id = this.form.campaign.id
        const  url = 'api/v1/campaign/get-leads/' + campaign_id
        await axios.get(url).then((r) => {
            this.attached_leads = r.data
            if (r.data.length == 0) {
                notifications.info('Please attach leads to campaign')
            }
        })
        this.parseLeads()
    },
    computed: {
        tableData() {
            return this.leads_table
        }
    },
    watch: {
        'tableData'(newVal) {
            this.attached_leads.push(...newVal)
        }
    },
    methods: {
        parseLeads() {
            const leads = this.leads
            for (let lead in leads) {
                leads[lead].id = leads[lead].value
                leads[lead].name = leads[lead].label
            }

            this.leads_options = leads
            for (let lead in leads) {
                this.attached_leads.map((item) => {
                    if (leads[lead].id === item.id) {
                        this.leads_options = this.leads_options.filter((lead_opt) => {
                            return lead_opt.id != item.id
                        })
                    }
                })
            }
        },
        attachLeads() {
            if (this.form.attached_lead.length) {
                this.$emit('update:submit', this.form)
                this.actions.lead_loading = true
                setTimeout(() => {
                    this.actions.lead_loading = false
                    this.form.attached_lead = []
                }, 2000)
            } else {
                notifications.error('No leads selected')
            }
        },
        async removeItem(item) {
            const item_id = item.item
            const lead_to_remove = this.attached_leads.filter((item) => {
                return item.id === item_id
            })

            this.actions.remove_lead_loading = true
            const url = 'api/v1/campaign/remove-campaign-lead/'
                + item_id + '/' + this.form.campaign.id
            await axios.delete(url).then((r) => {
                if (r.status === 200) {
                    notifications.success(r.data.message)
                    this.attached_leads = this.attached_leads.filter((item) => {
                        return item.id !== item_id
                    })
                    this.leads_options.push(lead_to_remove[0])
                }
            }).catch((e) => {
                notifications.error(e.response.data.error)
            })
            this.actions.remove_lead_loading = false
        },
    }
}
</script>

<template>
  <div class="campaign_modal_wrapper">
      <div v-if="leads_options && form">
          <h1 class="font-bold pl-3 pt-2 text-center">Select Leads to attach: </h1>
          <div class="rounded w-1/1 flex justify-center">
              <div class="w-1/3">
                  <MutliSelectInput
                      v-model:value="form.attached_lead"
                      label="Leads"
                      v-model:content="leads_options"/>
              </div>
          </div>
      </div>
      <div class="row">
          <h1 class="font-bold pl-3 pt-2 text-center">Attached leads to campaign:</h1>
          <div class="">
              <Table @remove:item="removeItem"
                      v-model:data="attached_leads"
                      :show="false"
                      :edit="false"
                      :remove="true"
                      :remove_loading="actions.remove_lead_loading"
                      :headers="headers">
              </Table>
          </div>
      </div>
      <div class="row p-2">
          <Button
              class="float-end p-2"
              :loading="actions.lead_loading"
              @clicked="attachLeads"
              type="primary" text="Attach leads" />
      </div>
  </div>
</template>

<style lang="scss">
.campaign_modal_wrapper {
    overflow: hidden;
}

.multiselect--active {
    max-width: 300px !important;
}
</style>
