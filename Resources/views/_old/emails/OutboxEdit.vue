<template>
  <section>


    <Loading v-if="loading"/>


    <div v-else>


      <table class="table">

        <tr>
          <th colspan="2">Modtager</th>
        </tr>


        <tr>
          <td width="150">Til E-mail</td>
          <td >


            <SelectUser 
              v-model="input.user_id"
            />


          </td>
        </tr>
    </table>

    <table class="table">
        <tr>
          <th colspan="2">
            Indhold
        
            <div class="link"
            @click="import_template" 
            ><i class="fas fa-plus mr-2"></i>Importer Skabelon
            </div>

          </th>
        </tr>


        <tr>
          <td width="150">Emne</td>
          <td >
            
            <input 
              type="text" 
              name="subject"
              v-model="input.subject"
              class="form-control"
            />

          </td>
        </tr>


        <tr>
          <td>Indhold</td>
          <td> 

            <VueEditor 
            :editor-toolbar="toolbar"
            v-model="input.message"
            />
    
          </td>
        </tr>

        <tr>
          <td>Vedhæftet</td>
          <td> 


            <FilePreview
              :files="attachments"
            />
           
          </td>
        </tr>

      </table>



      <table class="table">
        <tr>
          <th colspan="2">
            
            Afsendelse

          </th>
        </tr>

        <tr>
          <td width="150"></td>
          <td >

            <div>
              
              Afsendelses dato

              <v-chip v-if="is_sent" x-small color="success">
                SENDT
              </v-chip>
           
            </div>

            <div>

              <label>

                {{ convertSendAfterDate }}

              </label>

            </div>
            

          </td>
        </tr>

        <tr v-if="is_sent && !show_resend">
          <td></td>
          <td>   

            <v-btn color="default" @click="show_resend = !show_resend">Gensend E-mail</v-btn>

          </td>
        </tr>

        <tbody v-if="showResendSection">

          <tr >
            <td></td>
            <td >

              <div>
                <label>

                  <input type="radio" v-model="delivery.now" :value="1"> Send nu

                </label>
              </div>

              
              <div>
                <label>

                  <input type="radio" v-model="delivery.now" :value="0"> Send på et specifikt tidspunkt

                </label>
              </div>
              

            </td>
          </tr>

          
            <tr v-if="!deliver_now">
              <td>Dag</td>
              <td >

                <Datetimepicker 
                  v-model="input.send_after"
                  :minDate="new Date()"
                />
        
      
              </td>
            </tr>



        </tbody>

      </table>


  
      <v-btn 
      @click="submit()" 
      :loading="submitLoading"
      color="primary">Send E-mail</v-btn> 
      
      <v-btn @click="goto('module.email_outbox.index')">Annuller</v-btn>


      <Dialog 
        title="Importer skabelon"
        v-model="openImport"
      >
        
        <div class="mb-5">

          <SelectEmailTemplate 
            v-model="template"
            :withAttachments="true"
            :asObject="true"
          />

        </div>
        
        <v-btn color="primary" @click="submit_import">Importer</v-btn>


      </Dialog>


    </div>

  </section>
</template>

<script>

import TableEdit from "@/Mixins/TableEdit"
import collect from "collect.js"
import { mdiCloseCircleOutline } from '@mdi/js';
import { VueEditor } from "vue2-editor";
import SelectFiles from "@/Components/input/SelectFiles.vue"
import SelectEmailTemplate from "./../../assets/js/components/SelectEmailTemplate.vue"



export default {

  mixins: [TableEdit],

  components:{
    VueEditor,
    SelectFiles,
    SelectEmailTemplate
  },


  data(){

    return {

      input:{
        to: undefined,
        slug: undefined,
        subject: undefined,
        message: undefined,
        template_id: undefined,
        categories: [],
   //     attachments: [],
        available: undefined,
        send_after: new Date()
      },


      attachments:[],

      origInput: {...this.input},

      show_resend: false,

      delivery:{
        now: 1
      },
 
      openImport: false,

      template: 0,

      icons: {
        close: mdiCloseCircleOutline
      },

      toolbar: [
        [
          { 
            header: [false, 1, 2, 3, 4, 5, 6] 
          }
        ],
        ["bold", "italic", "underline"],
        [
          { align: "" },
          { align: "center" },
          { align: "right" },
        ],
        [
          { list: "bullet" }
        ],
        ["link"],
        [
          { color: [] }, 
          { background: [] }
        ],
        ["clean"] 
      ]

    }


  },

  computed:{

    deliver_now(){

      return this.delivery.now == 1

    },

    is_sent(){

      return this.input.sent != null

    },
    
    convertSendAfterDate(){

      return this.$moment.display(this.origInput.send_after)

    },

    showResendSection(){

      return !this.is_sent || (this.is_sent && this.show_resend)

    }

  },
 
  methods:{

    import_template(){

      this.openImport = true

    },

    submit_import(){

      let t = this.template

      this.input.template_id = t.template_id
      this.input.subject = t.title
      this.input.message = t.message
     
      this.template = 0
      this.openImport = false

    },

    async get(){

console.log(route("api.email.emails.show",{ 
          id: this.id,  
        }))

      return axios.get(route("api.email.emails.show",{ 
          id: this.id,  
        }),{
          params:{
          lang: null,
          dateformat: false,
        } 
      }).then((res)=>{

          console.log(res)

          let data = res.data.data

          this.origInput = data

          this.input = data

          this.attachments = data.attachments

          this.loading = false

          this.delivery.now = input.sent == null ? 0 : 1

        })


      },

      create(){


        let input = {...this.input}

        if(this.deliver_now){ input.send_after = null }


        return axios.post(route("api.email.emails.store"),input).then((res)=>{
  
          this.goto('module.email_outbox.index')
              // this.loading = false

        })


      },

      update(){


        let input = {...this.input}


        if(this.deliver_now){ 
            
          input.send_after = null 
            
        }
          
        return axios.patch(route("api.email.emails.update",{ id: this.id }),input).then((res)=>{

          this.goto('module.email_outbox.index')

        })

      }  

    }

}
</script>

<style scoped>
  
  .link{
        cursor: pointer;
        float:right;
        font-weight: 400;
    }

  #email-overview{
    max-height: 400px;
    min-height: 50px;
    overflow: auto;
    overflow-x: hidden;
    height: auto;

  }

</style>

