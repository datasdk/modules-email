<template>
  <section>
    <div>
      <div class="content-header">
        <h1>
          E-mail skabeloner
          <small>Her er en oversigt over alle dine E-mail skabeloner</small>
        </h1>
        <v-btn v-if="0" color="primary" @click="goto('module.mail_templates.create')">Opret skabelon</v-btn>  
      </div>

      <Table
        :headers="headers"   
        :include="include" 
        :table="table"
        :route="route"
        :delete="false"
        :move="false"
        :sorting="sorting"
      >
        <template v-slot:item.title="{ item }">
          <div class="mt-3 mb-3">
            <div class="mb-2">{{ item.subject }}</div>

            <!-- Slug komponent med clipboard + editable -->
            <Slug 
              v-model="item.name" 
              :editable="true"
              copy-title="Klik for at kopiere"
           
            />
          </div>
        </template>

        <template v-slot:item.html_template="{ item }">
          <div class="mt-3 mb-3">
            <div v-html="convertHtmlToPlain(item.html_template)" class="mail_content"></div>  
          </div>
        </template>

        <template v-slot:item.attachments="{ item }">
          <!--
          <TableAttachedFiles :attachments="item.attachments"/>
          -->
        </template>
      </Table>
    </div>
  </section>
</template>

<script>

import TableIndex from "@/Mixins/TableIndex";

export default {

  mixins: [TableIndex],

  data() {
    return {
      table: "mail_templates",
      route: "email.mail_templates",
      sorting: null,
      loading: true,
      headers: [
        { text: 'Emne', value: 'title', width: "300px"},
        { text: 'Indhold', value: 'html_template' },
        { text: 'Vedhæftet', value: 'attachments' },
        { text: 'Dato', value: 'created_at', width: "150px"},
        { text: '', value: 'actions' },
      ],
      include: "categories,attachments"
    }
  },

  methods: {
 

    convertHtmlToPlain(html) {
      if (!html) return "";

      let text = html
        .replace(/<\s*br\s*\/?>/gi, "\n")
        .replace(/<\s*p[^>]*>/gi, "\n")
        .replace(/<\/\s*p>/gi, "\n")
        .replace(/<\s*li[^>]*>/gi, "\n• ")
        .replace(/<\/\s*li>/gi, "")
        .replace(/<[^>]+>/g, "");

      const textarea = document.createElement("textarea");
      textarea.innerHTML = text;
      text = textarea.value;

      text = text
        .replace(/\u00A0/g, " ")
        .replace(/\n{3,}/g, "\n\n")
        .trim();

      const maxLength = 200;
      if (text.length > maxLength) {
        return text.substring(0, maxLength).trim() + "...";
      }

      return text;
    }
  }
}
</script>

<style>
.mail_content p {
  margin-bottom: 0px !important;
  font-size: 12px !important;
}

.mail_content {
  pointer-events: none;
  max-height: 50px;
  overflow: hidden;
  color: rgb(88, 88, 88);
}

.mail_content a {
  color: rgb(144, 144, 144) !important;
  text-decoration: underline;
}
</style>
