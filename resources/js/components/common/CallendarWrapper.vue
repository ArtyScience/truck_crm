<script type="module">
import Calendar from "./Calendar.vue";
export default {
  props: {
    state: Boolean
  },
  name: "CallendarWrapper",
  components: { Calendar }
}
</script>

<template>
  <div v-if="state">
    <Calendar />
  </div>
</template>

<style scoped lang="scss">

</style>