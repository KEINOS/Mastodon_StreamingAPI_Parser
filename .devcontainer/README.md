# Dev Container for Visual Studio Code User

**This directory contains files to develop the repo in a Docker container** using `Remote - Containers`, the VS Code extension.

If you have **VSCode, Docker and `remote-containers` extension installed**, then you won't need to install PHP, composer, extra extension and etc. in your local environment.

## How to

Be sure that Docker is available to run, and the extension is installed.

- VS Code Extension: `ms-vscode-remote.remote-containers`

From the VS Code, press `F1` and search for "`Remote-Containers: Reopen in Container`" and select it. The VS Code will reload and start to build the container image. This will take time for the first time.

If the files appear in the VS Code's explorer, then you are ready. Open a new terminal from the VS Code and run the tests as below.

```bash
composer test all
```

Or run the below for help.

```bash
composer test help
```

## File description

```text
./.devcontainer
├── Dockerfile          ... Container config
├── README.md           ... This file
├── devcontainer.json   ... VS Code extra extension config for Docker
└── install_composer.sh ... Composer installer
```
