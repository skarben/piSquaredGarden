#!/usr/bin/python
import os, sys
import serial
import time

ser = serial.Serial('/dev/ttyACM0',115200, timeout = 5)
currentTime = time.time()
previousTime = 0
# listen for the input, exit if nothing received in timeout period
while True:
	currentTime = time.time()
	#print("cTime done")
	if currentTime - previousTime > 0.5:
		previousTime = time.time()
		#print("pTime done")
		ser.write(str.encode("abcd"))
		#print("sWrite done") 
		lines = ser.readline()
		if len(lines) > 1:
			print(lines[:-2].decode())
			#print("pLine done")
	
	'''if len(lines) == 0:
		print("Time out! Exit.\n")
		sys.exit()
		#print(line[:-2].decode())'''
