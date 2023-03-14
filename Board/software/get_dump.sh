#!/bin/sh
# Download the dump files directory
ampy -p /dev/ttyACM0 get Dump/json_dump.json ./__dump__/json_dump.json
ampy -p /dev/ttyACM0 get Dump/plain_dump.txt ./__dump__/plain_dump.txt