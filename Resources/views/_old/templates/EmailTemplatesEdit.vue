<template>
  <section>



    <Loading v-if="loading"/>


    <div v-else>


      <table class="table">

        <tr>
          <th colspan="2">E-mail templates </th>
        </tr>

        <tr style="display:none">
          <td width="150">Navn</td>
          <td >
            
              <input 
              type="text" 
              slug="slug"
              v-model="input.name"
              class="form-control"
              />

          </td>
        </tr>


        <tr>
          <td width="150">Emne</td>
          <td >
            
            <TextField 
            name="title"
            v-model="input.subject"
            />

          </td>
        </tr>


        <tr>
          <td>Indhold</td>
          <td> 

            <TextEditor 
              name="message" 
              v-model="input.html_template"
              :tags="getPublicTags"
            />


          </td>
        </tr>

        <tr>
          <td>Aktiv</td>
          <td> 

            <label>

              <input 
                type="checkbox"
                v-model="input.active"
              /> 

              <strong>Templaten kan sendes som mail, til brugere.</strong>
              <div>Hvis feltet ikke er makeret, vil mail, som er tilknyttet denne template ikke blive sendt ud.</div>

            </label>
          

          </td>
        </tr>

        <tr v-if="0">
          <td>Vedhæftet</td>
          <td> 


            <SelectFiles 
              v-model="input.attachments"
              type="pdf"
            />
       

          </td>
        </tr>

      </table>


      <SelectCategories
        type = "mail_templates"
        v-model="input.categories"
        :checked="false"
      />

                

  
    <v-btn @click="submit()" 
    :loading="submitLoading"
    color="primary">Opdater dokument</v-btn> 
    
    <v-btn @click="goto('module.mail_templates.index')">Annuller</v-btn>
    
<!--
    <SendTestMail
      v-if="id"
      :id="id"
      route="api.mail_templates.send_test_mail"
      class="float-right"
    />
-->

    </div>

  </section>
</template>

<script>

import TableEdit from "@/Mixins/TableEdit"
import SelectFiles from "@/Components/input/SelectFiles.vue"
import collect from "collect.js"



export default {

  mixins: [TableEdit],

  components:{
    SelectFiles
  },

  data(){

        return{
 
          input:{
            slug: undefined,
            title: undefined,
            message: undefined,
            categories: undefined,
            attachments: [],
            active: 1
          },

        }

    },

  
    methods:{

      
        async get(){


          return axios.get(route("api.email.mail_templates.show",{ 
            id: this.id,
            lang: null
          }),{
            params:{
              include: "attachments,categories"
            } 
          }).then((res)=>{

        
            let data = res.data.data
              
              /*
            if(data.attachments.length){
              
            //  data.attachments = collect(data.attachments).pluck("url").toArray()

            }  
              */
 

            this.loading = false

            this.input = data


          })


        },


        create(){

         

            return axios.post(route("api.email.mail_templates.store"),this.input).then((res)=>{

               
                this.goto('module.mail_templates.index')
               // this.loading = false

            })


        },

        update(){

            return axios.patch(route("api.email.mail_templates.update",{ id: this.id }),this.input).then((res)=>{

          
               this.goto('module.mail_templates.index')

            })

        }

    },

    computed:{

      getPublicTags(){
  
        return window.laravel.config.tags

      }

    },

    beforeMount(){

    //  if(!this.id){ this.input.attachments = this.getDefaultAttachmentsValue }
      

    }

}
</script>

<style>

</style>