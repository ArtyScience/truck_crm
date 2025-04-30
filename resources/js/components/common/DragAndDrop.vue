<script>
import {
    ArrowCircleUpIcon,
    CogIcon,
    InformationCircleIcon,
    OfficeBuildingIcon,
    PencilAltIcon,
    PhoneIcon, TruckIcon
} from "@heroicons/vue/solid";
import Button from "../core/Button.vue";
import draggable from "vuedraggable/src/vuedraggable.js";
import Badge from "../core/Badge.vue";
import notifications from "../../helpers/notifications.js";

export default {
    name: "DragAndDrop",
    props: {
        status_deals_list: [Array, Object]
    },
    components: {
        TruckIcon,
        Badge,
        ArrowCircleUpIcon,
        draggable,
        OfficeBuildingIcon,
        PencilAltIcon,
        InformationCircleIcon,
        PhoneIcon,
        Button,
        CogIcon
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
    <section class="scroll-content"
             @scroll="handleScroll"
             ref="scrollContainer">
        <div v-for="(deal, status) in status_deals_list" class="p-0 drag_lists">
            <div class="list_wrapper p-0.5 float-left">
                <div class="statuses_wrapper">
                    <div class="p-0 status">
                        <span class="text_color"> {{ status.replace(/_/g, ' ') }} </span>
                    </div>
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
                        class="list rounded">
                        <template #item="{ element }">
                            <div :id="element.id" class="list-group-item">
                                <div :id="'card_'+element.id" class="deal-card">
                                    <div class="deal-header">
                                        <div class="absolute right-0.5 top-0.5">
                                            <Badge
                                                color="text-sky-900"
                                                width="px-0.5 py-0"
                                                :title="element.title">
                                                <template #icon>
                                                    <InformationCircleIcon style="width: 15px;" />
                                                </template>
                                            </Badge>
                                        </div>
                                        <div class="text-center" v-html="element.title" />

                                    </div>
                                    <div class="deal_options flex justify-between mt-1 mb-1">
                                        <div class="float-left">
                                            <!--                                                    TODO: Implement Dynamic task list-->
                                            <Badge
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
                                        <code style="font-size: 8px">created: </code>
                                        {{ element.created_at }}</div>
                                    <div class="deal-actions">
                                        <Badge
                                            color="bg-sky-800 text-blue-100"
                                            width="px-1 py-0"
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
        .list {
            min-height: 700px;
            border: 1px solid #ddd;
            padding: 10px 0px;
            background-color: #f9f9f9;
        }
    }
}

.dragg_text {
    font-size: 10px;
}

.deal-card {
    overflow: hidden;
    width: 235px;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 15px;
    background-color: #ffffff;
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
    margin-bottom: 5px;
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
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 4px 5px;
    border-radius: 10px;
    background-color: #f1f5f9;
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
