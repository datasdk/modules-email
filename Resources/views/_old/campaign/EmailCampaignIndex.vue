<template>
  <section>



    <div >


      <div class="content-header">

        <h1>
          E-mail kampangie
          <small>Her er en oversigt over alle dine E-mail skabeloner</small>
        </h1>
        
     
        <v-btn color="primary" @click="goto('module.email_campaigns.create')">Opret kampangie</v-btn>  
        <v-btn color="default" @click="goto('module.email_campaigns.send')" class="mr-3">Send kampangie</v-btn>  


      </div>

  
      <Table
        :headers="headers"   
        :include="include"
        :table="table"
        :sorting="sorting"
      >


          <template v-slot:item.title="{ item }">

            <div class="mt-3 mb-3" v-if="item.template">

              <div> <strong>{{ item.template.title }}</strong></div>

              <div class="text-muted"><small>{{ item.template.slug }}</small> </div>

            </div>

          </template>



          <template v-slot:item.attachments="{ item }">

            <div v-if="item.template">
              <div v-if="item.template.attachments?.length > 0">
                <!--
                <TableAttachedFiles :attachments="item.template.attachments"/>
                -->
              </div>
            </div>

          </template>


          <template v-slot:item.send_day="{ item }">

              <div v-if="item">
                Dag: {{ item.send_day}} 
                <small v-if="item.send_day == 1">(Samme dag)</small>
              </div>
              
               
          </template>



      </Table>



    </div>
    


  </section>
</template>

<script>

import TableIndex from "@/Mixins/TableIndex";
//import TableAttachedFiles from "./../../assets/js/components/TableAttachedFiles.vue";


export default {
  
  mixins: [TableIndex],

  components:{
   // TableAttachedFiles
  },

  data(){

    return {

      table: "email_campaigns",
      
      sorting: null,
 
      loading: true,
      items:null,
      search: '',

      headers: [
        //  { text: 'Emne', value: 'title' },
         // { text: 'Indhold', value: 'message' },
          { text: 'Titel', value: 'title' },
          { text: 'Vedhæftet', value: 'attachments' },
          { text: 'Kategori', value: 'categories' },
          { text: 'Sendes dag', value: 'send_day' },
          { text: 'Tid', value: 'send_time' },
          { text: '', value: 'actions' },
      ],


      include: "template,template.attachments,categories"


    }

  } 

}

</script>

<style >

.mail_content p {
  margin-bottom: 0px !important;
  font-size: 12px !important;
  
}

.mail_content{
  pointer-events: none;
  max-height: 100px;
  overflow: hidden;

}

.mail_content a{
  color: rgb(144, 144, 144) !important;
  text-decoration: underline;

}

</style>