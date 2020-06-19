int testPin = 2;
unsigned long prevTime = 0;
unsigned long currentTime = millis();
void setup() {
  // put your setup code here, to run once:
  pinMode(LED_BUILTIN, OUTPUT);
  pinMode(testPin, INPUT);
  Serial.begin(115200);
  while (!Serial) {
    ; // wait for serial port to connect. Needed for native USB port only
  }
  //establishContact();  // send a byte to establish contact until receiver responds
}
char hold;
char clearBuffer;
char hold2;
char send2;
char est[] = "*";
String data;
//unsigned long iter = 0;
void loop() {
  // put your main code here, to run repeatedly:
  currentTime = millis();
  if (Serial.available() /*&& currentTime - prevTime > 100*/) {      // If anything comes in Serial (USB),
    prevTime = millis();
    clearBuffer = Serial.read();
    int state = digitalRead(testPin);
    if (state == LOW){
      data = "Pin 2: LOW";
      digitalWrite(LED_BUILTIN, LOW);    // turn the LED off by making the voltage LOW
    }
    else if (state == HIGH){
      data = "Pin 2:HIGH";
      digitalWrite(LED_BUILTIN, HIGH);   // turn the LED on (HIGH is the voltage level)
    }
    //hold = Serial.read();
   // hold2 = '<' + hold;
    //send2 = hold2 + '>';
    //Serial.write(hold);   // read it and send it out Serial1 (pins 0 & 1)
    Serial.println(data);

  }
  
}

void establishContact() {
  while (Serial.available() <= 0) {
    currentTime = millis();
    if (currentTime - prevTime > 300){
      prevTime = millis();
      Serial.write(est);   // send contact characters
    }
  }
}
