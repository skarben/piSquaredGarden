//Arduino Sample Code for SHT1x Humidity and Temperature Sensor
//www.DFRobot.com
//Version 1.0

#include <SHT1x.h>

// Specify data and clock connections and instantiate SHT1x object
#define dataPin  10
#define clockPin 11
#define powerPinA 12
SHT1x sht1x(dataPin, clockPin);

void setup()
{
   Serial.begin(115200); // Open serial connection to report values to host
   while (!Serial) {
    ; // wait for serial port to connect. Needed for native USB port only
   }
   Serial.println("Starting up");
   pinMode(powerPinA, OUTPUT);
   digitalWrite(powerPinA, HIGH);
   
}

void loop()
{
  float temp_c;
  float temp_f;
  float humidity;
  //digitalWrite(powerPinA, HIGH);
  //delay(10);
  // Read values from the sensor
  temp_c = sht1x.readTemperatureC();
  temp_f = sht1x.readTemperatureF();
  humidity = sht1x.readHumidity();

  // Print the values to the serial port
  Serial.print("Temperature: ");
  Serial.print(temp_c, DEC);
  Serial.print("C / ");
  Serial.print(temp_f, DEC);
  Serial.print("F. Humidity: ");
  Serial.print(humidity);
  Serial.println("%");
  //delay(8000);
  //digitalWrite(powerPinA, LOW);
}
