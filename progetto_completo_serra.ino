#include <HTTPClient.h>
#include <b64.h>
#include <HttpClient.h>
#include <ArduinoJson.h>
#include <DHT.h>
#include <LiquidCrystal.h>
#include <NewPing.h>
#include <WiFi.h>
#define SENSORPIN 34
#define REDPIN 27
#define YELLOWPIN 25
#define GREENPIN 26
#define ENABLEPIN 22
#define PINTERRENO 35
#define ANALOG_PIN 0
#define MIN_VALUE_TERRA 1900
#define MAX_VALUE_TERRA 2800
#define MAX_VALUE_ACQUA 3500

LiquidCrystal lcd(15, 12, 4, 16, 18, 21);
#define DHTPIN 13        
#define DHTTYPE DHT11 

DHT dht(DHTPIN, DHTTYPE);

const int relay = 32;
const int relay_luce = 17;


//const char* ssid = "ciucani";
//const char* password = "79320770515041988478";
const char* ssid = "ONEPLUS_co_aprdqc";
const char* password = "Ciuca2000";

//const char* ssid = "BiblioSGV";
//const char* password = "ComuneSgv2020";
//const char* host = "ftp.greenhous3.altervista.org";

unsigned long lastTime1 = 0;
unsigned long lastTime2 = 0;
const long interval = 2000;
const long interval_long = 20000;

void setup() {
  lcd.begin(16, 2);
  Serial.begin(115200);
  //delay(5000);
  pinMode(DHTPIN, INPUT);
  dht.begin();
  pinMode(REDPIN, OUTPUT);
  pinMode(YELLOWPIN, OUTPUT);
  pinMode(GREENPIN, OUTPUT);
  pinMode(ENABLEPIN, OUTPUT);
  digitalWrite(ENABLEPIN, LOW);
  pinMode(ANALOG_PIN, OUTPUT);
  pinMode(relay, OUTPUT);
  pinMode(relay_luce, OUTPUT);
  digitalWrite(relay, LOW);
  digitalWrite(relay_luce, LOW);
  
  WiFi.begin(ssid, password);
  Serial.println("Connecting");
  while(WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());

  
  
}

void loop() {
  unsigned long currentMillis1 = millis();
  if (currentMillis1 - lastTime1 >= interval) {
     lastTime1 = currentMillis1;
     HTTPClient http_GET;
     http_GET.begin("http://greenhous3.altervista.org/retrieveButtons.php");

     int httpResponseCode_GET = http_GET.GET();
     String payload2 = http_GET.getString();
     Serial.print("Risposta get: ");
     Serial.println(httpResponseCode_GET);
     Serial.print("payload88: ");
     Serial.println(payload2);

     int StringCount = 0;
     String strs[20];

     char part1 = payload2.charAt(8); // restituisce i primi 5 caratteri di str ("Hello")
     char part2 = payload2.charAt(15);
  
     int value_ventola = part1- '0';
     int value_luce = part2- '0';

  
     Serial.print("Valore ventola: ");
     Serial.println(value_ventola);
     Serial.print("Valore luce: ");
     Serial.println(value_luce);
     

     if(value_ventola == 1){
        digitalWrite(relay, LOW);
     }else if(value_ventola == 0){
        digitalWrite(relay, HIGH);
     }else{
        Serial.print("errore valore ventola");
     }

     if(value_luce == 1){
       digitalWrite(relay_luce, LOW);
     }else if(value_luce == 0){
       digitalWrite(relay_luce, HIGH);
     }else{
       Serial.print("errore valore luce");
     }
  }

  unsigned long currentMillis2 = millis();
  
  if (currentMillis2 - lastTime2 >= interval_long) {
    lastTime2 = currentMillis2;
  
    //sensore umidità terreno
    //digitalWrite(alimentazione_terra, HIGH);
    //delay(500);
    int analog = analogRead(ANALOG_PIN);
    //digitalWrite(alimentazione_terra, LOW);
    int h_t = map(analog, MIN_VALUE_TERRA, MAX_VALUE_TERRA, 100, 0);
    //int h_t = (analog * 100) / MAX_VALUE_TERRA;
    h_t = constrain(h_t, 0, 100);
  
  
    //sensore temperatura con lcd
    int t = dht.readTemperature();
    int h = dht.readHumidity();
    lcd.clear();
    lcd.setCursor(0,0); //prima riga dello schermo
    lcd.print("temp:" +String(t) + "C");
    lcd.setCursor(0, 1);
    lcd.print("humidity:" +String(h) + "%");

    Serial.print("Temperatura: ");
    Serial.print(t);
    Serial.print(" °C - Umidità: ");
    Serial.print(h);
    Serial.print(" % ");
    Serial.print("terreno: ");
    Serial.println(h_t);
 
    //sensore livello acqua
    int level = readWaterLevel();
    if(level >= 0 && level < 2000){
      digitalWrite(REDPIN, HIGH);
      digitalWrite(GREENPIN, LOW);
      digitalWrite(YELLOWPIN, LOW);
    }else if(level >= 2000 && level < 3500){
        digitalWrite(REDPIN, LOW);
        digitalWrite(GREENPIN, HIGH);
        digitalWrite(YELLOWPIN, LOW);
    }else{
      digitalWrite(REDPIN, LOW);
      digitalWrite(GREENPIN, LOW);
      digitalWrite(YELLOWPIN, HIGH);
    }
    int percentuale_acqua = (level * 100) / MAX_VALUE_ACQUA;
    percentuale_acqua = constrain(percentuale_acqua, 0, 100);
  

    //collegamento al database
    HTTPClient http;
    

    http.begin("http://greenhous3.altervista.org/action_arduino.php");
    http.addHeader("Content-type", "application/x-www-form-urlencoded");
    String httpPost = "&umidita_aria="+String(h)+""+"&umidita_terra="+String(h_t)+""+"&temperatura="+String(t)+""+"&livello_acqua="+String(percentuale_acqua)+"";
    Serial.print("HTTP POST: ");
    Serial.println(httpPost);

    int httpResponse = http.POST(httpPost);

    String payload = http.getString();
    Serial.print("Risposta server: ");
    Serial.println(httpResponse);
    Serial.print("payload: ");
    Serial.println(payload);
  }

}


int readWaterLevel(){
  digitalWrite(ENABLEPIN, HIGH);
  delay(100);
  int level = analogRead(SENSORPIN);
  digitalWrite(ENABLEPIN, LOW);
  return level;
}
