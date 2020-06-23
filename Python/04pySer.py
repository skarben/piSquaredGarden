#!/usr/bin/python
import os, sys
import serial
import time

ser = serial.Serial('/dev/ttyACM0',115200, timeout = 5)
ser.write(str.encode("a"))
lines = ser.readline()
length = len(lines.decode())
while length < 10:
	ser.write(str.encode("a"))
	lines = ser.readline()
	length = len(lines.decode())
serialString = str(time.time()) + "," + lines[:-2].decode()
serialString = serialString.split(',')
try:
	serialData = [float(i) for i in serialString]
	print(serialData)
except:
	print("Float->String error")