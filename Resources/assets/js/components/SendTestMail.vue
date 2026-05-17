<template>
  <section>


     <v-btn @click="openSendTestMail()" color="default float-right" >Send test mail</v-btn>


      <Dialog
        v-model="showSendTestMail"
        width="500"
      >

        <v-card>
          <v-card-title class="text-h5 grey lighten-2">
            Send test mail
          </v-card-title>


          <v-card-text >
            
            <div class="pt-5 pb-5">Angiv den E-mail, som du vil sende en testmail til.</div>

            <input type="text" v-model="email" placeholder="Angiv E-mail" class="form-control">
            

          </v-card-text>

          <v-card-actions>
            
            <v-btn
              color="primary"
              @click="sendTextEmail()"
              :loading="loading "
            >
              Send E-mail
            </v-btn>

            <v-spacer></v-spacer>
            <v-btn
              color="primary"
              text
              @click="closeSendTestMail()"
            >
              Luk vindue
            </v-btn>
          </v-card-actions>


          <v-card-text v-if="msg" class="pt-3">
              
              <div><b>Fejl:</b></div>
              
              <pre  color="alert alert-secondary">
                
                {{msg}}

              </pre>

          </v-card-text>

        </v-card>
      </Dialog>


  </section>
</template>

<script>
export default {

    name: "SendTestMail",

    props:{

        id: { default: null },

        route: {
            required: true,
            type: String,
        }

    },

    data(){

        return {

            email: "",
            loading: false,
            msg: false,
            showSendTestMail: false,
            
        }

    },

    methods:{

         openSendTestMail(){

            this.showSendTestMail = true

            this.$emit("open",this.email)

        },

        closeSendTestMail(){

            this.showSendTestMail = false

        },

        sendTextEmail(){


          this.loading = true
          this.success = false
          this.msg = false,
          

          axios.post( route(this.route,{ id: this.id }) ,{ email: this.email } ).then((res)=>{

             // this.msg = res.response

              this.closeSendTestMail()

          }).catch((res)=>{

            this.msg = res.response

            console.log(res)

          }).then(()=>{
            
            this.loading = false

          })


        },

    }

}
</script>

<style>

</style>