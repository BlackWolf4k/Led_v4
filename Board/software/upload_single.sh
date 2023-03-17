#!/bin/sh
echo "Uploading $1 Module..."
ampy -p /dev/ttyACM0 put $1