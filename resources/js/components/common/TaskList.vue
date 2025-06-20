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
    <div class="task_list_wrapper max-w-lg mx-auto my-auto bg-white  pt-2 pr-2 mt-12 shadow shadow-slate-300">
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
    background-color: #004D61;
    //max-height: max-content;
    overflow-y: scroll;
    padding: 10px;

  &::-webkit-scrollbar {
    width: 8px;
  }
  /* Scrollbar Track Background */
  &::-webkit-scrollbar-track {
    background: #374151;
    border-radius: 6px;
  }
  /* Scrollbar Thumb */
  &::-webkit-scrollbar-thumb {
    background-color: #1F2937;
    border-radius: 6px;
    border: 1px solid #0B4161; /* Space around thumb */
  }
  /* Scrollbar Thumb on Hover */
  &::-webkit-scrollbar-thumb:hover {
    background-color: #555;
  }
}
</style>
