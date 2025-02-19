<script>
import Button from "../../core/Button.vue";
import TextInput from "../../core/form/TextInput.vue";
import {LocationMarkerIcon, PlusCircleIcon} from "@heroicons/vue/solid";
import MutliSelectInput from "../../core/form/MutliSelectInput.vue";
import GoogleLocationInput from "../GoogleLocationInput.vue";
import CheckboxInput from "../../core/form/CheckboxInput.vue";
import SelectInput from "../../core/form/SelectInput.vue";
import DateInput from "../../core/form/DateInput.vue";

/*TODO: Refactor classes put in PCSS*/

export default {
    name: "UserForm",
    emits: ['update:submit', 'unsaved:chages'],
    components: {
        DateInput,
        SelectInput,
        CheckboxInput, GoogleLocationInput,
        MutliSelectInput, TextInput, Button,
        PlusCircleIcon, LocationMarkerIcon},
    props: {
        content: {
            type: Object,
            required: true
        },
        btn_text: String,
        errors: Object,
        errors_text: Object,
        roles: [Array, Object],
        actions: Object
    },
    methods: {
        submitForm() {
            this.$emit('update:submit', this.form);
            // this.actions.update_lead = true
            // setTimeout(()=>{
            //     this.actions.update_lead = false
            // }, 3333)
        },
        selectRole(role_id) {
            this.form.role = role_id
        }
    },
    mounted() {
        this.form = this.content
        this.user_roles = this.roles

        if (this.form.role !== '') {
            this.selected_role = this.form.role
        }
    },
    data() {
        return {
            form: {},
            user_roles: [],
            selected_role: ''
        }
    },
}
</script>
<template>
    <form
        class=" px-2 md:px-3 py-2 mt-4">
        <div class="grid gap-4 mb-4 grid-cols-4">
            <div class="col-span-4">
               <div class="flex justify-between items-between">
                   <div class="w-1/3 mr-2">
                       <TextInput
                           :add_label_classes="'block mb-2 text-sm font-medium ' +
                     'dark:text-white dark:bg-cyan-700 rounded'"
                           :label="'Name*'"
                           v-model:content="form.name"
                           :error="errors.name"
                           :error_text="errors_text.name"
                           required/>
                   </div>
                   <div class="w-1/3 mr-2">
                       <TextInput
                           :add_label_classes="'block mb-2 text-sm font-medium ' +
                     'dark:text-white dark:bg-cyan-700 rounded'"
                           :label="'Email*'"
                           v-model:content="form.email"
                           :error="errors.email"
                           :error_text="errors_text.email"
                           required/>
                   </div>
                   <div class="mr-2 w-1/3">
                       <SelectInput
                           @update:selected="selectRole"
                           :selected="selected_role"
                           label="Assign role"
                           :items="user_roles"
                           :error="errors.role"
                           :error_text="errors_text.role"
                       />
                   </div>
               </div>
                <div class="flex justify-between items-between mt-2">
                    <div class="w-2/4 mr-2">
                        <TextInput
                            :input_type="'password'"
                            :add_label_classes="'block mb-2 text-sm font-medium ' +
                     'dark:text-white dark:bg-cyan-700 rounded'"
                            :label="'Password*'"
                            v-model:content="form.password"
                            :error="errors.password"
                            :error_text="errors_text.password"
                            required/>
                    </div>
                    <div class="w-2/4 mr-2">
                        <TextInput
                            :input_type="'password'"
                            :add_label_classes="'block mb-2 text-sm font-medium ' +
                     'dark:text-white dark:bg-cyan-700 rounded'"
                            :label="'Password repeat*'"
                            v-model:content="form.password_confirmation"
                            :error="errors.password"
                            :error_text="errors_text.password"
                            required/>
                    </div>
                </div>
            </div>
            <div
                class="col-span-4">
                <div class="w-2/4 mr-2">
                    <TextInput
                        :add_label_classes="'block mb-2 text-sm font-medium ' +
                     'dark:text-white dark:bg-cyan-700 rounded'"
                        :label="'Campaign Email*'"
                        v-model:content="form.campaign_email"
                        :error="errors.campaign_email"
                        :error_text="errors_text.campaign_email"
                        required/>
                  <Button type="info" class="float-end">
                    <template #icon_after>
                      <PlusCircleIcon style="width: 15px" />
                    </template>
                  </Button>
                </div>
            </div>
            <div class="col-span-4">
                <hr class="h-px my-1 bg-gray-200 border-0 dark:bg-gray-700">
            </div>
            <div class="col-span-4">
                <Button
                        @clicked="submitForm"
                        type="primary"
                        :text="btn_text"
                        aditional_classes="float-right mt-4">
                    <template #icon_before>
                <span style="width: 15px; margin: 2px 4px 0;">
                    <PlusCircleIcon></PlusCircleIcon>
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
