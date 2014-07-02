<?php

    namespace IdnoPlugins\S3 {

        class Main extends \Idno\Common\Plugin {

            function registerEventHooks() {

                // Load the AWS SDK
                //require_once dirname(__FILE__) . '/external/aws-sdk/build/aws-autoloader.php';

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

                $aws = \Aws\Common\Aws::factory(array(
                    'key'     => '',
                    'secret'  => '',
                    'region'  => 'us-east-1',
                ));


                // Impose the S3 filesystem
                \Idno\Core\site()->filesystem = new \IdnoPlugins\S3\S3FileSystem();

            }

        }

    }