#!/bin/bash
#
# Run our unit tests
#

# Errors are fatal
set -e

pushd $(dirname $0)
vendor/phpunit/phpunit/phpunit --colors=always tests/

