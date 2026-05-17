<template>
  <section>

    <div v-if="attachments.length > 0">


      <!-- V-chip, der åbner dialogen med fil-preview -->
      <v-chip
        class="clickable"
        @click="openDialog"
        color="default"
        x-small
      >
        Vis filer ({{ attachments.length }})
      </v-chip>

      <!-- Dialogboksen, der indeholder FilePreview -->
      <v-dialog v-model="dialog.show" max-width="800px">
        <v-card>
          <v-card-title class="headline">Fil Preview</v-card-title>

          <v-card-text>
            <!-- FilePreview-komponenten, som får "attachments" som input -->
            <FilePreview :files="attachments" />
          </v-card-text>

          <v-card-actions>
            <v-btn text @click="dialog.show = false">Luk</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>


    </div>

  </section>
</template>

<script>
import FilePreview from "@modules/Media/Resources/assets/js/Components/FilePreview.vue"

export default {
  components: {
    FilePreview
  },
  
  props: {
    // Hent de vedhæftede filer som prop
    attachments: {
      type: Array,
      required: true
    }
  },

  data() {
    return {
      // Dialogens synlighed
      dialog: {
        show: false
      }
    };
  },

  methods: {
    // Åbn dialogboksen
    openDialog() {
      this.dialog.show = true;
    }
  }
};
</script>

<style scoped>
/* Klikbar styling for chip'en */
.clickable {
  cursor: pointer;
 
}
</style>
