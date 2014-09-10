Добро пожаловать в справку по консольному CLI скрипту для генерации случаных данных.

Описание:

Консольный скрипт предназначенный для заполнения таблиц базы данных случайными данными.

Список команд:

--set_db_host - Установить сетевой адрес соединения текушего пользователя mySQL 
--set_db_name - Установить базу данных для соединения текушего пользователя mySQL 
--set_db_charset - Установить кодировку соединения текушего пользователя mySQL 
--set_db_user_name - Установить логин текушего пользователя mySQL 
--set_db_user_password - Установить пароль текушего пользователя mySQL 
--print_db_struct - Напечатать структуру текущей базы данных
--print_gen_data - Напечатать сгенерированные даныне
--gen_new_data=1 - Генерация данных для базы данных. В переметрах указывается количество строк для генерации
--help - Показать данную справку
--reconect - Пересоединение

Дополнительные сведения:

Поскольку скрипт понимает "Важность" команд. Необходимо учитывать это при заданинии действий.
К примеру, если Установить команду генерации данных, апосле указать подключение - то 
Скрипт сгенерирует данные для адреса по умолчанию, а после сменит его на необходимый.

Так же можно выполнять цепочки команд, 

1) Установить адрес mysql сервера
2) Сгенерировать данные 
3) Установить адрес нового mysql сервера
4) Сгенерировать данные 

К примеру:
$ php run_test_cli.php --set_db_host=127.0.0.1 --gen_new_data=10 --set_db_host=192.168.1.15 --reconect --gen_new_data=10
Будет выполнено несколько подключений к 2м базам данных по адресам(127.0.0.1,192.168.1.15) и заполнение их тестовыми данными.

Команда гнирации данных версии 0.1 поддерживает типы полей "text,int,tinyint". 
При необходимости спосок будет расширен.

Have a fun! :)
