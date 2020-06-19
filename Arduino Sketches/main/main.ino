/*
The Arduino code to send data to a Raspberry Pi microcomputer via serial communication. 

This code collects data from DS18B20 temperature probes, (TO DO: capacitive soil moisture, ambient 
air temperature and humidity). Data from a square foot garden, 4' x 4'.

Modified from templates from included Arduino DallasTemperature library and _(TO DO: include other 
mentions)

Samson Karben 2020
*/
//include libraries for DS18B20 temperature probes
#include <OneWire.h>
#include <DallasTemperature.h>

// Data wire for OneWire DS18B20 temperature probes is plugged into digital pin 2 and 3 
// on the Arduino. Pin 2 is group 1 of the sensors, pin 3 is group 2.
#define ONE_WIRE_BUS 2
#define ONE_WIRE_BUS2 3

#define NUMTSENSORS 15 //number of temperature probe DS18B20 sensors
#define NUMMSENSORS 12 //number of soil moisture analog sensors, used in loops later

// Setup two oneWire instances to communicate with the OneWire devices
OneWire oneWire(ONE_WIRE_BUS); //instance for Group 1
OneWire oneWire2(ONE_WIRE_BUS2); //instance for Group 2

// Pass oneWire reference to DallasTemperature library
DallasTemperature sensors(&oneWire); //group 1
DallasTemperature sensors2(&oneWire2); //group 2

// Addresses of the 15 DS18B20 sensors.

//save the device address to a variable
//group 1, sensors
uint8_t sensor0[8] = { 0x28, 0xA9, 0xD1, 0x0F, 0x2A, 0x19, 0x01, 0x23 };
uint8_t sensor5[8] = { 0x28, 0x03, 0xF4, 0x4C, 0x2A, 0x19, 0x01, 0xF2 }; 
uint8_t sensor8[8] = { 0x28, 0xA5, 0x7F, 0x1F, 0x2A, 0x19, 0x01, 0xF7 };
uint8_t sensor9[8] = { 0x28, 0x93, 0xAB, 0x17, 0x2A, 0x19, 0x01, 0xF5 };
uint8_t sensor10[8] = { 0x28, 0x31, 0xE4, 0x0C, 0x2A, 0x19, 0x01, 0xED };
uint8_t sensor11[8] = { 0x28, 0x7C, 0x2A, 0xF1, 0x29, 0x19, 0x01, 0x5A };
uint8_t sensor12[8] = { 0x28, 0x04, 0xD8, 0x16, 0x2A, 0x19, 0x01, 0x6D };
uint8_t sensor13[8] = { 0x28, 0x0A, 0xD1, 0x59, 0x2A, 0x19, 0x01, 0xF7 };
uint8_t sensor14[8] = { 0x28, 0xDB, 0x4E, 0x3A, 0x2A, 0x19, 0x01, 0xC4 };

//group 2, sensors2
uint8_t sensor1[8] = { 0x28, 0xCA, 0x4E, 0x25, 0x2A, 0x19, 0x01, 0x0A };
uint8_t sensor2[8] = { 0x28, 0xA6, 0x6E, 0x0D, 0x2A, 0x19, 0x01, 0x20 };
uint8_t sensor3[8] = { 0x28, 0x56, 0x08, 0x1F, 0x2A, 0x19, 0x01, 0xE0 };
uint8_t sensor4[8] = { 0x28, 0xFA, 0x67, 0x24, 0x2A, 0x19, 0x01, 0x63 };
uint8_t sensor6[8] = { 0x28, 0x10, 0x08, 0x59, 0x2A, 0x19, 0x01, 0xCE };
uint8_t sensor7[8] = { 0x28, 0x38, 0xB7, 0x15, 0x2A, 0x19, 0x01, 0x0B };

float tempC[NUMTSENSORS]; // for storing temperature data, 15 for 15 sensors

int tempPowerPin = 1; //pin for powering temperature pins on pin 1

int soilMoisVal[NUMMSENSORS]; // for storing soil moisture data

void setup() {
  // put your setup code here, to run once:
  Serial.begin(115200); //begin serial
  while (!Serial) {
    ; // wait for serial port to connect. Needed for native USB port only
  }
  pinMode(tempPowerPin, OUTPUT); // set pin 1 for temp probes as power output
  for (int i = 4; i < (NUMMSENSORS + 4); i++) { //set the power pins for the soil sensors
    pinMode(i, OUTPUT);
  }
  sensors.begin(); //begin group 1 sensors
  sensors2.begin(); //begin group 2 sensors
}

void loop() {
  // put your main code here, to run repeatedly:
  memset(tempC, 0, sizeof(tempC)); // clear temperature data
  memset(soilMoisVal, 0, sizeof(soilMoisVal)); //clear soil moisture data
  digitalWrite(tempPowerPin, HIGH); // turn on temp sensors
  delay(10); //wait until temperature probes stabilize  
  OnStoreAllSoilMoisture(); //turn on and record all soil sensors 
  storeTemperature(); //store the temperature, can be called once every 2 seconds
  Serial.println("Temps:");
  for (int i = 0; i < 15; i++) {
    Serial.print("Sensor ");
    Serial.print(i);
    Serial.print(": ");
    Serial.print(tempC[i]);
    Serial.println("°F");
  }
  Serial.println("Moisture:");
  for (int i = 0; i < NUMMSENSORS; i++) {
    Serial.print("Sensor ");
    Serial.print(i);
    Serial.print(": ");
    Serial.println(soilMoisVal[i]); //print the value to serial port  
  }
  Serial.println("--------------");
  turnOffSensors();
}

void storeTemperature() { //function to simplify calling all the sensors
  sensors.requestTemperatures(); //get temps from the sensors
  sensors2.requestTemperatures();
  //store data in the array
  tempC[0] = sensors.getTempC(sensor0);
  tempC[1] = sensors2.getTempC(sensor1);
  tempC[2] = sensors2.getTempC(sensor2);
  tempC[3] = sensors2.getTempC(sensor3);
  tempC[4] = sensors2.getTempC(sensor4);
  tempC[5] = sensors.getTempC(sensor5);
  tempC[6] = sensors2.getTempC(sensor6);
  tempC[7] = sensors2.getTempC(sensor7);
  tempC[8] = sensors.getTempC(sensor8);
  tempC[9] = sensors.getTempC(sensor9);
  tempC[10] = sensors.getTempC(sensor10);
  tempC[11] = sensors.getTempC(sensor11);
  tempC[12] = sensors.getTempC(sensor12);
  tempC[13] = sensors.getTempC(sensor13);
  tempC[14] = sensors.getTempC(sensor14);
}

void OnStoreAllSoilMoisture() { //function to simplify turning on and reading all soil sensors
  for (int i = 4; i < (NUMMSENSORS + 4); i++) { //turn on moisture sensors
  digitalWrite(i, HIGH);
  }
  delay(100); //wait until sensors stabilize
  for (int i = 0; i < NUMMSENSORS ; i++) {
    soilMoisVal[i] = analogRead(i); //connect sensor to Analog 0
    delay(10); //wait for ADC to stabilize
  }
}

void turnOffSensors() { //function to turn all sensors off
  digitalWrite(tempPowerPin, LOW); //turn off temp sensors
  for (int i = 5; i <= (NUMMSENSORS + 4); i++) { //turn off soil moisture sensors
    digitalWrite(i, LOW);
  }
}
