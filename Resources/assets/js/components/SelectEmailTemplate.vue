<template>
  <section>
    <Loading :type="2" v-if="loading" />

    <div v-else>
      <select class="form-control" v-model="input">
        <option value="0">(Vælg skabelon)</option>
        <option 
          v-for="item in mail_templates"
          :value="item" 
          :key="item.id"
        >
          {{ item.title }}
        </option>
      </select>
    </div>
  </section>
</template>

<script>
import axios from "axios";

export default {
  props: {
    value: { required: true },
    asObject: { default: false },
    withAttachments: { default: false }
  },

  data() {
    return {
      loading: true,
      input: this.value,
      mail_templates: []
    };
  },

  watch: {
    input(value) {
      this.$emit("input", this.asObject ? value : value.id);
    }
  },

  async beforeMount() {
    try {
      const response = await axios.post(route("api.mail_templates.search"), {
        sort: [{ field: "title", direction: "asc" }]
      }, {
        params: { 
          lang: this.$i18n.locale, // 🎯 Bruger Vue I18n i stedet for $root.lang
          include: this.withAttachments ? "attachments" : null
        }
      });

      this.mail_templates = response.data.data;
    } catch (error) {
      console.error("Fejl ved hentning af mail skabeloner:", error);
    } finally {
      this.loading = false;
    }
  }
};
</script>

<style>
/* Tilføj eventuelle styles her */
</style>
