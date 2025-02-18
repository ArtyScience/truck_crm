<script>
import Editor from '@tinymce/tinymce-vue';
import Button from "../../core/Button.vue";
import notifications from "../../../helpers/notifications.js";
import TextInput from "../../core/form/TextInput.vue";

export default {
    name: "CampaignTemplateForm",
    components: {TextInput, Button, Editor },
    props: { campaign_id: Number },
    data() {
        return {
            is_loading: false,
            subject: '',
            body: '',
            api_key: 'xx1ancv67l51kezvigq6w01scc4wf6o27rumrftiqjp2mh8f',
            editor_toolbar: 'undo redo | bold italic underline ' +
                '| alignleft aligncenter alignright | bullist numlist outdent indent | code'};
    },
    async mounted() {
        await axios.get('api/v1/campaign/get-template/' + this.campaign_id)
            .then(r => {
                if (r.data.subject == '' || r.data.body == '') {
                    notifications.info('Please implement template subject and body')
                } else {
                    this.body = r.data.body;
                    this.subject = r.data.subject;
                }
        })
    },
    methods: {
        async saveTemplate() {
            this.is_loading = true
            await axios.post('api/v1/campaign/save-template', {
                campaign_id: this.campaign_id,
                body: this.body,
                subject: this.subject,
            }).then(r => {
                this.is_loading = false
                notifications.success(r.data.message);
            }).catch((e) => {
                if (e.response !== undefined && e.response.status === 500) {
                    notifications.validationError(e.response.data.error)
                };
            })
            this.is_loading = false
        }
    }
}
</script>

<template>
    <div class="template_wrapper">
        <div class="col-span-4 m-2">
<!--
                :error_text="errors_text.company"
                :error="errors.company"
-->
            <TextInput
                :add_input_classes="'h-fit'"
                :add_label_classes="'block mb-2 text-sm font-medium ' +
                     'dark:text-white dark:bg-yellow-700 rounded float-left'"
                :label="'Subject*'" v-model:content="subject" required/>
        </div>
        <div>
            <div style="height: 300px">
                <Editor
                    :api-key="api_key"
                    v-model="body"
                    :init="{ height: 300, menubar: false, plugins: 'lists link image table code help',
                     toolbar: this.editor_toolbar}"/>
            </div>
            <div class="row mt-2 mr-2 mb-2 float-end">
                <Button type="primary"
                        :loading="is_loading"
                        @clicked="saveTemplate"
                        text="Save Template" />
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">

.template_wrapper {
    overflow: hidden;
    min-height: 408px;
}

.output {
    margin-top: 20px;
    border: 1px solid #ddd;
    padding: 10px;
}
</style>
