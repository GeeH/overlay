.PHONY: *

up:
	./vendor/bin/sail up -d --wait

down:
	./vendor/bin/sail down

db:
	./vendor/bin/sail artisan migrate:fresh --seed

reverb:
	./vendor/bin/sail artisan reverb:start

worker:
	./vendor/bin/sail artisan queue:work

build:
	npm run build
