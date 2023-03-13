#!/bin/sh
# Remove all the directories and files
ampy -p /dev/ttyUSB0 rmdir Animation
ampy -p /dev/ttyUSB0 rmdir Client
ampy -p /dev/ttyUSB0 rmdir Comunication
ampy -p /dev/ttyUSB0 rmdir Connections
ampy -p /dev/ttyUSB0 rmdir File
ampy -p /dev/ttyUSB0 rmdir Led
ampy -p /dev/ttyUSB0 rmdir Server
ampy -p /dev/ttyUSB0 rmdir Services
ampy -p /dev/ttyUSB0 rmdir Config
ampy -p /dev/ttyUSB0 rmdir Dump
ampy -p /dev/ttyUSB0 rm main.py