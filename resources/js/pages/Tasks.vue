<script>
import Table from "../components/core/Table.vue";
import Paginator from "../components/core/Paginator.vue";
import Button from "../components/core/Button.vue";
import Search from "../components/common/Search.vue";
import {PlusIcon} from "@heroicons/vue/solid";
import notifications from "../helpers/notifications.js";
import Modal from "../components/core/Modal.vue";
import ShowLeadCard from "../components/common/cards/ShowLeadCard.vue";
import LeadForm from "../components/common/forms/LeadForm.vue";
import TaskForm from "../components/common/forms/TaskForm.vue";
import ShowTaskCard from "../components/common/cards/ShowTaskCard.vue";

export default {
    name: "Tasks",
    components: {ShowTaskCard, TaskForm, LeadForm, ShowLeadCard, Modal, PlusIcon, Search, Button, Paginator, Table},
    props: {
        data: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            tasks: this.data.data,
            pagination: this.data,
            headers: ['ID', 'Title', 'Description', 'Deal', 'Lead', 'User', 'Status', 'Priority', 'Deadline'],
            form: {},
            actions: {
                modal: false,
                show: false,
                create: false,
                edit: false,
                remove: false
            },
            modalText: '',
            errors: {
                title: false,
                description: false,
                user_id: false,
                priority: false,
                deadline: false
            },
            errors_text: {
                title: '',
                description: '',
                user_id: '',
                priority: '',
                deadline: ''
            },
        }
    },
    computed: {
        task_mock() {
            return {
                title: '',
                description: '',
                user_id: '',
                deal_id: '',
                lead_id: '',
                status: 1,
                deadline: '',
                priority: '',
            }
        }
    },
    mounted() {
        this.tasks = this.data.data
        this.form = this.task_mock
    },
    watch: {
        async 'actions.modal'(state){
            if (!state && this.actions.edit) {
                this.form = this.task_mock
            }
        }
    },
    methods: {
        async updateTask(task) {
            const url = 'api/v1/task'
            await axios.post(url, task).then((r) => {
                this.form = this.task_mock
            })
        },
        async createTask () {
            this.modalText = 'Create Task';
            this.actions.modal = true
            this.actions.create = true
        },
        async removeItem(data) {
            const url = `api/v1/task/${data.item}`
            await axios.delete(url).then((r) => {
                if (r.status === 200) {
                    notifications.success("Task removed " + data.item)
                    this.updateTable(data.item)
                }
            })
        },
        async showItem(item) {
            this.modalText = 'Show task'
            this.actions.show = true
            this.actions.modal = true
            this.form = item.item[0]
        },
        async editItem(id) {
            this.modalText = 'Edit Task';
            await axios.get('api/v1/task/'+id[0].id+'/edit').then((r) => {
                this.form = r.data
                this.actions.modal = true
                this.actions.edit = true
            })
        },
        async changePage(page) {
            let rows = 25
            if (this.is_mobile) rows = 5

            await axios.get('api/v1/task?rows='+rows+'&page=' + page)
                .then((r) => {
                    this.pagination = r.data
                    this.tasks = r.data.data
                })
        },
        updateTable(id) {
            this.tasks = this.tasks.filter((item) => {
                return item.id !== id
            })
        },
        cleanActivity(activity) {
            Object.keys(activity)
                .forEach(action => activity[action] = false);
        },
        submitForm(form_data) {
            let url = 'api/v1/task'
            if (this.actions.create) {
                axios.post(url, form_data).then((r) => {
                    notifications.success(r.data.message)
                    this.cleanActivity(this.errors)
                    this.actions.modal = false
                    this.actions.create = false
                    this.form = this.task_mock
                    this.tasks.unshift(r.data.task)
                }).catch((e) => {
                    if (e.response !== undefined && e.response.status === 422) {
                        const errors = e.response.data.errors
                        const error_message = this.parseErrors(errors)
                        notifications.validationError(error_message)
                    };
                })
            } else if (this.actions.edit) {
                url = url + '/' + form_data.id
                axios.put(url, form_data).then((r) => {
                    notifications.success(r.data.message)
                    this.cleanActivity(this.errors)
                    for (const taskKey in this.tasks) {
                        if (this.tasks[taskKey].id === r.data.task.id) {
                            this.tasks[taskKey] = r.data.task
                        }
                    }
                }).catch((e) => {
                    if (e.response !== undefined && e.response.status === 422) {
                        const errors = e.response.data.errors
                        const error_message = this.parseErrors(errors)
                        notifications.validationError(error_message)
                    };
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
    }
}
</script>

<template>
    <div class="container-content">
        <Modal
            v-if="form"
            v-model:state="actions.modal"
            :title="modalText">
            <template #body>
                <div v-if="actions.create || actions.edit">
                    <TaskForm
                        :errors="errors"
                        :errors_text="errors_text"
                        @update:submit="submitForm"
                        :content="form" />
                </div>
                <div v-else-if="actions.show">
                    <ShowTaskCard :item="form" />
                </div>
            </template>
        </Modal>

        <div class="row-auto">
            <div class="flex justify-items-center">
                <Button
                    @clicked="createTask"
                    type="primary"
                    title="Create Task"
                    class="cursor-pointer ml-auto mt-1.5" >
                    <template #icon_after>
                        <PlusIcon class="" style="width: 15px;"/>
                    </template>
                </Button>
                <Search :placeholder="'Search in Tasks'" />
            </div>
        </div>
        <Table v-model:data="tasks"
               @remove:item="removeItem"
               @edit:item="editItem"
               @show:item="showItem"
               :show="true" :edit="true" :remove="true"
               :headers="headers">

            <template #pagination>
                <Paginator
                    @page-changed="changePage"
                    :itemsPerPage="pagination.per_page"
                    :total-items="pagination.total" />
            </template>
        </Table>
    </div>
</template>

<style scoped lang="scss">

</style>
