<template>
  <section>



    <div >


      <div class="content-header">

        <h1>
          E-mail <span v-if="is_failed">mislykkeds</span> jobs
          <small>Her er en oversigt over alle dine E-mail, som er sat på automatisk sendeliste. Aktiver cronjob for at afsende E-mails automatisk</small>
        </h1>
     
        <v-btn color="default" @click="change_to('job')" v-if="is_failed">Vis jobs</v-btn>  

        <v-btn color="default" @click="change_to('failed')" v-else>Vis mislykkeds jobs</v-btn>  


      </div>

  
      <Table
        :headers="headers"   
        :table="table"
        :filters="filters"
    
        :sorting="sorting"
        @loaded="loaded"
      >


          <template v-slot:item.data.template="{ item }">
            <div v-if="item.data.template">

         
              <div v-bind:class="{ dublicated: dublicated_url(item) }">{{ item.data.template }}</div>
           
                    
            </div>
          </template>


      </Table>



    </div>
    


  </section>
</template>

<script>

import TableIndex from "@/Mixins/TableIndex";

import collect from "collect.js"


export default {
  
  mixins: [TableIndex],

  beforeMount(){

  //  this.setTable()

  },

  data(){

    return {

      table: "jobs",
      
      sorting: null,
 
      loading: true,
      items:null,
      search: '',

      headers: [
        //  { text: 'Emne', value: 'title' },
          { text: 'ID', value: 'id' },
          { text: 'Modtager', value: 'data.to' },
          { text: 'Template', value: 'data.template' },
          { text: 'Udgivelsesdato', value: 'available_at_date' },
          { text: '', value: 'actions' },
      ],


      include: "template,template.attachments,categories",


      filters: [
        { "field" : "queue", "operator" : "=", "value" : "email" }
      ],

      sorting: false

    }

  },

  computed:{

    is_failed(){

      return this.$route.query.failed === "true"

    }

  },

  methods:{

    loaded(items){

      this.items = items

    },

    setTable(){

        if(this.is_failed){

        this.table = "failed_jobs"

      } else {

        this.table = "jobs"

      }



    },

    dublicated_url(item){

      let count = 0

      collect(this.items).map((e)=>{
       
        if(
          e.data.template === item.data.template && 
          e.data.email === item.data.email){
        
          count ++
        }

      })
  
      return count > 1

    },

    change_to(val){

      this.$router.replace({ query: { failed: (val === "failed") } });


    }


  },

  watch: {
        '$route' (to, from) {
          
          //  this.setTable()
            
        }
    }, 

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

  .dublicated{
    color: rgb(243, 134, 18);

  }

</style>