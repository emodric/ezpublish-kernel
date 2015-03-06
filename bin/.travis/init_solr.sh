#!/bin/sh

composer require -v --no-progress --no-interaction ezsystems/ezplatform-solr-search-engine:dev-master
./vendor/ezsystems/ezplatform-solr-search-engine/bin/.travis/init_solr.sh
