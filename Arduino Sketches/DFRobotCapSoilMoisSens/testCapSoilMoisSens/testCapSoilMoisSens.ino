#define NUMSENSORS 12
int maxVal[NUMSENSORS];
int minVal[NUMSENSORS];
int val[NUMSENSORS];
void setup() {
  // put your setup code here, to run once:
  Serial.begin(115200); //begin serial
  while (!Serial) {
    ; // wait for serial port to connect. Needed for native USB port only
  }
  for (int i = 4; i < (NUMSENSORS + 4); i++) {
    pinMode(i, OUTPUT);
  }
  memset(maxVal, 0, sizeof(maxVal));
  memset(minVal, 600, sizeof(minVal));
}

void loop() {
  // put your main code here, to run repeatedly:
  for (int i = 4; i < (NUMSENSORS + 4); i++) {
    digitalWrite(i, HIGH);
  }
  delay(100); // wait for sensors to stabilize
  for (int i = 0; i < NUMSENSORS ; i++) {
    val[i] = analogRead(i); //connect sensor to Analog 0
    if (val[i] > maxVal[i]){
      maxVal[i] = val[i];
    }
    else if (val[i] < minVal[i]){
      minVal[i] = val[i];
    }
    delay(10);
  }

  for (int i = 5; i <= (NUMSENSORS + 4); i++) {
    digitalWrite(i, LOW);
  }

  for (int i = 0; i < NUMSENSORS; i++) {
    Serial.print(i);
    Serial.print(": ");
    Serial.print(val[i]); //print the value to serial port
    Serial.print(", ");
    Serial.print(minVal[i]);
    Serial.print(", ");
    Serial.println(maxVal[i]);
    delay(10);   
  }
  Serial.println("-----");
  //delay(750);
}
