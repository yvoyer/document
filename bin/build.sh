#!/usr/bin/env bash
set -e

echo "############## Running PHPUnit ##############"
bin/phpunit

echo "############## Running Behat ##############"
bin/behat

echo "############## Running PHP Code sniffer ##############"
bin/phpcs

echo "############## Running PHPStan ##############"
bin/phpstan analyse -l max -c phpstan.neon src/ tests/

echo "############## Running Infection ##############"
bin/infection
