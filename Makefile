#!/usr/bin/make -f

.PHONY: all clean clean-all check test coverage update

# ---------------------------------------------------------------------

all: test

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

update: IP2LOCATION-LITE-DB1.CSV
	php bin/app.php update
	rm IP2LOCATION-LITE-DB1.CSV

IP2LOCATION-LITE-DB1.CSV:
	curl -sSO https://download.ip2location.com/lite/IP2LOCATION-LITE-DB1.CSV.ZIP
	unzip -o IP2LOCATION-LITE-DB1.CSV.ZIP
	rm -rf LICENSE-CC-BY-SA-4.0.TXT
	rm -rf README_LITE.TXT