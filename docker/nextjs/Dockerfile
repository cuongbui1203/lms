FROM node:22-alpine

RUN apk add --no-cache git

RUN git clone https://github.com/cuongbui1203/logistics-management-system-FE /app

WORKDIR /app

RUN cp .env.docker .env

RUN npm install

RUN npn build
