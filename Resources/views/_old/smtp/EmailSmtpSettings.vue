<template>
  <section>

    <Loading v-if="loading" />

    <div v-else>


      <table class="table">
        <tr>
          <th colspan="2">Mailserver</th>
        </tr>
        <tr>
          <td width="150">Udbyder</td>
          <td>


            <div v-for="item in providers" :key="item.name">
              <label>
         
                <input
                  type="radio"
                  name="provider"
                  v-bind:value="item.name"
                  v-model="input.default"
                />
                {{ item.label }}
          
              </label>
            </div>


          </td>
        </tr>
      </table>

      <!-- SMTP Configuration -->
      <form @submit.prevent="submit">
        <table class="table" v-if="isSMTP">
          <tr>
            <th colspan="2">SMTP</th>
          </tr>


          <tr>
            <td width="150">Aktiv</td>
            <td>
              
              <label>

                <input
                  type="checkbox"
                  v-model="input.mailers.smtp.active"
                />

                Aktiver mail-server

              </label>
              

            </td>
          </tr>

          <tr>
            <td width="150">Host</td>
            <td>
              <input
                type="text"
                v-model="input.mailers.smtp.host"
                class="form-control"
              />
            </td>
          </tr>

          <tr>
            <td>Port</td>
            <td>
              <input
                type="text"
                v-model="input.mailers.smtp.port"
                class="form-control"
              />
            </td>
          </tr>

          <tr>
            <td>Username</td>
            <td>
              <input
                type="text"
                v-model="input.mailers.smtp.username"
                class="form-control"
              />
            </td>
          </tr>

          <tr>
            <td>Password</td>
            <td>
              <input
                type="text"
                v-model="input.mailers.smtp.password"
                class="form-control password"
              />
            </td>
          </tr>

          <tr>
            <td>Encryption</td>
            <td>
              <select
                class="form-control"
                v-model="input.mailers.smtp.encryption"
              >
                <option
                  v-for="item in encryptions"
                  :value="item.value"
                  :key="item.value"
                >
                  {{ item.label }}
                </option>
              </select>
            </td>
          </tr>
        </table>

        <!-- Sendgrid Configuration -->
        <table class="table" v-if="isSendgrid">
          <tr>
            <th colspan="2">Sendgrid</th>
          </tr>
          <tr>
            <td width="150">Api</td>
            <td>
              <input
                type="text"
                v-model="input.sendgrid.api"
                class="form-control"
              />
            </td>
          </tr>
        </table>


        <!-- Sender Information -->
        <table class="table">
          <tr>
            <th colspan="2">Afsender (reply)</th>
          </tr>
          <tr>
            <td width="150">Navn</td>
            <td>
       
              <input
                type="text"
                v-model="input.from.name"
                class="form-control"
              />

            </td>
          </tr>
          <tr>
            <td>E-mail</td>
            <td>
              <input
                type="text"
                v-model="input.from.address"
                class="form-control"
              />
            </td>
          </tr>
          <tr>
            <td>Svar til</td>
            <td>
              <input
                type="text"
                v-model="input.from.reply_address"
                class="form-control"
              />
            </td>
          </tr>
        </table>

        <!-- Submit Buttons -->
        <v-btn color="primary" type="submit" :loading="submitLoading">
          {{ submitText }}
        </v-btn>
        <v-btn color="default" @click="goto('settings.index')">Annuller</v-btn>

        <!-- Send Test Mail -->
        <SendTestMail
          route="api.email.settings.smtp.send_test_mail"
          class="float-right"
          @open="open_send_test_mail"
        />
      </form>
    </div>
  </section>
</template>

<script>
import TableEdit from "@/Mixins/TableEdit";

export default {
  mixins: [TableEdit],

  data() {
    return {
      loading: true,
      submitLoading: false,
      providers: [
        { name: "smtp", label: "SMTP" },
      //  { name: "sendgrid", label: "Sendgrid" },
      ],
      input: {

        default: "smtp",
        
        mailers: {
            smtp: {
                active: false,
                transport: "smtp",
                host: null,
                port: 587,
                encryption: "tls",
                username: null,
                password: null,
                stream: {
                    ssl: {
                        allow_self_signed: true,
                        verify_peer: false,
                        verify_peer_name: false
                    }
                }
            }

        },
        from: {
            address: "hello@example.com",
            name: "Example"
        },

      },
      encryptions: [
        { value: "tls", label: "TLS (recommended)" },
        { value: "ssl", label: "SSL" },
        { value: "starttls", label: "START TLS" },
        { value: "", label: "Ingen" },
      ],
    };
  },

  created() {
    this.id = 1; // Dummy ID
  },

  computed: {

    isSMTP() {
      return this.input.default === "smtp";
    },

    isSendgrid() {
      return this.input.default === "sendgrid";
    },

  },

  methods: {

    get() {


      return axios
        .get(route("api.email.settings.smtp.show"))
        .then((res) => {
          // Uncomment and set response data to input if needed
           this.input = Object.assign(this.input, res.data);
        })
        .catch(() => {})
        .then(() => {
          this.loading = false;
        });


    },

    open_send_test_mail() {

      this.submit();

    },


    submit() {

      this.submitLoading = true;


      return axios
        .patch(route("api.email.settings.smtp.update"), this.input)
        .then((res) => {
          console.log(res);
        })
        .catch((res) => {
          console.log(res.response);
        })
        .then(() => {
          this.submitLoading = false;
        });


    },

  },

};
</script>

<style>
/* Optional custom styles for table, form, buttons, etc. */
</style>
