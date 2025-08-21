FROM mysql:8.0.33

RUN usermod -u 1000 mysql

COPY initdb.sql /docker-entrypoint-initdb.d/