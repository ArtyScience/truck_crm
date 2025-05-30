<script>
export default {
  name: "Title",
  props: {
    title: String,
  }
}
</script>

<template>
  <h1 class="text-center  mb-2">
    <span class="p-1 rounded  gradient-text">{{ title }}</span>
  </h1>
</template>

<style scoped lang="scss">
.gradient-text {
  font-size: 1.1rem;
  font-weight: bold;
  background: linear-gradient(to right, #6b7280, #ded8d8, #6b7280);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  color: transparent;
}
</style>