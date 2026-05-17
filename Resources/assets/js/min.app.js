// PAGES 

import EmailTemplatesIndex from "./../../views/templates/EmailTemplatesIndex.vue"

import EmailTemplatesEdit from "./../../views/templates/EmailTemplatesEdit.vue"

import EmailSmtpSettings from "./../../views/smtp/EmailSmtpSettings.vue"


import OutboxEdit from "./../../views/emails/OutboxEdit.vue"

import OutboxIndex from "./../../views/emails/OutboxIndex.vue"


import EmailCampaignIndex from "./../../views/campaign/EmailCampaignIndex.vue"

import EmailCampaignEdit from "./../../views/campaign/EmailCampaignEdit.vue"

import EmailCampaignSend from "./../../views/campaign/EmailCampaignSend.vue"


import EmailJobsIndex from "./../../views/jobs/EmailJobsIndex.vue"

import EmailJobsEdit from "./../../views/jobs/EmailJobsEdit.vue"




window.router.addRoute({ path: '/emails/emails', component: OutboxIndex, name: "module.email_outbox.index"})

window.router.addRoute({ path: '/emails/emails/create', component: OutboxEdit, name: "module.email_outbox.create" })

window.router.addRoute({ path: '/emails/emails/:id/edit', component: OutboxEdit, name: "module.email_outbox.edit", props: true })


window.router.addRoute({ path: '/emails/mail_templates', component: EmailTemplatesIndex, name: "module.mail_templates.index"})

window.router.addRoute({ path: '/emails/mail_templates/create', component: EmailTemplatesEdit, name: "module.mail_templates.create" })

window.router.addRoute({ path: '/emails/mail_templates/:id/edit', component: EmailTemplatesEdit, name: "module.mail_templates.edit", props: true })


window.router.addRoute({ path: '/emails/campaign', component: EmailCampaignIndex, name: "module.email_campaigns.index"})

window.router.addRoute({ path: '/emails/campaign/create', component: EmailCampaignEdit, name: "module.email_campaigns.create" })

window.router.addRoute({ path: '/emails/campaign/:id/edit', component: EmailCampaignEdit, name: "module.email_campaigns.edit", props: true })

window.router.addRoute({ path: '/emails/campaign/send', component: EmailCampaignSend, name: "module.email_campaigns.send"})


window.router.addRoute({ path: '/emails/smtp', component: EmailSmtpSettings, name: "module.mail_templates.settings.smtp.index"})


window.router.addRoute({ path: '/email/jobs', component: EmailJobsIndex, name: "module.email_jobs.index"})

window.router.addRoute({ path: '/email/jobs/create', component: EmailJobsEdit, name: "module.email_jobs.create" })

window.router.addRoute({ path: '/email/jobs/:id/edit', component: EmailJobsEdit, name: "module.email_jobs.edit", props: true })




// COMPONENTS


import SendTestMail from "./components/SendTestMail.vue";

Vue.component("SendTestMail", SendTestMail)