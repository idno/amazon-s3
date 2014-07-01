<?php

    namespace IdnoPlugins\S3 {

        class Main extends \Idno\Common\Plugin {

            function registerEventHooks() {

                // Impose the S3 filesystem
                \Idno\Core\site()->filesystem = new \IdnoPlugins\S3\S3FileSystem();

            }

        }

    }