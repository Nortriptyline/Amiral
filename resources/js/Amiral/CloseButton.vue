<template>
  <button
    class="self-start items-center pl-3 font-semibold text-xs uppercase tracking-widest focus:outline-none transition ease-in-out duration-150"
  >
    <svg
      xmlns="http://www.w3.org/2000/svg"
      viewBox="0 0 20 20"
      fill="currentColor"
      class="h-4 w-4 transform hover:rotate-90 transition ease-in-out duration-300"
      :class="colorClass"
    >
      <path
        fill-rule="evenodd"
        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
        clip-rule="evenodd"
      />
    </svg>
  </button>
</template>

<script>
export default {
  props: {
    color: {
      default: "grey",
    },
  },
  data: function () {
    return {
      depths: {
        text: 700,
        hover_text: 400,
        active_text: 900,
      },
    };
  },
  computed: {
    colorClass: function () {
      return (
        "text-" +
        this.color +
        "-" +
        this.depths.text +
        " hover:text-" +
        this.color +
        "-" +
        this.depths.hover_text +
        " focus:text-" +
        this.color +
        "-" +
        this.depths.active_text +
        " "
      );
    },
  },
};
</script>

<style>
</style>