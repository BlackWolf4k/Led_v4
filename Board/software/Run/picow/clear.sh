#!/bin/sh
# Remove all the directories and files
ampy -p /dev/ttyACM0 rmdir Animation
ampy -p /dev/ttyACM0 rmdir Client
ampy -p /dev/ttyACM0 rmdir Comunication
ampy -p /dev/ttyACM0 rmdir Connections
ampy -p /dev/ttyACM0 rmdir File
ampy -p /dev/ttyACM0 rmdir Led
ampy -p /dev/ttyACM0 rmdir Server
ampy -p /dev/ttyACM0 rmdir Services
ampy -p /dev/ttyACM0 rmdir Config
ampy -p /dev/ttyACM0 rmdir Dump
ampy -p /dev/ttyACM0 rm main.py