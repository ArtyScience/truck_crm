<script>
import {CurrencyDollarIcon} from "@heroicons/vue/solid";

export default {
    name: "Balance",
    data() {
        return {
            usd: '137.991'
        }
    },
    components: {
        CurrencyDollarIcon
    }
}
</script>

<template>
    <div class="ballance_wrapper">
        <div class="inlie-flex">
            <span class="price text-gray-500 dark:text-gray-200 inlie-flex">{{ usd }}</span>
            <span
                class="cursor-pointer text-green-700 dollar_wrapper inlie-flex float-right">
                <CurrencyDollarIcon />
            </span>
        </div>
    </div>

</template>

<style scoped lang="scss">
.ballance_wrapper {
    .dollar_wrapper {
        margin-top: 1px;
        width: 25px;
        svg {
            width: 25px;
        }
    }
    @media (max-width: 640px) {
        font-size: 13px;
        text-align: right;

        .price {
            padding-right: 5px;
            vertical-align: top;
            margin-right: 7px;
        }

        .dollar_wrapper {
            width: 15px;
            svg {
                width: 15px;
            }
        }
    }
}
</style>
