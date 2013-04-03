#!/bin/bash
set -e #abort when first programm errors

exitWithMessageOnError () {
  if [ ! $? -eq 0 ]; then
    echo "An error has occured during web site deployment."
    echo $1
    exit 1
  fi
}

# Prerequisites
if [ ! -f composer.phar ];
then
    exitWithMessageOnError "You need to commit a 'composer.phar' file in the root of your project to enable composer deployment."
    exit 1
fi

# Verify node.js installed
hash node 2> /dev/null
exitWithMessageOnError "Missing node.js executable, please install node.js, if already installed make sure it can be reached from current environment."

# Setup
SCRIPT_DIR="$( cd -P "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
ARTIFACTS=$SCRIPT_DIR/artifacts

if [[ ! -n "$DEPLOYMENT_SOURCE" ]]; then
  # This normally exists
  DEPLOYMENT_SOURCE=$SCRIPT_DIR
fi

if [[ ! -n "$NEXT_MANIFEST_PATH" ]]; then
  NEXT_MANIFEST_PATH=$ARTIFACTS/manifest

  if [[ ! -n "$PREVIOUS_MANIFEST_PATH" ]]; then
    PREVIOUS_MANIFEST_PATH=$NEXT_MANIFEST_PATH
  fi
fi

if [[ ! -n "$KUDU_SYNC_CMD" ]]; then
  echo Installing Kudu Sync
  npm install kudusync -g --silent
  exitWithMessageOnError "npm failed"

  KUDU_SYNC_CMD="kuduSync"
fi

if [[ ! -n "$DEPLOYMENT_TARGET" ]]; then
  DEPLOYMENT_TARGET=$ARTIFACTS/wwwroot
fi

##################################################################################################################################
# Deployment
# ----------

# Set Production Environment, used when calling Symfony commands through php.exe
export SYMFONY_ENV=prod

cd "$DEPLOYMENT_SOURCE"
# Invoke Composer, but without the scripts section because subprocesses don't have the correct user and permissions
"D:\Program Files (x86)\PHP\v5.4\php.exe" composer.phar install --prefer-dist -v --no-scripts
# Invoke the scripts section here manually, using right user and permissions
"D:\Program Files (x86)\PHP\v5.4\php.exe" vendor/sensio/distribution-bundle/Sensio/Bundle/DistributionBundle/Resources/bin/build_bootstrap.php
"D:\Program Files (x86)\PHP\v5.4\php.exe" app/console cache:clear
"D:\Program Files (x86)\PHP\v5.4\php.exe" app/console assets:install web/

echo Handling Basic Web Site deployment.

# 1. KuduSync
echo Kudu Sync from "$DEPLOYMENT_SOURCE" to "$DEPLOYMENT_TARGET"
$KUDU_SYNC_CMD -q -f "$DEPLOYMENT_SOURCE" -t "$DEPLOYMENT_TARGET" -n "$NEXT_MANIFEST_PATH" -p "$PREVIOUS_MANIFEST_PATH" -i ".git;.deployment;build_azure.sh;composer.phar"
exitWithMessageOnError "Kudu Sync failed"

##################################################################################################################################

echo "Clearing Production cache by deleting $TEMP/cache/prod"
rm -Rf "$TEMP/cache/prod"

echo "Finished successfully."

