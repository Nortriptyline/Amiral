<template>
  <div class="block px-4 py-2"
  :class="[size, color]">
      <slot></slot>
  </div>
</template>

<script>
export default {
  props: {
    size: {
      default: "text-xs",
    },
    color: {
      default: "text-gray-400"
    }
  }
};
</script>

<style>
</style>