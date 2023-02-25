This is an example Symfony project made for demonstration purposes only.
The project is a "customer loyality service" with an API method to register user. 
After successful registration, the service sends a notification to the user via email/sms(Mock email/sms sender used).

Below is a list of some compromises, missed features and controversial decisions made in this project, and my reasoning behind that.
Missed features(May be I will do that later):
- Api documentation
- CI-platform scripts

Compromises / controversial decisions:
- Non-standard folder structure - Although Symfony does have some default directories, framework does not limit us to them. My preferred way to structure
  project is to split it vertically(by modules / features / contexts) in the first place, not horizontally.
  Splitting project vertically is more scalable and informative, and it also makes cross-module coupling easier to track.
  We can split Symfony services configuration in multiple files, to make modules even more self-sufficient
- Usage of __invoke method - Whether or not to use __invoke is a matter of taste, and in my opinion, should be once decided and recorded in a project style-guide
- Code in Entities - I prefer to use [Domain Model pattern](https://martinfowler.com/eaaCatalog/domainModel.html),
  where entities is not just a "representation of a DB-table", but contain business-logic and business-rules.
  This obliges us to treat decomposition very seriously, to not end up with god-object entities, but helps us to keep project maintainable in the long run.
  Also, if you are not using Domain Model pattern, you probably don't need the doctrine ORM
- Synchronous domain event processing, no retry logic - made for simplicity. It can be easily replaced with events dispatcher, which will save events to some store(db / quque)


To set up project run, run:
```
make up
make app_shell (enter the php pod shell)
make deps (run inside the php pod shell)
make init (run inside the php pod shell)
```

To access local database use credentials below:
```
host: localhost:5432
user: app
password: 123123123
database: database
```

Example curl query to call service. Only US phone numbers are supported
```
curl --location --request POST 'localhost:81/user/register' \
--header 'Content-Type: application/json' \
--data-raw '{
    "fullName": "Evgenii R",
    "email":  "user@test.com",
    "phoneNumber":  "+11234567890"
}'
```
