<template>
  <div>
    <!-- Knappen der åbner dialogen -->
    <v-chip color="primary" small @click="open = true">Vis e-mail</v-chip>

    <!-- Dialogen -->
    <v-dialog
      v-model="open"
      max-width="600"
      persistent
    >
      <v-card>
        <v-card-title>
          E-mail detaljer
          <v-spacer></v-spacer>
          <v-btn icon @click="open = false">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-card-title>

        <v-card-text v-if="email">

            <div><strong>Til:</strong></div>
            <div>{{ email.to }}</div>


            <div><strong>Emne:</strong> </div>
            <div>{{ email.subject }}</div>

            <hr>

                <div v-if="email.message" class="email-content">
                    <strong>Indhold:</strong>
                    <p v-html="email.message"></p>
                </div>

            <hr>

         

            <p v-if="email.user">
                <strong>Bruger:</strong> {{ email.user.first_name }} {{ email.user.middle_name }} {{ email.user.last_name }}
            </p>

            <p><strong>Sende-dato:</strong> {{ momentDisplay(email.send_after) }}</p>

            <p><strong>Status:</strong> {{ email.status }}</p>

            <p><strong>Oprettet:</strong> {{ momentDisplay(email.created_at) }}</p>

            <div v-if="email.attachments && email.attachments.length">

                <hr>
                
                <strong>Vedhæftede filer:</strong>

                <FilePreview :files="email.attachments"/>
                
            </div>


        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn text color="primary" @click="open = false">Luk</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>

import moment from 'moment';
import FilePreview from "@modules/Media/Resources/assets/js/Components/FilePreview.vue"


export default {

  name: 'EmailShowDialog',

  components:{
    FilePreview

  },

  props:{
    email: {
        required: true
    }
  },

  data() {
    return {
      open: false,
    };
  },

  methods: {

    storageLink(attachment) {

      return ('/storage' + attachment).replace("//", "/");

    },

    momentDisplay(date) {

      if (!date) return '';

      return moment(date).format('DD-MM-YYYY HH:mm');

    }

  }
};
</script>

<style scoped>

.email-content{
 
}

</style>
