
## Overview
Intelx-Solid is an implementation of the IntelX API into a SolidJS Client.

## Setup Instructions

### Prerequisites

- PHP Server (e.g., XAMPP for Windows)
- Access to a terminal or command line interface

### Installation

1. **Clone the repository or download the source code** to your local machine/server.

2. **Place the project folder** in your web server's root directory (e.g., `htdocs` for XAMPP).

3. **Start your web server** (Apache, Nginx, etc.).

4. **Open the project** by navigating to `http://localhost/[project-folder-name]` in your web browser.

### Configuration

- Ensure that the `handler/functions.php` file is correctly set up with necessary functions and configurations.
- Update the `$apiUrl` and `$apiKey` in `handler/inx.php` with your actual API URL and key.

## API Routes

### `/index.php`

#### Request Parameters

- `model`: Specifies the handler to use. Current implementation supports 'inx'.
- `ac`: Action to perform. Available actions: 'init_search', 'search', 'preview', 'read'.
- Additional parameters depending on the action (`term`, `id`, `offset`, etc.).

#### Handlers

- **INX Handler**: Interacts with IntelX API, performing search operations and handling data retrieval.

### Route Details

1. **Init Search (`ac=init_search`)**: 
   - Initiates a search query.
   - Parameters: `term` (search term).

2. **Search (`ac=search`)**:
   - Performs a search based on the given ID.
   - Parameters: `id` (search ID), `offset` (pagination offset).

3. **Preview (`ac=preview`)**:
   - Retrieves a preview of a file.
   - Parameters: `sid` (storage ID), `mid` (media ID), `bid` (bucket ID).

4. **Read (`ac=read`)**:
   - Retrieves the full view of a file.
   - Parameters: `sid` (storage ID), `bid` (bucket ID).

## Notes
In order to use the application you must need to set the API Key from [IntelX](https://intelx.io/) in the env variable
