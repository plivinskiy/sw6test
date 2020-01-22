#!/usr/bin/env bash
#DESCRIPTION: installs the dependencies for the administration using npm

npm clean-install --prefix vendor/shopware/platform/src/Administration/Resources
npm run --prefix vendor/shopware/platform/src/Administration/Resources lerna -- bootstrap
