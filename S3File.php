<?php

    namespace Idno\Files {

        class S3File extends File
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
                if (file_exists($this->internal_filename)) {
                    if ($file_handle = fopen($this->internal_filename,'r')) {
                        ob_end_flush();
                        fpassthru($file_handle);
                        fclose($file_handle);
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

        }

    }