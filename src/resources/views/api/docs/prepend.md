This API gives access to the Social Recruitment Monitor (EBM). It is meant to enable various crawlers to submit their data to the EBM database.

# Authentication

An API token is needed to authenticate communication with this API. The API token has to be sent as an Authorization header with each request. 

`Auhorization: Bearer {token}`

```bash
curl -X GET -G "http://localhost/api/v1/organization" \
    -H "Authorization: Bearer {token}" \
    -H "Accept: application/json"
   
```

You can request a token by contacting devops@maximum.com.
