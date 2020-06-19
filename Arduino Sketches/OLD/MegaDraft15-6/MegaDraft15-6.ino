#include <OneWire.h>

int DS18S20_Pin = 2; //DS18S20 Signal pin on digital 2

//Temperature chip i/o
OneWire ds(DS18S20_Pin);  // on digital pin 2


int analogPins[16];
float anaToV = 5.0000 / 1023.0000;
void setup() {
  // put your setup code here, to run once:
  for (int i = A0; i <= A15; i++) {
    pinMode(i, INPUT); 
    analogPins[i] = i;
  }
  Serial.begin(115200); //begin serial connection at 115200
  while (!Serial) {
    ; // wait for serial port to connect. Needed for native USB port only
  }
}
char clearBuffer;
void loop() {
  // put your main code here, to run repeatedly:
  if (Serial.available()){
    clearBuffer = Serial.read();
    float data[16];
    for (int i = A0; i <= A15; i++) {
      data[i] = analogRead(i);
     /* delay(10);
      data[i] = analogRead(i);
      data[i] = data[i] * anaToV;*/
      Serial.print(data[i] * anaToV);
      Serial.print("V");
      Serial.println(i-54);
      delay(10); 
    }
  }
}

float getTemp(){
  //returns the temperature from one DS18S20 in DEG Celsius

  byte data[12];
  byte addr[8];

  if ( !ds.search(addr)) {
      //no more sensors on chain, reset search
      ds.reset_search();
      return -1000;
  }

  if ( OneWire::crc8( addr, 7) != addr[7]) {
      Serial.println("CRC is not valid!");
      return -1000;
  }

  if ( addr[0] != 0x10 && addr[0] != 0x28) {
      Serial.print("Device is not recognized");
      return -1000;
  }

  ds.reset();
  ds.select(addr);
  ds.write(0x44,1); // start conversion, with parasite power on at the end

  byte present = ds.reset();
  ds.select(addr);
  ds.write(0xBE); // Read Scratchpad


  for (int i = 0; i < 9; i++) { // we need 9 bytes
    data[i] = ds.read();
  }

  ds.reset_search();

  byte MSB = data[1];
  byte LSB = data[0];

  float tempRead = ((MSB << 8) | LSB); //using two's compliment
  float TemperatureSum = tempRead / 16;

  return TemperatureSum;

}
