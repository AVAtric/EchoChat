# VERSION defines the version for the docker containers.
# To build a specific set of containers with a version,
# you can use the VERSION as an arg of the docker build command (e.g make docker VERSION=0.0.2)
VERSION ?= v0.0.1

# REGISTRY defines the registry where we store our images.
# To push to a specific registry,
# you can use the REGISTRY as an arg of the docker build command (e.g make docker REGISTRY=my_registry.com/username)
# You may also change the default value if you are using a different registry as a default
REGISTRY ?= avatric


# Commands
docker: docker-build docker-push

docker-build:
	docker build . --target cli -t ${REGISTRY}/cli:${VERSION} -t ${REGISTRY}/cli:latest
	docker build . --target cron -t ${REGISTRY}/cron:${VERSION} -t ${REGISTRY}/cron:latest
	docker build . --target websocket -t ${REGISTRY}/websocket:${VERSION} -t ${REGISTRY}/websocket:latest
	docker build . --target fpm_server -t ${REGISTRY}/fpm_server:${VERSION} -t ${REGISTRY}/fpm_server:latest
	docker build . --target web_server -t ${REGISTRY}/web_server:${VERSION} -t ${REGISTRY}/web_server:latest

docker-push:
	docker push ${REGISTRY}/cli:${VERSION}
	docker push ${REGISTRY}/cli:latest
	docker push ${REGISTRY}/cron:${VERSION}
	docker push ${REGISTRY}/cron:latest
	docker push ${REGISTRY}/websocket:${VERSION}
	docker push ${REGISTRY}/websocket:latest
	docker push ${REGISTRY}/fpm_server:${VERSION}
	docker push ${REGISTRY}/fpm_server:latest
	docker push ${REGISTRY}/web_server:${VERSION}
	docker push ${REGISTRY}/web_server:latest