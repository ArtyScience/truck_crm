<script>
import axios from 'axios';
import {CogIcon, EyeIcon, PencilIcon,
  TrashIcon, ArrowNarrowDownIcon, PlusIcon} from '@heroicons/vue/solid'
import Paginator from "../components/core/Paginator.vue";
import Table from "../components/core/Table.vue";
import notifications from "../helpers/notifications.js";
import Search from "../components/common/Search.vue";
import Button from "../components/core/Button.vue";
import Modal from "../components/core/Modal.vue";
import UserForm from "../components/common/forms/UserForm.vue";
import ShowUserCard from "../components/common/cards/ShowUserCard.vue";

export default {
    name: "User",
    components: {
        ShowUserCard,
        Button, Search, Table,
        Paginator, CogIcon, EyeIcon, PencilIcon, Modal,
        TrashIcon, ArrowNarrowDownIcon, PlusIcon, UserForm},
    props: {
        data: {
            type: Object,
            required: true
        },
        roles: {
            type: Object,
            required: true
        }
    },
    created() {
        this.pagination = this.data
        this.users = this.data.data
    },
    data() {
        return {
            actions: {
                modal: false,
                show: false,
                create: false,
                edit: false,
                remove: false
            },
            form: {
                user: {},
            },
            modal_text: "",
            api_response: {},
            users: {},
            pagination: {},
            headers: ['Id', 'Name', 'Email', 'Campaign Email', 'Role'],
            body_columns_skip: ['password', 'password_confirmation'],
            errors: {
                name: false,
                email: false,
            },
            errors_text: {
                name: '',
                phone: '',
            },
        }
    },
    watch: {
        api_response(newVal, oldVal) {
            if (typeof newVal !== 'string') {
                this.api_response = newVal
            }
        },
        'actions.modal'(state) {
            if (!state) {
                this.cleanForm()
            }
        }
    },
    computed: {
        user_mock() {
            return {
                name: '',
                email: '',
                campaign_email: '',
                role: '',
            }
        },
    },
    methods: {
        cleanForm(){
            this.cleanActivity(this.actions)
            this.cleanActivity(this.errors)
        },
        cleanActivity(activity) {
            Object.keys(activity)
                .forEach(action => activity[action] = false);
        },
        async changePage(page) {
            const rows = 15;

            await axios.get('api/v1/user?rows='+rows+'&page=' + page)
                .then((r) => {
                    this.pagination = r.data
                    this.users = r.data.data
                })

        },
        remove($id) {
            this.tableUpdate($id);
            const uri = '/api/user/' + $id;
            const config = {method: 'DELETE', url: uri };
            this.axiosRequest(config)
        },
        tableUpdate($id) {
            this.users = this.users.filter(function( obj ) {
                return obj.id != $id;
            });
        },
        axiosRequest(config) {
            axios(config)
                .then(response => {
                    this.api_response = response.data;
                })
                .catch(error => {
                    if (error.code === 'ERR_BAD_REQUEST')
                        console.error(`ERROR: ${error}`)
                });
        },
        showItem(item) {
            this.form.user = item.item[0];
            this.actions.show = true;
            this.actions.modal = true;
            this.modal_text = 'Show user';
        },
        async removeItem(item) {
            const url = `api/v1/user/${item.item}`
            await axios.delete(url).then((r) => {
                if (r.status === 200) {
                    notifications.success("User removed " + item.item)
                    this.users = this.users.filter((user) => {
                        return user.id !== item.item
                    })
                }
            })
        },
        editItem(item) {
            const user = item[0]
            this.modal_text = 'Edit user'
            this.form.user = {...user}
            this.actions.modal = true
            this.actions.edit = true
        },
        createItem() {
            this.form.user = this.user_mock
            this.modal_text = 'Create user'
            this.actions.modal = true
            this.actions.create = true
        },
        closeModal() {
            this.actions.modal = false
        },
        submitForm() {
            if (this.actions.create) {
                const url = 'api/v1/user'
                axios.post(url, this.form.user).then((r) => {
                    setTimeout(() => {
                        this.actions.modal = false
                    }, 200)
                    notifications.success('User created')
                    this.users.unshift(r.data)
                    this.form.user = this.user_mock
                    this.actions.create = false
                }).catch((e) => {
                    if (e.response !== undefined && e.response.status === 422) {
                        const errors = e.response.data.errors
                        const error_message = this.parseErrors(errors)
                        notifications.validationError(error_message)
                    };
                })
            } else if (this.actions.edit) {
                const url = 'api/v1/user/' + this.form.user.id
                axios.put(url, this.form.user).then((r) => {
                    notifications.success('User edited')
                    this.form.user = this.user_mock
                    this.actions.edit = false
                    this.actions.modal = false
                    for (const taskKey in this.users) {
                        if (this.users[taskKey].id === r.data.id) {
                            this.users[taskKey] = r.data
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
    },

}
</script>

<template>
    <div
        @keydown.esc="closeModal"
        class="users_wrapper relative">
        <Modal
            v-model:state="actions.modal"
            :title="modal_text">
            <template #body>
                <div v-if="actions.create || actions.edit">
                    <UserForm
                        :roles="roles"
                        :errors="errors"
                        :errors_text="errors_text"
                        :content="form.user"
                        @update:submit="submitForm"
                    />
                </div>
                <div v-else-if="actions.show"
                     @keydown.esc="closeModal"
                     style="height: auto;">
                    <ShowUserCard :item="form.user" />
                </div>
            </template>
        </Modal>

        <div class="row-auto">
            <div class="flex justify-items-center">
                <Button
                    @click="createItem"
                    type="primary"
                    title="Create User"
                    class="cursor-pointer ml-auto mt-1.5" >
                    <template #icon_after>
                        <PlusIcon class="" style="width: 15px;"/>
                    </template>
                </Button>
                <Search :placeholder="'Search in Users'" />
            </div>
        </div>

        <Table @show:item="showItem"
               @remove:item="removeItem"
               @edit:item="editItem"
               :show="true" :remove="true" :edit="true"
               v-model:data="users"
               :body_columns_skip="body_columns_skip"
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
    .users_wrapper {

    }
</style>
