#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

if [ "$1" = 'php-fpm' ] || [ "$1" = 'php' ] || [ "$1" = 'bin/console' ]; then

	mkdir -p var/cache var/log

	setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX var
	setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX var

	mkdir -p resources
	setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX resources
	setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX resources
fi

exec docker-php-entrypoint "$@"