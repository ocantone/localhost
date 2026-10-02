/*********
  Servidor web con ESP32
  Incluye un lcd H44780 conectado via bus I2C. La dirección IP obtenida via 
  protocolo DHCP en el display.
  El display también muestra el estado de cada uno de dos relés conectados al microcontrolador y manejados mediante elcódigo HTML.
  Este sketch se basa en elejemplo de Rui Santos 
  y fue editado y adaptado por mí en Ramos Mejía, ARGENTINA, Agosto 2020.
  - Osvaldo Cantone  correo@cantone.com.ar
  reles:
    Relé1 GPIO.15
    Relé2 GPIO.2
  display:
    SDA GPIO.21
    SCL GPIO.22
*********/

#include <Arduino.h>
#include <WiFi.h>
#include <LiquidCrystal_PCF8574.h>
#include <Wire.h>

LiquidCrystal_PCF8574 lcd(0x27); // setea la dirección del LCD en 0x27 


// Reemplazar las X's con sus credenciales de red
const char* ssid = "XXXXXXXXXXXXX";
const char* password = "XXXXXXXXXXXXX";

// Setea el puerto de web server en 80
WiFiServer server(80);

// Variable tipo String que almacena la HTTP request
String header;

// Estado de las variables 
String relay1State = "off";
String relay2State = "off";

// Mapeo de variables en los pines GPIO
//const int output26 = 26;
//const int output27 = 27;
const int Relay1 = 15;
const int Relay2 = 2;

// Time actual
unsigned long currentTime = millis();
// Time previo
unsigned long previousTime = 0; 
// Define timeout en milisegundos 
const long timeoutTime = 2000;
int show = -1;

void setup() {

  int error;

  Serial.begin(9600);
  // Define salidas.
  pinMode(Relay1, OUTPUT);
  pinMode(Relay2, OUTPUT);
  // Inicialmente a nivel bajo.
  digitalWrite(Relay1, LOW);
  digitalWrite(Relay2, LOW);

  //Inicializa el display
Wire.begin();
  Wire.beginTransmission(0x27);
  error = Wire.endTransmission();
  Serial.print("Error: ");
  Serial.print(error);

  if (error == 0) {
    Serial.println(": LCD found.");
    show = 0;
    lcd.begin(16, 2); // inicializa el LCD, 16 columnas y 2 filas
 } else {
    Serial.println(": LCD not found.");
  } // if
    lcd.setBacklight(255);
    lcd.home();
    lcd.clear();
    lcd.setCursor(0, 0);
//  lcd.print("0123456789ABCDEF");
    lcd.print(" ESP32 WebSerer ");


  // Conecta con la red Wi-Fi.
  Serial.print("Connecting to "); //
  Serial.println(ssid);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  // Cuando se conecta, imprime la dirección IP asignada
  Serial.println("");
  Serial.println("WiFi connected.");
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());

    lcd.setCursor(0, 1);
    lcd.print(WiFi.localIP());
delay(2000);
    lcd.setCursor(0, 0);
    lcd.print("ESP32 ");
    lcd.print(WiFi.localIP());
    lcd.setCursor(0, 1);
    lcd.print("R1: ON   R2: ON ");


  server.begin();
}

