#!/bin/sh
# Upload the files to the board
echo "Uploading Animation Module..."
ampy -p /dev/ttyACM0 put Animation
echo "Uploading Client Module..."
ampy -p /dev/ttyACM0 put Client
echo "Uploading Comunication Module..."
ampy -p /dev/ttyACM0 put Comunication
echo "Uploading Connections Module..."
ampy -p /dev/ttyACM0 put Connections
echo "Uploading File Module..."
ampy -p /dev/ttyACM0 put File
echo "Uploading Led Module..."
ampy -p /dev/ttyACM0 put Led
echo "Uploading Button Module..."
ampy -p /dev/ttyACM0 put Button
echo "Uploading Server Module..."
ampy -p /dev/ttyACM0 put Server
echo "Uploading Services Module..."
ampy -p /dev/ttyACM0 put Services
echo "Uploading Config Module..."
ampy -p /dev/ttyACM0 put Config
echo "Uploading Dump Module..."
ampy -p /dev/ttyACM0 put Dump
echo "Uploading Main..."
ampy -p /dev/ttyACM0 put main.py