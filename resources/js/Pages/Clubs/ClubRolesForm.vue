<template>
  <jet-form-section @submitted="updateClubRoles">
    <template #title> Club Roles </template>

    <template #description> Manage your club's roles </template>

    <template #form>
      <!-- Name -->
      <div class="col-span-6 sm:col-span-4">
        <jet-label for="name" value="Name" />
        <jet-input
          id="name"
          type="text"
          class="mt-1 block w-full"
          v-model="form.name"
          autocomplete="name"
        />
        <jet-input-error :message="form.error('name')" class="mt-2" />
      </div>
    </template>
  </jet-form-section>
</template>

<script>
import JetButton from "@/Jetstream/Button";
import JetFormSection from "@/Jetstream/FormSection";
import JetInput from "@/Jetstream/Input";
import JetInputError from "@/Jetstream/InputError";
import JetLabel from "@/Jetstream/Label";
import JetActionMessage from "@/Jetstream/ActionMessage";
import JetSecondaryButton from "@/Jetstream/SecondaryButton";
export default {
  components: {
    JetButton,
    JetFormSection,
    JetInput,
    JetInputError,
    JetLabel,
    JetActionMessage,
    JetSecondaryButton,
  },
  props: ["roles"],
  data() {
    return {
      form: this.$inertia.form(
        {
          _method: "PUT",
        },
        {
          bag: "updateClubRoles",
          resetOnSuccess: false,
        }
      ),
    };
  },

  methods: {
    updateClubRoles() {
      this.form.post(route("club-roles.update"), {
        preserveScroll: true,
      });
    },
  },
};
</script>

<style>
</style>