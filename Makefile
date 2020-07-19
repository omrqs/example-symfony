init:
	# copying .env.dist to .env, if not exists.
	cp -u .env.dist .env
	docker-compose up
