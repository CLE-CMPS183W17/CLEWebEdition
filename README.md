# College Length Estimator Web Edition
A web application that gives the user an estimate on how long it would take to complete a set number of courses under ideal conditions.

Project introduced for Winter 2017 CMPS 183 at UCSC. Scrum Board can be viewed [here.] (https://tauboard.com/v/52758768ef23b0524f46beb522140549)

Inspired by [the Java version] (https://github.com/divark/college-length-estimator) of the College Length Estimator made by Tyler Schmidt.

[![Deploy](https://www.herokucdn.com/deploy/button.svg)](https://heroku.com/deploy)

## Technologies used:
- Languages: HTML5, PHP, CSS (Managed by Bootstrap), JavaScript/jQuery
- Web Framework: CakePHP
- Database: PostgreSQL
- Development Environments: Atom Editor, NetBeans

## Installation Instructions:
To preview this program, you will need CakePHP 3. You can get that [here.] (https://book.cakephp.org/3.0/en/installation.html)

Once that is acquired, follow the instructions [here] (https://book.cakephp.org/3.0/en/installation.html#create-a-cakephp-project) to create your own project.

When that is done, you'll want to import the table files from the database folder into your PostgreSQL database.
Proceed to then configure the app.php file in the config folder, changing the following lines:
- 'driver' => 'Cake\Database\Driver\Postgres'
- username, password, and database is dependent on how you set up Postgres.

Then, proceed to run from your bin folder: 
- cake bake all course (Or, in the future, if there are more table files, cake bake all tablenamehere)

Once successful, copy the src folder from this repo and replace it with the newly generated one.
