# Simple order demo
This project is a simple demo on how a single page application consume a simple CRUD API. Use Vue.js + Vuex for frontend development and lumen for backend development.

# Backend Installation
A local PHP server (contains mysql sever) is required in order to serve the backend.

Install the dependencies.

```sh
$ cd kobe
$ cd lumen-api
$ composer install
```

# Backend Configuration
Create .env file under "lumen-api" folder and copy all the contents from .env.example

Change the content as following:

          DB_PORT=<your mysql server port>
          DB_DATABASE=<your favourite schema name>
          DB_USERNAME=<your mysql login username>
          DB_PASSWORD=<your mysql login password>
# Database setup
Go to your MySQL sever to create a schema. The name should be same as what you put in the .env file

Run data migration (Please notice that, if you are using Homestead virtual machine, you need to ssh to vagrant and find the folder you map to in the server and run this command)
```sh
$ cd kobe
$ cd lumen-api
$ php artisan migrate
```

This command will give you two default users. You can use them to login to user dashboard.Credentials are listed as followed:

     User1:
        username: user1
        password: user1

     User2:
        username: user2
        password: user2

Also, it will generate a default admin account. Credential is listed as bellow:

        username:admin1
        password:admin1

# Running backend

Running your PHP server. Open browser and head to your backend url. if you see 'Lumen (5.8.9) (Laravel Components 5.8)' in window means backend is running correctly

# Frontend Installation
Install the dependencies.

```sh
$ cd kobe
$ cd vue-spa
$ yarn install
```
# Frontend Configuration
Head to "vue-spa/src/store.js"

Find variable "apiUrl" in this file and  assign your backend URL to this variable (replace 'http://kobe.test/api' with 'http://<your URL>/api' )

# Running frontend
Make sure both frontend and backend dependencies are installed, and configurations are completed.

Running frontend
```sh
$ cd kobe
$ cd vue-spa
$ yarn run sever
```
 Open 'http://localhost:8080/' in browser, you will see the home page of the website.

 # Project details
 - Backend (lumen-api)
     1) All controllers are located in Controller folder (app/Http/Controllers).
     2) Routes are defined in web.php (routes/web.php).
     3) Models are defined inside app folder (Admin.php, Orders.php, User.php).
     4) Two authentication middleware are used to protect API from unauthenticated users (app/Http/Middleware/AdminAuthenticate.php, app/Http/Middleware/Authenticate.php).
     5) Users have ability to call API to get their own orders and post a new orders.
     6) Admin can see all orders placed by all users and update the status of a certain order. However, they cannot place a new order.
     7) Both user and admin cannot delete orders.
     8) There is another Middleware (app/Http/CrosMiddleware.php) to change the header of each API call.
     9) There are two login functions, one for user login, the other for admin login. Once they (either user or admin) login, API will return an API key meanwhile update this information in the database. Fontend will store this API key in the browser in order to call the API.
     10) Each user (or admin) will have a unique API key when they login. When they logout, the API key record in database will be removed. Every time they login, they will get a new API key.

- frontend (vue-spa)
     1) Fontend contains both user area and admin area.
     2) In order to access admin area, you need to type "http://localhost:8080/#/admin-login" in browser manually. (Use 'username:admin1 password:admin1' to login)
     3) In terms of user area, you can use both <username: user1 password: user1> and <username: user2 password: user2> to login as different users.
     4) Frontend use 'vue-material' to build look and feel, use 'vuex' to store the login status and use 'axios' to call API
     5) Router is defined in src/router.js
     6) Vuex status is defined in src/store.js
     7) In store.js, two global variables 'isUserLogged' and 'isAdminLogged' are used to check whether user or admin has login.
     8) In route.js, all the routes which required authentication to access have a attribute "meta" to identify.
     9) In user dashboard page (/dashboard/:id), function 'checkCurrentUser()' will prevent user to access other users' dashboard via manually typing url. This function will check whether current API key is matching with current user id.

# Author
Jason Ping
