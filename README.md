# Orpheus Framework Website
Orpheus Framework Website is obviously using Orpheus and is Open Source under MIT License

## Developing Orpheus Locally

Orpheus Website is the project used to develop locally Orpheus and the Orpheus' universe.

### Install
Get all Orpheus' project locally in a specific folder (with Orpheus Framework, Orpheus Website & other Orpheus packages).
Orpheus Website project allows an alternative composer.local.json file using script "./scripts/composer-update -lv" to live connect to orpheus dependencies.
But these dependencies are still using a specific version of other dependencies, this is why you will need to switch it to a very tolerant version (aka "*").

### Switch dependencies to wildcard requirements

In your Orpheus global folder, to set dependencies to wildcard, run:
```
./orpheus-website/scripts/orpheus-dependencies-requirements.php
```
Use -h to show usage of this command.

### Testing locally

#### Develop Orpheus & libraries
You wish to update Orpheus' libraries and Orpheus' bootstrap.
Test developments easily using the orpheus-website project and set dependencies to wildcard requirements

#### Develop Orpheus Framework basics
You wish to improve the basic home page of Orpheus Framework without changing the orpheus website home page.
Current changes may be unstable to commit and you need to deploy locally orpheus, try this HowTo's.
Changes is requiring to be committed (not pushed) and you can then set requirements to "dev-main as 4.0", as of v4.0, in orpheus-website.
Use the script deploy-test.php in project orpheus-setup to deploy the local orpheus to a new local website.
