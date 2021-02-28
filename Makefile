lint:
	composer run-script phpcs -- --standard=PSR12 src
install:
	composer install
test:
	vendor/bin/phpunit ./tests/JsonFileComparatorTest.php
