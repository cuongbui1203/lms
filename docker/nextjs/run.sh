#!/usr/bin/env bash

if [ -f builded ]; then
    npm run start
else
    npm run build && touch builded && npm run start
fi
