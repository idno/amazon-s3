<?php

require_once 'phing/Task.php';

/**
 * Increments a version number on the mini level.
 *
 * Alpha, Beta, Dev Versions are stripped from the version, assumption is
 * that the next release will be stable.
 *
 * @author Benjamin Eberlei <kontakt@beberlei.de>
 */
class VersionIncrementTask extends Task
{
    protected $version;

    protected $property;

    public function setVersion($version)
    {
        $this->version = $version;
    }

    public function setProperty($property)
    {
        $this->property = $property;
    }

    public function init()
    {

    }

    public function main()
    {
        $parts = explode(".", str_ireplace(array('-DEV', '-ALPHA', '-BETA'), '', $this->version));
        if (count($parts) != 3) {
            throw new \InvalidArgumentException("Version is assumed in format x.y.z, $this->version given");
        }
        $parts[2]++;
        $this->project->setProperty($this->property, implode(".", $parts));
    }
}
