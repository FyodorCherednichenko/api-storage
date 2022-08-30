<p align="center"><a href="" target="_blank"><img src="https://github.com/FyodorCherednichenko/api-storage/blob/master/1528641301_5.jpg" width="400" alt="Laravel Logo"></a></p>


## Задача

Необходимо разработать прототип сервиса облачного хранилища для файлов. 
Сервис должен предоставлять REST API, работающее поверх HTTP в формате JSON.

## Обязательный функционал

- регистрация и авторизация (каждый пользователь видит свои файлы);
- базовая информация о пользователе
- создание папок (один уровень вложенности, без подпапок);
- список файлов;
- загрузка файлов;
- переименование файлов;
- удаление файлов;
- скачивание файлов;
- объем всех файлов на диске одного пользователя - `100 мб`;
- валидация при загрузке файлов:
    1. максимальный размер одного файла `20мб`
    2. запрещено загружать `*.php` файлы

### Опциональный функционал

- возможность, при загрузке, указывать срок хранения файла, после которого он сам удаляется `+ 50 баллов`

## Ссылка на коллекцию Postman
<p align="left"><a href="https://documenter.getpostman.com/view/11553101/VUxLy9mG#2ffcccdc-3b83-4816-9997-7f200ddbb94a" target="_blank"><img src="https://res.cloudinary.com/postman/image/upload/t_team_logo_pubdoc/v1/team/a1314e68bb0799b6e158a12b33ed352fb4f743f4159a566b60496af5c5bd0393" width="200" alt="Laravel Logo"></a></p>

## Инструкция по запуску:

1)Для установки нужен docker, docker-compose, git. <br>
2)Установить новый проект ларавель: curl -s https://laravel.build/example-app | bash <br>
3)Клонировать репозиторий git clone git@github.com:/FyodorCherednichenko/api-storage.git <br>
4)После клонирования код проекта будет в директории api-storage, необходимо скопировать файлы из api-storage в директорию в, которую установлен ларавель с заменой файлов. <br>
5)Директорию api-storage можно удалить <br>
6)В дириктории с ларавель выполнить команду ./vendor/bin/sail up -d <br>
7)В дириктории с ларавель выполнить команду ./vendor/bin/sail php artisan create-storage <br>
8)В дириктории с ларавель выполнить команду ./vendor/bin/sail php artisan migrate <br>
9)В дириктории с ларавель выполнить команду ./vendor/bin/sail php artisan queue:work <br>
10)Можно тестировать проект =) <br>
