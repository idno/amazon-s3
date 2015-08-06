<?php

    namespace IdnoPlugins\S3 {

        class Main extends \Idno\Common\Plugin {

            function registerPages() {
                \Idno\Core\site()->addPageHandler('/file/([A-Za-z0-9]+)(/.*)?', '\IdnoPlugins\S3\Pages\File\View', true);
                \Idno\Core\site()->hijackPageHandler('/file/([A-Za-z0-9]+)(/.*)?', '\IdnoPlugins\S3\Pages\File\View', true);
            }

            function registerEventHooks() {

                // All of this only makes sense if we have an aws_key and aws_secret

                if (!empty(\Idno\Core\site()->config()->aws_key)
                    && !empty(\Idno\Core\site()->config()->aws_secret)
                    && !empty(\Idno\Core\site()->config()->aws_bucket)) {

                    // Load AWS SDK and dependencies
                    $classLoader = new \Symfony\Component\ClassLoader\UniversalClassLoader();
                    $classLoader->registerNamespaces(array(
                        'Aws'      => dirname(__FILE__) . '/external/aws-sdk/src',
                        'Guzzle'   => dirname(__FILE__) . '/external/guzzle/src',
                        'Doctrine' => dirname(__FILE__) . '/external/doctrine/lib/Doctrine',
                        'Psr'      => dirname(__FILE__) . '/external/psrlog',
                        'Monolog'  => dirname(__FILE__) . '/external/monolog/src'
                    ));

                    $classLoader->register();

                    if (empty(\Idno\Core\site()->config()->aws_region)) {
                        $region = 'us-east-1';
                    } else {
                        $region = \Idno\Core\site()->config()->aws_region;
                    }

                    $aws = \Aws\Common\Aws::factory(array(
                        'key'     => \Idno\Core\site()->config()->aws_key,
                        'secret'  => \Idno\Core\site()->config()->aws_secret,
                        'region'  => $region,
                    ));

                    // Impose the S3 filesystem
                    \Idno\Core\site()->filesystem = new \IdnoPlugins\S3\S3FileSystem();
                    $s3client = $aws->get('S3');
                    $s3client->registerStreamWrapper();

                    \Idno\Core\site()->filesystem->attachAWSClient($s3client);

                }

            }

        }

    }