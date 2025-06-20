<script>
import vuedraggable from "vuedraggable/src/vuedraggable.js";
import Button from "../components/core/Button.vue";
import { ChevronDoubleLeftIcon, ChevronDoubleRightIcon,  UploadIcon, PhoneIcon, MailIcon, MapIcon,
        PlusIcon, ChevronLeftIcon, ChevronRightIcon, ChevronUpIcon, PencilAltIcon, ChatIcon, InformationCircleIcon,
        ArrowCircleDownIcon, ArrowCircleUpIcon, CogIcon, TruckIcon, OfficeBuildingIcon, DocumentDuplicateIcon
} from "@heroicons/vue/solid";
import Search from "../components/common/Search.vue";
import LeadForm from "../components/common/forms/LeadForm.vue";
import Modal from "../components/core/Modal.vue";
import ShowLeadCard from "../components/common/cards/ShowLeadCard.vue";
import DealForm from "../components/common/forms/DealForm.vue";
import Badge from "../components/core/Badge.vue";
import notifications from "../helpers/notifications.js";
import Swal from "sweetalert2";

export default {
    name: "Deal",
    props: {deals: [Object, Array]},
    components: {
        Badge, DealForm, UploadIcon, ShowLeadCard, Modal, ChevronLeftIcon, ChevronRightIcon, OfficeBuildingIcon,
        InformationCircleIcon,
        ArrowCircleDownIcon, ArrowCircleUpIcon, LeadForm, Search, PlusIcon, Button, CogIcon, TruckIcon, ChevronUpIcon,
        draggable: vuedraggable, ChevronDoubleLeftIcon, ChevronDoubleRightIcon, ChatIcon, DocumentDuplicateIcon,
        PencilAltIcon, PhoneIcon, MailIcon, MapIcon},
    data() {
        return {
            prev_status: '',
            form_not_changed: true,
            actions: {
                modal: false,
                create: false,
                edit: false,
            },
            status_deals_list: [],
            deal_item: '',
            form: { deal: {} },
            errors: {},
            errors_text: {},
            modal_btn_text: '',
            modal_title_text: '',
        }
    },
    computed: {
        deal_mock() {
            return {
                name: '',
                lead_id: '',
                lead_tmp: '',
                status_id: '',
                income: '',
                outcome: '',
                company_contact: '',
                carrier_name: '',
                equipment_type: '',
                shipment_type: '',
                deal_type: '',
                pick_up_location: [],
                pick_up_location_tmp: [],
                delivery_location: [],
                delivery_location_tmp: [],
                pick_up_date: new Date(),
                delivery_date: new Date(),
                currency: '',
                pick_up: false,
                delivery: false,
                haz: false,
                tarp: false,
                temp: false,
            }
        },
        getDealId() {
            let deal_id
            Object.entries(this.deal_item).map((item) => {
                item.map((object) => {
                    if (object.element !== undefined)
                        deal_id = object.element.id
                })
            })
            return deal_id;
        },
    },
    mounted() {
        this.form.deal = this.deal_mock
        this.status_deals_list = this.deals
    },
    watch: {
        'actions.modal'(state){
            /*TODO: Refactor and improve, find another way of implementation*/
            // if (
            //     this.form_not_changed === false
            //     && state === false
            //     && this.actions.edit === true
            // ) {
            //     Swal.fire({
            //         title: "Save changes?",
            //         icon: "warning",
            //         showCancelButton: true,
            //         confirmButtonColor: "#3085d6",
            //         cancelButtonColor: "#d33",
            //         confirmButtonText: "Yes"
            //     }).then((result) => {
            //         if (result.isConfirmed) {
            //             Swal.fire({
            //                 title: "Changes saved!",
            //                 icon: "success"
            //             });
            //         } else this.form.deal = this.deal_mock
            //
            //         this.form_not_changed = true
            //     });
            // }

            if (state === false) this.cleanForm()
        }
    },
    methods: {
        makeCall(phone) {
            window.location.href = "tel:" + phone;
        },
        cleanForm(){
            this.cleanActivity(this.actions)
            this.cleanActivity(this.errors)
            this.form.deal = this.deal_mock
        },
        cleanActivity(activity) {
            Object.keys(activity)
                .forEach(action => activity[action] = false);
        },
        closeModal() {
            this.actions.modal = false
        },
        startElement(element) {
            this.prev_status = element.from.id
        },
        async editDeal(id) {
            this.modal_btn_text = 'Edit Deal'
            this.modal_title_text = 'Edit Deal'
            await axios.get('api/v1/deal/' + id + '/edit').then((r) => {
                this.form.deal = r.data.deal
            })
            this.actions.edit = true;
            this.actions.modal = true;
        },
        saveDeal(status) {
            const url = 'api/v1/deal/status/update';
            axios.post(url, {
                status_name: status.to.id,
                prev_status: this.prev_status,
                deal_id: this.getDealId
            })
        },
        prepareDeal(deal) {
            this.deal_item = deal
        },
        scrollLeft(type = null) {
            let distance = -100000;
            if (type === null) {
                distance = -250;
            }
            const container = this.$refs.scrollContainer
            container.scrollBy({
                left:distance,
                behavior: "smooth"
            });
        },
        scrollRight(type = null) {
            let distance = 100000;
            if (type === null) {
                distance = 250;
            }
            const container = this.$refs.scrollContainer
            container.scrollBy({
                left: distance,
                behavior: "smooth"
            });
        },
        createDeal() {
            this.modal_btn_text =  'Save new Deal'
            this.modal_title_text = 'Create Deal'
            this.actions.modal = true
            this.actions.create = true
        },
        async submitDeal(deal) {
            const url = 'api/v1/deal'
            if (this.actions.create) {
                await axios.post(url, deal).then((r) => {
                    const deal = r.data[0];
                    this.status_deals_list.Database = [deal, ...this.status_deals_list.Database]
                    notifications.success('Deal created ' + deal.id)
                    this.actions.modal = false
                    this.form.deal = this.deal_mock
                }).catch((e) => {
                    if (e.response !== undefined && e.response.status === 422) {
                        const errors = e.response.data.errors
                        const error_message = this.parseErrors(errors)
                        notifications.validationError(error_message)
                    };
                });
            } else if (this.actions.edit) {
                await axios.put(url + '/' + deal.id, deal).then((r) => {
                    for (const key in this.status_deals_list) {
                        this.status_deals_list[key].map((item, index) => {
                            if (item.id == deal.id) {
                                this.status_deals_list[key][index] = r.data.deal
                            }
                        })
                    }
                    notifications.success('Deal Updated ' + deal.id)
                    this.actions.modal = false
                    this.actions.edit = false
                    this.form.deal = this.deal_mock
                })
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
        unsavedChanges(status) {
            this.form_not_changed = status
        },
    }
}
</script>

<template>
    <div
        @keydown.esc="closeModal"
        class="deals_wrapper">
        <Modal
            v-model:state="actions.modal"
            :title="modal_title_text">
            <template #body>
               <DealForm
                   @unsaved:chages="unsavedChanges"
                   @update:submit="submitDeal"
                   :btn_text="modal_btn_text"
                   :errors="errors"
                   :errors_text="errors_text"
                   :content="form.deal" />
            </template>
        </Modal>

        <div class="top part pb-3">
            <div class="row-auto">
                <div class="flex justify-items-center">
                    <Button
                        @clicked="createDeal"
                        type="primary"
                        class="cursor-pointer ml-auto mt-1.5" >
                        <template #icon_after>
                            <PlusIcon
                                style="width: 15px;"/>
                        </template>
                    </Button>
                    <Search :placeholder="'Search in Deals'" />
                </div>
            </div>
        </div>
        <div class="horizontal-scroll-wrapper relative mt-3">
            <div class="flex" style="position: absolute; left: 45%; top: 0px; z-index: 9; cursor: pointer">
                <ChevronDoubleLeftIcon
                    @click="scrollLeft('max')"
                    class="text-cyan-600 p-0.5 border rounded-[100px] bg-white mr-5" style="width: 25px"
                ></ChevronDoubleLeftIcon>
                <ChevronLeftIcon
                    @click="scrollLeft(null)"
                    class="text-cyan-600 p-0.5 border rounded-[100px] bg-white mr-1" style="width: 25px"></ChevronLeftIcon>
                <ChevronRightIcon
                    @click="scrollRight(null)"
                    class="text-cyan-600 p-0.5 border rounded-[100px] bg-white" style="width: 25px"></ChevronRightIcon>
                <ChevronDoubleRightIcon
                    @click="scrollRight('max')"
                    class="text-cyan-600 p-0.5 border rounded-[100px] bg-white ml-5" style="width: 25px"
                ></ChevronDoubleRightIcon>
            </div>
            <section class="scroll-content"
                     @scroll="handleScroll"
                     ref="scrollContainer">
                <div v-for="(deal, status) in status_deals_list" class="p-0 drag_lists">
                    <div class="list_wrapper p-0.5 float-left">
                        <div class="statuses_wrapper w-[80%] m-auto">
                          <span class="w-10/12">{{ status.replace(/_/g, ' ') }}</span>
                          <span class="w-2/12 p-0 status mt-1">
                            <div class="text_color"></div>
                          </span>
                        </div>
                        <div class="item">
                            <draggable
                                :id="status"
                                :list="deal"
                                :group="{ name: 'items', pull: true, put: true }"
                                :emptyInsertThreshold="5"
                                @start="startElement"
                                @end="saveDeal"
                                @change="prepareDeal"
                                :itemKey="status"
                                class="list rounded dark:bg-gray-800">
                                <template #item="{ element }">
                                    <div :id="element.id" class="list-group-item">
                                        <div :id="'card_'+element.id" class="deal-card dark:bg-gray-700">
                                            <div class="deal-header">
                                                <div class="absolute right-0.5 top-0.5">
                                                    <Badge
                                                        class="dark:text-white dark:bg-sky-900"
                                                        width="px-0.5 py-0"
                                                        :title="element.title">
                                                        <template #icon>
                                                            <InformationCircleIcon style="width: 15px;" />
                                                        </template>
                                                    </Badge>
                                                </div>
                                                <div class="text-center" v-html="element.title" />
                                            </div>
                                            <div class="deal_options flex justify-between mt-1 mb-1 p-2">
                                                <div class="float-left">
<!--                                                    TODO: Implement Dynamic task list-->
                                                <Badge
                                                    width="px-1 py-0"
                                                    title="Deal Unique ID"
                                                    :text="element.id" />
                                                </div>
                                                <div class="text-green-600">
                                                    <ArrowCircleUpIcon class="w-3 float-left" />
                                                    {{ element.income }} $</div>
                                                <div class="text-amber-800">
                                                    <CogIcon class="w-3 float-left" />
                                                    {{ element.equipment }}
                                                </div>
                                                <div class="text-blue-800">
                                                    <TruckIcon class="w-3 float-left" />
                                                    {{ element.shipment }}</div>
                                            </div>
                                            <div class="deal-contact">
                                                <div>
                                                    <OfficeBuildingIcon
                                                        class="w-4 float-left mt-0.5 text-sky-950" />
                                                    <code style="font-size: 8px">lead: </code>
                                                    {{ element.lead_name }}
                                                </div>
                                                <div>
                                                    <TruckIcon
                                                        class="w-4 float-left mt-0.5 text-sky-950"
                                                    />
                                                    <code style="font-size: 8px">pick-up: </code> {{ element.pick_up_date }}
                                                </div>
                                            </div>
                                            <div class="deal-date">
                                                <code class="dark:text-slate-300" style="font-size: 8px">created: </code>
                                                <span class="dark:text-slate-100" >{{ element.created_at }}</span>
                                            </div>
                                            <div class="deal-actions">
                                                <Badge
                                                    width="px-2 py-0"
                                                    title="Tasks number"
                                                    :text="element.tasks ? element.tasks : 0" />

                                                <Button
                                                    size="sm"
                                                    title="Edit Deal"
                                                    @click="editDeal(element.id)"
                                                    type="primary">
                                                    <template #icon_after>
                                                        <PencilAltIcon class="w-3 float-left" />
                                                    </template>
                                                </Button>

                                                <Button
                                                    size="sm"
                                                    title="Make Call"
                                                    @clicked="makeCall(element.lead_phone)"
                                                    type="success">
                                                    <template #icon_after>
                                                        <PhoneIcon class="w-3 float-left" />
                                                    </template>
                                                </Button>

<!--                                                TODO: Implement buttons actions-->

<!--                                                <Button-->
<!--                                                    size="sm"-->
<!--                                                    title="Write Email"-->
<!--                                                    type="info">-->
<!--                                                    <template #icon_after>-->
<!--                                                        <MailIcon class="w-3 float-left" />-->
<!--                                                    </template>-->
<!--                                                </Button>-->

<!--                                                <Button-->
<!--                                                    size="sm"-->
<!--                                                    title="Open Chat"-->
<!--                                                    type="info">-->
<!--                                                    <template #icon_after>-->
<!--                                                        <ChatIcon class="w-3 float-left" />-->
<!--                                                    </template>-->
<!--                                                </Button>-->

<!--                                                <Button-->
<!--                                                    size="sm"-->
<!--                                                    title="Duplicate Deal"-->
<!--                                                    type="info">-->
<!--                                                    <template #icon_after>-->
<!--                                                        <DocumentDuplicateIcon class="w-3 float-left" />-->
<!--                                                    </template>-->
<!--                                                </Button>-->
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </draggable>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<style scoped lang="scss">
.deals_wrapper {
    margin: 0 auto;
    position: relative;
}

.scroll-content {
    z-index: 9;
    width: auto;
    padding-top: 30px;
    max-height: 700px;
    display: flex;
    white-space: nowrap;
    overflow-x: scroll;
    overflow-y: hidden;

  /*CUSTOM SCROLLBAR SETTINGS*/
  /* Scrollbar Track */
  &::-webkit-scrollbar {
    width: 8px;
  }
  /* Scrollbar Track Background */
  &::-webkit-scrollbar-track {
    background: #374151;
    border-radius: 6px;
  }
  /* Scrollbar Thumb */
  &::-webkit-scrollbar-thumb {
    background-color: #1F2937;
    border-radius: 6px;
    border: 1px solid #0B4161; /* Space around thumb */
  }
  /* Scrollbar Thumb on Hover */
  &::-webkit-scrollbar-thumb:hover {
    background-color: #555;
  }
    .drag_lists {
        &:first-child {
            .statuses_wrapper {
                .status {
                    background-color: rgb(21 128 61);
                    .text_color {
                        color: #FFF;
                    }
                }
            }
        }
        &:nth-child(2) {
            .statuses_wrapper {
                .status {
                    background-color: rgb(34 197 94);
                    .text_color {
                        color: #FFF;
                    }
                }
            }
        }
        &:nth-child(3) {
            .statuses_wrapper {
                .status {
                    background-color: rgb(100 116 139);
                    .text_color {
                        color: #FFF;
                    }
                }
            }
        }
        &:nth-child(4) {
            .statuses_wrapper {
                .status {
                    background-color: rgb(71 85 105);
                    .text_color {
                        color: #FFF;
                    }
                }
            }
        }
        &:nth-child(5) {
            .statuses_wrapper {
                .status {
                    background-color: rgb(101 163 13);
                    .text_color {
                        color: #FFF;
                    }
                }
            }
        }
        &:nth-child(6) {
            .statuses_wrapper {
                .status {
                    background-color: rgb(6 182 212);
                    .text_color {
                        color: #FFF;
                    }
                }
            }
        }
        &:nth-child(7) {
            .statuses_wrapper {
                .status {
                    background-color: rgb(8 145 178);
                    .text_color {
                        color: #FFF;
                    }
                }
            }
        }
        &:nth-child(8) {
            .statuses_wrapper {
                .status {
                    background-color: rgb(202 138 4);
                    .text_color {
                        color: #FFF;
                    }
                }
            }
        }
    }

    .list_wrapper {
        min-width: 250px;
        float: left;
        margin-top: 15px;
        .statuses_wrapper {
            margin-top: 15px;
            display: flex;
            justify-items: self-end;
            justify-content: space-around;
            .status {
                display: inline-block;
                text-transform: uppercase;
                padding: 3px 8px;
                border-radius: 15px;
                font-size: 14px;
                font-weight: bold;
                text-align: center;
                box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
                margin-bottom: 10px; /* Add space between status and card */
            }
            .text_color {
                text-shadow: 1px 1px 1px black;
            }
        }
    }
    .item {
        text-align: center;
        max-height: 700px;
        overflow-y: scroll;
        padding-right: 3px;
        margin-top: 20px;
        padding-bottom: 200px;

        /*CUSTOM SCROLLBAR SETTINGS*/
        /* Scrollbar Track */
        &::-webkit-scrollbar {
          width: 8px;
        }
        /* Scrollbar Track Background */
        &::-webkit-scrollbar-track {
          background: #374151;
          border-radius: 6px;
        }
        /* Scrollbar Thumb */
        &::-webkit-scrollbar-thumb {
          background-color: #1F2937;
          border-radius: 6px;
          border: 1px solid #0B4161; /* Space around thumb */
        }
        /* Scrollbar Thumb on Hover */
        &::-webkit-scrollbar-thumb:hover {
          background-color: #555;
        }
        .list {
            min-height: 700px;
            padding: 0px 10px;
            //background-color: #f9f9f9;
        }
    }
}

.dragg_text {
    font-size: 10px;
}

.deal-card {
    overflow: hidden;
    width: 235px;
    border-radius: 10px;
    padding: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    position: relative;
    cursor: pointer;
    margin: auto;
    margin-bottom: 5px;
}

.deal-header {
    text-align: center;
    overflow: hidden;
    display: block;
    font-size: 10px;
    font-weight: bold;
    color: #333;
    margin: 5px 0px 5px;
    padding: 0px 5px;
    border-radius: 10px;
    background-color: #f1f5f9;
}

.deal-info {
    font-size: 12px;
    color: #777;
    margin-bottom: 10px;
}

.deal_options {
    font-size: 12px;
    font-weight: bold;
}

.deal-contact {
    margin-top: 10px;
    font-size: 12px;
    text-align: center;
    margin-bottom: 8px;
}

.deal-date {
    font-size: 12px;
    color: #777;
    margin-bottom: 5px;
}

.deal-actions {
    margin-top: 20px;
    font-size: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 10px;
    background-color: transparent;
}

.activity-link {
    font-size: 12px;
    cursor: pointer;
}

.activity-date {
    font-size: 12px;
    color: #777;
}

.icon {
    font-size: 14px;
    color: #007bff;
    margin-right: 8px;
    cursor: pointer;
}

.profile-pic {
    width: 25px;
    height: 25px;
    border-radius: 50%;
    position: absolute;
    bottom: 10px;
    right: 10px;
}

.horizontal-scroll-wrapper {
    width: 100%;              /* Set desired width */

}

</style>
