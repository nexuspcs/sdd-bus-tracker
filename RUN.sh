#!/bin/bash

# Install Apache HTTP Server (httpd) and PHP using Homebrew (brew)
brew install httpd php

# Check if the installation was successful before starting the PHP server
if [ $? -eq 0 ]; then
  # Start the PHP development server
  php -S localhost:9000
else
  echo "Error: Installation failed. Please check the output above for any error messages."
fi
