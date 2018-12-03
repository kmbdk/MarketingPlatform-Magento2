# eMailPlatform-Magento2

Magento 2 module for integrating with eMailPlatofrm application through API.

Subscribers will be added to eMailPlatform with emailaddress
Possible to add checkout subscription - subscribers will be added to eMailPlatform (possibility to apply firstname, lastname & mobile for subsciption).

API Username & Token is handed by eMailPlatform support team.

## Getting Started

Download the extension as a ZIP file from this repository or install our module with [Composer](https://getcomposer.org/) using the following command:

```
composer require emp/magento2integration
```

If you're installing the extension manually, unzip the archive and upload the files to `/app/code/EP/Emailplatform`. After uploading, run the following [Magento CLI](http://devdocs.magento.com/guides/v2.0/config-guide/cli/config-cli-subcommands.html) commands:

```
bin/magento module:enable EMP_Emailplatform --clear-static-content
bin/magento setup:upgrade
```

These commands will enable the eMailPlatform extension, perform necessary database updates, and re-compile your Magento store. From there, you'll want to run through the pre-import checklist and set everything up using our [extension guide](https://emailplatform.com).


## Get your API User from eMailPlatform support

Email: support@emailplatform.com
Tel: +45 72 44 44 44
