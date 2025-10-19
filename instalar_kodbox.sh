#!/bin/bash

echo "=== Instalando Kodbox (Gestor de archivos web) ==="

# 1️⃣ Verificar directorio base
if [ ! -d "/var/www/AppRNP" ]; then
    echo "❌ La ruta /var/www/AppRNP no existe. Créala primero."
    exit 1
fi

# 2️⃣ Crear carpeta Kodbox
echo "📂 Creando carpeta /var/www/AppRNP/kodbox"
sudo mkdir -p /var/www/AppRNP/kodbox
cd /var/www/AppRNP/kodbox

# 3️⃣ Descargar Kodbox última versión estable
echo "⬇️ Descargando Kodbox..."
sudo wget https://static.kodcloud.com/update/download/kodbox.1.41.zip -O kodbox.zip

# 4️⃣ Instalar unzip si no existe
sudo dnf install -y unzip

# 5️⃣ Extraer archivos
echo "📦 Extrayendo Kodbox..."
sudo unzip kodbox.zip
sudo rm kodbox.zip

# 6️⃣ Verificar carpeta /files
if [ ! -d "/var/www/AppRNP/files" ]; then
    echo "⚠ La carpeta /var/www/AppRNP/files no existe, creándola..."
    sudo mkdir -p /var/www/AppRNP/files
fi

# 7️⃣ Asignar permisos de Apache
echo "🔐 Ajustando permisos para Apache..."
sudo chown -R apache:apache /var/www/AppRNP/kodbox
sudo chown -R apache:apache /var/www/AppRNP/files
sudo chmod -R 775 /var/www/AppRNP/kodbox
sudo chmod -R 775 /var/www/AppRNP/files

# 8️⃣ Crear Alias de Apache para acceso web
echo "🛠 Configurando Apache en /etc/httpd/conf.d/kodbox.conf"
cat <<EOF | sudo tee /etc/httpd/conf.d/kodbox.conf
Alias /kodbox /var/www/AppRNP/kodbox

<Directory /var/www/AppRNP/kodbox>
    AllowOverride All
    Require all granted
</Directory>

<Directory /var/www/AppRNP/files>
    AllowOverride All
    Require all granted
</Directory>
EOF

# 9️⃣ Reiniciar Apache
echo "🔄 Reiniciando Apache..."
sudo systemctl restart httpd

echo "✅ Instalación completada."
echo "👉 Ahora accede a: https://app2.rnp.hn/kodbox"
echo "📂 Y ya tiene permisos sobre: /var/www/AppRNP/files"



# Instrucciones para ejecutar el script:
# 1. Crear el archivo del script
#nano instalar_kodbox.sh
# ⬅ Pegar todo el script

# 2. Guardar y salir (Ctrl + O, Enter, Ctrl + X)
# 3. Dar permisos de ejecución y ejecutar el script 

#chmod +x instalar_kodbox.sh
#sudo ./instalar_kodbox.sh

