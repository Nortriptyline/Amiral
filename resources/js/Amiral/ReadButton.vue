<template>
  <button
    @click.prevent="markAsRead"
    class="self-start items-center pl-3 font-semibold text-xs uppercase tracking-widest focus:outline-none transition ease-in-out duration-150"
  >
    <svg
      xmlns="http://www.w3.org/2000/svg"
      viewBox="0 0 20 20"
      fill="currentColor"
      class="h-4 w-4 transform transition ease-in-out duration-300"
      :class="[colorClass, rotate]"
    >
      <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
      <path
        fill-rule="evenodd"
        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
        clip-rule="evenodd"
      />
    </svg>
  </button>
</template>

<script>
export default {
  props: {
    read: {
      default: false,
    },
    color: {
      default: "gray",
    },
    unreadable: {
      default: false
    },
  },
  data: function () {
    return {
      rotate: 0,
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
        this.textDepth +
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
    angle: function() {
      return this.readable ? "180" : "0";
    },
    textDepth: function() {
      return this.unreadable ? "400" : "700";
    },
    readable: function() {
      return this.unreadable ? false : this.read;
    }
  },
  methods: {
    markAsRead: function () {
      if (!this.unreadable) {
        this.rotate = this.read ? "rotate-180" : "rotate-0";
        this.$emit("mark-as-read");
      }
    },
  },
};
</script>

<style>
</style>