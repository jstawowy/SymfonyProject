The database service I used was self-hosted MySQL databse. To setup the envirounment on the new machine with current settings you need to run MySQL module followed by 2 commands:

'symfony console doctrine:database:create'
'symfony console doctrine:migrations:migrate'

The useable URLs are:

http://127.0.0.1:8000/category - for adding categories options
http://127.0.0.1:8000/addProduct - for adding a product to the database
http://127.0.0.1:8000/allProducts - for checking all the products in the database
(http://127.0.0.1:8000/notifications - SMS and Chat code implemented but halted due of lack of DSN credentials)


# KodanoProject
