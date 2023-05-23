# Library Management System

## General info
This project is a library management system.
It allows users to browse and borrow the library resources.
Librarians as admins are able to change data about the books and manage borrowed publications.

## Features
A user can perform the following tasks:
* View borrowed books and their due dates.
* Browse books by title, author and category.
* Reserve a book for borrowing.
* Browse authors and view their description and list of books.
* Browse categories and view books in a selected category.
* View their profile and edit a name and an email address.

An admin can perform the following tasks:
* View the list of all borrowed books and their due dates.
* Mark a borrowed book as returned.
* Add, edit and delete books.
* Add, edit and delete authors.
* Add, edit and delete categories.
* View the users list.
* Edit users' and admins' data on their profile pages.

## Technologies
This project is created with:
* PHP 8.2.0
* MySQL 10.4.27-MariaDB

## ER Diagram
![er-diagram.png](/images/er-diagram.png)</br>
Generated with [dbdiagram.io](https://dbdiagram.io)

## Installation
1. Clone the repository to xampp > htdocs folder.
2. Create a new database in phpMyAdmin with the name "library_db".
3. Import the database schema located in db_export folder.
4. Open the application in a web browser (http://localhost/library-management-system).
5. Login using the following account:
   * admin:
     * email: admin@gmail.com
     * password: admin123
   * user:
     * email: user@gmail.com
     * password: user123