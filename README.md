**Don't use this code yet.....**

# Installing CloudCMS

* Add the following to your composer.json and install.

`
{	
	"repositories": [
		{
			"type": "vcs",
			"url": "git@github.com:cloudmanic/cloudcms.git"
		}
	],
    
	"require": {
		"cloudmanic/cloudcms": "dev-master",
		"slim/slim": "2.*"
	}
}
`

* cd /path/to/your/document/root
* mkdir cms
* Using your favorite editor create a file called "index.php" and add the following lines.

`<?php
require '../vendor/autoload.php';
CMS::boostrap('../vendor');`

* Now you can access your CMS via http://yourdomain.com/cms