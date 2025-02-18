<script>
import CardMobile from "../../components/mobile/CardMobile.vue";
import Swal from "sweetalert2";

export default {
    name: "LeadsMobile",
    components: {CardMobile},
    emits: ['show:item', 'remove:item', 'edit:item'],
    props: {
        data: {
            type: [Array, Object],
            required: true
        },
    },
    methods: {
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
            this.$emit('remove:item', id)
        },
    }
}
</script>

<template>
    <div class="leads_mobile_wrapper">
        <CardMobile
            @show:item="showItem"
            @remove:item="removeItem"
            @edit:item="editItem"
            v-for="lead in data"
            :id="lead.id"
            :user-name="lead.name"
            :email="lead.email"
            :company="lead.company"
            :locations="lead.locations_list"
            :comodities="lead.comodities_list"
            :created_at="lead.created_at_formated"
            class="m-auto mt-2" />
    </div>
</template>

<style scoped lang="scss">
@media (max-width: 640px) {

}
</style>
