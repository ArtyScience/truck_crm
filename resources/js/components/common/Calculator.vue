<script>

import {CalculatorIcon, XIcon} from "@heroicons/vue/solid";
import * as mathjs from "mathjs";

const DEFAULT = 0;
export default {
    name: "Calculator",
    data() {
        return {
            expression: DEFAULT,
            calc: false
        };
    },
    components: {
        CalculatorIcon, XIcon
    },
    methods: {
        closeCalc() {
            this.calc = false
        },
        addToExpression(value) {
            if (this.expression === 0) {
                this.expression = '';
            }
            this.expression += value;
        },
        calculate() {
            try {
                this.expression = mathjs.evaluate(this.expression).toString();
            } catch (error) {
                this.expression = 'Error';
            }
        },
        clear() {
            this.expression = DEFAULT;
        },
        showCalculator() {
            this.calc = true;
        }
    },
}
</script>

<template>
    <div class="calculator_wrapper">
        <div class="action_button float-right" @click="showCalculator">
            <CalculatorIcon
                class="text-cyan-600 dark:text-white"/>
        </div>
        <div v-show="calc" class="calculator dark-theme">
            <div class="float-right">
                <div @click="closeCalc"
                     class="close_btn">
                    <XIcon />
              </div>
            </div>
            <div class="display">{{ expression }}</div>
            <div class="buttons">
                <button @click="addToExpression('1')">1</button>
                <button @click="addToExpression('2')">2</button>
                <button @click="addToExpression('3')">3</button>
                <button @click="clear()">C</button>
                <button @click="addToExpression('4')">4</button>
                <button @click="addToExpression('5')">5</button>
                <button @click="addToExpression('6')">6</button>
                <button @click="addToExpression('/')">/</button>
                <button @click="addToExpression('7')">7</button>
                <button @click="addToExpression('8')">8</button>
                <button @click="addToExpression('9')">9</button>
                <button @click="addToExpression('*')">x</button>
                <button @click="addToExpression('-')">-</button>
                <button @click="addToExpression('0')">0</button>
                <button @click="addToExpression('+')">+</button>
                <button @click="calculate()">=</button>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">

.calculator_wrapper {
    position: relative;

    .action_button {
        cursor: pointer;
        width: 35px;

        @media (max-width: 640px) {
            margin-right: 10px;
            width: 25px;
        }
    }
}

.calculator {
    right: 50px;
    position: absolute;
    width: 300px;
    margin: 0 auto;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);

    .close_btn {
        padding: 2px;
        cursor: pointer;
        position: absolute;
        top: 3px;
        right: 3px;
        width: 18px;
    }
}

.display {
    font-size: 24px;
    margin-bottom: 10px;
    padding: 10px;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    text-align: right;
}

.buttons {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    grid-gap: 10px;
}

button {
    padding: 10px;
    font-size: 18px;
    border: 1px solid #ddd;
    border-radius: 4px;
    cursor: pointer;
}

.calculator {
    // Existing styles ...
    &.dark-theme {
        background-color: #2b2b2b;
        color: #ffffff;

        .display {
            background-color: #3a3a3a;
            border: 1px solid #555;
        }

        button {
            background-color: #555;
            border: 1px solid #444;
            color: #fff;

            &:hover {
                background-color: #777;
            }
        }
    }
}

</style>
