#!/usr/bin/env bash

THIS_SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd )"
cd "$THIS_SCRIPT_DIR" || exit 1
cd ../Docker || exit 1

export PHP=${PHP:-8.1}
export DOCKER_PHP_IMAGE=`echo "typo3/core-testing-php${PHP}" | sed -e 's/\.//'`
export ROOT_DIR=`readlink -f ${PWD}/../../`
export HOST_UID=`id -u`

SUITE=$1
ARGS="${@:2}"

case $SUITE in
    clean)
        rm -rf $ROOT_DIR/.Build
        rm -rf $ROOT_DIR/Documentation-GENERATED-temp
        rm -rf $ROOT_DIR/var
        rm -f $ROOT_DIR/.php-cs-fixer.cache
        rm -f $ROOT_DIR/composer.lock
        exit 0;
    ;;
    composer)
        docker compose run composer $ARGS
    ;;
    unit)
        ARGS=${ARGS:-Tests/Unit/}
        docker compose run unit -c .Build/vendor/typo3/testing-framework/Resources/Core/Build/UnitTests.xml $ARGS
    ;;
    functional)
        rm -rf .Build/public/typo3temp/var/tests/
        ARGS=${ARGS:-Tests/Functional/}
        ARGS="-c .Build/vendor/typo3/testing-framework/Resources/Core/Build/FunctionalTests.xml $ARGS"
        docker compose run functional $ARGS
    ;;
    *)
    echo "Invalid argument '$SUITE'"
    exit 1
    ;;
esac

SUITE_EXIT_CODE=$?
docker compose down --remove-orphans
exit $SUITE_EXIT_CODE
