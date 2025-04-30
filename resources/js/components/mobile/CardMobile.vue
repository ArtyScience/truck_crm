<script>
import {LocationMarkerIcon, CogIcon, EyeIcon, PencilIcon, TrashIcon} from "@heroicons/vue/solid";
import Button from "../core/Button.vue";
import Swal from "sweetalert2";


export default {
    name: "CardMobile",
    components: {Button, LocationMarkerIcon, CogIcon, EyeIcon, PencilIcon, TrashIcon},
    emits: ['edit:item', 'show:item', 'remove:item'],
    props: {
        id: Number,
        company: String,
        userName: String,
        email: String,
        locations: [Object],
        comodities: [Object],
        created_at: String,
    },
    mounted() {
    },
    methods: {
        editItem(id) {
            this.$emit('edit:item', id)
        },
        showItem(id) {
            this.$emit('show:item', id)
        },
        removeItem(id) {
            Swal.fire({
                title: "Remove Lead?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes"
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$emit('remove:item', {item: id})
                }
            });
        },
    }
}
</script>

<template>
    <div class="card">
        <div class="header">
            <span class="company-name">{{ company }}</span>
            <div class="actions flex float-right">
                <Button @clicked="showItem(id)" type="info">
                    <template #icon_before>
                        <EyeIcon style="width: 10px;" />
                    </template>
                </Button>
                <Button @clicked="editItem(id)" type="primary">
                    <template #icon_before>
                        <PencilIcon style="width: 10px;" />
                    </template>
                </Button>
                <Button @clicked="removeItem(id)" type="danger">
                    <template #icon_before>
                        <TrashIcon style="width: 10px;" />
                    </template>
                </Button>
            </div>
        </div>
        <div class="user_wrapper">
            <span class="user">{{ userName }}</span>
        </div>
        <div>
            <span class="email">{{ email }}</span>
        </div>
        <hr>
        <div class="location">
            <div class="origin">
                <span class="text-green-600">
                    <LocationMarkerIcon style="width: 12px" />
                </span>
                <span class="city">Location</span>
                <div class="w-[100%]">
                    <ul class="locations float-right">
                        <li v-for="location in locations"
                            class="float-left text-center mr-0.5 mb-0.5 p-0.5 bg-green-600 rounded">
                            {{ location.replace(/^,+/, '') }}
                        </li>
                    </ul>
                </div>
            </div>
            <div class="destination">
                <span class="text-cyan-600">
                    <CogIcon style="width: 12px" />
                </span>
                <span class="city">Comodities</span>
                <ul class="comodities">
                    <li v-for="comodity in comodities"
                        class="float-left mr-1 mb-0.5 p-0.5 bg-blue-500 rounded">
                        {{ comodity }}
                    </li>
                </ul>
            </div>
            <span class="date-time text-center"><b>Created at</b>: {{ created_at }}</span>
        </div>
    </div>
</template>

<style scoped lang="scss">
.card {
    width: 300px;
    padding: 5px 10px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    background-color: white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    line-height: 10px;

    .email {
        font-size: 12px;
    }

    .comodities, .locations {
        font-size: 10px;
        color: white;
    }

    .header {
        font-size: 12px;
        color: #4c6ef5;
        font-weight: bold;
        margin-bottom: 8px;

        .actions {
        }
    }

}

.user_wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.user {
    font-size: 15px;
    font-weight: bold;
    color: black;
}

.distance {
    font-size: 20px;
    font-weight: bold;
    color: black;
}

.details {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    font-size: 12px;
    color: #888;
    margin-bottom: 8px;
}

hr {
    border: none;
    border-top: 1px solid #e0e0e0;
    margin: 12px 0;
}

.location {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.origin, .destination {
    display: flex;
    align-items: center;
    gap: 8px;
}

.dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.blue {
    background-color: #3b82f6;
}

.green {
    background-color: #22c55e;
}

.city {
    font-weight: bold;
    font-size: 14px;
    color: black;
}

.date-time {
    font-size: 12px;
    color: #667;
}
</style>
