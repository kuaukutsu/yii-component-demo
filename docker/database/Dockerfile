FROM postgres:12

COPY install-pgcrypto.sh /docker-entrypoint-initdb.d/
COPY configs/postgresql.conf /var/lib/postgresql/data/
COPY configs/hba.conf /var/lib/postgresql/data/

RUN localedef -i ru_RU -c -f UTF-8 -A /usr/share/locale/locale.alias ru_RU.UTF-8
ENV LANG ru_RU.utf8
