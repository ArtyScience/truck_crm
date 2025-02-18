<script>
import {CogIcon, EyeIcon, PencilIcon, TrashIcon, InformationCircleIcon,
    ArrowNarrowUpIcon, ArrowNarrowDownIcon, PlusIcon} from '@heroicons/vue/solid'
import Button from "./Button.vue";
import Badge from "./Badge.vue";

export default {
    name: "Table",
    components: {Badge, Button, CogIcon, EyeIcon, PencilIcon, TrashIcon,
        ArrowNarrowUpIcon, ArrowNarrowDownIcon, PlusIcon, InformationCircleIcon},
    emits: ['show:item', 'remove:item', 'edit:item'],
    props: {
        data: {
            type: [Array, Object],
            required: true
        },

        headers: Array,

        body_columns_skip: Array,
        body_columns: Object,

        show: Boolean,
        show_loading: Boolean,
        edit: Boolean,
        edit_loading: Boolean,
        remove: Boolean,
        remove_loading: Boolean
    },
    data() {
        return {
            result: {},
            api_response: {}
        }
    },
    watch: {
        api_response(newVal) {
            if (typeof newVal !== 'string') {
                this.api_response = newVal
            }
        }
    },
    mounted() {
        this.result = this.data
    },
    methods: {
        skipColumn(column) {
            for (const key in this.body_columns_skip) {
                if (this.body_columns_skip[key] === column) {
                    return true
                } else false
            }
        },
        editItem(id) {
            let result = this.data;
            const item = result.filter((item) => {
                return item.id === id
            })
            this.$emit('edit:item', item)
        },
        showItem(id) {
            const item = this.data.filter((item) => {
                return item.id === id
            })
            this.$emit('show:item', {item: item})
        },
        removeItem(id) {
            this.$emit('remove:item', {item: id})

            //  Swal.fire({
            //     title: "Remove?",
            //     icon: "warning",
            //     showCancelButton: true,
            //     confirmButtonColor: "#d33",
            //     cancelButtonColor: "#3085d6",
            //     confirmButtonText: "Yes"
            // }).then((result) => {
            //     if (result.isConfirmed) {
            //         /*TODO: Refactor all backend items ID*/
            //     }
            // });
        },
        getFirstArrayElement(array) {
          return array[0]
        },
        showHint(item_id) {
            if (this.$refs[item_id] !== undefined) {
                this.$refs[item_id][0].style.display = 'block'
            }
        },
        hideHint(item_id){
            if (this.$refs[item_id] !== undefined) {
                this.$refs[item_id][0].style.display = 'none'
            }
        }
    },
}
</script>

<template>
    <div class="table_wrapper relative mt-5">
        <table v-if="result" class="table  table-auto text-xs">
            <thead v-if="headers" class="">
                <tr class="font-bold text-cyan-900 dark:text-blue-100 ">
                    <th v-for="title in headers" class="relative rounded">
                        <div class="w-[100%]
                         bg-gray-500 rounded
                         inline-flex ml-1 cursor-pointer
                         dark:hover:text-green-500 px-3 py-1">
                            <div class="flex">
                                <ArrowNarrowUpIcon
                                    class="text-amber-300"
                                    style="width: 13px;"/>
                                <span class="ml-1 text-white">{{ title}}</span>
                            </div>
                        </div>
                    </th>
                    <th> <CogIcon class="settings_icon"/> </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in data"
                    class="hover:bg-blue-100 dark:hover:bg-blue-700 cursor-pointer">
                    <td v-for="(value, key) in item"
                        @click="showItem(item.id)"
                        v-show="!skipColumn(key)">
                        <div
                            class="relative">
                            <span v-if="Array.isArray(value) && value.length > 0">
                                <ul
                                    @mouseenter="showHint('tooltip_' + item.id + key)"
                                    @mouseleave="hideHint('tooltip_' + item.id + key)"
                                    class="list-none w-[100%]">
                                    <li class="mb-1 text-xs float-left flex">
                                        <Badge :text="getFirstArrayElement(value)" />
                                        <span
                                            class="text-yellow-600"
                                            v-if="value.length > 1">
                                            <InformationCircleIcon style="width: 15px;" />
                                        </span>
                                    </li>
                                </ul>
                                <div v-if="value.length > 1"
                                    class="tooltip_wrapper">
                                    <ul :ref="'tooltip_' + item.id + key"
                                        class="list-none absolute
                                                w-[100%] overflow-hidden
                                                p-1 rounded-[10px] bg-gray-100">
                                        <li v-for="text in value"
                                            class="mb-1 text-xs float-left">
                                            <Badge :text="text.replace(/^,+/, '')" />
                                        </li>
                                    </ul>
                                </div>
                            </span>
                            <span v-else-if="typeof value !== 'object'">{{ value }}</span>
                        </div>
                    </td>
                    <td>
                        <div
                            style="z-index: 100"
                            class="buttons_wrapper pt-2 w-[150px] flex justify-end">
                            <Button
                                v-if="show"
                                title="Show"
                                aditional_classes="mr-2"
                                @clicked="showItem(item.id)"
                                type="info">
                                <template #icon_after>
                                    <div style="width: 11px; padding: 0px 0px; border-radius: 100px" >
                                        <EyeIcon/>
                                    </div>
                                </template>
                            </Button>
                            <Button
                                v-if="edit"
                                title="Edit"
                                aditional_classes="mr-2"
                                @clicked="editItem(item.id)"
                                type="primary">
                                <template #icon_after>
                                    <div style="width: 11px; padding: 0px 0px; border-radius: 100px" >
                                        <PencilIcon/>
                                    </div>
                                </template>
                            </Button>
                            <Button
                                v-if="remove"
                                :loading="remove_loading"
                                title="Delete"
                                aditional_classes="mr-2"
                                @clicked="removeItem(item.id)"
                                type="danger">
                                <template #icon_after>
                                    <div style="width: 11px; padding: 0px 0px; border-radius: 100px" >
                                        <TrashIcon/>
                                    </div>
                                </template>
                            </Button>
                            <slot name="buttons"></slot>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <nav aria-label="Page navigation example" class="text-center mt-2">
            <slot name="pagination"></slot>
        </nav>
    </div>
</template>

<style scoped lang="scss">
.tooltip_wrapper {
    ul {
        z-index: 100;
        top: 0;
        right: -100px;
        display: none;
    }
}

.table_wrapper {
    button {
        z-index: 2;
    }
    table {
        margin: 0 auto;

        thead {
            tr {
                z-index: -1 !important;
                line-height: 16px;
                th {
                    padding: 2px 10px;
                    text-align: left;

                    .settings_icon {
                        max-width: 23px;
                        margin: 0 auto;
                    }
                }
            }
        }

        tbody {
            tr {
                border-bottom: 1px dashed lightslategray;
                td {
                    padding: 3px 5px 13px 17px;
                }
            }
        }
    }
}
</style>
