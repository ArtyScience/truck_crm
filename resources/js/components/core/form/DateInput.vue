<script>
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

export default {
    name: "DateInput",
    emits: ['update:date'],
    components: {VueDatePicker},
    props: {
        date: [String, Date, Object],
        label: String,
        placeholder: String,
        error: Boolean,
        error_text: String,
        no_past_date: Boolean,
        format: String,
        time_picker: Boolean
    },
    data() {
        return {
            dateFormat: 'H:m - dd/MM/yyyy',
        };
    },
    mounted() {
      console.log('here date');
        if (this.format) {
            this.dateFormat = this.format
        }
    },
    computed: {
        dateValue: {
            get() {
                return this.date;
            },
            set(value) {
                if (value.minutes < 10) {
                    value.minutes = "0" + value.minutes
                }
                if (value.hours < 10) {
                    value.hours = "0" + value.hours
                }
                this.$emit('update:date', value);
            },
        },
    },
}
</script>

<template>
    <div class="date_wrapper relative" :class="{'error': error}">
        <label
            v-if="label"
            class="absolute text-sm text-gray-600 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-800 px-2 peer-focus:px-2 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1 block mb-2 text-sm font-medium dark:text-white dark:bg-yellow-700 rounded float-left">
            {{ label }}
        </label>
        <VueDatePicker
            class="dark:bg-gray-800"
            :time-picker="time_picker"
            :format="dateFormat"
            :min-date="no_past_date ? new Date() : null"
            :placeholder="placeholder"
            v-model="dateValue" />
        <span v-if="error" class="error_wrapper bg-gray-800
                text-red-400 dark:bg-gray-800 dark:text-red-400">{{ error_text }}</span>
    </div>
</template>

<style lang="scss">
.relative {
  .dp__pointer {
    background-color: rgb(75 85 99 / var(--tw-border-opacity, 1));
  }
    .error_wrapper {
        text-align: right;
        z-index: 1000;
        max-width: 150px;
        position: absolute;
        top: 0;
        right: 0;
        padding: 1px 5px 0;
        font-size: 9px;
        letter-spacing: 1px;
        text-transform: uppercase;
        border: 1px solid white;
        border-radius: 5px;
        font-weight: bold;
    }
    &.error {
        input {
            border: 1px solid red;
        }
    }
}

.dp__input_wrap {
    input {
        color: #fff;
        font-size: 0.875rem;
    }
}
</style>
