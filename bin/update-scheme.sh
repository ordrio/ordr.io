#!/bin/env bash

sudo chown -R elasticbeanstalk:elasticbeanstalk app/cache
sudo chown -R elasticbeanstalk:elasticbeanstalk app/logs
sudo chmod -R 777 app/cache
sudo chmod -R 777 app/logs
php app/console doctrine:schema:update --force --env="prod"
sudo chown -R elasticbeanstalk:elasticbeanstalk app/cache
sudo chown -R elasticbeanstalk:elasticbeanstalk app/logs
sudo chmod -R 777 app/cache
sudo chmod -R 777 app/logs
