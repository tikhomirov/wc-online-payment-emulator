# WooCommerce Online Payment Emulator (`wc-online-payment-emulator`)

![WordPress Plugin](https://img.shields.io/badge/WordPress-5.0%2B-blue.svg)
![PHP Support](https://img.shields.io/badge/PHP-7.4%20%7C%208.0%20%7C%208.1%20%7C%208.2%20%7C%208.3-777BB4.svg)
![License](https://img.shields.io/badge/License-GPLv2-green.svg)

Платежный шлюз-эмулятор для WooCommerce. Идеально подходит для локальной разработки, тестирования оформления заказов и создания собственных платежных интеграций.

---

## 🚀 Возможности

- 💳 **Эмуляция успешной оплаты:** Мгновенный перевод заказа в статус "Оплачен" без ввода реальных карт.
- 🛠️ **Шаблон шлюза:** Полезен как чистая база для разработки собственных платежных модулей для WooCommerce.
- ⚙️ **Настройки в админке:** Включение/выключение и переименование шлюза на странице **WooCommerce → Настройки → Платежи**.

---

## 📥 Установка

### Через Composer (рекомендуется)
```bash
composer config repositories.tikhomirov-wc-online-payment-emulator git https://github.com/tikhomirov/wc-online-payment-emulator.git
composer require tikhomirov/wc-online-payment-emulator
```

### Вручную
1. Скачайте ZIP-архив репозитория.
2. Распакуйте в директорию `/wp-content/plugins/wc-online-payment-emulator/`.
3. Активируйте плагин в админ-панели **Плагины → Установленные**.

---

## 💻 Использование

1. Активируйте плагин.
2. Перейдите в **WooCommerce → Настройки → Платежи**.
3. Включите **Online Payment Emulator** и проведите тестовый заказ.

---

## 🛠️ Требования

- **WordPress:** 5.0 или выше
- **PHP:** 7.4, 8.0, 8.1, 8.2, 8.3
