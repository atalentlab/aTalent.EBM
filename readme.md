# Employer Branding Monitor (Social Recruitment Monitor)

## Setup

1. Clone this repo
2. Create your own laradock .env file by copying the example one: `cp laradock/env-example laradock/.env`
3. Create your own Laravel .env file by copying the example one: `cp src/.env.example src/.env`
4. Configure both .env files based on your requirements
5. If you want to have laradock run under different ports, those have to be changed in the `laradock/.env` file.`
6. Important is to configure the same app name in both `COMPOSE_PROJECT_NAME` (`laradock/.env`) and `APP_NAME` (`src/.env`).
7. The database settings (database name, user and password) should also be the same in both `.env` files. 
8. Run the `./setup.sh` script

## Docker

* run `./start.sh` to start all containers related to this project
* run `./stop.sh` to stop all containers to this project
* run `./remove.sh` to remove all containers, images and volumes related to this project

## Commands

The project comes with a few custom commands, you can find them by going into your docker workspace container and typing `php artisan list | grep srm`. Most commands also have a `--help` flag which provides some more info.

* `srm:create-next-period`: Generates the next period. This commands is automatically executed by cron every Sunday night.
* `srm:calculate-indices`: Generates EBM indices based on the data available for the current period. This command is also executed by cron every Sunday night. To regenerate all indices, append the `--all` flag.
* `srm:data-integrity-check`: Checks the data integrity of the post data and follower data. Generates reports in `storage/app/private/exports` per channel. Pass the `--fix` flag to also fix the found data issues. 


## API for crawlers

The EBM gets its data from crawlers who crawl different social channels. Those crawlers pass the data to the EBM through a simple REST API, the documentations for which can be found at `http://localhost/apidocs/index.html`. 

You can regenerate the API docs by running the bash script `./scripts/generate_api_docs.sh`.

## Integrations

### Mailchimp

Email addresses obtained from contact requests and newsletter subscriptions are stored locally and sent to Mailchimp by API. Check the `.env` file for more info. 

### WeChat JS SDK

The website uses the WeChat JS SDK to customize sharing title, description and image. Check the `.env` file for more info. 