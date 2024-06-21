
# Databox Integration Challenge

## Introduction
This project is designed to extract metrics from various data sources, such as GitHub and OpenWeatherMap, and send them to the Databox platform.

## Setup Instructions

### Prerequisites
- PHP 8.0 or higher
- Composer
- Docker (optional, for containerized deployment)

### Clone the Repository
```sh
git clone https://github.com/vesnalebar/databox-integration-challenge.git
cd databox-integration-challenge
```

You can also clone the repository using the web URL from GitHub:
1. Navigate to the repository on GitHub.
2. Click on the "Code" button.
3. Copy the URL under "HTTPS".
4. Use the copied URL with the `git clone` command:
    ```sh
    git clone https://github.com/vesnalebar/databox-integration-challenge.git
    cd databox-integration-challenge
    ```

### Set Up Environment Variables
 Copy the one .env I sent in email to Mateja and put it to root directory, should look like this:
```
DATABOX_TOKEN=my_databox_token
GITHUB_TOKEN=my_github_token
OPENWEATHERMAP_API_KEY=my_openweathermap_api_key
```

### Install Dependencies
```sh
composer install
```

## Usage Instructions

### Fetch and Push Data to Databox

#### GitHub
```sh
php src/index.php github
```

#### OpenWeatherMap
```sh
php src/index.php weather
```

### Create New Metrics in Databox
Update `config/new_metrics.json` and run:
```sh
php src/create_metrics.php
```

## Docker Setup

### Build and Run the Docker Container
1. **Build the Docker Image**
    ```sh
    docker-compose build
    ```

2. **Run the Docker Container**
    ```sh
    docker-compose up
    ```

3. **Execute Commands in Docker**
    ```sh
    docker-compose exec app php src/index.php github
    docker-compose exec app php src/index.php weather
    ```

## Logging
Logs are stored in the `logs/app.log` file.


## Contact
If you have any questions, please feel free to contact me on email vesnaa.lebar@gmail.com

