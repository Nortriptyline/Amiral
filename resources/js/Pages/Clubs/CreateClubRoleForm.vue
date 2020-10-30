<template>
  <jet-form-section @submitted="createRole">
    <template #title> Create Role </template>

    <template #description> Create a new Role for your Club. </template>

    <template #form>
      <!-- Role name -->
      <div class="col-span-6 sm:col-span-4">
        <jet-label for="name" value="Role Name" />
        <jet-input
          id="RoleName"
          type="text"
          class="mt-1 block w-full"
          v-model="form.name"
          autocomplete="roleName"
        />
        <jet-input-error :message="form.error('name')" class="mt-2" />
      </div>
    </template>

    <template #actions>
      <jet-action-message :on="form.recentlySuccessful" class="mr-3">
        Saved.
      </jet-action-message>
      <jet-button
        :class="{ 'opacity-25': form.processing }"
        :disabled="form.processing"
      >
        Save
      </jet-button>
    </template>
  </jet-form-section>
</template>

<script>
import JetFormSection from "@/Jetstream/FormSection";
import JetLabel from "@/Jetstream/Label";
import JetInput from "@/Jetstream/Input";
import JetInputError from "@/Jetstream/InputError";
import JetButton from "@/Jetstream/Button";
import JetActionMessage from "@/Jetstream/ActionMessage";

export default {
  components: {
    JetFormSection,
    JetLabel,
    JetInput,
    JetInputError,
    JetButton,
    JetActionMessage,
  },

  data: function () {
    return {
      form: this.$inertia.form(
        {
          name: "",
        },
        {
          bag: "createClubRole",
          resetOnSuccess: true,
        }
      ),
    };
  },

  methods: {
    createRole: function () {
      this.form.post(route("club-roles.store"), {
        preserveScroll: true,
      });
    },
  },
};
</script>

<style>
</style>