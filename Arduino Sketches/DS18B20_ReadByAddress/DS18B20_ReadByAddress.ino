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

// Addresses of 3 DS18B20s

//round 1
uint8_t sensor5[8] = { 0x28, 0x03, 0xF4, 0x4C, 0x2A, 0x19, 0x01, 0xF2 };
uint8_t sensor8[8] = { 0x28, 0xA5, 0x7F, 0x1F, 0x2A, 0x19, 0x01, 0xF7 };
uint8_t sensor9[8] = { 0x28, 0x93, 0xAB, 0x17, 0x2A, 0x19, 0x01, 0xF5 };
uint8_t sensor10[8] = { 0x28, 0x31, 0xE4, 0x0C, 0x2A, 0x19, 0x01, 0xED };
uint8_t sensor11[8] = { 0x28, 0x7C, 0x2A, 0xF1, 0x29, 0x19, 0x01, 0x5A };
uint8_t sensor12[8] = { 0x28, 0x04, 0xD8, 0x16, 0x2A, 0x19, 0x01, 0x6D };
uint8_t sensor14[8] = { 0x28, 0xDB, 0x4E, 0x3A, 0x2A, 0x19, 0x01, 0xC4 };
uint8_t sensor15[8] = { 0x28, 0xA9, 0xD1, 0x0F, 0x2A, 0x19, 0x01, 0x23 };
uint8_t sensor16[8] = { 0x28, 0x0A, 0xD1, 0x59, 0x2A, 0x19, 0x01, 0xF7 };

//round 2
uint8_t sensor1[8] = { 0x28, 0xCA, 0x4E, 0x25, 0x2A, 0x19, 0x01, 0x0A };
uint8_t sensor2[8] = { 0x28, 0xA6, 0x6E, 0x0D, 0x2A, 0x19, 0x01, 0x20 };
uint8_t sensor3[8] = { 0x28, 0x56, 0x08, 0x1F, 0x2A, 0x19, 0x01, 0xE0 };
uint8_t sensor4[8] = { 0x28, 0xFA, 0x67, 0x24, 0x2A, 0x19, 0x01, 0x63 };
uint8_t sensor6[8] = { 0x28, 0x10, 0x08, 0x59, 0x2A, 0x19, 0x01, 0xCE };
uint8_t sensor7[8] = { 0x28, 0x38, 0xB7, 0x15, 0x2A, 0x19, 0x01, 0x0B };

void setup(void)
{
  Serial.begin(9600);
  sensors.begin();
  sensors2.begin();
}

void loop(void)
{
  sensors.requestTemperatures();
  sensors2.requestTemperatures();

  Serial.print("Sensor 1: ");
  printTemperature2(sensor1);

  Serial.print("Sensor 2: ");
  printTemperature2(sensor2);

  Serial.print("Sensor 3: ");
  printTemperature2(sensor3);

  Serial.print("Sensor 4: ");
  printTemperature2(sensor4);

  Serial.print("Sensor 5: ");
  printTemperature(sensor5);
  
  Serial.print("Sensor 6: ");
  printTemperature2(sensor6);

  Serial.print("Sensor 7: ");
  printTemperature2(sensor7);

  Serial.print("Sensor 8: ");
  printTemperature(sensor8);

  Serial.print("Sensor 9: ");
  printTemperature(sensor9);
  
  Serial.print("Sensor 10: ");
  printTemperature(sensor10);

  Serial.print("Sensor 11: ");
  printTemperature(sensor11); 
  
  Serial.print("Sensor 12: ");
  printTemperature(sensor12);

  Serial.print("Sensor 14: ");
  printTemperature(sensor14);

  Serial.print("Sensor 15: ");
  printTemperature(sensor15);
  
  Serial.print("Sensor 16: ");
  printTemperature(sensor16);
  
  Serial.println();
  delay(1000);
}

void printTemperature(DeviceAddress deviceAddress)
{
  float tempC = sensors.getTempC(deviceAddress);
  Serial.print(tempC);
  Serial.print((char)176);
  Serial.print("C  |  ");
  Serial.print(DallasTemperature::toFahrenheit(tempC));
  Serial.print((char)176);
  Serial.println("F");
}

void printTemperature2(DeviceAddress deviceAddress)
{
  float tempC = sensors2.getTempC(deviceAddress);
  Serial.print(tempC);
  Serial.print((char)176);
  Serial.print("C  |  ");
  Serial.print(DallasTemperature::toFahrenheit(tempC));
  Serial.print((char)176);
  Serial.println("F");
}
