#!/bin/bash
#
# This script is full of various test-cases which I'll use for my code.
#
# Some day I'll replace this with unit tests, but at this point, doing
# manual tests is a better value proposition.  As the project evolves, I will
# continue to re-evaluate that decision.
#

VERBOSE="-v"

#./main.php  --players 100,10,0:120:100,10,1,120:1000,250,1,120  --debug-rolls 4,4,4,4,4,4 --num-games 3 | ./colorize
#./main.php  --players 100,10,0,200,100,10,1,200 --debug-rolls 4,4,4,4,4,4 --num-games 3 ${VERBOSE} | ./colorize
#./main.php  --players 100,10,0:100,10,1 --debug-rolls 4,4,4,4,4,4 ${VERBOSE} $@ | ./colorize
#./main.php  --players 100,25,0,200:100,25,1,200 ${VERBOSE} $@ | ./colorize
./main.php  --players 100,25,0,200:100,25 ${VERBOSE} $@ | ./colorize
#./main.php  --players 100,100 --debug-rolls 2,2,2 --num-games 3 ${VERBOSE} | ./colorize



