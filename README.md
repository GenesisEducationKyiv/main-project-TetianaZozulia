
# Comments to test task

## CurrencyController
Не знайшла безкоштовного api для отримання курсу валют UAN до BTC, буде USD до BTC

* [Route('/api/rate', name: 'rate', methods: 'GET')](/rate)
  Данні по курсу беруться з storage (в нашому випадку файл, може бути і база).
  Чому зі сторедж? Тому що стороннє api ставить ліміти на к-ть запитів в місяць/день.
  Щоб уникнути переліміту обновлення данних запускаєм 2-3 рази на день, або якщо збереженої копії не існує в потрібному стореджі.

  Також результат можна кешувати. Особливо якщо сторедж - це база. Щоб не відправляти зайві запити на базу.

* [Route('/api/rate/update', name: 'rate_update', methods: 'PATCH')](/rate_update)
  роут для обновлення локальної копії данних. Треба ставити на якийсь крон



## MailerController
* [Route('/api/subscribe', name: 'subscribe', methods: 'POST')](/subscribe)
  Якщо записів буде багато і притримуємось стратегії сторедж - це файл,
  можливо варто буде розбивати файли по N(наприклад 10000) записів
  і потім обробку вести в чергах

* [Route('/api/sendEmails', name: 'sendEmails', methods: 'POST')](/sendEmails)
  Хотіла показати що записів в файлі може бути дуже багато. Відповідно їх обробка має це враховувати.
  Копіювання в файл для процессінгу, видалення обробленних данних, щоб у випадку якщо скрипт навернеться
  не втратити данні по тому хто вже отримав, хто ще ні лист.

  Є кращий варіант для реалізація такої задачі.
  створити ендпоінт-таск який буде: отримувати патч емайлів і всі данні листа
    - забирати патч імейлів зі стореджа
    - апдейтити ці імейли як оброблені
    - відправляти на них листи
      і запустити якийсь демон, який буде ганяти цей ендпоінт поки не оброблених імейлів не залишиться
      Впринципі ті самі черги

  Ще є коментарі в коді, не дублюю їх тут.


## Docker up
* build
```bash
$ docker build -t currency_api_project .
```

* run
```bash
  $ docker run -p 8000:8000 currency_api_project
```

* exec
```bash
  $ docker ps
  $ docker exec -ti 440b710fe5d7 /bin/bash  
```


## Run code sniffer
```bash
  $ composer sniff
```

## Run test
* just tests
```bash
  $ composer tests
```

* with coverage
```bash
  $ composer tests-coverage
```

* run infection
```bash
  $ infection --threads=1 --noop
  $ infection --threads=5 --filter=src/Controller/MailerController --show-mutations
```