# Doctrine Build Commons

This project is a base submodule for all Doctrine projects that are using
a build process yet. It handles versioning, making releases and distributing them.

By the nature of phings limit capabilities we ship a bunch of additional tasks, that mimic Ants API as much as possible to allow future migration to Ant and possibly Manuel Piechlers [build-commons library](http://github.com/manuelpiechler/build-commons).

## New tasks

* ReplaceTask with options file, token, value
* VersionIncrementTask with options property and version

## Targets

* Build - Depends on clean, prepare, generate-package
* Clean - Clearn the directory for the next build
* Prepare - Retrieve version from Version Class constant, also calculate PEAR version and stability automatically.
* generate-package - Generates the PEAR package, depends on define-pear-package as "abstract task".
* pirum-release - Use '-Dproject.pirum_dir=path' during build to push to pirum.
* distribute-download - Use '-Dproject.download_dir=path' during build to move download file.
* make-release-commit - This task makes a release commit by asking for the release version, sets composer.json version, the Version constant and then increments the version in the mini level and makes another commit for the next "dev".

## Properties

Your build file for a build-common supported project has to look at least:

    # Project Name
    project.name=DoctrineDBAL

    # Version class and file
    project.version_class = Doctrine\DBAL\Version
    project.version_file = lib/Doctrine/DBAL/Version.php
