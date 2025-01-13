# RUBICON - 4chan Command and Control Suite

## Application Detail
Check out the [WIKI](https://gitgud.io/saltorester/rubicon/-/wikis/home) which has comprehensive documentation.  The purpose of this README is exclusively installation.

## Installation - Docker
RUBICON is containerized and can be pulled directly from Docker Hub.  This means the application and all it's dependencies are stored in an immutable image you can pull and run in your docker client.  Containerization also provides cross-platform compatibility.

### Install Windows Docker Desktop
https://docs.docker.com/desktop/install/windows-install/

### Install MacOS Docker Desktop
https://docs.docker.com/desktop/install/mac-install/

### Install Linux Docker Desktop
https://docs.docker.com/desktop/install/linux-install/

## Pull the RUBICON Docker Image
```
docker pull saltorester/rubicon
```

## Run the RUBICON Docker Image
```
docker run -d -p 8888:8888 saltorester/rubicon
```

## Use RUBICON!
Just navigate your browser to [http://localhost:8888](http://localhost:8888).  The data model will automatically build, as will your Rainbow Table using the default wordlist (ignus-100k.txt).