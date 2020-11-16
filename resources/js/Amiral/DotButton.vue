<template>
  <button type="button" @click.prevent="$emit('clicked')">
    <svg
      xmlns="http://www.w3.org/2000/svg"
      viewBox="0 0 20 20"
      fill="currentColor"
      :width="size"
      :height="size"
      :class="[colorClass]"
    >
      <path
        d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"
      />
    </svg>
  </button>
</template>

<script>
export default {
  props: {
    size: {
      default: 20,
    },
    color: {
      default: "gray-800",
    },
  },
  computed: {
    colorClass: function () {
      return "text-" + this.color;
    },
  },
};
</script>

<style>
</style>