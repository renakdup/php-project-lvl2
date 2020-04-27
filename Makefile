install:
	composer install
dump:
	composer dump-autoload
lint:
	composer run-script phpcs -- --standard=PSR12 public/inc tests
lint-fix:
	composer run-script phpcbf -- --standard=PSR12 public/inc tests
test-phpunit:
	composer phpunit