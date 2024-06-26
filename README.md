
###  README

# Databox Integration Challenge

## Introduction
This project is designed to extract metrics from various data sources, currently GitHub and OpenWeatherMap, and send them to the Databox platform.


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

Alternatively, you can clone the repository using the web URL from GitHub:
1. Navigate to the repository on GitHub.
2. Click on the "Code" button.
3. Copy the URL under "HTTPS".
4. Use the copied URL with the `git clone` command:
    ```sh
    git clone https://github.com/vesnalebar/databox-integration-challenge.git
    cd databox-integration-challenge
    ```

### Set Up Environment Variables
Copy the `.env` file provided via email and place it in the root directory. The `.env` file should contain the following:
```
DATABOX_TOKEN=my_databox_token
GITHUB_TOKEN=my_github_token
OPENWEATHERMAP_API_KEY=my_openweathermap_api_key
```

### Install Dependencies
```sh
composer install
```

## Configuration Files
You can change the configuration files to customize the metrics fetched from GitHub and OpenWeatherMap.

### GitHub Configuration
Located at `config/github.json`. Example:
```json
{
  "username": "freeCodeCamp",
  "repository": "freeCodeCamp",
  "base_url": "https://api.github.com",
  "metrics": [
      {
          "key": "public_repos",
          "path": "public_repos"
      },
      {
          "key": "followers",
          "path": "followers"
      },
      {
          "key": "stars",
          "type": "count",
          "path": "stargazers_count"
      }
  ]
}
```

### OpenWeatherMap Configuration
Located at `config/weather.json`. Example:
```json
{
    "base_url": "https://api.openweathermap.org/data/2.5/weather",
    "city": "Maribor",
    "metrics": {
        "temp": "main.temp",
        "humidity": "main.humidity",
        "pressure": "main.pressure",
        "wind_speed": "wind.speed"
    }
}
```

## Usage Instructions

### Create New Metrics in Databox (Optional)
If you use your own tokens and keys in the `.env` file or add new metrics, you need to create custom metrics before pushing data to Databox. You can do this directly in Databox or by running the `create_metrics.php` script that uses `config/new_metrics.json`.

Naming convention for metrics:
- For weather: `city_metricKey`
- For GitHub: `username_metricKey`

Example configuration for creating metrics is in `config/new_metrics.json`. You can create metrics by running:
```sh
php src/create_metrics.php
```

### Fetch and Push Data to Databox
You can skip creating metrics if the config files remain the same and you want to see metrics in my Databox dashboard. Just run:

#### GitHub
```sh
php src/index.php github
```

#### OpenWeatherMap
```sh
php src/index.php weather
```
## Running Tests

### Description of Tests
- **DataboxIntegrationTest**: Tests the integration logic for pushing data to Databox, using mocks to simulate API interactions.
- **GitHubTest**: Tests fetching and processing data from GitHub, using mocks to simulate API interactions.
- **OpenWeatherMapTest**: Tests fetching and processing data from OpenWeatherMap, using mocks to simulate API interactions.

### Running Tests Locally
 **Run all unit tests**
    ```sh
    ./vendor/bin/phpunit --testdox tests/unit
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
    docker-compose up weather
    docker-compose up github
    ```

### Running Tests
1. **Run all unit tests**
    ```sh
    docker-compose run tests
    ```


## Logging
Logs are stored in the `logs/app.log` file.

```sh
docker-compose logs
```

## Checking Dockerfiles
- **Dockerfile**: Located in the project root. This file defines the Docker image build process.
- **docker-compose.yml**: Located in the project root. This file defines the services, volumes, and network configurations for Docker Compose.

## Databox Dashboard
You can view the metrics on the Databox dashboard. Here are the shareable links:

**GitHub FreeCodeCamp**:  
[https://app.databox.com/datawall/9c2f24b353570ae2cfc2309518f5582b1dd673c666cc61d]

**Weather**:  
[https://app.databox.com/datawall/f29a6680b6594a899384a72e1d2f4fe11e5f646676949b]

## Contact
If you have any questions, please feel free to contact me at [vesnaa.lebar@gmail.com]
```
