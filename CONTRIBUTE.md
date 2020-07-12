# Contribute / Develop / Debug

Any contribution are welcome.

You can develop in any way you want but the PR (pull request) must pass the tests before review/merge.

## Tests required to pass

- Diagnosis: `composer diagnose`
- Syntax check: `php-cs` (Compliant to PSR-2 and PSR-12)
- Unit test: `phpunit`
- Mess detection: `php-md`
- Static analysis: `PHPStan`, `Psalm`, `Phan`
- Code coverage: `COVERALLS`
  - Only if access token of COVERALLS is set in `./tests/conf/COVERALLS.env`.

## Directory structure

<details><summary>View the directory structure</summary><div><br>

- Files/directories with `*` below are the ones that you don't usually touch unless it's necessary.

```text
./
├── src/
│   ├── Parser.php ................... The main class.
│   ├── ParserProtectedMethods.php ... Place the protected methods here. Parent
│   │                                    class of "Parser".
│   ├── ParserStaticMethods.php ...... Place the static methods here. Parent class
│   │                                    of "ParserProtectedMethods".
│   └── interfaces/
│        ├── ParserConstants.php ..... Define here the class constants here.
│        └── ParserInterface.php ..... Define the public methods of "Parser" class
│                                         here.
├── tests/ ............................ Place the PHPUnit tests here.
│   ├── ParserTest.php
│   ├── ...
│   │
│   ├── * conf ....................... Configuration files of the tests.
│   └── * run-tests.sh ............... Test runner script.
├── samples/ ................ Sample usage of the class.
│   └── Main.php
├── bench/
│   └── HashBench.php ..... Sample of benchmark. Create one if you want to compare
│                              methods or functions' speed, if you wander which
│                              implementation is better. Don't forget that readable is
│                              better than faster in this repo.
│
├── * .dockerignore ........ Dir/files to ignore including to the containers.
├── * .gitattributes ....... Dir/files to ignore including to the composer packages.
├── * .gitignore ........... Dir/files to ignore commit.
├── * .phpcs.xml ........... Config file of PHP-CS.
├── * .scrutinizer.yml ..... Config file of Scrutinizer for code quality check.
├── * .travis.yml .......... Config file of Travis-CI for testing on other PHP versions.
├── * CONTRIBUTE.md ........ This file.
├── * Dockerfile ........... Sample Docker container to run the script in./samples/
│                              on  PHP8-alpha.
├── * composer.json ........ Composer config file.
├── * composer.lock ........ Do not commit this file.
├── * docker-compose.yml ... Used for Docker and docker-compose user.
│                              directory.
├── * LICENSE .............. MIT license.
├── * README.md ............ The main README.
│
├── * .devcontainer/ ....... Contains Docker container files for VS Code users.
├── * .testcontainer/ ...... Contains Docker container files for non VS Code users.
├── * .init/ ............... Contains files for initialization of this package.
├── * bin/ ................. Phar archived file will be placed here. Mostly not used.
├── * report/ .............. Coverage reports from PHPUnit will be placed here.
└── * vendor/ .............. Composer vendor directory. It will be created by
                                composer. Don't commit this dir as well.
```

</div></details>

## Develop

This repo provides several ways to run the tests before PR.

### Develop on local (Ordinary way)

- Installed in local
  - PHP ^7.1, `composer`

```bash
// Install dependencies for development
composer install
// Run tests
composer test local all
```

- Note: For Windows10 users it is recommended to develop via WSL2

### Recommended

- Installed in local
  - VS Code, `remote-containers` (VS Code Extension) and Docker

If you have the above installed then you don't need PHP, `composer`, etc. in your local environment.

Just press `F1` in your VS Code and search for "`Remote-Containers: Reopen in Container`" and select it (This will take time for the first launch). Then VS Code will connect to the VS Code server in the container with all the stuff you need.

Open a new terminal in VS Code then run the below command to run the tests.

```bash
composer test all verbose
```

### Docker + docker-compose User (No PHP)

- Installed in local
  - Docker and `docker-compose` (no PHP)

In your terminal run:

```bash
docker-compose run --entrypoint='/bin/sh' --workdir='/app' -v $(pwd):/app dev
```

Then run the tests inside the container.

```bash
composer test all
```
