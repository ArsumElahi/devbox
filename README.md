
## Legal One Coding Challenge


I completed the challenge but didn't  find the end date. I discovered four parameters 
where I only received one date parameter, and I used that parameter as the start date.
I implement the api in accordance with the provided api.yaml file; the url for the 
api is  http://localhost:9002/api/count and the arguments are what you provided.
## Deployment

I use the logs.txt file that you provided, which I put in the public folder under 
the name inputfile.txt, and I import the logs using that file.

To run this project, you need to follow these steps

To get the updated code
```bash
   git pull origin master
```
Setup the environment
```bash
   docker compose up  
```
Install the dependencies

```bash
   composer install 
```

Run the Migration

```bash
   php bin/console doctrine:database:create
```
```bash
   php bin/console doctrine:migrations:migrate
```
 
 Run the user defined command to import the file into database

```bash
    php bin/console log:dump-log-file
```
