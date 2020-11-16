<template>
  <jet-form-section @submitted="createTeam">
    <template #description> Create a new Team to manage your players </template>

    <template #form>
      <div class="col-span-6 sm:col-span-4">
        <jet-label for="name" value="Team Name" />
        <jet-input
          id="name"
          type="text"
          class="mt-1 block w-full"
          v-model="form.name"
          autofocus
        />
        <jet-input-error :message="form.error('name')" class="mt-2" />
      </div>

      <div class="col-span-6 sm:col-span-4">
        <jet-label for="description" value="Club Description" />
        <amiral-textarea
          id="description"
          class="mt-1 block w-full"
          v-model="form.description"
        ></amiral-textarea>
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
import JetActionMessage from "@/Jetstream/ActionMessage";
import JetButton from "@/Jetstream/Button";
import JetFormSection from "@/Jetstream/FormSection";
import JetInput from "@/Jetstream/Input";
import JetInputError from "@/Jetstream/InputError";
import JetLabel from "@/Jetstream/Label";
import AmiralTextarea from "@/Amiral/Textarea";

export default {
  components: {
    JetActionMessage,
    JetButton,
    JetFormSection,
    JetInput,
    JetInputError,
    JetLabel,
    AmiralTextarea,
  },
  props: ['team'],
  data() {
    return {
      form: this.$inertia.form(
        {
          name: this.team.name,
          description: this.team.description,
        },
        {
          bag: "updateTeam",
          resetOnSuccess: false,
        }
      ),
    };
  },

  methods: {
    createTeam() {
      this.form.post(route("teams.update"), {
        preserveScroll: true,
      });
    },
  },
};
</script>
<style>
</style>