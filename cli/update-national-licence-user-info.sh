#!/bin/bash
VUFIND_BASE=/home/nicolas/PhpstormProjects/swissbib/source
VUFIND_CACHE=$VUFIND_BASE/local/cache

BASEDIR=$(dirname $0)
INDEX="$BASEDIR/../public/index.php"
VUFIND_LOCAL_DIR="$BASEDIR/../local"

export VUFIND_LOCAL_MODULES=Swissbib
export VUFIND_LOCAL_DIR
export APPLICATION_ENV=development

php $INDEX update-national-licence-user-info $@

rm -rf $VUFIND_CACHE/searchspecs/*
rm -rf $VUFIND_CACHE/objects/*
rm -rf $VUFIND_CACHE/languages/*
