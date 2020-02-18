Amazon S3 file handler for Known
================================

Uses the Amazon Simple Storage Service (S3) to allow S3 file storage for the local back-end for Known.

Installation
------------

1. Modify your known `composer.json` to include the below; then update your lockfile
 * `"idno/S3": "^1.0.0"`
2. `composer update --lock`
* Enable the plugin
* Set the following values in your site config.ini:
 * aws_key: Your AWS access key
 * aws_secret: Your AWS access secret
 * aws_bucket: The S3 bucket you want to store data inside
 * aws_region: The region you wish to connect to if not 'us-east-1'
3. modify your config.ini so that `s3://your-bucket/Uploads` is the `uploadpath`
4. Enjoy!

Once these are set, S3 will be set as the back-end for all files.
It is worthwhile using s3 sync to retrieve existing files and upload.
This has only been manually tested on greenfield deploys.

License & libraries
-------------------

Released under an Apache license.

Contains the AWS PHP SDK and associated dependencies.
