<script>
import Button from "../components/core/Button.vue";
import TextInput from "../components/core/form/TextInput.vue";
import CustomInput from "../components/core/form/CustomInput.vue";
import CheckboxInput from "../components/core/form/CheckboxInput.vue";
import SelectInput from "../components/core/form/SelectInput.vue";
import vuedraggable from "vuedraggable/src/vuedraggable.js";

export default {
    name: "Components",
    components: {SelectInput, CheckboxInput, TextInput, Button, CustomInput, vuedraggable},
    data() {
        return {
            title: '',
            content: '',
            form: {
                name: '',
                checkbox: false
            },
            select: {
                items: [
                    {label: 'Madrid', value: 'Madrid'},
                    {label: 'Paris', value: 'Paris'},
                    {label: 'Milano', value: 'Milano'},
                    {label: 'Bucharest', value: 'Bucharest'},
                ],
                selected: 'Please select'
            },
            myArray: [
                {
                    "name": "Edgard",
                    "id": 6
                },
                {
                    "name": "Juan",
                    "id": 5
                },
                {
                    "name": "Joao",
                    "id": 2
                },
                {
                    "name": "John",
                    "id": 1
                },
                {
                    "name": "Johnson",
                    "id": 7
                }
            ],
            myArray2: [
                {
                    "name": "Wasea",
                    "id": 8
                },
                {
                    "name": "Shmasea",
                    "id": 9
                },
                {
                    "name": "Djimmy",
                    "id": 10
                },
                {
                    "name": "Keysy",
                    "id": 11
                },
                {
                    "name": "Naicy",
                    "id": 12
                }
            ]

        };
    }
}
</script>

<template>
    <div>
        <h2 class="font-bold">Buttons: </h2>
        <div><code style="font-size: 10px;">*loading on click</code></div>
        <div class="flex">
            <Button type="success" text="Submit" />
            <Button type="danger" text="Cancel" />
            <Button type="info" text="Info" />
            <Button type="warning" text="Warning" />
            <Button type="primary" text="Next" />
        </div>
    </div>
    <div>
        <h2 class="font-bold">Form inputs: </h2>
        <div class="row mt-2">
            <div class="columns-2">
                <TextInput :label="'Text Input'" v-model:content="form.name" />
                <p>Content: {{ form.name }}</p>
            </div>
        </div>
        <div class="row mt-4">
            <div class="columns-2">
                <CheckboxInput v-model:state="form.checkbox" />
                State:
                <span :class="{'text-green-700': form.checkbox, 'text-red-700': !form.checkbox}">
                    {{ form.checkbox }}
                </span>
            </div>
        </div>
        <div class="row mt-4 ">
            <div class="">
                <div class="w-1/4 float-left">
                    <SelectInput
                        :label="'Please choose your city:'"
                        v-model:selected="select.selected" :items="select.items" />
                </div>
                <div class="w-1/2 float-right">
                    <div>{{ select.selected }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-[200px]">
        <div class="flex justify-items-start">
            <div class="vue_draggable_wrapper">
                <vuedraggable
                    v-model="myArray"
                    group="people"
                    @start="drag=true"
                    @end="drag=false"
                    item-key="id">
                    <template #item="{element}">
                        <div>{{element.name}}</div>
                    </template>
                </vuedraggable>
            </div>
            <div class="vue_draggable_wrapper">
                <vuedraggable
                    v-model="myArray2"
                    group="people"
                    @start="drag=true"
                    @end="drag=false"
                    item-key="id">
                    <template #item="{element}">
                        <div>{{element.name}}</div>
                    </template>
                </vuedraggable>
            </div>
        </div>
    </div>
</template>
<style scoped lang="scss">
.vue_draggable_wrapper {
    border: 1px solid #ccc;
    padding: 10px;
    min-width: 100px;
    div {
        div {
            cursor: pointer;
        }
    }
}
</style>
