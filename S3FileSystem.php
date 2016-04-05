<?php

    namespace IdnoPlugins\S3 {

        /*
         * Class S3FileSystem
         * A file system capable of storing files on Amazon S3
         * @package Idno\Files
         */

        class S3FileSystem extends \Idno\Files\FileSystem
        {

            public $client = false;

            /**
             * Attach an instantiated AWS client
             * @param \Aws\S3\S3Client $client
             */
            public function attachAWSClient($client)
            {
                $this->client = $client;
            }

            /**
             * Returns the attached AWS client
             * @return \Aws\S3\S3Client $client
             */
            public function getClient()
            {
                return $this->client;
            }

            /**
             * Find a file.
             * @param $id
             * @return mixed
             */
            public function findOne($id)
            {
                // Get path to load from
                $path = '';

                if (is_array($id)) {
                    if (!empty($id['_id'])) {
                        $id = $id['_id'];
                    }
                }

                $upload_file = $path . \Idno\Core\site()->config()->getFileBaseDirName() . '/' . $id[0] . '/' . $id[1] . '/' . $id[2] . '/' . $id[3] . '/' . $id . '.file';
                $data_file   = $path . \Idno\Core\site()->config()->getFileBaseDirName() . '/' . $id[0] . '/' . $id[1] . '/' . $id[2] . '/' . $id[3] . '/' . $id . '.data';

                    if (file_exists('s3://' . \Idno\Core\site()->config()->aws_bucket . '/' . $upload_file)) {

                        $file                    = new \IdnoPlugins\S3\S3File();
                        $file->_id               = $id;
                        $file->internal_filename = 's3://' . \Idno\Core\site()->config()->aws_bucket . '/' . $upload_file;
                        if ($metadata = file_get_contents('s3://' . \Idno\Core\site()->config()->aws_bucket . '/' . $data_file)) {
                            if ($metadata = json_decode($metadata, true)) {
                                $file->metadata       = $metadata;
                                $file->file           = $metadata;
                                $file->file['_id']    = $id;
                                $file->file['length'] = filesize('s3://' . \Idno\Core\site()->config()->aws_bucket . '/' . $upload_file);
                            }
                        }

                        return $file;
                    } else {
                        \Idno\Core\site()->session()->addMessage('s3://' . \Idno\Core\site()->config()->aws_bucket . '/' . $upload_file . " doesn't exist");
                    }

                return false;
            }

            /**
             * Forward to a file blindly
             * @param $id
             */
            public function passThroughOne($id) {

                // Get path to load from
                $path = 'http://' . \Idno\Core\site()->config()->aws_bucket . '/';

                if (is_array($id)) {
                    if (!empty($id['_id'])) {
                        $id = $id['_id'];
                    }
                }

                $upload_file = $path . \Idno\Core\site()->config()->getFileBaseDirName() . '/' . $id[0] . '/' . $id[1] . '/' . $id[2] . '/' . $id[3] . '/' . $id . '.file';

                header("Location: {$upload_file}"); exit;

            }

            /**
             * Store the file at $file_path with $metadata and $options
             * @param $file_path
             * @param $metadata
             * @param $options
             * @return \Idno\Files\File
             */
            public function storeFile($file_path, $metadata, $options)
            {
                if (file_exists($file_path)) {

                    // Ensure we have a bucket
                    if (!$this->getClient()->doesBucketExist(\Idno\Core\site()->config()->aws_bucket)) {
                        if ($this->getClient()->createBucket(['Bucket' => \Idno\Core\site()->config()->aws_bucket])) {
                            $this->getClient()->waitUntil('BucketExists', array('Bucket' => \Idno\Core\site()->config()->aws_bucket));
                        }
                    }

                    // Encode metadata for saving
                    $json_metadata = json_encode($metadata);

                    // Generate a random ID
                    $id = md5(time() . $json_metadata);

                    // Blank save path for now
                    $path = 's3://' . \Idno\Core\site()->config()->aws_bucket . '/';

                    $upload_file = \Idno\Core\site()->config()->getFileBaseDirName() . '/' . $id[0] . '/' . $id[1] . '/' . $id[2] . '/' . $id[3] . '/' . $id . '.file';
                    $data_file   = \Idno\Core\site()->config()->getFileBaseDirName() . '/' . $id[0] . '/' . $id[1] . '/' . $id[2] . '/' . $id[3] . '/' . $id . '.data';

                    $result = $this->getClient()->putObject(array(
                        'Bucket'     => \Idno\Core\site()->config()->aws_bucket,
                        'Key'        => $upload_file,
                        'SourceFile' => $file_path,
                        'ACL'        => 'public-read',
                        'ContentDisposition'
                                     => 'inline; filename="' . $metadata['filename'] . '"',
                        'ContentType'
                                     => $metadata['mime_type']
                    ));

                    error_log("PUTTING {$upload_file}; " . $json_metadata);

                    file_put_contents('s3://' . \Idno\Core\site()->config()->aws_bucket . '/' . $data_file, $json_metadata);

                    return $id;

                }

                return false;
            }

        }

    }
