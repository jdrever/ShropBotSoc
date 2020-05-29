---
layout: default
---
# Production Notes

Domain on <https://www.123-reg.co.uk/>

Publish to GAE on push to master branch

<https://github.com/GoogleCloudPlatform/github-actions/tree/master/example-workflows/gae>



* `App Engine- roles/appengine.appAdmin`: allows for the creation of new services
* `Browser - roles/browser`: allows for viewing and inserting items to the project (needed for Storage access)
* `Cloud Build Service Account - roles/cloudbuild.builds.builder`: allows for
  running and manipulating Cloud Build and Storage resources
* `Cloud Build Editor - roles/cloudbuild.builds.editor`: allows for deploying cloud builds
* `Service Account User - roles/iam.serviceAccountUser`: actAs requirement

<https://github.com/GoogleCloudPlatform/github-actions/blob/master/example-workflows/gae/.github/workflows/app-engine.yml>

Enable the Cloud Build API <https://console.developers.google.com/apis/api/cloudbuild.googleapis.com/overview?project=captain-magenta>
