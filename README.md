# uiuc-course-calendar
A quick way to view your UIUC course schedule as a calendar.

Deployed to https://myclasses.web.illinois.edu

## How it works
When a user/service authenticates with Shibboleth (the login service the university uses), Shibboleth sends back information about the user including every class they have ever taken. This data is used to create the calendar.

1. User logs in via Shibboleth (UIUC)
2. Shibboleth tells me what classes user has taken / is taking.
3. Filter for classes in the current semester.
4. Request the course explorer (courses.illinois.edu) for information about each class.
5. Create the calendar.

## Deployment
This site is hosted on UIUC's web hosting (web.illinois.edu) as that makes authentication very easy to setup.

## Running Locally
The site can be run locally with a php server. As authentication cannot happen locally, all calls to `getRedirEnv` will have to be mocked.