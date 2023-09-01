FROM nginx:stable-alpine

# Setup working directory
WORKDIR /var/www/html

RUN apk add --no-cache \
    openssl \
    vim

RUN openssl req -x509 -newkey rsa:4096 -sha256 -days 3650 -nodes \
    -subj "/C=US/ST=CT/O=IQvia, Inc./CN=ltfu.io/Subject Type=CA/Path Length=/basicConstraints=CA:FALSE" \
    -addext "subjectAltName=DNS:ltfu.io,DNS:*.ltfu.io,IP:192.168.96.2" \
    -keyout /etc/ssl/private/nginx-selfsigned.key -out /etc/ssl/certs/nginx-selfsigned.crt