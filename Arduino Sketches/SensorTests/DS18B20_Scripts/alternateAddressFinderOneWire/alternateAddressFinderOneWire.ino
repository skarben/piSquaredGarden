// This sketch looks for 1-wire devices and
// prints their addresses (serial number) to
// the serial monitor, in hexadecimal format
// so that the ROM code can be copied and pasted
// into a sketch.
// Pete El_Supremo
#include <OneWire.h>

#define ONEWIRE_PIN 8

OneWire  ds(ONEWIRE_PIN);

void setup(void) {
  Serial.begin(9600);
  while(!Serial);
  delay(2000);
  discoverOneWireDevices();
}

void discoverOneWireDevices(void) {
  byte i;
  byte present = 0;
  byte data[12];
  byte addr[8];
  
  Serial.print("Looking for 1-Wire devices on pin ");
  Serial.println(ONEWIRE_PIN);
  
  while(ds.search(addr)) {
//    Serial.print("\n\rFound \'1-Wire\' device with address:\n\r");
    for( i = 0; i < 8; i++) {
      Serial.print("0x");
      if (addr[i] < 16) {
        Serial.print('0');
      }
      Serial.print(addr[i], HEX);
      if (i < 7) {
        Serial.print(", ");
      }
    }
    if ( OneWire::crc8( addr, 7) != addr[7]) {
        Serial.print("CRC is not valid!\n");
        return;
    }
    Serial.println("");
  }
  Serial.print("\nThat's all.\n");
  ds.reset_search();
}

void loop(void) {
  // nothing to see here
}
