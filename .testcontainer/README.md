# Docker Container for Testing

This directory contains `Dockerfile` for other PHP versions to test. Not aimed to be used directly. `docker-compose` will use these files.

```text
./.testcontainer
├── Dockerfile          ... PHP 7.4 by default.
├── Dockerfile.php5     ... For PHP5 but most code won't work.
├── README.md           ... This file
└── composer.php5.json  ... Composer installer for PHP5

0 directories, 4 files
```
