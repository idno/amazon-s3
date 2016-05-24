<?php

    namespace IdnoPlugins\S3 {

        class S3File extends \Idno\Files\File
        {

            public $internal_filename = '';
            public $metadata_filename = '';

            /**
             * Get this file's contents. For larger files this might not be wise.
             * @return mixed|string
             */
            function getBytes()
            {
                if (file_exists($this->internal_filename)) {
                    return file_get_contents($this->internal_filename);
                }
            }

            /**
             * Output the contents of the file to the buffer
             * @return mixed|void
             */
            function passThroughBytes()
            {
                if ($url = $this->getS3URL()) {
                    header('Location: ' . $url); exit;
                }
                if (file_exists($this->internal_filename)) {
                    if ($file_handle = fopen($this->internal_filename,'r')) {
                        ob_end_flush();
                        fpassthru($file_handle);
                        fclose($file_handle);
                    }
                }
            }

            /**
             * Returns a file resource referencing the S3 object
             * @return resource
             */
            function getResource() {
                if (file_exists($this->internal_filename)) {
                    if ($file_handle = fopen($this->internal_filename, 'r')) {
                        return $file_handle;
                    }
                }
            }

            /**
             * Delete this file
             */
            function delete()
            {
                @unlink($this->internal_filename);
                @unlink($this->metadata_filename);
            }

            /**
             * Writes this file to the filename specified in $path
             * @param string $path
             * @return bool|mixed
             */
            function write($path)
            {
                return @copy($this->internal_filename, $path);
            }

            /**
             * Returns this file's filename
             * @return string
             */
            function getFilename()
            {
                if (!empty($this->metadata['filename'])) {
                    return $this->metadata['filename'];
                }

                return basename($this->internal_filename);
            }
	    
	    /**
	     * Returns this file's size in bytes.
	     * @return int
	     */
	    function getSize() {
		if (!empty($this->file['length'])) {
                    return $this->file['length'];
                }
		
		if (file_exists($this->internal_filename)) {
		    return filesize($this->internal_filename);
		}
	    }

            /**
             * Retrieve this object's URL on S3
             * @return bool|string
             */
            function getS3URL()
            {
                $client = \Idno\Core\site()->filesystem()->getClient();
                /* @var \Aws\S3\S3Client $client */

                $key = str_replace('s3://' . \Idno\Core\site()->config()->aws_bucket . '/','', $this->internal_filename);

                if ($url = $client->getObjectUrl(\Idno\Core\site()->config()->aws_bucket, $key)) {
                    return $url;
                }

                return false;
            }

        }

    }