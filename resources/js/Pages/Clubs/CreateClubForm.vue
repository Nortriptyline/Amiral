<template>
  <jet-form-section @submitted="createClub">
    <template #title> Club Details </template>

    <template #description>
      Create a new club to collaborate with others on projects.
    </template>

    <template #form>
      <div class="col-span-6">
        <jet-label value="Club Owner" />

        <div class="flex items-center mt-2">
          <img
            class="w-12 h-12 rounded-full object-cover"
            :src="$page.user.profile_photo_url"
            :alt="$page.user.name"
          />

          <div class="ml-4 leading-tight">
            <div>{{ $page.user.name }}</div>
            <div class="text-gray-700 text-sm">{{ $page.user.email }}</div>
          </div>
        </div>
      </div>

      <div class="col-span-6 sm:col-span-4">
        <jet-label for="name" value="Club Name" />
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
        <jet-label for="name" value="Club Description" />
        <amiral-textarea
          id="description"
          class="mt-1 block w-full"
          v-model="form.description"
          autofocus
        ></amiral-textarea>
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

  data() {
    return {
      form: this.$inertia.form(
        {
          name: "",
          description: "",
        },
        {
          bag: "createClub",
          resetOnSuccess: false,
        }
      ),
    };
  },

  methods: {
    createClub() {
      this.form.post(route("clubs.store"), {
        preserveScroll: true,
      });
    },
  },
};
</script>
