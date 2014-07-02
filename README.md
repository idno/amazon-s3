Amazon S3 file handler for Known
================================

Uses the Amazon Simple Storage Service (S3) as a file back-end for Known.

Installation
------------

* Enable the plugin
* Set the following values in your site config.ini:
 * aws_key: Your AWS access key
 * aws_secret: Your AWS access secret
 * aws_bucket: The S3 bucket you want to store data inside

Once these are set, S3 will be set as the back-end for all files.

License & libraries
-------------------

Released under an Apache license.

Contains the AWS PHP SDK and associated dependencies.
