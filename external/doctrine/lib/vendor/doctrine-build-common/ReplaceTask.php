<?php
require_once "phing/Task.php";

class ReplaceTask extends Task
{
    protected $file;
    protected $token;
    protected $value;

    public function setFile($file)
    {
        $this->file = $file;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function init()
    {
    }

    public function main()
    {
        if (!file_exists($this->file)) {
            throw new \InvalidArgumentException("not a valid file: " . $this->file);
        }
        $data = file_get_contents($this->file);
        $data = str_replace($this->token, $this->value, $data);
        file_put_contents($this->file, $data);
    }
}
