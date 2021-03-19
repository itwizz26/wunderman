# Wunderman HN

This is a Symfony 5 and Reactjs clone of the Hacker News website.

## Introduction

This system is built on PHP 7 in the backend and ES6 on the frontend.

##Requirements
Please ensure you have the latest version of PHP installed and a database server (MySQL or other).
Also ensure that you have yarn or npm installed on the test machine.

## Running the web application

First of, navigate to the project, open the .env.test file to enter your database credentials and save it as 
.env (without the .test).

Open a command line console of your choice and run the below commands, in the exact order.
Run
```
composer install and npm i/yarn install
```
to install all the depencies required by the application (backend and frontend).
Run
```
php bin\console doctrine:schema:update --force
```
to create the DB and its entities.
Run 
```
php bin\console app:save-news
```
to fetch all the news from HN (consuming the API) and save into your database.
Run 
```
php bin\console app:save-comments
```
to fetch and save all the news item comments.
Now run 
```
php bin\console server:run
```
to start the web server; and in another console window, run 
```
yarn run dev or npm run dev
```
to run the React frontend.

Open the application in your browser - this should be on [localhost:8000](http://localhost:8000); and start browsing.

## Testing and debugging

To test and/or watch for any possible errors, you can use [Postman](https://go.pstmn.io/github) or similar, for the API/backend and the developer tools of your web browser, to test the frontend.

### Credits

[HN](https://news.ycombinator.com/newcomments)
[Google](http://www.google.com)
[Hacker News API](https://github.com/HackerNews/API)

### Repository

The code base may be found here on [GitHub](https://github.com/itwizz26/wunderman).

### License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.

### Developer

Application crafted and perfected by [itwizz26](https://github.com/itwizz26).
