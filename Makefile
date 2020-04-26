install:
	composer install
dump:
	composer dump-autoload
lint:
	composer run-script phpcs -- --standard=PSR12 public
test-phpunit:
	composer phpunit