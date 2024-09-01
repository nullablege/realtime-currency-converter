Real-Time Currency Converter
This project utilizes the frontend part taken from GitHub and customizes it to fit the purpose. The goal is to perform real-time currency conversion between 162 currencies using the ExchangeRate API.

Features
Real-Time Currency Conversion: Converts currencies using up-to-date exchange rates from the ExchangeRate API.
User Limitation: Limits the number of queries per hour based on IP address.
SQL-Based Protection: Limits the number of queries per user within a specific timeframe, and deletes old records after the interval.

Requirements
PHP - For running on a web server.
MySQL - For database management.

Database Setup
Create Table
CREATE TABLE currency (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip VARCHAR(255),
    tekrar INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

Create Event
CREATE EVENT delete_old_records
ON SCHEDULE EVERY 1 HOUR
DO
    DELETE FROM currency
    WHERE created_at < NOW() - INTERVAL 1 HOUR;

Usage
Frontend: Upload the index.php file to your server.
Backend and SQL: Use the SQL commands above to set up your database.
Conversion: Visit the site and perform currency conversion.

Purpose
This project is intended for preparation and development for the BTBS327 Server-Side Internet Programming course.

Gerçek Zamanlı Döviz Çevirici
Bu proje, Github'dan alınan frontend kısmını kullanarak ve amaca uygun şekilde düzenleyerek oluşturulmuştur. Projenin amacı, ExchangeRate API kullanarak 162 para birimi arasında gerçek zamanlı döviz çevirisi yapmaktır.

Özellikler
Gerçek Zamanlı Döviz Çevirisi: ExchangeRate API ile güncel döviz kurları kullanılarak çeviri yapılır.
Kullanıcı Sınırlaması: Her kullanıcı IP adresine göre saatlik sorgu sınırı uygulanır.
SQL Tabanlı Koruma: Belirli bir sürede belirli bir sayıda sorgu hakkı tanımlanır ve bu süre sonunda eski kayıtlar silinir.

Gereksinimler
PHP - Web sunucusunda çalışması için.
MySQL - Veritabanı yönetimi için.

Veritabanı Yapılandırması
Tablo Oluşturma
CREATE TABLE currency (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip VARCHAR(255),
    tekrar INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

Event Oluşturma
CREATE EVENT delete_old_records
ON SCHEDULE EVERY 1 HOUR
DO
    DELETE FROM currency
    WHERE created_at < NOW() - INTERVAL 1 HOUR;

Kullanım
Frontend: index.php dosyasını sunucunuza yükleyin.
Backend ve SQL: Yukarıdaki SQL komutlarını kullanarak veritabanınızı yapılandırın.
Çeviri: Siteye gidin ve döviz çevirisi yapın.
