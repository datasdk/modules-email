<template>
  <section>

   
    <table class="table">
      
      <tr>
        <th colspan="2">Modtager</th>
      </tr>

      <tr>
        <td width="150">Modtager</td>
        <td>

          <SelectUser v-model="input.user_id"/>

        </td>
      </tr>

    </table>


    <table class="table">
      
      <tr>
        <th colspan="2">Afsendelses datoer</th>
      </tr>

      <tr >
        <td width="150">Fra dato</td>
        <td>
         
          <Datepicker 
            v-model="input.from_date"
            format="dd-MM-yyyy" 
            :monday-first="true"
            placeholder="Fra dato"
            :clear-button="false"
            class="datetimepicker"            
          />

        </td>
      </tr>

    </table>


     <table class="table">

      <tr>
        <th colspan="2">Send E-mail kampangie </th>
      </tr>

      <tr>
        <td width="150">Mail kampanige</td>
        <td>
          
       
          <Loading v-if="category_loading" :type="2"/>

          <div v-else>


            <v-treeview 
              selectable
              open-all
              selection-type="independent"
              open-on-click
              v-model="input.categories"
              :items="categories"
            />
       
            
          </div>
          

        </td>
      </tr>
    </table>


     <table class="table">

      <tr>
        <th colspan="2">Indstillinger</th>
      </tr>

      <tr>
        <td width="150">Duplikering</td>
        <td>

          <label>
            
            <input type="checkbox" name="avoid_duplicate" v-model="input.avoid_duplicate" > Undlad at sende mail, som brugeren allerede har modtaget

          </label>

        </td>
      </tr>
     </table>



    <v-btn @click="submit()" :loading="submitLoading" 
    color="primary">Send kampangie</v-btn> 

    <Dialog 
    title="Sending overview"
    v-model="modal.open">

    <v-alert v-if="!this.modal.content.length" type="info">
      Ingen E-mails er sendt
    </v-alert>

      <div v-for="content in this.modal.content" :key="content.template_id" 
      class="border border-secondary mb-3 p-3">

        <div><strong>Template id: {{ content.template_id }}</strong></div>
        
        <div>Dag nr.: {{ content.params.send_day }}</div>
        <div>Sende dato: {{ content.send_at_date }}</div>
        

      </div>

      <v-btn color="primary" @click="modal.open=false">Luk vindue</v-btn>

    </Dialog>



  </section>
</template>

<script>

import TableEdit from "@/Mixins/TableEdit"
import Datepicker   from '@/Components/input/Datepicker.vue';


export default {

  mixins: [TableEdit],

  components:{
    Datepicker
  },

 
    data(){

    return{

      input: {
        categories: [],
        user_id: undefined,
        from_date: undefined,
        avoid_duplicate: false
      },

      categories: [],
      category_loading: true,

      modal:{
        open: false,
        content: [],
      }

    }

  },

  methods:{

    create(){

      let input = this.input

    
 
      return axios.post(route("api.email_campaigns.send",{
        user_id: input.user_id,
        categories: input.categories,
        avoid_duplicate: input.avoid_duplicate,
        from_date: this.$moment.display(input.from_date)
      })).then((res)=>{
        
       console.log(res)
        
        this.modal.open = true
        this.modal.content = res.data.data


      })


    }

  },

  computed:{

    content(){

      return this.modal.content;

    }

  },

  mounted(){

    axios.post(route("api.categories.tree",{             
      type: "email_campaigns"
    }),{
          
      lang: this.$i18n.locale

    }).then((res)=>{ 
          
      this.categories = res.data.data
      this.category_loading = false

    })


  }

}
</script>

<style>

</style>