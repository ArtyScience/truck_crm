<!--TODO: REFACTOR STYLES PUT IT IN PCSS-->
<script>
const css_settings = {
    sizes: {
        sm: 'px-1.5 py-0.5',
        md: 'px-2.5 py-1',
        lg: 'px-5 py-2.5'
    },
    types: {
        success: 'from-green-500 via-green-600 to-green-700 ' +
            'focus:ring-green-300 dark:focus:ring-green-800 ' +
            'shadow-lg shadow-green-500/50 dark:shadow-green-800/80',

        info: 'from-cyan-500 via-cyan-600 to-cyan-700 focus:ring-cyan-300 ' +
            'dark:focus:ring-cyan-800 focus:ring-blue-300 dark:focus:ring-blue-800 dark:shadow-cyan-800/80',

        primary: 'from-sky-700 via-sky-900 to-sky-950 focus:ring-blue-300' +
            ' dark:focus:ring-blue-800 shadow-blue-500/50 dark:shadow-blue-800/80',
        warning: 'from-lime-500 via-lime-600 to-lime-700 focus:ring-lime-300 dark:focus:ring-lime-800 shadow-lg shadow-lime-500/50 dark:shadow-lime-800/80',
        danger: 'from-gray-500 via-gray-600 to-gray-700 focus:ring-red-300 dark:focus:ring-red-800 dark:shadow-red-800/80',
        close: 'text-gray-400 bg-transparent hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:text-white'
    }
}
import {RefreshIcon} from "@heroicons/vue/solid";
export default {
    name: "Button",
    emits: ['clicked'],
    components: {RefreshIcon},
    props: {
        type: {
            type: String,
            required: true
        },
        text: {
            type: String,
            default: ''
        },
        size: {
            type: String,
            default: 'md'
        },
        loading: {
            type: Boolean,
            default: false
        },
        aditional_classes: String
    },
    data(){
        return {
            tooltip: false
        }
    },
    computed: {
        btn_size() {
            return css_settings.sizes[this.size]
        },
        btn_type() {
            return css_settings.types[this.type]
        }
    },
    methods: {
        click() {
            this.$emit('clicked', {type: this.type})
        },
        showTooltip() {
            this.tooltip = true
        },
    }
}
</script>

<template>
    <div class="btn_wrapper">
        <span v-show="tooltip">
              <div class="tooltip">
                  <span class="tooltip-text">{{ text }}</span>
              </div>
        </span>

        <button type="button"
                @click="click"
                @mouseover="showTooltip"
                :disabled="loading"
                class="text-white bg-gradient-to-r hover:bg-gradient-to-br focus:ring-2 focus:outline-none
                  shadow-lg dark:shadow-lg font-medium rounded-lg text-sm text-center "
                :class="btn_size, btn_type, aditional_classes">
            <slot name="icon_before"></slot>
                <div v-if="text">{{ text }}</div>
                <RefreshIcon v-if="loading"
                             :class="{'animate-spin': loading}"
                             class="btn_icon w-3 mr-2"></RefreshIcon>
            <slot name="icon_after"></slot>
        </button>
    </div>
</template>

<style lang="scss" scoped>
.btn_wrapper {

    button {
        text-shadow: 2px 2px 2px darkslategray;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: white ;
        display: flex;

        &:disabled {
            opacity: 0.7;
            box-shadow: 1px 1px 1px darkslategrey;
        }
    }
    @media (max-width: 640px) {
        .btn_text {
            font-size: 10px;
        }
        .btn_icon {
            width: 10px;
        }
    }


}

/* Tooltip container */
.tooltip {
    position: absolute;
    display: inline-block;
    cursor: pointer;
    z-index: 1000;
    top: 0;
    left: 0;
}

/* Tooltip text */
.tooltip .tooltip-text {
    background-color: #333; /* Background color */
    color: #fff; /* Text color */
    text-align: center;
    border-radius: 5px; /* Rounded corners */
    padding: 5px 0;
    position: absolute;
    z-index: 1;
    transform: translateX(-50%);
    opacity: 0; /* Hide initially */
    transition: opacity 0.3s; /* Smooth fade-in */
}

/* Tooltip arrow */
.tooltip .tooltip-text::after {
    content: "";
    position: absolute;
    top: 100%; /* Position arrow below the tooltip */
    left: 50%;
    transform: translateX(-50%);
    border-width: 5px;
    border-style: solid;
    border-color: #333 transparent transparent transparent; /* Arrow color matches tooltip background */
}

</style>
