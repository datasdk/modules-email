<template>
  <section>



    <div >


      <div class="content-header">

        <h1>
          E-mails
          <small>Her er en oversigt over alle sendte E-mail og udbakken.</small>
        </h1>
        
        <v-btn color="primary" @click="goto('module.email_outbox.create')">Opret E-mail</v-btn>  

      </div>

  
      <Table
        :headers="headers"   
    
        :table="table"
        :route="route"
        :sorting="sorting"
   
        :move="false"
        :customOptions="customOptions"
        @resend="resend"
      >

          <template v-slot:item.to="{ item }">
            <section v-if="item">
            
              <span class="break-email">{{item.to}}</span>

              <span v-if="item.user">{{item.user.first_name}} {{item.user.middle_name}} {{item.user.last_name}}</span>

            </section>
          </template>

          <template v-slot:item.attachments="{ item }">
            <section>

        
              <TableAttachedFiles
                :attachments="item.attachments"
              />
          
            </section>
          </template>

          <template v-slot:item.send_after="{ item }">
            <div :class="statusClass(item)">
              {{ item.send_after }}
            </div>
          </template>


          <template v-slot:item.show="{ item }">
            <div>

              <EmailShowDialog :email="item"/>

            </div>
          </template>
      </Table>



    </div>
    
    

    <Dialog 
    title="Attachment overview"
    v-model="dialog.attachments.open"
    >

      <li v-for="attachment in dialog.attachments.text" :key="attachment">

        <a :href="storageLink(attachment.url)" target="_blank">{{ attachment.url }}</a>

      </li>
      
    </Dialog>


  </section>
</template>

<script>

import TableIndex from "@/Mixins/TableIndex";
import TableAttachedFiles from "@modules/Email/Resources/assets/js/components/TableAttachedFiles.vue"
import moment from "moment"
import EmailShowDialog from "./../../assets/js/components/EmailShowDialog.vue"

export default {

  components:{
    TableAttachedFiles,
    EmailShowDialog
  },
  
  mixins: [TableIndex],

  data(){

    return {

      dialog:{

        attachments:{
          open: false,
          message: null
        },
      },      


      table: "emails",
      route: "email.emails",
      
      loading: true,
      items: null,
      search: '',

      headers: [
          { text: 'E-mail', value: 'to', width: "200px" },
          { text: 'Emne', value: 'subject'}, 
          { text: 'Vedhæftet', value: 'attachments' },
          { text: 'Sende-dato', value: 'send_after', width: "150px"},
          { text: 'Status', value: 'status' },
          { text: 'Oprettet', value: 'created_at', width: "150px"},
          { text: '', value: 'show' },
          { text: '', value: 'actions' },
      ],

      
      customOptions: [
        { name: "resend", label: "Gensend" }],

  
      sorting: [
        { "field" : "send_after", "direction" : "desc" }, 
        { "field" : "to", "direction" : "asc" },    
      ],
   

      filters : [
        //{ field : "send_after", "operator" : ">=", "value" : moment().subtract(1, 'day').format('YYYY-MM-DD HH:mm') },
      ] 

    }

  },

  methods:{

    is_new(date){

      return moment(date,"DD/MM/YY").isSame( new Date() )

    },

    resend(option){

      

    },

    is_past(data){

      if(moment(data,"YYYY-MM-DD").isBefore( moment() ) ){

        console.log( moment(data) )

      }

      return moment(data,"YYYY-MM-DD").isBefore( moment() ) 

    },

  
    show(obj){

      alert()

    },

    copylink(str){

      return navigator.clipboard.writeText(str);

    },




    has_error(error){

      return error && error !== "NULL"

    },

    open_files(attachments){

      this.dialog.attachments = { 
        open: true,
        text: attachments 
      }
      
    },

    storageLink(attachment){

      return ('/storage'+attachment).replace("//","/")

    },

    statusClass(item) {
   

      return item.errors == null ? '' : 'text-red';
    },


  }

}
</script>

<style>
.break-email {
    word-break: break-all;      /* Bryd lange ord hvor som helst */
    /* eller */
    overflow-wrap: anywhere;    /* Moderne alternativ til word-break */
}
.text-red {
  color: red;
}



</style>