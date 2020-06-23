#!/usr/bin/python
import os, sys
import serial
import time

ser = serial.Serial('/dev/ttyACM0',115200, timeout = 5)
previousTime = 0
while True:
	ser.write(str.encode("a"))
	lines = ser.readline()
	print(lines[:-2].decode(), "", time.time())