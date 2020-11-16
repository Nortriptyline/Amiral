<template>
  <form @submit.prevent="$emit('toggle-notification')">
    <button
      class="self-start items-center pl-3 font-semibold text-xs uppercase tracking-widest focus:outline-none transition ease-in-out duration-150"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
        fill="currentColor"
        class="relative z-10 h-4 w-4 transform transition ease-in-out duration-300"
        :class="[colorClass, rotateClass]"
      >
        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
        <path
          fill-rule="evenodd"
          d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
          clip-rule="evenodd"
        />
      </svg>
    </button>
  </form>
</template>

<script>
export default {
  props: ["notification", "color", "lock"],
  data: function () {
    return {
      rotate: 0,
    };
  },
  computed: {
    colorClass: function () {
      return (
        "text-" +
        this.textColor +
        "-" +
        this.textDepth +
        " hover:text-" +
        this.textColor +
        "-" +
        this.textHover +
        " "
      );
    },
    rotateClass: function () {
      return "rotate-" + this.angle
    },
    textColor: function() {
      return this.color != null ? this.color : this.read ? "indigo" : "gray";
    },
    angle: function () {
      return this.read ? "180" : "0";
    },
    textDepth: function () {
      return this.read ? "700" : "400";
    },
    textHover: function () {
      return this.lock ? "400" : this.read ? "400" : "700";
    },
    read: function() {
      return !this.notification.read_at;
    }
  },
  methods: {

  },
};
</script>

<style>
</style>