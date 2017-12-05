# WPCI

WordPress Continuous Integration

### Lets start
* Start with `composer install`, it will download wordpress to `./wordpress`, and other required packages to `./vendor`
* The next step is run docker containers, to start application locally. You should have installed docker-ce and docker-compose as well. 
`cd ./docker-config` and start docker with `docker-compose up --build -d` 
You can check all container running well using `docker-compose ps`, 
and stop all with `docker-compose stop`
* Open website on `localhost` in your browser and make install