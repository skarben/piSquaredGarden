#!/usr/bin/python
import os, sys
import serial
import time
import httplib2

ser = serial.Serial('/dev/ttyACM0',115200, timeout = 5)
h = httplib2.Http(".cache")
ser.write(str.encode("a"))
lines = ser.readline()
length = len(lines.decode())
while length < 10:
	ser.write(str.encode("a"))
	lines = ser.readline()
	length = len(lines.decode())
serialStringO = str(time.time()) + "," + lines[:-2].decode()
#serialStringL = serialStringO.split(',')
try:
	#serialData = [float(i) for i in serialStringL]
	#(resp_headers, content) = h.request(urllib.parse.quote_plus("http://karben14.com/pi2/index.php?data=" + serialStringO), "GET")
	(resp_headers, content) = h.request("http://karben14.com/pi2/index.php?data=" + serialStringO, "GET")
	#print(serialData)
	print((resp_headers, content))
except:
	print("Float->String error")