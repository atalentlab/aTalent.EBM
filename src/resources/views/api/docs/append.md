# Response codes

This API uses the following response codes:


Error Code | Meaning
---------- | -------
200 | OK
401 | Unauthorized -- Your API token is wrong
404 | Not Found -- The specified resource could not be found
405 | Method Not Allowed -- You tried to access a resource with the wrong method
418 | I'm a teapot
422 | Unprocessable Entity -- Validation error
429 | Too Many Requests -- Your requests have been throttled
500 | Internal Server Error -- You found a bug
