MultiCurl – PHP Multi-cURL Request Manager
MultiCurl is a lightweight PHP class that allows you to efficiently manage and execute multiple HTTP requests in parallel using PHP’s cURL library. This is especially useful for scenarios where you need to make several API calls or web requests without waiting for each to complete sequentially.

Features
Parallel HTTP requests: Perform multiple POST and GET requests simultaneously.
Time-efficient: Reduce wait time by executing requests concurrently.
Error Handling: Supports basic exception handling.
Configurable Timeout Settings: Customize connection and request timeouts.
SSL Handling: Disable SSL verification when necessary.
Requirements
PHP 7.0 or higher
cURL extension enabled (most PHP installations have cURL enabled by default)
Installation
No external dependencies are required. Just download the MultiCurl class and include it in your project.

bash
Copy code
git clone https://github.com/your-repo/multicurl.git
Alternatively, copy the MultiCurl class directly into your project.

Usage
Below is a quick example of how to use the MultiCurl class to perform multiple GET and POST requests simultaneously.

php
Copy code
<?php

require 'path-to-multicurl.php'; // Adjust the path as needed

try {
    $multiCurl = new MultiCurl();

    // Add a POST request
    $multiCurl->addRequest('https://httpbin.org/post', ['key1' => 'value1'], 'POST');

    // Add another POST request
    $multiCurl->addRequest('https://httpbin.org/post', ['key2' => 'value2'], 'POST');

    // Add a GET request
    $multiCurl->addRequest('https://httpbin.org/get', ['key3' => 'value3'], 'GET');

    // Execute all requests simultaneously
    $multiCurl->execute();

    // Close all handles and free resources
    $multiCurl->close();

    echo "All requests completed successfully!";
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}
Methods
__construct()
Initializes the multi-cURL handler.

addRequest(string $url, array $params = [], string $requestType = 'POST'): void
Adds a new request to the multi-cURL handler.

$url: The endpoint URL.
$params: Optional parameters for the request.
$requestType: HTTP method (POST or GET). Throws an exception for unsupported types.
execute(): void
Executes all added requests concurrently.

close(): void
Closes all cURL handles and releases resources.

Error Handling
The MultiCurl class uses try-catch blocks to handle exceptions. If an invalid request type is provided, an exception is thrown. Ensure you catch exceptions when using the class to gracefully manage errors.

Configuration
The following cURL options are set by default but can be modified within the class if needed:

Timeout Settings:

Connection Timeout: 30 seconds
Request Timeout: 100 seconds
SSL Settings:

CURLOPT_SSL_VERIFYPEER: false (Disables SSL certificate verification)
CURLOPT_SSL_VERIFYHOST: 0 (Disables host verification)
Modify these settings within the addRequest method if stricter security or different timeouts are needed.

License
This project is licensed under the MIT License. Feel free to modify and use it in your projects.

Contributing
Contributions are welcome! Please fork the repository and submit a pull request with your improvements or bug fixes.

Support
If you encounter any issues or have questions, feel free to open an issue on the GitHub repository.

Summary
The MultiCurl class simplifies making multiple HTTP requests concurrently using PHP. Whether you are integrating APIs or scraping multiple web pages, MultiCurl helps speed up your process by executing requests in parallel.
