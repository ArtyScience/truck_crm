<script>
import Button from "../../core/Button.vue";
import TextInput from "../../core/form/TextInput.vue";
import {LocationMarkerIcon, PlusCircleIcon} from "@heroicons/vue/solid";
import MutliSelectInput from "../../core/form/MutliSelectInput.vue";
import GoogleLocationInput from "../GoogleLocationInput.vue";
import CheckboxInput from "../../core/form/CheckboxInput.vue";
import SelectInput from "../../core/form/SelectInput.vue";
import DateInput from "../../core/form/DateInput.vue";
import utility from "../../../helpers/utility.js";

/*TODO: Refactor classes put in PCSS*/

export default {
    name: "CampainForm",
    emits: ['update:submit', 'unsaved:chages'],
    components: {
        DateInput,
        SelectInput,
        CheckboxInput, GoogleLocationInput, MutliSelectInput, TextInput, Button,
        PlusCircleIcon, LocationMarkerIcon},
    props: {
        content: {
            type: Object,
            required: true
        },
        btn_text: String,
        errors: Object,
        errors_text: Object
    },
    methods: {
        updateForm(form) {
            this.form_loading = true
            this.$emit('update:submit', form);
        },
    },
    mounted() {
        this.form = this.content
    },
    data() {
        return {
            form: {},
            form_loading: false
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
                   <div class="w-2/3 mr-2">
                       <TextInput
                           :add_label_classes="'block mb-2 text-sm font-medium ' +
                                               'dark:text-white dark:bg-cyan-700 rounded'"
                           :label="'Campain Name*'"
                           :error="errors.name"
                           :error_text="errors_text.name"
                           v-model:content="form.name"  required/>
                   </div>
               </div>
            </div>
            <div class="col-span-4">
                <hr class="h-px my-1 bg-gray-200 border-0 dark:bg-gray-700">
            </div>
            <div class="col-span-4">
                <Button
                        :loading="form_loading"
                        @clicked="updateForm(form)"
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
