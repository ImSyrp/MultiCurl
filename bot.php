<?php

ignore_user_abort(true); // Continue execution even if the user disconnects.
set_time_limit(0); // Disable the maximum execution time limit.

class MultiCurl
{
    private $handles = []; // Stores individual cURL handles.
    private $mh; // The multi cURL handle.

    // Constructor: Initializes the multi cURL handle.
    public function __construct()
    {
        $this->mh = curl_multi_init();
    }

    /**
     * Adds a cURL request to the multi handle.
     *
     * @param string $url The URL for the request.
     * @param array $params Optional parameters for the request.
     * @param string $requestType The HTTP request type (POST or GET).
     */
    public function addRequest(string $url, array $params = [], string $requestType = 'GET'): void
    {
        $ch = curl_init(); // Initialize a new cURL session.

        try {
            if ($requestType === 'POST') {
                curl_setopt($ch, CURLOPT_URL, $url); // Set the URL.
                curl_setopt($ch, CURLOPT_POST, 1); // Set POST method.
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params)); // Add POST parameters.
            } elseif ($requestType === 'GET') {
                curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($params)); // Set URL with query parameters.
            } else {
                throw new Exception("Invalid request type specified. Only POST and GET are allowed.");
            }

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string.
            curl_setopt($ch, CURLOPT_HEADER, false); // Do not include headers in the output.
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects.
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); // Set connection timeout.
            curl_setopt($ch, CURLOPT_TIMEOUT, 100); // Set request timeout.
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Skip SSL certificate verification.
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // Do not verify the host.

            curl_multi_add_handle($this->mh, $ch); // Add the cURL handle to the multi handle.
            $this->handles[] = $ch; // Store the handle for later cleanup.
        } catch (Exception $e) {
            echo "Error adding request: " . $e->getMessage();
            curl_close($ch); // Close the handle if an error occurs.
        }
    }

    /**
     * Executes all added requests.
     */
    public function execute(): void
    {
        $running = null;

        do {
            curl_multi_exec($this->mh, $running); // Execute the multi cURL handle.
            curl_multi_select($this->mh); // Wait for activity on any cURL handle.
        } while ($running > 0); // Continue while there are active requests.
    }

    /**
     * Closes all cURL handles and releases resources.
     */
    public function close(): void
    {
        foreach ($this->handles as $ch) {
            curl_multi_remove_handle($this->mh, $ch); // Remove each handle from the multi handle.
            curl_close($ch); // Close each individual handle.
        }

        curl_multi_close($this->mh); // Close the multi handle.
    }
}

// Example usage of the MultiCurl class.
try {
    $multiCurl = new MultiCurl();

    // Adding two POST requests.
    $multiCurl->addRequest('https://httpbin.org/post', ['param1' => 'value1'], 'POST');
    $multiCurl->addRequest('https://httpbin.org/post', ['param2' => 'value2'], 'POST');

    // Adding a GET request.
    $multiCurl->addRequest('https://httpbin.org/get');

    // Execute all requests.
    $multiCurl->execute();

    // Close all handles and free resources.
    $multiCurl->close();

    echo "All requests executed successfully!";
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}
?>
