#!/bin/sh
esptool.py --port /dev/ttyUSB0 erase_flash
esptool.py --port /dev/ttyUSB0 write_flash --flash_size=detect -fm dio 0x00000 esp8266-20220618-v1.19.1.bin
