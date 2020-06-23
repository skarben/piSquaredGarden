#include <OneWire.h>
#include <DallasTemperature.h>

// Data wire is plugged into digital pin 2 on the Arduino
#define ONE_WIRE_BUS 8
#define ONE_WIRE_BUS2 12
// Setup a oneWire instance to communicate with any OneWire device
OneWire oneWire(ONE_WIRE_BUS);  
OneWire oneWire2(ONE_WIRE_BUS2);
// Pass oneWire reference to DallasTemperature library
DallasTemperature sensors(&oneWire);
DallasTemperature sensors2(&oneWire2);
int deviceCount = 0;
int deviceCount2 = 0;
float tempC;

void setup(void)
{
  sensors.begin();  // Start up the library
  sensors2.begin();
  Serial.begin(9600);
  
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
}

void loop(void)
{ 
  // Send command to all the sensors for temperature conversion
  sensors.requestTemperatures();
  sensors2.requestTemperatures(); 
  
  // Display temperature from each sensor
  Serial.println("Sensors 1:");
  for (int i = 0;  i < deviceCount;  i++)
  {
    Serial.print("Sensor ");
    Serial.print(i+1);
    Serial.print(" : ");
    tempC = sensors.getTempCByIndex(i);
    Serial.print(tempC);
    Serial.print((char)176);//shows degrees character
    Serial.print("C  |  ");
    Serial.print(DallasTemperature::toFahrenheit(tempC));
    Serial.print((char)176);//shows degrees character
    Serial.println("F");
  }

  Serial.println("Sensors 2:");
  for (int i = 0;  i < deviceCount2;  i++)
  {
    Serial.print("Sensor ");
    Serial.print(i+1);
    Serial.print(" : ");
    tempC = sensors2.getTempCByIndex(i);
    Serial.print(tempC);
    Serial.print((char)176);//shows degrees character
    Serial.print("C  |  ");
    Serial.print(DallasTemperature::toFahrenheit(tempC));
    Serial.print((char)176);//shows degrees character
    Serial.println("F");
  }
  
  Serial.println("");
  delay(1000);
}
