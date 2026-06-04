# Car Dealership Management System

## Overview

This project is a web-based Car Dealership Management System developed as part of an academic project in Software Engineering.

The application allows users to browse available vehicles, view detailed information about cars, place orders, and contact the dealership. It also includes an administration panel for managing cars, orders, and customer inquiries.

## Features

### Customer Features

* Browse vehicle catalog
* View detailed car information
* Contact the dealership
* Place vehicle orders
* User authentication and login

### Admin Features

* Secure admin authentication
* Manage vehicle inventory (Create, Read, Update, Delete)
* Manage customer orders
* Manage contact requests
* Dashboard for administration

## Technologies Used

### Frontend

* HTML5
* CSS3
* JavaScript

### Backend

* PHP (Object-Oriented Programming)

### Database

* MySQL

### Development Environment

* XAMPP
* Git & GitHub

## Project Structure

```text
admin/
├── cars/
├── contacts/
├── orders/
├── templates/
├── login.php
├── logout.php
└── dashboard.php

BrandManager.php
Car.php
CarManager.php
Contact.php
ContactManager.php
Order.php
OrderManager.php
User.php
UserManager.php
Database.php
api.php
auth.php
```

## Object-Oriented Design

The application follows Object-Oriented Programming principles.

Main classes include:

* Car
* CarManager
* BrandManager
* User
* UserManager
* Order
* OrderManager
* Contact
* ContactManager
* Database

## Installation

1. Clone the repository:

```bash
git clone https://github.com/IBFTN/projet-ibf-auto.git
```

2. Move the project to your XAMPP `htdocs` directory.

3. Start:

   * Apache
   * MySQL

4. Create the database in phpMyAdmin.

5. Import the SQL database file.

6. Configure database credentials in `Database.php`.

7. Open the project in your browser:

```text
http://localhost/AHMED_GHEZAL_G2_Projet_TPW/
```

## Learning Outcomes

Through this project, I gained experience with:

* Object-Oriented Programming in PHP
* Database design and management
* CRUD operations
* Authentication and authorization
* Frontend and backend integration
* Git and GitHub version control

## Author

Ahmed Ghezal

Software Engineering Student
