#!/usr/bin/python
import os, sys
import serial
import time

ser = serial.Serial('/dev/ttyACM0',115200, timeout = 5)
previousTime = 0
while True:
	currentTime = time.time()
	if (currentTime - previousTime) >= 3:
		previousTime = time.time()
		ser.write(str.encode("a"))
		lines = ser.readline()
		serialString = str(previousTime) + "," + lines[:-2].decode()
		serialString = serialString.split(',')
		try:
			serialData = [float(i) for i in serialString]
			print(serialData)
		except:
			print("Float->String error")
		