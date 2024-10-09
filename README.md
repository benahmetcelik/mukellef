Merhabalar ,
Sistem iki parçadan oluşmaktadır.

## Varyant 1

Bu aşamada iletilen dökümanın üzerine ek özellikler eklenerek aşağıdaki yetenekler sisteme kazandırılmıştır

- Abonelik ve ödeme işlemleri ayrıldı
- Servisler içinde filtre eklendi
- Ara tablo oluşturularak tarihsel olarak takip mekanizması sağlandı
- Period değişkeni ile paketlerin kaç kere yenilenebileceği dinamikleştirildi
- Üyelikler için farklı durum tipleri eklendi
- Ödemeler için farklı durum tipleri eklendi

## Varyant 2

Bu aşamada iletilen dökümanın birebir aynısı yapılmaya çalışıldı

# Kurulum

Gereksinimler:

- Docker
- 2GB Ram
- 4GB Disk

Uyarılar:

- Cihazınızda 80 portu üzerinde çalışan servis var ise lütfen durdurunuz
- .env.example içinde mail alanında şifre bölümü bpoş bırakılmıştır. Kendi mail bilgilerinizi girerek kullanınız.
- .env.example üzerinde değişiklik yaptıktan sonra herhangi bir ayar yapmadan kurulum adımlarını takip ediniz

Adımlar:

- Projenin yüklü olduğu dizine gelerek aşağıdaki komutu çalıştırınız

```bash 
docker compose up -d --build
```

İşlem tamamlandıktan sonra ana dizinde bulunan ilgili postman collection dosyasını içe aktarıp testlere
başlayabilirsiniz.
