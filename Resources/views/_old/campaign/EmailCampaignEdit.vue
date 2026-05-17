<template>
  <section>
    <Loading v-if="loading" />

    <div v-else>
      <table class="table">
        <tr>
          <th colspan="2">E-mail kampagne</th>
        </tr>

        <tr>
          <td width="150">Vedhæftet</td>
          <td>
            <Loading v-if="template_loading" :type="2" />
            <div v-else>
              <SelectEmailTemplate v-model="input.template_id" />
            </div>
          </td>
        </tr>
      </table>

      <Loading v-if="show.loading" />

      <table class="table" v-else-if="show.template">
        <tr>
          <th colspan="2">E-mail template</th>
        </tr>

        <tr>
          <td>
            <div><strong>{{ show.template.title }}</strong></div>
            <div><small class="text-muted">{{ show.template.slug }}</small></div>
            <div v-if="show.template.attachments" class="pt-2">
              <ul>
                <li v-for="attachment in show.template.attachments" :key="attachment.id">
                  {{ attachment.url }}
                </li>
              </ul>
            </div>
          </td>
        </tr>
      </table>

      <table class="table">
        <tr>
          <th colspan="2">Delivery</th>
        </tr>
        <tr>
          <td width="150">Dag nr.</td>
          <td>
            <input 
              type="number" 
              name="send_day" 
              v-model="input.send_day" 
              min="1"
              class="form-control"
            />
          </td>
        </tr>
        <tr>
          <td>Send time</td>
          <td>
            <Timepicker v-model="input.send_time" />
          </td>
        </tr>
      </table>

      <SelectCategories
        type="email_campaigns"
        v-model="input.categories"
        :checked="false"
      />

      <v-btn @click="submit()" :loading="submitLoading" color="primary">Opdater dokument</v-btn>
      <v-btn @click="goto('module.email_campaigns.index')">Annuller</v-btn>
    </div>
  </section>
</template>

<script>
import TableEdit from "@/Mixins/TableEdit";
import moment from "moment";
import axios from "axios";
import collect from "collect.js";

import SelectEmailTemplate from "./../../assets/js/components/SelectEmailTemplate.vue";

export default {
  mixins: [TableEdit],

  components: {
    SelectEmailTemplate
  },

  data() {
    return {
      mail_templates: [],
      template_loading: true,

      input: {
        template_id: 0,
        send_day: 1,
        send_time: undefined,
        categories: undefined,
      },

      show: {
        template: null,
        loading: false
      }
    };
  },

  watch: {
    "input.send_day"(value) {
      if (value !== "" && value < 1) {
        this.input.send_day = 1;
      }
    },

    "input.template_id"(value) {
      this.getEmailTemplate(value);
    }
  },

  methods: {
    async get() {
      try {
        const res = await axios.get(route("api.email_campaigns.show", { id: this.id }), {
          params: {
            lang: null,
            include: "categories"
          }
        });

        let data = res.data.data;
        data.send_time = moment(data.send_time, "HH:mm").toDate();
        this.input = data;
        this.loading = false;
      } catch (error) {
        console.error("Fejl ved hentning af e-mail kampagne:", error);
      }
    },

    async create() {
      try {
        await axios.post(route("api.email_campaigns.store"), this.formatInput(this.input));
        this.goto("module.email_campaigns.index");
      } catch (error) {
        console.error("Fejl ved oprettelse:", error);
      }
    },

    async update() {
      try {
        await axios.patch(route("api.email_campaigns.update", { id: this.id }), this.formatInput(this.input));
        this.goto("module.email_campaigns.index");
      } catch (error) {
        console.error("Fejl ved opdatering:", error);
      }
    },

    getEmailTemplate(id) {
      this.show.template = this.mail_templates.find(t => t.id === id) || null;
    },

    formatInput(input) {
      return {
        template_id: input.template_id,
        send_day: input.send_day,
        send_time: moment(input.send_time).format("HH:mm:ss"),
        categories: input.categories
      };
    }
  },

  async beforeMount() {
    try {
      const res = await axios.post(route("api.mail_templates.search"), { 
        sort: [{ field: "title", direction: "asc" }]
      }, {
        params: { 
          lang: this.$i18n.locale // 🎯 Bruger Vue I18n
        }
      });

      this.mail_templates = res.data.data;
    } catch (error) {
      console.error("Fejl ved hentning af mail skabeloner:", error);
    } finally {
      this.template_loading = false;
    }
  }
};
</script>

<style>
/* Tilføj eventuelle styles her */
</style>
