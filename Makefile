load-test:
	docker compose run --rm k6 run --out experimental-prometheus-rw /scripts/test_homepage.js