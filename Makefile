#!/usr/bin/make -f

.PHONY: all clean clean-all check test analyse coverage

# ---------------------------------------------------------------------

all: test analyse

clean:
	git clean -Xfq build

clean-all: clean
	rm -rf ./vendor
	rm -rf ./composer.lock

check:
	php -dmemory_limit=-1 vendor/bin/phpcs

test: clean check
	php -dmemory_limit=-1 -dxdebug.mode=coverage vendor/bin/phpunit --coverage-text

bench:
	php vendor/bin/phpbench run tests/Benchmark --report=default

coverage: test
	@if [ "`uname`" = "Darwin" ]; then open build/coverage/index.html; fi
