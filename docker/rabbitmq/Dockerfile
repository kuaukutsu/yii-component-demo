FROM rabbitmq:3-management-alpine
# https://registry.hub.docker.com/_/rabbitmq/

COPY rabbitmq.developer.conf /etc/rabbitmq/conf.d/rabbitmq.conf

# Arguments 999:lxd
ARG UID=999

USER $UID
