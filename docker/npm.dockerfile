FROM node:12

COPY ./ /var/www/html
WORKDIR /var/www/html

RUN ["npm", "install", "--global", "cross-env"]

ENTRYPOINT ["npm"]
