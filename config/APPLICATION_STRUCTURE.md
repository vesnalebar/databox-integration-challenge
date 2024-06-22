
## Main Components

### 1. Configuration Files
- **`config/github.json`**: Configuration file for GitHub, containing information about the username, repository, and metrics to fetch.
- **`config/weather.json`**: Configuration file for OpenWeatherMap, containing information about the city and metrics to fetch.
- **`config/new_metrics.json`**: Configuration file for creating new metrics in Databox.

### 2. Main Files and Classes
- **`src/index.php`**: The main entry point for the application. Depending on the parameters, it fetches data from GitHub or OpenWeatherMap and sends it to Databox.
- **`src/GitHub.php`**: Class for fetching data from the GitHub API.
- **`src/OpenWeatherMap.php`**: Class for fetching data from the OpenWeatherMap API.
- **`src/DataboxIntegration.php`**: Class for sending fetched data to Databox.
- **`src/Logger.php`**: Class for logging.
- **`src/create_metrics.php`**: Script for creating new metrics in Databox as defined in `config/new_metrics.json`.

### 3. Docker Configuration
- **`Dockerfile`**: Defines the Docker image build process.
- **`docker-compose.yml`**: Defines the Docker services, including environment variables, volumes, and commands for running the application.

### 4. Tests Directory
- **`tests/unit/DataboxIntegrationTest.php`**: Unit tests for the `DataboxIntegration` class, using mocks to simulate API interactions.
- **`tests/unit/GitHubTest.php`**: Unit tests for the `GitHub` class, using mocks to simulate API interactions.
- **`tests/unit/OpenWeatherMapTest.php`**: Unit tests for the `OpenWeatherMap` class, using mocks to simulate API interactions.

## Functionality

### Fetching Data
The application fetches data from the GitHub API and the OpenWeatherMap API based on the configurations in `config/github.json` and `config/weather.json`.

### Sending Data to Databox
The fetched data is then sent to the Databox platform using the `DataboxIntegration` class.

### Creating Metrics in Databox
Before sending data, metrics need to be created in Databox. This is done using the `src/create_metrics.php` script, which reads configurations from `config/new_metrics.json`.

### Logging
The application logs all important events and errors to the `logs/app.log` file using the `Logger` class.

### Testing
The application includes unit tests to ensure the correctness of the main components.

