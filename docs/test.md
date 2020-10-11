---
layout: default
---
# Test Environments

* On Google App Engine
  * Deployed via a Github Action <https://github.com/joejcollins/captain-magenta/actions>.
  * At <https://captain-magenta.nw.r.appspot.com/>
  * No writeable cache.
  * Console at <https://console.cloud.google.com/appengine?project=captain-magenta&serviceId=default>
  * Needs enable the Cloud Build API <https://console.developers.google.com/apis/api/cloudbuild.googleapis.com/overview?project=captain-magenta>
    * `App Engine- roles/appengine.appAdmin`: allows for the creation of new services
    * `Browser - roles/browser`: allows for viewing and inserting items to the project (needed for Storage access)
    * `Cloud Build Service Account - roles/cloudbuild.builds.builder`: allows for
      running and manipulating Cloud Build and Storage resources
    * `Cloud Build Editor - roles/cloudbuild.builds.editor`: allows for deploying cloud builds
    * `Service Account User - roles/iam.serviceAccountUser`: actAs requirement

* On Microsoft Azure
  * Deployed using the Azure Deployment Center <https://portal.azure.com/>.
  * At <http://captain-magenta.azurewebsites.net/>
  * With file caching of queries.
