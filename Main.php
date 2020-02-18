<?php

namespace IdnoPlugins\S3 {

    use \Idno\Common\Plugin;
    use \Aws\S3\S3Client;

    class Main extends Plugin {
        public $_s3client;
        
        function registerTranslations()
        {

            \Idno\Core\Idno::site()->language()->register(
                new \Idno\Core\GetTextTranslation(
                    's3', dirname(__FILE__) . '/languages/'
                )
            );
        }

        function registerEventHooks() {
            $config = \Idno\Core\Idno::site()->config();
            // All of this only makes sense if we have an aws_key and aws_secret
            if (!empty($config->aws_key)
                && !empty($config->aws_secret)
                && !empty($config->aws_bucket)) {

                if (empty($config->aws_region)) {
                    $region = 'us-east-1';
                } else {
                    $region = $config->aws_region;
                }

                $params = array(
                    'credentials' => [
                        'key'     => $config->aws_key,
                        'secret'  => $config->aws_secret,
                    ],
                    'region'  => $region,
                    'version' => 'latest',
                );

                if (!empty($config->aws_base_url)) {
                    $params['base_url'] = $config->aws_base_url;
                }

                if (!empty($config->aws_suppress_region)) {
                    unset($params['region']);
                }

                $this->_s3client = S3Client::factory($params);
                $this->_s3client->registerStreamWrapper();
            }
        }
    }
}
