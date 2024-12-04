# ipinfotest

Для поиска информации об ip используется https://api.sypexgeo.net/.

Для проверки работы нужно зайти на страницу /form_geoip

Порядок работы:
1. сначала нужно установить миграцию с помощью модуля sprint.migration (в ней находится создание HL-блока для хранения информации об уже найденных ip).
2. Если во время поиска в HL-блоке не находится информация о введенном ip, то она будет искаться у sypexgeo, после чего запишется в HL-блок

![image](https://github.com/user-attachments/assets/30adbb9b-9612-44d2-b3e2-0760922e4e04)


![image](https://github.com/user-attachments/assets/616b04b3-26ae-41f3-9b71-33ae96523a8f)

![image](https://github.com/user-attachments/assets/bbae209e-48e8-4d44-bcab-c510f28f9aac)

![image](https://github.com/user-attachments/assets/fd7f2ab4-b2c5-4834-be67-92b13fd12af6)

![image](https://github.com/user-attachments/assets/b6c9cdb3-c6a2-4855-abf7-3ca73be67f6d)
