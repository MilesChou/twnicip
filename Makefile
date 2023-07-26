#!/usr/bin/make -f

GLOBAL_CONFIG := -d memory_limit=-1


.PHONY: all
all: test

.PHONY: clean
clean:
	git clean -Xfq build

.PHONY: clean-all
clean-all: clean
	rm -rf ./vendor
	rm -rf ./composer.lock

.PHONY: check
check:
	php ${GLOBAL_CONFIG} vendor/bin/phpcs

.PHONY: test
test: clean
	php ${GLOBAL_CONFIG} -d xdebug.mode=coverage vendor/bin/phpunit --coverage-text

.PHONY: bench
bench:
	php ${GLOBAL_CONFIG} vendor/bin/phpbench run tests/Benchmark --report=default

.PHONY: coverage
coverage: test
	@if [ "`uname`" = "Darwin" ]; then open build/coverage/index.html; fi

.PHONY: update
update: IP2LOCATION-LITE-DB1.CSV
	php bin/app.php update
	rm IP2LOCATION-LITE-DB1.CSV

IP2LOCATION-LITE-DB1.CSV:
	curl -sSO https://download.ip2location.com/lite/IP2LOCATION-LITE-DB1.CSV.ZIP
	unzip -o IP2LOCATION-LITE-DB1.CSV.ZIP
	rm IP2LOCATION-LITE-DB1.CSV.ZIP
	rm -rf LICENSE-CC-BY-SA-4.0.TXT
	rm -rf README_LITE.TXT