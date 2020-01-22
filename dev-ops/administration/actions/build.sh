#!/usr/bin/env bash
#DESCRIPTION: build administration for production and run assetic

bin/console bundle:dump
PROJECT_ROOT=__PROJECT_ROOT__ npm run --prefix vendor/shopware/platform/src/Administration/Resources/app/administration/ build
bin/console assets:install
