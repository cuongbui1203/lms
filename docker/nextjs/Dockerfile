FROM node:22-alpine

RUN apk add --no-cache git
RUN apk add --no-cache libc6-compat

RUN git clone https://github.com/cuongbui1203/lms_fe /app

RUN addgroup --system --gid 1001 nodejs
RUN adduser --system --uid 1001 nextjs

WORKDIR /app
RUN cp .env.docker .env
RUN \
    if [ -f yarn.lock ]; then yarn --frozen-lockfile; \
    elif [ -f package-lock.json ]; then npm ci; \
    elif [ -f pnpm-lock.yaml ]; then corepack enable pnpm && pnpm i --frozen-lockfile; \
    else echo "Lockfile not found." && exit 1; \
    fi

# USER nextjs

EXPOSE 3000

ENV PORT 3000

CMD npm run build;npm run start
