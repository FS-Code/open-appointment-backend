# Open Appointment

## Description

This project is designed to...

## Getting Started

To get started with this project, please follow the instructions below.

### Prerequisites

Before running the project, make sure you have the following installed:

- Composer
- PHP (version ^8.0)
- RDBMS (PostgreSQL or MySQL)

### Installation

1. Clone the repository to your local machine.
   ```bash
   git clone https://github.com/FS-Code/open-appointment-backend
   ```

2. Install the dependencies.
   ```bash
   composer update
   ```

3. Create a `.env` file based on the `.env.example` file.

    - Duplicate the `.env.example` file and rename it to `.env`.
    - Open the `.env` file in your IDE.
    - Fill in the required information, such as database username and password, access tokens, and other sensitive information. This file will hold your private information and must be kept secure.
    - Make sure not to commit the `.env` file to the remote repository. It's added to the `.gitignore` file to ensure it is not accidentally pushed. Do not edit the `.gitignore` file, unless said otherwise.

### Usage

Run the project through your local server such as laragon, xampp etc.

### Contributing

If you would like to contribute to this project, please follow the guidelines below:

1. Create a new `branch` for your feature or bug fix.
2. Make the necessary changes and `commit` them.
3. `push` your changes to your created branch.
4. Submit a pull request describing your changes to the `development` branch.
