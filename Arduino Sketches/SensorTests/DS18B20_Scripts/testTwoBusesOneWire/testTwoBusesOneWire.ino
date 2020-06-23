#include <OneWire.h>
#include <DallasTemperature.h>

// Data wire is plugged into port 2 on the Arduino
#define ONE_WIRE_BUS 8
#define ONE_WIRE_BUS2 12
// Setup a oneWire instance to communicate with any OneWire devices
OneWire oneWire(ONE_WIRE_BUS);
OneWire oneWire2(ONE_WIRE_BUS2);

// Pass our oneWire reference to Dallas Temperature.
DallasTemperature sensors(&oneWire);
DallasTemperature sensors2(&oneWire2);
// variable to hold device addresses
DeviceAddress Thermometer;
DeviceAddress Thermometer2;

int deviceCount = 0;
int deviceCount2 = 0;

void setup(void)
{
  // start serial port
  Serial.begin(9600);
  Serial.println("Booted");

  // Start up the library
  sensors.begin();
  sensors2.begin();

  // locate devices on the bus
  Serial.println("Locating devices...");
  Serial.print("Found ");
  deviceCount = sensors.getDeviceCount();
  deviceCount2 = sensors2.getDeviceCount();
  Serial.print(deviceCount, DEC);
  Serial.print(" devices on bus 1 and ");
  Serial.print(deviceCount2, DEC);
  Serial.println(" devices on bus 2");
  Serial.println("");
  
  Serial.println("Printing addresses 1...");
  for (int i = 0;  i < deviceCount;  i++)
  {
    Serial.print("Sensor ");
    Serial.print(i+1);
    Serial.print(" : ");
    sensors.getAddress(Thermometer, i);
    printAddress(Thermometer);
  }
  Serial.println("Printing addresses 2...");
  for (int i = 0;  i < deviceCount2;  i++)
  {
    Serial.print("Sensor ");
    Serial.print(i+1);
    Serial.print(" : ");
    sensors.getAddress(Thermometer2, i);
    printAddress(Thermometer2);
  }
}

void loop(void)
{}

void printAddress(DeviceAddress deviceAddress)
{ 
  for (uint8_t i = 0; i < 8; i++)
  {
    Serial.print("0x");
    if (deviceAddress[i] < 0x10) Serial.print("0");
    Serial.print(deviceAddress[i], HEX);
    if (i < 7) Serial.print(", ");
  }
  Serial.println("");
}
