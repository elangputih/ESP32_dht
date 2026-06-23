#include <WiFi.h>
#include <HTTPClient.h>
#include <DHT.h>

#define DHTPIN 4          // Pin data DHT terhubung ke GPIO 4
#define DHTTYPE DHT11     // Ubah menjadi DHT22 jika menggunakan sensor DHT22

DHT dht(DHTPIN, DHTTYPE);

// Konfigurasi Wi-Fi
const char* ssid     = "Redmi 9";
const char* password = "elang507";

// URL endpoint server Anda (Ganti IP sesuai IP Laptop/Server Anda)
// Jika uji coba lokal pakai XAMPP, gunakan IP Laptop Anda (misal: 192.168.1.10)
const char* serverName = "http://10.105.4.78/dht-monitor/insert_data.php";

unsigned long lastTime = 0;
unsigned long timerDelay = 10000; // Kirim data setiap 10 detik

void setup() {
  Serial.begin(115200);
  dht.begin();

  WiFi.begin(ssid, password);
  Serial.print("Connecting to WiFi");
  while(WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());
}

void loop() {
  // Kirim data berdasarkan interval timer
  if ((millis() - lastTime) > timerDelay) {
    if(WiFi.status()== WL_CONNECTED){
      HTTPClient http;

      // Membaca data dari sensor DHT
      float h = dht.readHumidity();
      float t = dht.readTemperature();

      // Cek apakah pembacaan sensor valid
      if (isnan(h) || isnan(t)) {
        Serial.println("Gagal membaca dari sensor DHT!");
        return;
      }

      // Memulai koneksi HTTP
      http.begin(serverName);
      
      // Menentukan header content-type untuk POST data (form-urlencoded)
      http.addHeader("Content-Type", "application/x-www-form-urlencoded");
      
      // Menyusun data string yang akan dikirim
      String httpRequestData = "temperature=" + String(t) + "&humidity=" + String(h);
      
      // Mengirimkan HTTP POST request
      int httpResponseCode = http.POST(httpRequestData);
      
      Serial.print("HTTP Response code: ");
      Serial.println(httpResponseCode);
        
      // Bebaskan resources
      http.end();
    }
    else {
      Serial.println("WiFi Terputus");
    }
    lastTime = millis();
  }
}
