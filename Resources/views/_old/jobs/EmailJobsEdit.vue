<template>
  <section>



    <Loading v-if="loading"/>


    <div v-else>


      <table class="table">

        <tr>
          <th colspan="2">E-mail kampangie </th>
        </tr>

        <tr>
          <td width="150">Vedhæftet</td>
          <td> 

            <Loading v-if="template_loading" :type="2"/>

            <div v-else>


              <select class="form-control" v-model="input.template_id">
                
                <option value="0">(Vælg skabelon)</option>

                <option 
                  v-for="item in mail_templates"
                  :value="item.id" 
                  :key="item.id"            
                >{{ item.title }}</option>
            
              </select>


            </div>


          </td>
        </tr>

      </table>


      <table class="table">

        <tr>
          <th colspan="2">Delivery</th>
        </tr>
        <tr>
          <td width="150">
            <div>Dag nr.</div>
            <div><small class="text-muted">(1 = samme dag)</small></div>
          </td>
          <td> 

            <input 
            type="number" 
            name="send_day" 
            v-model="input.send_day" 
            min="1"
            class="form-control"
            >

          </td>
        </tr>

        <tr>
          <td>Send time</td>
          <td> 


            <Timepicker
              v-model="input.send_time"
            />


          </td>
        </tr>

      </table>


  
      <SelectCategories
          type = "email_campaigns"
          v-model="input.categories"
          :checked="false"
      />

         

  
    <v-btn @click="submit()" 
    :loading="submitLoading"
    color="primary">Opdater dokument</v-btn> 
    
    <v-btn @click="goto('module.email_campaigns.index')">Annuller</v-btn>

    </div>

  </section>
</template>

<script>

import TableEdit from "@/Mixins/TableEdit"
import Timeselector from '@/Components/input/Timepicker.vue';
import moment from "moment"



export default {

  mixins: [TableEdit],

  components:{
    Timeselector
  },


  data(){

        return{

            mail_templates: [],

            template_loading: true,
 
            input:{
                template_id: 0,
                send_day: 1,
                send_time: undefined,
                categories: undefined,
            },

      
        }

    },

    watch:{

      "input.send_day": function(value){

        if(value !== "" && value < 1){ 
          
          this.input.send_day = 1

        }

      }

    },



    methods:{

        get(){


          return axios.get(route("api.email_campaigns.show",{ 
            id: this.id 
          }),{
            params:{
              lang: null,
              include: "categories"
            } 
          }).then((res)=>{



            let data = res.data.data

          //  data.send_time = this.$moment(data.send_time).toDate(); 
            data.send_time = moment(data.send_time,'HH:mm').toDate()

            console.log( data )

            this.input = data
              

            this.loading = false


          })



        },


        create(){


            return axios.post(route("api.email_campaigns.store"),
              this.formatInput(this.input)
            ).then((res)=>{

               
                this.goto('module.email_campaigns.index')
               // this.loading = false

            })


        },

        update(){

          
            
            return axios.patch(route("api.email_campaigns.update",{ id: this.id }),
              this.formatInput(this.input)
            ).then((res)=>{

             console.log(res)
               this.goto('module.email_campaigns.index')

            })

        },

        
        close(){

            this.$refs.timepicker.close()

        },


         formatInput(input){

          return {
            template_id: input.template_id,
            send_day: input.send_day,
            send_time: moment(input.send_time).format("HH:mm"),
            categories: input.categories
          }

        }
  

    },

    beforeMount(){

      return axios.get(route("api.mail_templates.index"),{ params: { lang: this.$i18n.locale } }).then((res)=>{

        this.template_loading = false

        this.mail_templates = res.data.data
      
      })

    }

}
</script>

<style>

</style>