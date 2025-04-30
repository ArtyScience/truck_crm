<template>
    <div class="pagination-wrapper">
        <button
            class="prev_btn"
            @click="prevPage" :disabled="currentPage === 1">
            <span ><ArrowLeftIcon /></span>
        </button>
        <button
            v-for="page in pages"
            :key="page"
            @click="changePage(page)"
            :class="{ active: currentPage === page, 'text-lime-600': currentPage === page}"
        >
            {{ page }}
        </button>
        <button
            class="next_btn"
            @click="nextPage" :disabled="currentPage === totalPages">
            <ArrowRightIcon />
        </button>
    </div>
</template>

<script>
import {ArrowLeftIcon, ArrowRightIcon} from "@heroicons/vue/solid";
export default {
    name: "Paginator",
    components: { ArrowLeftIcon, ArrowRightIcon },
    props: {
        totalItems: {
            type: Number,
            required: true,
        },
        itemsPerPage: {
            type: Number,
            default: 10,
        },
    },
    data() {
        return {
            currentPage: 1,
        };
    },
    computed: {
        totalPages() {
            return Math.ceil(this.totalItems / this.itemsPerPage);
        },
        pages() {
            let startPage, endPage;
            if (this.totalPages <= 10) {
                startPage = 1;
                endPage = this.totalPages;
            } else {
                if (this.currentPage <= 6) {
                    startPage = 1;
                    endPage = 10;
                } else if (this.currentPage + 4 >= this.totalPages) {
                    startPage = this.totalPages - 9;
                    endPage = this.totalPages;
                } else {
                    startPage = this.currentPage - 5;
                    endPage = this.currentPage + 4;
                }
            }
            return [...Array((endPage + 1) - startPage).keys()].map(i => startPage + i);
        },
    },
    methods: {
        changePage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
                this.$emit('page-changed', this.currentPage);
            }
        },
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.$emit('page-changed', this.currentPage);
            }
        },
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
                this.$emit('page-changed', this.currentPage);
            }
        },
    },
};
</script>

<style scoped lang="scss">
.pagination-wrapper {
    margin-top: 20px;
    padding-bottom: 30px;
    align-items: center;

    button {
        background-color: #112d4e;
        color: white;
        margin: 0 2px;
        padding: 5px 10px;
        cursor: pointer;
        font-size: 13px;
        width: 35px;
        height: 35px;
        border-radius: 100px;
    }
    button.active {
        color: gold;
        font-weight: bold;
        border-top: 2px solid #112d4e;
        box-shadow: 0px 1px 3px black;
    }
    button:disabled {
        cursor: not-allowed;
    }

    .prev_btn, .next_btn {
        //margin: 0 0 2px;
        vertical-align: middle;
    }

    .prev_btn > i, .next_btn > i {
        border: 1px solid #112d4e;
        border-radius: 100%;
    }

    @media (max-width: 640px) {
        button {
            margin: 0 1px;
            padding: 5px 5px;
            cursor: pointer;
            font-size: 10px;
            width: 25px;
        }
    }

}

</style>
