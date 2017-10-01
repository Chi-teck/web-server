# Web server

Console commands for running applications using the PHP built-in web server.

## Instalation

Phar

```bash
release_url=https://api.github.com/repos/chi-teck/web-server/releases/latest
wget $(wget -qO- $release_url | awk -F'"' '/browser_download_url/ { print $4 }')
chmod +x web.server.phar
sudo mv web.server.phar /usr/local/bin/web.server
web.server --version
```
Composer

```bash
composer require chi-teck/web-server
./vendor/bin/web.server --version
```

## Available commands:
```
help    Displays help for a command
list    Lists commands
run     Runs a local web server
start   Starts a local web server in the background
status  Outputs the status of the local web server for the given address
stop    Stops the local web server that was started with the server:start command
```
## Credits
This packages is built on top of [Symfony web server bundle](https://github.com/symfony/web-server-bundle).
