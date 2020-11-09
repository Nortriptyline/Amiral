<template>
  <button
    :type="type"
    :class="classes"
    class="inline-flex items-center px-4 py-2 font-semibold text-xs uppercase tracking-widest focus:outline-none transition ease-in-out duration-150"
  >
    <slot></slot>
  </button>
</template>

<script>
export default {
  props: {
    type: {
      type: String,
      default: "button",
    },
    color: {
      default: "grey",
    },
    border: "",
  },
  data: function () {
    return {
      depths: {
        border: 300,
        text: 700,
        hover_text: 400,
        focus_border: 300,
        active_text: 900,
        active_bg: 50,
      },
    };
  },
  // transition ease-in-out duration-150
  computed: {
    classes: function () {
      var classes = "";
      classes += this.colorClass;
      classes += this.borderClass;
      
      return classes;
    },

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

    borderClass: function () {
      return this.border !== undefined
        ? "border border-" + this.color + "-" + this.depths.border + " rounded-md "
        : "";
    },

    bgClass: function () {
      return this.bg
        ? "bg-" +
            this.bg +
            " active:bg-" +
            this.bg +
            "-" +
            this.depths.active_bg +
            " "
        : "";
    },
  },
};
</script>
                // text-indigo-700 focus:outline-none focus:text-indigo-800 transition duration-150 ease-in-out
