.DEFAULT_GOAL := help

help:
	@echo ""
	@echo "Available tasks:"
	@echo "    test    Run all tests and generate coverage"
	@echo "    fixer    Run only php cs fixer"
	@echo "    unit    Run unit tests and generate coverage"
	@echo "    vendor  Install dependencies"
	@echo "    clean   Remove vendor and composer.lock"
	@echo ""

vendor: $(wildcard composer.lock)
	composer install --prefer-dist

fixer-vendor:
	composer install --working-dir=tools/php-cs-fixer

fixer: fixer-vendor
	tools/php-cs-fixer/vendor/bin/php-cs-fixer fix src --rules=@Symfony,@PSR12

unit: vendor
	 XDEBUG_MODE=coverage vendor/bin/phpunit --stop-on-failure

coverage: vendor
	 XDEBUG_MODE=coverage vendor/bin/phpunit --coverage-text --coverage-clover=./.phpunit.cache/coverage.xml --coverage-html=./.phpunit.cache/html-coverage/

test: fixer unit

clean:
	rm -rf ./.phpunit.result.cache
	rm -rf ./var
	rm -rf ./.php-cs-fixer.cache

.PHONY: help fixer unit test clean