# 🛡️ Proyecto SOTEIRA 

Este es el repositorio oficial para la práctica de despliegue de servicios de la organización **Soteira**.
---

## 🚀 Cómo arrancar el proyecto

Hemos configurado todo para que arranque con un solo comando. Desde la raíz:

```bash
docker compose up -d

```

**⚠️ AVISO IMPORTANTE:**
La primera vez que arranques, **Moodle tarda bastante (unos 3-4 minutos)** en estar listo porque tiene que instalar toda la base de datos desde cero.

* Puedes ver si ya terminó con: `docker logs -f soteira_moodle`
* Espera hasta ver: *"Welcome to the Bitnami Moodle container"*.

---

## 🔗 Accesos Directos

La IP del servidor está configurada en el archivo `.env` (`10.2.84.206`).

| Servicio | URL | Usuario / Pass (Default) |
| --- | --- | --- |
| **Intranet** | `http://10.2.84.206:8080` | *(Acceso libre)* |
| **Moodle** | `http://10.2.84.206:8082` | `user` / `bitnami` |
| **Nextcloud** | `http://10.2.84.206:8083` | *(Crear admin al entrar)* |
| **LetsChat** | `http://10.2.84.206:8084` | *(Requiere registro)* |
| **Peppermint** | `http://10.2.84.206:8085` | `admin@peppermint.com` / `admin` |

---

## 🔧 Desafíos y Soluciones (Bitácora)

Durante el desarrollo nos encontramos varios problemas que hemos solucionado así:

### 1. El problema de Moodle y "Bitnami Legacy"

Al principio intentamos usar la imagen `latest` de Bitnami, pero nos daba errores constantes de permisos y "404 Not Found" al intentar descargarla en la VM.

* **Solución:** Investigamos y cambiamos a la imagen `bitnamilegacy/moodle:5.0.2-debian-12-r2`. Esta versión es más estable y nos ha funcionado bien en Debian 12.

### 2. Cambio de RocketChat a LetsChat

Teníamos pensado usar RocketChat, pero la configuración de *MongoDB ReplicaSet* consumía demasiada RAM y hacía que la máquina virtual fuera muy lenta.


### 3. Conflicto de Puertos

Tuvimos problemas con el puerto 80 y el 3000 porque varios servicios querían usarlos a la vez.

* **Solución:** Hicimos una tabla de puertos en el rango `808X` (8080, 8081, 8082...) para tenerlo todo ordenado y evitar choques.

---

## 📝 Cosas por mejorar (To-Do)

Si tuviéramos más tiempo, nos gustaría añadir:

* [ ] Configurar HTTPS con certificados SSL reales.
* [ ] Crear un script de backup automático para las carpetas `data`.
* [ ] Mejorar el diseño CSS de la Intranet.

---

## 📂 Estructura de carpetas

Hemos separado los servicios por carpetas para no tener todo mezclado en la raíz:

* `/education_services`: Todo lo de Moodle.
* `/colab_services`: Chat y Nube.
* `/web_services`: El código PHP de la Intranet.
* `docker-compose.yml`: El orquestador general.

---
