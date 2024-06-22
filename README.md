
# Databox Integration Challenge

## Introduction
This project is designed to extract metrics from various data sources, for now just from GitHub and OpenWeatherMap, and send them to the Databox platform.

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
If you use your own tokens and key in the .env file or adding some metric, you need first to create custom metrics before pushing data to Databox. You can do this directly in Databox or by running the `create_metrics.php` script that uses `config/new_metrics.
json`, 
I made it so that names of metrics are in format 
- for weather: city_metricKey
- for github: username_metricKey  
So in databox is then clear from which source data is,
take this in consideration when creating metrics, so that the data can be pushed to databox properly
Example for creating metrics for the metrics I was pushing to databox is in  C:\Users\jcinc\Documents\Vesna\JobSearch\Databox\databox-integration-challenge\config\new_metrics.json
You create it with running:
```sh
php src/create_metrics.php
```
### Fetch and Push Data to Databox

you can skip create metrics if the config files will stay same as they are, and want to see metrics in my Databox dashboard, then you can just run:

#### GitHub
```sh
php src/index.php github
```

#### OpenWeatherMap
```sh
php src/index.php weather
```

## Docker Setup

### Build and Run the Docker Container

1. **Build the Docker Image**
    ```powershell
    docker-compose build
    ```

2. **Run the Docker Container**
    ```powershell
    docker-compose up
    ```

3. **Execute Commands in Docker**
    ```powershell
    docker-compose up weather
    docker-compose up github
    ```

### Running Tests
1. **Run all unit tests**
    ```powershell
    docker-compose run tests
    ```

## Logging
Logs are stored in the `logs/app.log` file.
 ```powershell
docker-compose logs
```
## Checking Dockerfiles
- **Dockerfile**: Located in the project root. This file defines the Docker image build process.
- **docker-compose.yml**: Located in the project root. This file defines the services, volumes, and network configurations for Docker Compose.

## Databox Dashboard
You can view the metrics on the Databox dashboard. Here is the shareable link to mine Databox dashboard
Github FreeCodeCamp
https://app.databox.com/datawall/9c2f24b353570ae2cfc2309518f5582b1dd673c666cc61d

Weather:
https://app.databox.com/datawall/f29a6680b6594a899384a72e1d2f4fe11e5f646676949b
## Contact
If you have any questions, please feel free to contact me at [vesnaa.lebar@gmail.com]

