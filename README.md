### Требования
На локальном сервере сервере установлено:
- Любая ОС
- php 7.1 и выше
- zip
- composer
- git

На удаленном сервере установлено:
- Linux
- composer
- git
- nginx сконфигурирован на папку укзанную в config.php ({path}/current)
- другое по в post_deploy

### Установка
```
composer install
cp config/config.example.php config/config.php 
```
Настроить доступы

### Использование
Перед деплоем обязательно сделать инициализирование 
```
php ./bin/deploy deploy:init

```

Чтобы выполнить деплой
```
php ./bin/deploy  deploy:deploy
```

Чтобы откатить до конкректной версии ранее задеплоеной на целевой сервер
```
php ./bin/deploy deploy:rollback {deployId}
```

### Ограничения

- при неудачном деплое вебсервер возвращает предпоследний релиз. (Гит коммит)
если релиз пропустится, программа не сможет вернуть все назад.
- Не поддерживаются ключи с паролем
- Нет тестов

@todo 
1. вынести из deploy весь мусор
2. вебкухки после деплоя
3. вебкухи на деплой
