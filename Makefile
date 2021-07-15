.DEFAULT_GOAL := help
VERSION         = v1
SERVICE_NAME    = fair-manager
PRODUCT_NAME    = bumeran
DOMAIN          ?= services.peru.com
ENV            ?= dev
NETWORK			?= bumeran_network
PATH_SERVICE    = /$(VERSION)/$(SERVICE_NAME)
PROJECT_NAME    = $(PRODUCT_NAME)-$(ENV)-$(SERVICE_NAME)
CONTAINER_NAME   = $(PROJECT_NAME)_backend
IMAGE_DEV		 = ronaldgcr/apt_fair_backend:nginx
install: ## Install project
	@./scripts/install.sh

build: ## build image, usage: make build, make build image=nginx
	@./scripts/task.build.sh ${image}

start: ## Up docker containers, usage: make start
	docker-compose up -d

up: ##@Local Start the project
	export PATH_SERVICE="$(PATH_SERVICE)" && IMAGE_DEV="$(IMAGE_DEV)" && CONTAINER_NAME="$(CONTAINER_NAME)" && NETWORK="$(NETWORK)" && \
		docker-compose -p $(PROJECT_NAME) up -d

stop: ## Stops and removes the docker containers, usage: make stop
	docker-compose down

restart: ## Restart all containers, usage: make restart
	docker-compose restart

status: ## Show containers status, usage: make status
	docker-compose ps

log: ## Show container logs, usage: make log image=nginx
	docker-compose logs -f $(image)

composer: ## Run composer command
	./scripts/task.composer.sh ${args}

ssh: ## Enter ssh container, usage : make ssh container=nginx
	docker run -it ${CONTAINER_NAME} sh

test: ## Run test
	@./scripts/tast.tests.sh

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-16s\033[0m %s\n", $$1, $$2}'
