install:
	composer install
lint:
	composer run-script phpcs -- --standard=PSR12 public
test-phpunit:
	composer phpunit
