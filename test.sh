#!/bin/bash
#
# Run our unit tests
#

# Errors are fatal
set -e

pushd $(dirname $0)
vendor/phpunit/phpunit/phpunit --colors=always tests/

echo

#
# Now do a test run
#
./main.php --players 1000,10,0,1100:1000,10,1,1100

