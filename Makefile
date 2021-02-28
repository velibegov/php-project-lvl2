lint:
	composer run-script phpcs -- --standard=PSR12 src
install:
	composer install
test:
	vendor/bin/phpunit ./tests/JsonFileComparatorTest.php
test-coverage:
	composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml
