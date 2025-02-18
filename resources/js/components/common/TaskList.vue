<script>
import {TrashIcon, CheckCircleIcon} from "@heroicons/vue/solid";
import notifications from "../../helpers/notifications.js";
import {InformationCircleIcon, ViewListIcon} from "@heroicons/vue/solid";
import Button from "../core/Button.vue";
export default {
    name: "TaskList",
    props: {
        tasks: Object
    },
    data() {
        return {
            tasks_list: []
        }
    },
    mounted() {
        this.tasks_list = this.tasks
    },
    components: {Button, TrashIcon, CheckCircleIcon, InformationCircleIcon, ViewListIcon},
    methods: {
        openTasks(){
            return window.open(
                '/task',
                '_blank' // <- This is what makes it open in a new window.
            );
        },
        taskDone(id) {
            const url = 'api/v1/task/done'
            axios.post(url, {id: id}).then((r) => {
                if (r.status === 200) {
                    this.tasks_list = this.tasks_list.filter((item) => {
                        return item.id !== id
                    })
                    notifications.success('Task done')
                }
            })
        }
    }
}
</script>

<template>
    <div class="task_list_wrapper max-w-lg mx-auto my-auto bg-white  pt-2 pr-2 rounded-xl shadow shadow-slate-300">
        <div class="text-center text-cyan-700">
            <h1 class="font-medium font-bold"><span class="border-2 p-1 rounded border-cyan-600">TASKS LIST</span></h1>
        </div>
        <div class="my-5 tasks_wrapper">
            <div class="flex flex-row justify-around items-center text-sm">
                <div class="font-bold">NAME</div>
                <div class="font-bold">DEADLINE</div>
            </div>
            <div v-if="tasks_list.length" v-for="task in tasks_list"
                 class="border-b border-slate-200 py-3 px-2 border-l-4  border-l-transparent bg-gradient-to-r from-transparent to-transparent hover:from-slate-100 transition ease-linear duration-150">
                <div class="flex justify-between items-stretch">
                    <div title="Done">
                        <CheckCircleIcon
                            @click="taskDone(task.id)"
                            class="cursor-pointer text-green-600"
                            style="width: 25px" />
                    </div>
                    <div>{{ task.title }}</div>
                    <div class="w-1/2 relative" :title="task.deadline">
                        <div class="w-[90%] bg-blue-600 rounded-full h-2.5 dark:bg-red-700 float-left mt-2.5">
                            <div class="bg-red-900 h-2.5 rounded-full" :style="{width: task.timeline}"></div>
                        </div>
                        <div class="w-[10%] float-right cursor-help p-2">
                            <InformationCircleIcon style="width: 15px; color: darkgoldenrod;"/>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else>
                <p>No tasks attached yet, please attach one in Tasks
                    <Button
                        @clicked="openTasks"
                        class="float-right mt-1" type="info">
                        <template #icon_after>
                            <ViewListIcon style="width: 12px" />
                        </template>
                    </Button>
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.task_list_wrapper {
    max-height: 400px;
    overflow-x: scroll;
    padding: 10px;
}
</style>
