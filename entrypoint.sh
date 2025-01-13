#!/bin/sh
redis-server /usr/local/etc/redis/redis.conf --daemonize yes
# Start the PHP server in the background
php -S 0.0.0.0:8888 &

# Wait for the PHP server to start
sleep 3

# Run the curl commands
curl -sS http://0.0.0.0:8888/julius/run
curl -sS http://0.0.0.0:8888/rainbow/build

# Keep the container running
tail -f /dev/null
