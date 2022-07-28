## PHP Challenge

The project is already setup for using with docker compose.
You can customize the mariadb user and passwords in the ```docker-compose.yml``` file.

## Setup
In order to run the project for the first time you have to define the database url as an enviromental variable:
```bash
DATABASE_URL=mysql://<username>:<password>@mysql/<database-name>
```
This values must be set exactly as the ones defined in the ```docker-compose.yml``` file.

By default it should be 
```bash
DATABASE_URL=mysql://my_user:my_pass@mysql/jobsity_challenge
```
After this you can run the project with:
```bash
docker compose up -d
```

Once the containers are running, you have to create the database and grant access to the user. You can do this with the mysql cli via ```docker exec``` as root:
```bash
docker exec -it jobsity-mysql sh -c "mysql -A -p"
```
Using the above example the sql code would look like:
```sql
CREATE DATABASE jobsity_challenge;
GRANT ALL ON jobsity_challenge.* TO 'my_user' IDENTIFIED BY 'my_pass';
```

Finally, you can create the database schema by running the following command:
```bash
docker exec -it jobsity-php sh -c "php vendor/bin/doctrine orm:schema-tool:update --force"
```

## API
The api has 3 methods:

```
POST /signIn
```
Creates a new user in the database.
Accepts json or www-url-encoded data. The required fields are ```email``` and ```password```. 

```
GET /stock?q=<stock symbol>
```
Returns a stock quote from the [Stooq API](https://stooq.com/q/l/?s=aapl.us&f=sd2t2ohlcvn&h&e=json) in json format and saves it to the database. Requires authentication.

```
GET /history
```
Returns the quote request history by user in json format. Requires authentication.

