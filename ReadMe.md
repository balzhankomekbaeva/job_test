# 🚀 Pipedrive CRM Test Application

Тестовое задание для позиции **JavaScript Developer Intern**.

## 📌 Описание
Веб-приложение, которое открывается во фрейме (iframe) и позволяет создавать сделки (Deals) в **Pipedrive CRM** через API.  
Форма собирает данные о клиенте, услуге и расписании, после чего отправляет их на бэкенд (PHP).  
Бэкенд делает запросы к Pipedrive API:
1. Создаёт контакт (Person)
2. Создаёт сделку (Deal)
3. Добавляет заметку (Note) с подробностями заявки

## 🛠 Технологии
- **Frontend**: HTML, CSS, JavaScript (fetch API)
- **Backend**: PHP (cURL)
- **API**: [Pipedrive REST API](https://pipedrive.readme.io/docs/core-api-concepts)
- **Hosting**: InfinityFree (http://www.balzhan.42web.io)

## ⚙️ Установка и запуск локально
1. Склонировать проект:
   ```bash
   git clone https://github.com/<your-username>/pipedrive-test-task.git
   cd pipedrive-test-task
   ```
2. Настроить Pipedrive API ключ
    - Получить API Token в **Pipedrive → Личные настройки → API**
    - Указать его в `api/config.php`:
      ```php
      return [
        'api_base' => 'https://api.pipedrive.com/v1',
        'api_token' => 'ВАШ_API_ТОКЕН',
        'pipeline_id' => 1,
        'stage_id' => 2
      ];
      ```
3. Запустить локальный сервер (например, встроенный PHP):
   ```bash
   php -S localhost:8000
   ```
4. Открыть в браузере:
   ```
   http://localhost:8000
   ```

## 🌍 Демо
Приложение доступно по адресу:  
👉 [http://www.balzhan.42web.io]

## 🎥 Видео-демо
Скринкаст с объяснением работы приложения:  
👉 [Loom link]

## 📂 Структура проекта
```
/api
  ├── config.php       # настройки API
  ├── pd_client.php    # клиент для работы с Pipedrive API
  └── save_deal.php    # обработка формы и сохранение сделки
/index.html            # основная страница с iframe
/form.html             # форма для создания сделки
```

## ✅ Функционал
- [x] Отображение формы во фрейме
- [x] Отправка данных на сервер
- [x] Создание контакта в Pipedrive
- [x] Создание сделки с контактом
- [x] Добавление заметки с деталями

---
👩‍💻 Автор: Balzhan Komekbaeva
