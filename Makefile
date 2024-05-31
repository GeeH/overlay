.PHONY: *

db:
	./vendor/bin/sail artisan migrate:fresh --seed