void loop(){
  WiFiClient client = server.available();   // Escucha a los clientes entrantes

  if (client) {                             // Si se conecta un nuevo cliente,
    currentTime = millis();
    previousTime = currentTime;
    Serial.println("New Client.");          // imprime un mensaje en la consola
    String currentLine = "";                // string para almacenar los datos entrantes del cliente
    while (client.connected() && currentTime - previousTime <= timeoutTime) {  // loop mientras el cliente está conectado
      currentTime = millis();
      if (client.available()) {             // si hay bytes para leer del cliente,
        char c = client.read();             // lee un byte
        Serial.write(c);                    // imprime el byte en la consola
        header += c;
        if (c == '\n') {                    // si el byte es un caracter de nueva línea
          // si la linea actual está en blanco, entonces obtuviste dos caracteres de nueva línea seguidos.
          // ese es el final de la HTTP request, entonces envía una respuesta:  
          if (currentLine.length() == 0) {
            // Envia una respuesta estándar HTTP 200 OK
            // y content-type de modo que el cliente sepa lo que viene, luego una línea en blanco:
            client.println("HTTP/1.1 200 OK");
            client.println("Content-type:text/html");
            client.println("Connection: close");
            client.println();

            // Maneja las solicitudes GET para encender/apagar los reles
            if (header.indexOf("GET /26/on") >= 0) {
              Serial.println("GPIO 26 on");
              relay1State = "on";
              digitalWrite(Relay1, HIGH);
                lcd.setCursor(4, 1);
                lcd.print("OFF");

            } else if (header.indexOf("GET /26/off") >= 0) {
              Serial.println("GPIO 26 off");
              relay1State = "off";
              digitalWrite(Relay1, LOW);
                lcd.setCursor(4, 1);
                lcd.print("ON ");

            } else if (header.indexOf("GET /27/on") >= 0) {
              Serial.println("GPIO 27 on");
              relay2State = "on";
              digitalWrite(Relay2, HIGH);
                lcd.setCursor(13, 1);
                lcd.print("OFF");
            } else if (header.indexOf("GET /27/off") >= 0) {
              Serial.println("GPIO 27 off");
              relay2State = "off";
              digitalWrite(Relay2, LOW);
                lcd.setCursor(13, 1);
                lcd.print("ON ");
            }

            // Muestra la página web HTML
            client.println("<!DOCTYPE html><html>");
            client.println("<head><meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">");
            client.println("<link rel=\"icon\" href=\"data:,\">");
            
            // CSS de los botones 
            client.println("<style>html { font-family: Helvetica; display: inline-block; margin: 0px auto; text-align: center;}");
            client.println(".button { background-color: #4CAF50; border: none; color: white; padding: 16px 40px;");
            client.println("text-decoration: none; font-size: 30px; margin: 2px; cursor: pointer;}");
            client.println(".button2 {background-color: #555555;}</style></head>");



            // Encabezado de la página web 
            client.println("<body><h1>ESP32 Web Server</h1>");
            client.println("<p>Adapted by Osvaldo Cantone <a href=http://www.cantone.com.ar/wordpress/esp32-web-server-relay-control-lcd-i2c-display/>tecteach.net</a> </p>");

            // Muestra el estado actual, y los botones ON/OFF para GPIO 26  
            client.println("<p>Relay 1 (GPIO 15) - State: " + relay1State + "</p>");
            // Si el output26State está en off, muestra el botón ON  
            if (relay1State=="off") {
              client.println("<p><a href=\"/26/on\"><button class=\"button\">ON</button></a></p>");
            } else {
              client.println("<p><a href=\"/26/off\"><button class=\"button button2\">OFF</button></a></p>");
            } 

            // Muestra el estado actual, y los botones ON/OFF para GPIO 27  
            client.println("<p>Relay 2 (GPIO 2) - State: " + relay2State + "</p>");
            // Si el output27State está en off, muestra el botón ON 
            if (relay2State=="off") {
              client.println("<p><a href=\"/27/on\"><button class=\"button\">ON</button></a></p>");
            } else {
              client.println("<p><a href=\"/27/off\"><button class=\"button button2\">OFF</button></a></p>");
            }
            client.println("</body></html>");

            // La respuesta HTTP termina con una línea en blanco
            client.println();
            // Interrumpe el bucle while 
            break;
          } else { // Si tienes una nueva línea, pero la línea actual no está en blanco,
            currentLine = "";
          }
        } else if (c != '\r') {  // Si el byte no es un caracter de retorno de carro,
          currentLine += c;      // agrega el byte a la línea actual.
        }
      }
    }
    // Limpia la variable header.
    header = "";
    // Cierra la conexión.
    client.stop();
    Serial.println("Client disconnected.");
    Serial.println("");
  }
}